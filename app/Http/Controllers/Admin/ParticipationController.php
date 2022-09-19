<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Livewire\ChatCreate;
use App\Models\Chat;
use App\Models\chat_status;
use App\Models\Collection;
use App\Models\collection_winner;
use App\Models\Message;
use App\Models\Participation;
use App\Models\Participation_work;
use App\Models\Pat_status;
use App\Models\Printorder;
use App\Models\Transaction;
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
        $participation = Participation::where('id', $request->pat_id)->first();

        $user->notify(new EmailNotification(
                'Требуется оплата участия',
            $user['name'],
            "Спешим сообщить, что Ваши произведения как нельзя лучше подходят для сборника '" . collection::where('id',$collection_id)->value('title') . '! ' .
            "Сразу после оплаты (" . $participation['total_price'] . " рублей с учетом скидки) Вы будете включены в список авторов сборника и будете получать уведомления о всех этапах его публикации. " .
            "Оплата происходит в автоматическом режиме. Для этого необходимо нажать кнопку 'Оплатить " . $participation['total_price'] . " руб.' на странице Вашего участия:",
            "Перейти к оплате",
                route('homePortal') . "/myaccount/collections/" . $collection_id . "/participation/" . $request->pat_id . '#payment_block')
        );

        \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
            'Смена статуса участия в сборнике!',
                route('homePortal') . "/myaccount/collections/" . $collection_id . "/participation/" . $request->pat_id)
        );

        session()->flash('success', 'change_printorder');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Успешно!');
        session()->flash('alert_text', 'Статус участника изменен и мы даже послали ему Email об этом :)');

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
        $collections_to_update = Collection::OrderBy('created_at', 'desc')->get();
        $participation = Participation::where('id', $participation_id)->with('participation_work')->first();
        $pat_statuses = Pat_status::orderBy('id')->get();
        $chat = Chat::where('user_created', $participation['user_id'])->where('collection_id', $participation['collection_id'])->first();
        $chat_statuses = chat_status::orderBy('id')->get();
        $transactions = Transaction::where('participation_id', $participation_id)->get();
        return view('admin.collection.user_participation', [
            'participation' => $participation,
            'pat_statuses' => $pat_statuses,
            'chat' => $chat,
            'chat_statuses' => $chat_statuses,
            'transactions' => $transactions,
            'collections_to_update' => $collections_to_update,
        ]);

    }



    public function change_user_collection($participation_id, Request $request)
    {

//        $participation = Participation::where('id', $request->participation_id)->first();
//        $collection_from_update = Collection::where('id', $participation['collection_id'])->first();
//        $collection_to_update = Collection::where('id', $request->collection_id_to_update)->first();
//
//
//        // ---- Меняем сборник в участии ---- //
//        Participation::where('id', $request->participation_id)->update(array(
//            'collection_id' => $collection_to_update['id']
//        ));
//
//        // ---- Меняем сборник в печатном заказе ---- //
//        IF ($participation['printorder_id'] ?? 0 > 0) {
//            Printorder::where('id', $participation['printorder_id'])->update(array(
//                'collection_id' => $collection_to_update['id']
//            ));
//        }

//        // ---- Меняем сборник в чате ---- //
//        Chat::where('collection_id', $participation['collection_id'])
//            ->where('user_created', $participation['user_id'])
//            ->update(array(
//            'collection_id' => $collection_to_update['id'],
//            'title' => 'Личный чат по сборнику: ' . $collection_to_update['title']
//        ));

//
//        session()->flash('success', 'change_printorder');
//        session()->flash('alert_type', 'success');
//        session()->flash('alert_title', 'Успешно!');
//        session()->flash('alert_text', 'Заменили сборник участнику!');
//
//        return redirect()->back();


    }

}
