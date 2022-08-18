<?php


use App\Models\Participation;
use App\Notifications\new_participation;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\Account\ParticipationController;
use App\Http\Controllers\Portal\CollectionController;
use App\Http\Controllers\Portal\PortalController;
use App\Http\Controllers\PaymentController;

// ---------  Регистрация --------- //
Auth::routes(['verify' => true]);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $participations = Participation::where('user_id', Auth::user()->id)->get();
    return view('account/collections/index', [
        'participations' => $participations,
    ]);
})->middleware(['auth', 'signed'])->name('verification.verify');
// ---------  Регистрация --------- //


// ---------  ПОРТАЛ --------- //
Route::middleware([])->prefix('/')->group(function () {
    Route::get('/', [PortalController::class, 'index'])->name('homePortal');
    Route::get('/collections/{collection_id}', [CollectionController::class, 'index'])->name('collection_page');
    Route::get('/own_book', [PortalController::class, 'own_book_page'])->name('own_book_page');

    Route::get('/our_collections', [PortalController::class, 'old_collections'])->name('old_collections');
    Route::get('/our_collections/actual', [PortalController::class, 'actual_collections'])->name('actual_collections');

    Route::get('/our_collections/{collection_input_search}', [PortalController::class, 'collection_search'])->name('collection_search');

    Route::get('/own_books', [PortalController::class, 'own_books'])->name('own_books_portal');
    Route::get('/own_books/{own_book_input_search}', [PortalController::class, 'own_book_search'])->name('own_book_search');

    Route::get('/about', [PortalController::class, 'about'])->name('about');
    Route::get('/help/account', [PortalController::class, 'help_account'])->name('help_account');
    Route::get('/help/collection', [PortalController::class, 'help_collection'])->name('help_collection');
    Route::get('/help/own_book', [PortalController::class, 'help_own_book'])->name('help_own_book');

});
// ----------------------------------------------



// ---------  ЛИЧНЫЙ КАБИНЕТ --------- //

