<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\ext_promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtPromotionController extends Controller
{
    public function application () {
        return view('account.ext_promotion.application');
    }

    public function index ($id) {
        $ext_promotion = ext_promotion::where('id', $id)->first();
        return view('account.ext_promotion.index', [
            'ext_promotion' => $ext_promotion
        ]);
    }

    public function my_ext_promotions () {
        $ext_promotions = ext_promotion::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('account.ext_promotion.my_ext_promotions', [
            'ext_promotions' => $ext_promotions
        ]);
    }


}
