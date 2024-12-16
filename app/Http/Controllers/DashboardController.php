<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;


class DashboardController extends Controller
{
    public function index()
    {
        $users =User::whereNot('id',auth()->id())
            ->paginate(15);
        return view('dashboard',compact('users'));

    }
}
