<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $works = Work::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        session(['previous-url' => request()->url()]);
        return view('account.my_works.index', [
            'works' => $works,
            'work_input_search' => 'no_search',
        ]);
    }

    public function work_search($work_input_search)
    {
//        dd($work_input_search);
        $works = Work::where('user_id', Auth::user()->id)
            ->where('title', 'like', '%' . $work_input_search . '%')
            ->orWhere('text', 'like', '%' . $work_input_search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        session(['previous-url' => request()->url()]);
        return view('account.my_works.index', [
            'works' => $works,
            'work_input_search' => $work_input_search,
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work $work)
    {
        $work->title = $request->title;
        $work->text = $request->text;
        $work->user_id = Auth::user()->id;
        $work->save();
        $works = Work::where('user_id', Auth::user()->id)->get();
        return view('account.my_works.index', [
            'works' => $works
        ]);
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
