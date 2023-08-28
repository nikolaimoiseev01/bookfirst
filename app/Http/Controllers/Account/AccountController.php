<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\own_book;
use App\Models\Participation;
use App\Models\Pat_status;
use App\Models\User;
use App\Models\user_subscription;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AccountController extends Controller
{
    public function collections() {
        $participations = Participation::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('account/collections/index', [
            'participations' => $participations,
        ]);
    }

    public function own_books() {
        $own_books = own_book::where('user_id', Auth::user()->id)->where('own_book_status_id', '<>', 99)->get();
        return view('account/own_books/index', [
            'own_books' => $own_books,
        ]);
    }



    public function myawards($data = null) {
        return view('account/myawards', [
        ]);
    }


    public function mysubscribtions() {
        $user_subed_to_ids = user_subscription::where('user_id', Auth::user()->id)->pluck('subscribed_to_user_Id')->toArray();
        $sub_users = User::wherein('id', $user_subed_to_ids)->paginate(10);

        return view('account/mysubscriptions', [
            'sub_users' => $sub_users
        ]);
    }




    public function mynotifications() {

        return view('account/mynotifications', [
            'notifications' => 5,
        ]);
    }

    public function mysettings() {
        return view('account/mysettings', [
        ]);
    }



    public function digital_sales () {
        $digital_sales = \App\Models\digital_sale::where('user_id', Auth::user()->id)->get();
        $user_wallet = UserWallet::where('user_id', Auth::user()->id)->first();
        return view('account.digital_sales', [
            'digital_sales' => $digital_sales,
            'user_wallet' => $user_wallet,
        ]);
    }








}
