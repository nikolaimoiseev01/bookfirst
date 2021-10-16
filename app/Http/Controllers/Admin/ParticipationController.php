<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Livewire\ChatCreate;
use App\Models\Chat;
use App\Models\chat_status;
use App\Models\Collection;
use App\Models\Participation;
use App\Models\Participation_work;
use App\Models\Pat_status;
use App\Models\PrintOrder;
use App\Models\User;
use App\Notifications\EmailNotification;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ParticipationController extends Controller
{
    public function participants(Request $request)
    {
        $participations = Participation::orderBy('pat_status_id','asc')->where('collection_id', $request->collection_id)->get();
        $collection_title = DB::table('collections')->where('id', $request->collection_id)->value('title');
        $printorders = PrintOrder::orderBy('id','desc')->where('collection_id',$request->collection_id)->get();
        return view('admin.collection.participants', [
            'participations' => $participations,
            'collection_title' => $collection_title,
            'printorders' => $printorders
        ]);
    }


    public function change_user_status(Request $request)
    {
        Participation::where('id', $request->pat_id)->update(array(
            'pat_status_id' => $request->pat_status_id,
            'approved_at' => Carbon::now()
        ));
        $user = User::where('id', $request->user_id)->first();
        $collection_id = Participation::where('id', $request->pat_id)->value('collection_id');

        $user->notify(new EmailNotification(
                'Участие в сборнике',
            $user['name'],
            "Спешим сообщить, что статус вашего участия в сборнике '" . collection::where('id',$collection_id)->value('title') .
            "' был изменен! На данный момент ваш статус: '" . Pat_status::where('id', $request->pat_status_id)->value('pat_status_title') . "'. " .
                "Вся подробная информация об издании сборника и вашем процессе указана на странице участия:",
            "Ваша страница участия",
                route('homePortal') . "/myaccount/collections/" . $collection_id . "/participation/" . $request->pat_id)
        );

        \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
            'Смена статуса участия в сборнике!',
                route('homePortal') . "/myaccount/collections/" . $collection_id . "/participation/" . $request->pat_id)
        );

        return redirect()->back();

    }



    public function new_participants()
    {

        $new_participations = Participation::orderby('created_at', 'desc')->where('pat_status_id', 1)->get();
        return view('admin.collection.new_participants', [
            'new_participations' => $new_participations,
        ]);

    }

    public function user_participation($participation_id)
    {

        $participation = Participation::where('id', $participation_id)->with('participation_work')->first();
        $pat_statuses = Pat_status::orderBy('id')->get();
        $chat = Chat::where('user_created', $participation['user_id'])->where('collection_id', $participation['collection_id'])->first();
        $chat_statuses = chat_status::orderBy('id')->get();
        return view('admin.collection.user_participation', [
            'participation' => $participation,
            'pat_statuses' => $pat_statuses,
            'chat' => $chat,
            'chat_statuses' => $chat_statuses,
        ]);

    }

}
