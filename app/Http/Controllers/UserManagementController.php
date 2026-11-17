
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::with('role')
            ->orderBy('name')
            ->paginate(10);

        return view('users.index', compact('users'));
    }
}
