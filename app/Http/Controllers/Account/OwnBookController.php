<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\digital_sale;
use App\Models\own_book;
use App\Models\own_book_status;
use App\Models\Participation;
use App\Models\preview_comment;
use App\Models\Printorder;
use App\Models\User;
use App\Notifications\EmailNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnBookController extends Controller
{
    public function book_page($request)
    {
        $own_book = own_book::where('id', $request)->first();
        $chat = Chat::where('own_book_id', $own_book['id'])->first();
        return
            view('account.own_books.book_page', [
                'own_book' => $own_book,
                'chat' => $chat
            ]);
    }


    public function pay_for_own_book(Request $request)
    {

        $this->own_book = own_book::where('id', $request->own_book_id)->first();


        own_book::where('id', $request->own_book_status_id)->update(array(
            'own_book_status_id' => 3,
        ));


        $user = User::where('id', Auth::user()->id)->first();

        $user->notify(new EmailNotification(
            'Оплата подтверждена!',
            $user['name'],
            "Это письмо - подтверждение успешной оплаты за издание (кроме печати) книги '" . own_book::where('id', $request->own_book_id)->value('title') . "'." .
            "\nНа текущий момент издание имеет общий статус: '" . own_book_status::where('id', $request->own_book_status_id)->value('status_title') . "'. Всю подробную информацию об издании Вы всегда можете отслеживать на специальной странице издания книги.",
            "Страница издания",
            route('book_page', $this->own_book['id']))
        );

        return redirect()->back();

    }

    public function pay_for_own_book_print(Request $request)
    {
        Printorder::where('own_book_id', $request->own_book_id)->update(array(
            'paid_at' => Carbon::now()
        ));
        own_book::where('id', $request->own_book_id)->update(array(
            'own_book_status_id' => 5
        ));


        $user = User::where('id', Auth::user()->id)->first();

        $user->notify(new EmailNotification(
            'Оплата за печать подтверждена!',
                $user['name'],
                "Это письмо - подтверждение успешной оплаты за печать книги '" . own_book::where('id', $request->own_book_id)->value('title') . "'." .
                "\nНа текущий момент издание имеет общий статус: '" . own_book_status::where('id', $request->own_book_status_id)->value('status_title') . "'. Всю подробную информацию об издании Вы всегда можете отслеживать на специальной странице издания книги.",
                "Страница издания",
                route('book_page', $this->own_book['id']))
        );

        session()->flash('success', 'success');
        session()->flash('alert_text', 'Заказ печатных экземпляров успешно оплачен!');
        return redirect()->to(url()->previous());

    }


}
