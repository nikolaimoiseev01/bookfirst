<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Collection;
use App\Models\Participation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $chats_check = Chat::where('chat_status_id', '<>', 3)->where('collection_id', null)->where('own_book_id', null)
            ->where(function($q) {
                $q->where('user_to', Auth::user()->id)
                    ->orWhere('user_created', Auth::user()->id);
            })
            ->get();
        $new_user_id = null;
        return view('account/my_chats/index', [
            'chats_check'=>$chats_check,
            'new_user_id' => $new_user_id
        ]);
    }


    public function new_chat($new_user_id)
    {

        $chats_check = Chat::where('chat_status_id', '<>', 3)->where('collection_id', null)->where('own_book_id', null)
            ->where(function($q) {
                $q->where('user_to', Auth::user()->id)
                    ->orWhere('user_created', Auth::user()->id);
            })
            ->get();

        return view('account/my_chats/index', [
            'chats_check'=>$chats_check,
            'new_user_id' => $new_user_id
        ]);
    }

    public function chat($chat_id)
    {
        $chat = Chat::where('id', $chat_id)->first();
        return view('account/my_chats/chat', [
            'chat'=>$chat
        ]);
    }

    public function archive()
    {
        $chats = Chat::where('chat_status_id', 3)
            ->where(function($q) {
                $q->where('user_to', Auth::user()->id)
                    ->orWhere('user_created', Auth::user()->id);
            })
            ->get();

        return view('account/my_chats/archive', [
          'chats'=>$chats,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $collection_id = Collection::where('title', Str::substr($request->chat_title,19,50))->value('id');
        return view('account/my_chats/create', [
            'chat_title' => $request->chat_title,
            'collection_id' => $collection_id
        ]);
    }

    public function change_chat_status (\Illuminate\Http\Request $request) {

        \App\Models\Chat::where('id', $request->chat_id)->update(array(
            'chat_status_id' => $request->chat_status_id,
        ));
        session()->flash('success', 'change_printorder');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Статус чата успешно изменен!');


        return redirect()->back();
    }

}