Route::middleware(['verified'])->prefix('myaccount')->group(function () {

    Route::get('/collections', [App\Http\Controllers\Account\AccountController::class, 'collections'])->name('collections');
    Route::middleware([])->prefix('collections/{collection_id}')->group(function () {
        Route::get('/participation/create', [App\Http\Controllers\Account\ParticipationController::class, 'create'])->name('participation_create');
        Route::get('/participation/{participation_id}', [App\Http\Controllers\Account\ParticipationController::class, 'index'])->name('participation_index');
        Route::get('/participation/{participation_id}/edit', [App\Http\Controllers\Account\ParticipationController::class, 'edit'])->name('participation_edit');
    });
    Route::post('/participation/{participation_id}/pay_for_participation', [App\Http\Controllers\Account\ParticipationController::class, 'pay_for_participation'])->name('pay_for_participation');


    Route::get('/mybooks', [App\Http\Controllers\Account\AccountController::class, 'own_books'])->name('own_books');
    Route::middleware([])->prefix('mybooks/{own_book_id}')->group(function () {
        Route::get('/book_page', [App\Http\Controllers\Account\OwnBookController::class, 'book_page'])->name('book_page');
    });
    Route::post('/mybooks/pay_for_own_book/{own_book_id}', [App\Http\Controllers\Account\OwnBookController::class, 'pay_for_own_book'])->name('pay_for_own_book');
    Route::post('/mybooks/pay_for_own_book_print/{own_book_id}', [App\Http\Controllers\Account\OwnBookController::class, 'pay_for_own_book_print'])->name('pay_for_own_book_print');

    Route::get('/work/create_from_doc', function () {
        return view('account/my_works/create_from_doc', [
        ]);
    })->name('create_from_doc');

    Route::get('/mybooks/own_book/create', function () {
        return view('account.own_books.create', [
        ]);
    })->name('own_book_create');

    Route::post('temp-uploads/{file_source}',[\App\Http\Controllers\UploadController::class, 'store']);

    Route::resource('work', \App\Http\Controllers\Account\WorkController::class,);
    Route::get('/work/search/{work_input_search}', [App\Http\Controllers\Account\WorkController::class, 'index_search'])->name('work_search');
    Route::get('/myawards', [App\Http\Controllers\Account\AccountController::class, 'myawards'])->name('myawards');
    Route::get('/mynotifications', [App\Http\Controllers\Account\AccountController::class, 'mynotifications'])->name('mynotifications');
    Route::get('/chats', [App\Http\Controllers\ChatController::class, 'index'])->name('all_chats');
    Route::get('/chats/archive', [App\Http\Controllers\ChatController::class, 'archive'])->name('archive_chats');
    Route::get('/chats/{chat_id}', [App\Http\Controllers\ChatController::class, 'chat'])->name('chat');
    Route::get('/chats/create/{chat_title}', [App\Http\Controllers\ChatController::class, 'create'])->name('chat_create');
    Route::get('/mysettings', [App\Http\Controllers\Account\AccountController::class, 'mysettings'])->name('mysettings');

    Route::get('/my_digital_sales', function () {
        $digital_sales = \App\Models\digital_sale::where('user_id', Auth::user()->id)->get();
        return view('account.digital_sales', [
            'digital_sales' => $digital_sales,
        ]);
    })->name('my_digital_sales');

    // ---------  ОПЛАТА --------- //
    Route::post('/payments/create_part_payment/part_id={participation_id}/amount={amount}', [PaymentController::class, 'create_part_payment'])->name('payment.create_part_payment');
    Route::post('/payments/create_send_payment/print_id={print_id}/amount={amount}', [PaymentController::class, 'create_send_payment'])->name('payment.create_send_payment');
    Route::post('/payments/create_own_book_payment/own_book_id={own_book_id}/payment_type={payment_type}/amount={amount}', [PaymentController::class, 'create_own_book_payment'])->name('payment.create_own_book_payment');
    Route::post('/payments/create_buying_collection/collection_id={collection_id}', [PaymentController::class, 'create_buying_collection'])->name('payment.create_buying_collection');
    Route::post('/payments/create_buying_own_book/own_book_id={collection_id}', [PaymentController::class, 'create_buying_own_book'])->name('payment.create_buying_own_book');
    // ---------  // ОПЛАТА --------- //

});

Route::match(['POST', 'GET'], '/payments/callback', [PaymentController::class, 'callback'])->name('payment.callback');


// ----------------------------------------------



// ---------  Панель Админа --------- //

