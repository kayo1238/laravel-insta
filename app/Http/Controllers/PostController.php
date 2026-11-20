<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $post;

    private $category;

    public function __construct(Post $post, Category $category)
    {
        $this->post = $post;
        $this->category = $category;
    }

    # To open CREATE POST PAGE
    public function create()
    {
        $all_categories = $this->category->all(); // we need all categories to display in create post page
        return view('users.posts.create')->with('all_categories', $all_categories); // send all categories data to create post view
    }

    # add data to datebase
    public function store(Request $request)
    {
        # 1. Validate all from data (check if input data are correct)
        $request->validate([
            'category'           => 'required|array|between:1,3',  // 1,3 mean 1～3まで
            'description'        => 'required|min:1|max:1000',
            'image'              => 'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        # 2. save post to database
        $this->post->user_id = Auth::user()->id;  // login users are the one who will create the post
        $this->post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));    // date:image/myimage.ipg;base64,(base64string)
        $this->post->description  = $request->description;
        $this->post->save();

        # 3. save the categories to pivot table (category_post table)
        // $category_post = [];
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
            // category_post array for the categories
            // 'category_id' is the column from pivot table
        }
        $this->post->categoryPost()->createMany($category_post);
        // this post is the current post (to know which post the categoriers belongs)
        //createMany - save a 2D array to datacbase

        # 4. Go back to homepage if successful
        return redirect()->route('index');
    }

    # Show specific controller
    public function show($id)
    {
        $post = $this->post->findOrFail($id);   // find the specific post using id
        return view('users.posts.show')->with('post', $post);
    }

    # Edit specific post
    public function edit($id)
    {
        $post = $this->post->findOrFail($id);   // find specific post to edit

        # if the AUTH USER is NOT the ownew of the post, redirect to homepage
        if (Auth::user()->id != $post->user->id) {
            return redirect()->route('index');
        }

        # get all categoies to desplay in edit post page
        $all_categories = $this->category->all();

        #get ALL the category IDs of THE POST. Save inside an array
        $selected_categories = [];  // array for selected catefories
        foreach ($post->categoryPost as $category_post) {
            // get all categories under a post by calling categoryPost relationship
            $selected_categories[] = $category_post->category_id; // put all IDs inside the selected_catefory_bategories array
        }

        return view('users.posts.edit')
            ->with('post', $post)
            ->with('all_categories', $all_categories)
            ->with('selected_categories', $selected_categories);
    }

    # create an update method
    public function update(Request $request, $id)
    {
        # 1. Validate the form data
        $request->validate([
            'category'    => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image'       => 'nullable|mimes:jpeg,jpg,png,gif|max:1048'
        ]);
        # 2. Uptate the post (also update if there's new image)
        $post              = Post::findOrFail($id);     // ① 編集する投稿をデータベースから取得する（$idを使用）
        $post->user_id     = Auth::user()->id;  // ② ユーザーIDやディスクリプション（文章）は常に更新
        $post->description = $request->description;
        // $post->category    = $request->category;
        if ($request->hasFile('image')) {    // ③ もし新しい画像がアップロードされていたら画像も更新する
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }
        $post->save();                         // ④ データベースに保存（更新）する
       
        # 3. Delete all records from category_post table that is related to this post
        $post->categoryPost()->delete();
        # 4. Save the new categories to the category_post table
         $post->categoryPost()->delete();  // 一度既存の紐付けを削除して新しく保存し直す あるいはsyncに相当する処理
        if ($request->category) {          // もし複数選択（配列）であれば、ループして保存する形になります
            foreach ($request->category as $categoryId) {
                $post->categoryPost()->create([
                    'category_id' => $categoryId,
                ]);
            }
        }
        # 5. redirect to Show Post Page (to confirm the uptate)
         return redirect()->route('index');
    }
     # create destroy method (delete)
     public function destroy($id){
        $post = Post::findOrFail($id);
        $post->categoryPost()->delete();   // 中間テーブルの紐付けを削除
        $post->delete();                    // 投稿本体を削除

    return redirect()->route('index');
     }

}
