<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Col_status;
use App\Models\own_book;
use Illuminate\Support\Facades\Session;

class PortalController extends Controller
{
    public function index() {
        $own_books = own_book::where('own_book_status_id', 9)->where('promo_price', '>', 1000)->orderBy('id')->get();
        $collections = Collection::where('col_status_id','1')->get();
        $col_statuses = Col_status::orderBY('id')->get();
        return view('portal.index', [
            'collections' => $collections,
            'col_statuses' => $col_statuses,
            'own_books' => $own_books
        ]);
    }

    public function old_collections() {
        $collections = Collection::orderBY('id', 'desc')->where('col_status_id', 9)->paginate(9);
        return view('portal.old_collections', [
            'collections' => $collections,
            'collection_input_search' => 'no_search',
        ]);
    }

    public function actual_collections() {
        $collections = Collection::orderBY('id', 'desc')->where('col_status_id', 1)->paginate(9);
        return view('portal.actual_collections', [
            'collections' => $collections,
        ]);
    }

    public function collection_search($collection_input_search)
    {

        $collections = Collection::where('title', 'like', '%' . $collection_input_search . '%')->orderBy('id','desc')->paginate(9);
        session(['previous-url' => request()->url()]);
        return view('portal.old_collections', [
            'collections' => $collections,
            'collection_input_search' => $collection_input_search,
        ]);
    }

    public function own_book_page() {
            return view('portal.own_book_page', [
        ]);
    }

    public function about() {
        return view('portal.about', [
        ]);
    }


    public function own_books() {
        $own_books = own_book::where('own_book_status_id', 9)->where('promo_type', 2)->orWhere('old_author_email', "<>", "")->orderBY('id', 'desc')->paginate(9);
        return view('portal.own_books', [
            'own_books' => $own_books,
            'own_book_input_search' => 'no_search',
        ]);
    }

    public function own_book_search($own_book_input_search)
    {

        $own_books = own_book::where('title', 'like', '%' . $own_book_input_search . '%')->orderBy('id', 'desc')->paginate(9);
        session(['previous-url' => request()->url()]);
        return view('portal.own_books', [
            'own_books' => $own_books,
            'own_book_input_search' => $own_book_input_search,
        ]);
    }




}