Route::middleware(['role:admin'])->prefix('admin_panel')->group(function () {
    Route::get('/col', [App\Http\Controllers\Admin\CollectionController::class, 'index'])->name('homeAdmin');
    Route::get('/create_col_file', [App\Http\Controllers\Admin\CollectionController::class, 'create_col_file'])->name('create_col_file');
    Route::get('/download_all_prints', [App\Http\Controllers\Admin\CollectionController::class, 'download_all_prints'])->name('download_all_prints');
    Route::get('/collections/closed', [App\Http\Controllers\Admin\CollectionController::class, 'closed_collections'])->name('closed_collections');
    Route::post('/change_user_collection/{participation_id}', [App\Http\Controllers\Admin\ParticipationController::class, 'change_user_collection'])->name('change_user_collection');


    Route::get('/own_books', [App\Http\Controllers\Admin\OwnBookController::class, 'index'])->name('own_books_index');
    Route::get('/own_books/closed', [App\Http\Controllers\Admin\OwnBookController::class, 'closed_own_books'])->name('closed_own_books');
    Route::get('/own_books/{own_book_id}', [App\Http\Controllers\Admin\OwnBookController::class, 'own_books_page'])->name('own_books_page');
    Route::get('/collections/new_participants', [App\Http\Controllers\Admin\ParticipationController::class, 'new_participants'])->name('new_participants');
    Route::get('/collections/participants/{collection_id}', [\App\Http\Controllers\Admin\ParticipationController::class, 'participants'])->name('participants');
    Route::get('/user/{user_id}', [\App\Http\Controllers\Admin\UserController::class, 'user_page'])->name('user_page');
    Route::resource('collection', \App\Http\Controllers\Admin\CollectionController::class,);
    Route::get('/promocodes', [\App\Http\Controllers\Admin\PromocodeController::class, 'index'])->name('promocodes_page');
    Route::post('/add_winner/{collection_id}', [App\Http\Controllers\Admin\CollectionController::class, 'add_winner'])->name('add_winner');
    Route::get('/collections/participation/{participation_id}', [App\Http\Controllers\Admin\ParticipationController::class, 'user_participation'])->name('user_participation');
    Route::get('/chats', [App\Http\Controllers\Admin\UserController::class, 'chats'])->name('chats');
    Route::get('/chats/{chat_id}', [App\Http\Controllers\Admin\UserController::class, 'chat'])->name('admin_chat');

    Route::get('/login_as/{user_id}', [App\Http\Controllers\Admin\UserController::class, 'login_as'])->name('login_as');

    Route::post('/change_chat_status/{chat_id}', [\App\Http\Controllers\ChatController::class, 'change_chat_status'])->name('change_chat_status');
    Route::post('/change_user_status', [\App\Http\Controllers\Admin\ParticipationController::class, 'change_user_status'])->name('change_user_status');
    Route::post('/send_email_all_participants', [\App\Http\Controllers\Admin\CollectionController::class, 'send_email_all_participants'])->name('send_email_all_participants');




    Route::post('/change_book_status', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_book_status'])->name('change_book_status');
    Route::post('/change_amazon_link', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_amazon_link'])->name('change_amazon_link');
    Route::post('/change_book_inside_status', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_book_inside_status'])->name('change_book_inside_status');
    Route::post('/change_book_cover_status', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_book_cover_status'])->name('change_book_cover_status');
    Route::post('/change_book_pages', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_book_pages'])->name('change_book_pages');
    Route::post('/change_book_promo_type', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_book_promo_type'])->name('change_book_promo_type');

    Route::post('/change_preview_comment_status/{preview_comment_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_preview_comment_status'])->name('change_preview_comment_status');
    Route::post('/change_all_preview_comment_status/{own_book_id}/{comment_type}', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_all_preview_comment_status'])->name('change_all_preview_comment_status');
    Route::post('/change_all_preview_collection_comment_status/{collection_id}', [\App\Http\Controllers\Admin\CollectionController::class, 'change_all_preview_collection_comment_status'])->name('change_all_preview_collection_comment_status');

    Route::post('/update_own_book_cover/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_cover'])->name('update_own_book_cover');
    Route::post('/update_own_book_prices/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_prices'])->name('update_own_book_prices');
    Route::post('/update_own_book_inside/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_inside'])->name('update_own_book_inside');
    Route::post('/update_own_book_track_number/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_track_number'])->name('update_own_book_track_number');
    Route::post('/update_own_book_send_price/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_send_price'])->name('update_own_book_send_price');
    Route::post('/update_own_book_desc/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_desc'])->name('update_own_book_desc');

    Route::get('/transactions', function () {
        $transactions = \App\Models\Transaction::orderBy('created_at', 'desc')->get();
        return view('admin.transactions', [
            'transactions' => $transactions,
        ]);
    })->name('transactions_from_admin');

    Route::resource('user', \App\Http\Controllers\Admin\UserController::class);
    Route::get('/subscribers', [App\Http\Controllers\Admin\UserController::class, 'subscribers_index'])->name('subscribers_index');
    Route::get('/subscribers/get', [App\Http\Controllers\Admin\UserController::class, 'subscribers_download'])->name('subscribers_download');
});
// ----------------------------------------------
