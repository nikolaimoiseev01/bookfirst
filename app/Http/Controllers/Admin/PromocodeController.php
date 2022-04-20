<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\promocode;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    public function index(Request $request)
    {
        $promocodes = promocode::orderBy('id')->get();
        return view('admin.promocodes', [
            'promocodes' => $promocodes,
        ]);
    }
}
