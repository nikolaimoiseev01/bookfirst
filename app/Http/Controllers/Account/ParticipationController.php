<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Col_status;
use App\Models\Collection;
use App\Models\collection_winner;
use App\Models\Message;
use App\Models\Participation;
use App\Models\Participation_work;
use App\Models\Pat_status;
use App\Models\Printorder;
use App\Models\User;
use App\Models\vote;
use App\Models\Work;
use App\Notifications\EmailNotification;
use App\Notifications\ParticipationChange;
use App\Rules\SamePart;
use App\Rules\SameParticipation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ParticipationController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the participation dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request)
    {




        $participation = Participation::where('user_id', Auth::user()->id)->where('collection_id', $request->collection_id)->first() ?? array('pat_status_id' => 0);
        $printorder = Printorder::where('id', $participation['printorder_id'] ?? 1)->first() ?? 0;
        $collection = Collection::orderBY('id')->find($request->collection_id);
        $col_statuses = Col_status::orderBY('id')->get();
        $pat_statuses = Pat_status::orderBY('id')->get();
        $chat = Chat::where('user_created', Auth::user()->id)->where('collection_id', $request->collection_id)->first() ?? null;
//      $pre_var_chat = Chat::where('chat_status_id', '<>', 3)->where([['user_created', Auth::user()->id], ['pre_comment_flag', 1]])->first();

        $last_mes_id = $chat_question_check['id'] ?? null;

        $chat_question_check =  Message::where('chat_id', $chat['id'] ?? null)->get() ?? null;
        if ($chat_question_check) {
            $chat_question_check = Message::where('chat_id', $chat['id'] ?? null)->latest('created_at')->first();
            $chat_question_check = ($chat_question_check['user_from'] ?? 0 == 2 && $chat['flag_hide_question'] ?? 0 <> 1);
        }

         $voted_to = Participation::where('collection_id', $request->collection_id)
            ->where('user_id', vote::where('user_id_from', Auth::user()->id)->where('collection_id', $request->collection_id)->value('user_id_to'))
            ->first();


        $votes_for_me = vote::where('collection_id', $request->collection_id)->where('user_id_to', Auth::user()->id)->count();

        return
            view('account.collections.participation.index', [
                'col_statuses' => $col_statuses,
                'collection' => $collection,
                'participation' => $participation,
                'pat_statuses' => $pat_statuses,
                'printorder' => $printorder,
                'chat' => $chat,
                'voted_to' => $voted_to,
                'votes_for_me' => $votes_for_me,
                'chat_question_check' => $chat_question_check,
                'last_mes_id' => $last_mes_id
            ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Collection $collection
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        $works = Work::orderBy('id', 'asc')->where('user_id', Auth::user()->id)->get();
        $collection = Collection::orderBY('id')->find($request->collection_id);
        return view('account.collections.participation.create', [
            'collection' => $collection,
            'works' => $works
        ]);
    }

    public function messages()
    {
        return [
            'name.required' => 'A title is required',
            'body.required' => 'A message is required',
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Participation $participation
     * @return \Illuminate\Http\Response
     */
    public function show(Participation $participation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Participation $participation
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */

    public function edit(Request $request)
    {
        $collection = Collection::orderBY('id')->find($request->collection_id);
        $participation = Participation::orderBY('id')->where('id', $request->participation_id)->first();
        $printorder = Printorder::orderBY('id')->where('id', $request->printorder_id)->first();
        $user_works = Work::where('id', Auth::user()->id)->get();
        $works_already_in = Participation_work::where('participation_id', $request->participation_id)->get();
        $print_check = 3;
        return view('account.collections.participation.edit', [
            'participation' => $participation,
            'collection' => $collection,
            'printorder' => $printorder,
            'user_works' => $user_works,
            'works_already_in' => $works_already_in,
            'print_check' => $print_check,
            'check_check' => $participation['check_price'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Participation $participation
     * @return \Illuminate\Http\Response
     */


    public function pay_for_participation(Request $request)
    {
        Participation::where('id', $request->pat_id)->update(array(
            'pat_status_id' => 3,
            'paid_at' => Carbon::now()
        ));

        $collection_id = Participation::where('id', $request->pat_id)->value('collection_id');
//
//        $new_chat = new Chat();
//        $new_chat->user_created = Auth::user()->id;
//        $new_chat->user_to = 2;
//        $new_chat->title = 'Предварительная проверка';
//        $new_chat->collection_id = $collection_id;
//        $new_chat->own_book_id = 0;
//        $new_chat->chat_status_id = 1;
//        $new_chat->save();


        $user = User::where('id', Auth::user()->id)->first();

        $user->notify(new EmailNotification(
            'Оплата подтвердена!',
            $user['name'],
            "Отлично, вы успешно оплатили заявку в сборике: '" . collection::where('id', $collection_id)->value('title') .
            "'. Теперь остается ждать издания! Вся информацию по этому сборнику будет по ссылке:",
            "Страница сборника",
            route('homePortal') . "/collection/" . $collection_id . "/participation/"));

        return redirect()->back();

    }

//    public function to_edit(Request $request)
//    {
//        Participation::where('id', $request->pat_id)->update(array(
//            'pat_status_id' => $request->new_pat_status_id
//        ));
//
//        Auth::user()->notify(new ParticipationChange);
//
//        return redirect()->back()
//            ->with('success', 'Теперь Вы можете редактировать заявку!');
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Participation $participation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Participation $participation)
    {
        //
    }
}
