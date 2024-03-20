<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\chat_status;
use App\Models\Collection;
use App\Models\ext_promotion;
use App\Models\ext_promotion_status;
use App\Models\Participation;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\EmailNotification;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtPromotionController extends Controller
{
    public function list() {

        $ext_promotions = ext_promotion::orderBy('created_at', 'desc')->get();

        return view('admin.ext_promotions.list', [
            'ext_promotions' => $ext_promotions
        ]);
    }

    public function index($id) {

        $ext_promotion = ext_promotion::where('id', $id)->first();
        $ext_promotion_statuses = ext_promotion_status::all();
        $transactions = Transaction::where('ext_promotion_id', $id)->get();
        $chat = Chat::where('id', $ext_promotion['chat_id'])->first();
        $chat_statuses = chat_status::all();

        return view('admin.ext_promotions.index', [
            'ext_promotion' => $ext_promotion,
            'ext_promotion_statuses' => $ext_promotion_statuses,
            'transactions' => $transactions,
            'chat' => $chat,
            'chat_statuses' => $chat_statuses
        ]);
    }

    public function change_ext_promotion_status(Request $request)
    {

        $ext_promotion = ext_promotion::where('id', $request->ext_promotion_id)->first();

        if ($request->ext_promotion_status_id == 2) { // Если уже полностью оплатил и только чуть поменял произведения
            $email_subject = 'Требуется оплата участия';
            $email_text = "Спешим сообщить, что ваша заявка на продвижение на сайте {$ext_promotion['site']} была одобрена! " .
                "Сразу после оплаты (" . $ext_promotion['price_total'] . " рублей с учетом скидки) мы сможем начать процесс. " .
                "Оплата происходит в автоматическом режиме. Для этого необходимо нажать кнопку 'Оплатить " . $ext_promotion['price_total'] . " руб.' на странице вашего продвижения:";
        } elseif ($request->ext_promotion_status_id == 4) {
            $email_subject = 'Продвижение началось';
            $email_text = "Спешим сообщить, что ваше продвижение на сайте {$ext_promotion['site']} началось! " .
                "Всю подробную информацию (включая статистику) вы можете отслеживать на странице вашего продвижения:";
        }
        elseif ($request->ext_promotion_status_id == 9) {
            $email_subject = 'Продвижение закончено';
            $email_text = "Спешим сообщить, что ваше продвижение на сайте {$ext_promotion['site']} завершено! " .
                "Всю подробную информацию (включая статистику) вы можете проверить на странице вашего продвижения:";
        }

        ext_promotion::where('id', $request->ext_promotion_id)->update(array(
            'ext_promotion_status_id' => $request->ext_promotion_status_id
        ));

        $user = User::where('id', $request->user_id)->first();

        if(in_array($request->ext_promotion_status_id, [2,4,9])) {
            $user->notify(new EmailNotification(
                    $email_subject,
                    $user['name'],
                    $email_text,
                    "Перейти к странице продвижения",
                    route('index_ext_promotion', $ext_promotion['id']) . '/#payment_block')
            );
        }

        session()->flash('success', 'change_printorder');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Успешно!');
        session()->flash('alert_text', 'Статус продвижения изменен и мы даже послали ему Email об этом :)');

        return redirect()->back();

    }

    public function add_ext_promotion_comment(Request $request)
    {
        $ext_promotion = Participation::where('id', $request->ext_promotion_id)->first();

        ext_promotion::where('id', $request->ext_promotion_id)->update(array(
            'comment' => $request->comment
        ));

        session()->flash('success', 'change_printorder');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Успешно!');
        session()->flash('alert_text', 'Обновили комментарий!');

        return redirect()->back();

    }
}
