<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\chat_status;
use App\Models\Collection;
use App\Models\subscriber;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Date\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{
    public function index()
    {

        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.user.index', [
            'users' => $users
        ]);
    }

    public function user_page(Request $request)
    {

        $user = User::orderBy('created_at', 'desc')->where('id', $request->user_id)->first();
        $chats = Chat::where('user_to', $request->user_id)->orWhere('user_created', $request->user_id)->get();
        return view('admin.user.user_page', [
            'user' => $user,
            'chats' => $chats,
        ]);
    }

    public function subscribers_index()
    {

        $subscribers = subscriber::all();
        return view('admin.user.subscribers_index', [
            'subscribers' => $subscribers
        ]);
    }

    public function subscribers_download()
    {

        $subscribers = subscriber::all();

        App::setLocale('ru');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Email');
        $sheet->setCellValue('B1', 'Создан');

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);

        foreach ($subscribers as $key => $subscriber) {
            $sheet->setCellValue("A" . ($key + 2), $subscriber['email']);
            $sheet->setCellValue("B" . ($key + 2), Date::parse($subscriber['created_at'])->addHours(3)->format('j F H:i'));
        }

        foreach (range('A', 'B') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $file_title = 'Подписчики с сайта';
        $writer->save($file_title . '.xlsx');
        return response()->download($file_title . '.xlsx')->deleteFileAfterSend(true);

    }


    public function chats()
    {

        $users = User::orderBy('created_at', 'desc')->get();
        $chats = Chat::orderBy('chat_status_id', 'asc')->orderBy('updated_at', 'desc')->with('message')->get();
        return view('admin.chats', [
            'users' => $users,
            'chats' => $chats
        ]);
    }

    public function chat(Request $request)
    {

        $chat = Chat::where('id', $request->chat_id)->first();
        $chat_statuses = chat_status::orderBy('id')->get();
        return view('admin.chat', [
            'chat' => $chat,
            'chat_statuses' => $chat_statuses,
        ]);
    }

    public function login_as(User $user, Request $request)
    {
        Auth::loginUsingId($request->user_id);
        return redirect()->route('collections');
    }


    public function login_admin(User $user, Request $request)
    {
        Auth::loginUsingId(2);
        return redirect()->route('homeAdmin');
    }

}
