<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;
    public function __construct(User $user) {
        $this->user = $user;
    }

    # open admin/users and display all users
    public function index() {
        $all_users = $this->user->withTrashed()->latest()->paginate(5);  // get all users from latest added
        // withTrashed() - include soft deleted data in a query result
        return view('admin.users.index')->with('all_users', $all_users);
    }

    # deactivate a user
    public function deactivate($id) {
        $this->user->destroy($id);
        return redirect()->back();
    }

    # activate a user
    public function activate($id) {
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        // onlyTrashed() - retrieves only soft deleted data
        // restore() - this will "un-delete" soft deleted datas. will set "deleted_at" column to null
        return redirect()->back();
    }
}
