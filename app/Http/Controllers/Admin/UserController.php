<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Collection;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {

        $users = User::orderBy('created_at','desc')->get();
        return view('admin.user.index',[
            'users' => $users
        ]);
    }

    public function user_page(Request $request) {

        $user = User::orderBy('created_at','desc')->where('id',$request->user_id)->first();
        $chats = Chat::where('user_to',$request->user_id)->orWhere('user_created', $request->user_id)->get();
        return view('admin.user.user_page',[
            'user' => $user,
            'chats' => $chats,
        ]);
    }

    public function chats() {

        $users = User::orderBy('created_at','desc')->get();
        $chats = Chat::orderBy('chat_status_id','asc')->orderBy('updated_at','desc')->with('message')->get();
        return view('admin.chats',[
            'users' => $users,
            'chats' => $chats
        ]);
    }

    public function chat(Request $request) {

        $chat = Chat::where('id',$request->chat_id)->first();
        return view('admin.chat',[
            'chat' => $chat
        ]);
    }

}
