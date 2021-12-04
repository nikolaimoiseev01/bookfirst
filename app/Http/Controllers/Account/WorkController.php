<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $page_type = 'no_search';
        session(['previous-url' => request()->url()]);
        return view('account.my_works.index', [
            'page_type' => 'no_search',
            'work_input_search' => 'no_search',
        ]);
    }

    public function index_search($work_input_search)
    {
        session(['previous-url' => request()->url()]);
        return view('account.my_works.index', [
            'work_input_search' => $work_input_search,
            'page_type' => 'search',
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return
            view('account.my_works.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_work = new Work();
        $new_work->title = $request->title;
        $new_work->text = $request->text;
        $new_work->symbols = $request->symbols;
        $new_work->rows = $request->rows;
        $new_work->pages = $request->pages;
        $new_work->user_id = Auth::user()->id;
        $new_work->save();
        return redirect(session('previous-url'));

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Work $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Work $work
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        return
            view('account.my_works.edit', [
                'work' => $work,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Work $work
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Work $work)
    {


        // --------- Ищем ошибки в заполнении  --------- //
        $errors_array = [];

        if ($request->title == null || $request->text == null) {
            array_push($errors_array, 'Не все поля заполнены!');
        }


        if (!empty($errors_array)) {
            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'error');
            session()->flash('alert_title', 'Ошибка!');
            session()->flash('alert_text', 'Не все поля заполнены!');

            return redirect()->back();
        }

        // --------- //Ищем ошибки в заполнении  --------- //


        if (empty($errors_array)) {
            $work->title = $request->title;
            $work->text = $request->text;
            $work->user_id = Auth::user()->id;
            $work->save();
            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Успешно!');
            session()->flash('alert_text', 'Произведение "' . $request->title . '" отредактировано.');

            return redirect('/myaccount/work');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Work $work
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work $work)
    {
        //
    }
}
