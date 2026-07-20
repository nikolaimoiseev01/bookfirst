<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->email;

        Mail::raw('This is a test email from Bookfirst API.', function ($message) use ($email) {
            $message->to($email)
                ->subject('Test Email');
        });

        return response()->json([
            'message' => 'Test email sent successfully to ' . $email,
        ]);
    }
}
