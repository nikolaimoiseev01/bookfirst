<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\award;
use App\Models\award_type;
use App\Models\Chat;
use App\Models\chat_status;
use App\Models\Collection;
use App\Models\subscriber;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Date\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{
    public function index()
    {
        $users_amt = User::count();
        $subscribers_amt = subscriber::count();
        $users = User::orderBy('created_at', 'desc')->paginate(50);
        $users_online = User::where('last_seen', '>', now()->subMinute(5)->toDateTimeString())->count();
        return view('admin.user.index', [
            'users' => $users,
            'users_amt' => $users_amt,
            'subscribers_amt' => $subscribers_amt,
            'users_online' => $users_online
        ]);
    }

    public function user_page(Request $request)
    {

        $user = User::orderBy('created_at', 'desc')->where('id', $request->user_id)->first();
        $chats = Chat::where('user_to', $request->user_id)->orWhere('user_created', $request->user_id)->get();
        $awards = award::where('user_id', $request->user_id)->get();
        $awards_types = award_type::orderBy('created_at')->get();
        return view('admin.user.user_page', [
            'user' => $user,
            'chats' => $chats,
            'awards' => $awards,
            'awards_types' => $awards_types
        ]);
    }

    public function add_user_comment(Request $request) {

        User::where('id', $request->user_id)->update(array(
            'comment' =>  $request->comment
        ));

        session()->flash('success', 'change_printorder');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Успешно!');
        session()->flash('alert_text', 'Обновили комментарий!');

        return redirect()->back();

    }



    public function search_user($users_input)
    {
        $query = User::query();
        $columns = Schema::getColumnListing('users');

        foreach($columns as $column){
            $query->orWhere($column, 'LIKE', '%' . $users_input . '%');
        }

        $users = $query->get();
        $users_amt = count($users);
        $users = $query->paginate(50);


        return view('admin.user.user_search', [
            'users' => $users,
            'user_input' => $users_input,
            'users_amt' => $users_amt,
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


    public function chats_admin()
    {

        $users = User::orderBy('created_at', 'desc')->get();
        $chats = Chat::where('user_created', '=', 2)->orwhere('user_to', '=', 2)->orderBy('chat_status_id', 'asc')->orderBy('updated_at', 'desc')->with('message')->paginate(50);
        return view('admin.chats_admin', [
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



    public function login_ext_promotion_admin_key(User $user, Request $request)
    {
        Auth::loginUsingId(ENV('APP_DEBUG') ? 3 : 2380);
        return redirect()->route('admin_ext_promotions');
    }

    public function login_secondary_admin_key(User $user, Request $request)
    {
        Auth::loginUsingId(ENV('APP_DEBUG') ? 5 : 2956);
        return redirect()->route('homeAdmin');
    }



    public function add_user_award(Request $request) {
        $new_award = new award;
        $new_award->user_id = $request->user_id;
        $new_award->award_type_id = $request->award_id_to_update;
        $new_award->save();
    }


}
