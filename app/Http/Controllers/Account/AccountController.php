<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\own_book;
use App\Models\Participation;
use App\Models\Pat_status;
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
        $own_books = own_book::where('user_id', Auth::user()->id)->get();
        return view('account/own_books/index', [
            'own_books' => $own_books,
        ]);
    }



    public function myawards($data = null) {
        return view('account/myawards', [
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


}
