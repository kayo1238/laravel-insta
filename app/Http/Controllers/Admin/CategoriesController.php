<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index() {
        $all_categories = Category::all();
        // 例：カテゴリーに所属していない投稿の数を取得する場合
        $uncategorized_count = \App\Models\Post::whereDoesntHave('categoryPost')->count();

        return view('admin.categories.index')
                ->with('all_categories', $all_categories)
                ->with('uncategorized_count', $uncategorized_count);
    }

    public function store(Request $request) {
        $request->validate([
        'name' => 'required|max:50',
    ]);

    Category::create([
        'name' => $request->name,   // データベースに保存
    ]);

    return redirect()->back();
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|max:50|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return redirect()->back();
    }

    public function destroy($id) {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back();
    }
}
