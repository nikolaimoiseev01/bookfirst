<?php


use App\Http\Controllers\Social\SocialController;
use App\Http\Controllers\Social\WorkCommentsController;
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

    if(Auth::user()->hasVerifiedEmail()) {
        return redirect()->route('collections');
    }
    else {
        return view('auth.verify-email');
    }

})->middleware('auth')->name('verification.notice');



Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $participations = Participation::where('user_id', Auth::user()->id)->get();
    return view('account/collections/index', [
        'participations' => $participations,
    ]);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/sign-in/vkontakte', [\App\Http\Controllers\Auth\LoginController::class, 'sign_vk'])->name('sign_vk');
Route::get('/sign-in/vkontakte/callback', [\App\Http\Controllers\Auth\LoginController::class, 'callback_vk'])->name('callback_vk');

Route::get('/sign-in/ok', [\App\Http\Controllers\Auth\LoginController::class, 'sign_ok'])->name('sign_ok');
Route::get('/sign-in/ok/callback', [\App\Http\Controllers\Auth\LoginController::class, 'callback_ok'])->name('callback_ok');

Route::get('/sign-in/ya', [\App\Http\Controllers\Auth\LoginController::class, 'sign_ya'])->name('sign_ya');
Route::get('/sign-in/ya/callback', [\App\Http\Controllers\Auth\LoginController::class, 'callback_ya'])->name('callback_ya');

Route::get('/sign-in/google', [\App\Http\Controllers\Auth\LoginController::class, 'sign_google'])->name('sign_google');
Route::get('/sign-in/google/callback', [\App\Http\Controllers\Auth\LoginController::class, 'callback_google'])->name('callback_google');

Route::get('/sign-in/facebook', [\App\Http\Controllers\Auth\LoginController::class, 'sign_facebook'])->name('sign_facebook');
Route::get('/sign-in/facebook/callback', [\App\Http\Controllers\Auth\LoginController::class, 'callback_facebook'])->name('callback_facebook');
// ---------  Регистрация --------- //






// ---------  СОЦИАЛЬНАЯ СЕТЬ --------- //

Route::prefix('social')->group(function () {
//    Route::get('/', function () {
//        return 'Hello World';
//    });
    Route::get('/', [SocialController::class, 'index'])->name('social.home');
//    Route::get('/', [SocialController::class, 'index'])->name('social.home');
    Route::get('/user/{user_id}', [SocialController::class, 'user_page'])->name('social.user_page');
    Route::get('/work/{work_id}', [SocialController::class, 'work_page'])->name('social.work_page');
    Route::get('/works_feed', [SocialController::class, 'all_works_feed'])->name('social.all_works_feed');
    Route::post('/create_work_comment', [WorkCommentsController::class, 'create_comment'])->name('social.create_comment');
    Route::get('/search/{search_input}', [\App\Http\Controllers\Controller::class, 'site_search'])->name('site_search');

});

// ---------  СОЦИАЛЬНАЯ СЕТЬ --------- //

//    Route::get('/', [PortalController::class, 'index'])->name('homePortal');

// ---------  ПОРТАЛ --------- //
Route::middleware([])->group(function () {

    Route::get('/', [PortalController::class, 'index'])->name('homePortal');
    Route::get('/collections/{collection_id}', [CollectionController::class, 'index'])->name('collection_page');
    Route::get('/own_book', [PortalController::class, 'own_book_page'])->name('own_book_page');
    Route::get('/own_book/{own_book_id}', [PortalController::class, 'own_book_user_page'])->name('own_book_user_page');

    Route::get('/our_collections', [PortalController::class, 'old_collections'])->name('old_collections');
    Route::get('/our_collections/actual', [PortalController::class, 'actual_collections'])->name('actual_collections');

    Route::get('/our_collections/{collection_input_search}', [PortalController::class, 'collection_search'])->name('collection_search');

    Route::get('/own_books', [PortalController::class, 'own_books'])->name('own_books_portal');
    Route::get('/own_books/{own_book_input_search}', [PortalController::class, 'own_book_search'])->name('own_book_search');

    Route::get('/about', [PortalController::class, 'about'])->name('about');
    Route::get('/help/account', [PortalController::class, 'help_account'])->name('help_account');
    Route::get('/help/collection', [PortalController::class, 'help_collection'])->name('help_collection');
    Route::get('/help/own_book', [PortalController::class, 'help_own_book'])->name('help_own_book');
    Route::get('/help/ext_promotion', [PortalController::class, 'help_ext_promotion'])->name('help_ext_promotion');

    Route::get('/ext_promotion', [PortalController::class, 'ext_promotion'])->name('ext_promotion');


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

    Route::any('temp-uploads/{file_source}',[\App\Http\Controllers\UploadController::class, 'store']);

    Route::resource('work', \App\Http\Controllers\Account\WorkController::class,);
    Route::get('/work/search/{work_input_search}', [App\Http\Controllers\Account\WorkController::class, 'index_search'])->name('work_search');
    Route::get('/myawards', [App\Http\Controllers\Account\AccountController::class, 'myawards'])->name('myawards');
    Route::get('/mysubscribtions', [App\Http\Controllers\Account\AccountController::class, 'mysubscribtions'])->name('mysubscribtions');
    Route::get('/mynotifications', [App\Http\Controllers\Account\AccountController::class, 'mynotifications'])->name('mynotifications');
    Route::get('/chats', [App\Http\Controllers\ChatController::class, 'index'])->name('all_chats');
    Route::get('/chats/new_chat_user_id={new_chat_user_id}', [App\Http\Controllers\ChatController::class, 'new_chat'])->name('new_chat');
    Route::get('/chats/archive', [App\Http\Controllers\ChatController::class, 'archive'])->name('archive_chats');
    Route::get('/chats/{chat_id}', [App\Http\Controllers\ChatController::class, 'chat'])->name('chat');
    Route::get('/chats/create/{chat_title}', [App\Http\Controllers\ChatController::class, 'create'])->name('chat_create');
    Route::get('/mysettings', [App\Http\Controllers\Account\AccountController::class, 'mysettings'])->name('mysettings');

    Route::get('/my_digital_sales', [App\Http\Controllers\Account\AccountController::class, 'digital_sales'])->name('my_digital_sales');
    Route::post('/make_donate', [App\Http\Controllers\Account\AccountController::class, 'make_donate'])->name('make_donate');

    Route::get('/ext_promotion/apply', [App\Http\Controllers\Account\ExtPromotionController::class, 'application'])->name('make_ext_promotion');
    Route::get('/ext_promotion/{id}', [App\Http\Controllers\Account\ExtPromotionController::class, 'index'])->name('index_ext_promotion');
    Route::get('/ext_promotion', [App\Http\Controllers\Account\ExtPromotionController::class, 'my_ext_promotions'])->name('my_ext_promotions');

    // ---------  ОПЛАТА --------- //
    Route::post('/payments/create_part_payment/part_id={participation_id}/amount={amount}', [PaymentController::class, 'create_part_payment'])->name('payment.create_part_payment');
    Route::post('/payments/create_send_payment/print_id={print_id}/amount={amount}', [PaymentController::class, 'create_send_payment'])->name('payment.create_send_payment');
    Route::post('/payments/create_own_book_payment/own_book_id={own_book_id}/payment_type={payment_type}/amount={amount}', [PaymentController::class, 'create_own_book_payment'])->name('payment.create_own_book_payment');
    Route::post('/payments/create_buying_collection/collection_id={collection_id}', [PaymentController::class, 'create_buying_collection'])->name('payment.create_buying_collection');
    Route::post('/payments/create_buying_own_book/own_book_id={collection_id}', [PaymentController::class, 'create_buying_own_book'])->name('payment.create_buying_own_book');
    Route::post('/payments/create_points_payment', [PaymentController::class, 'create_points_payment'])->name('payment.create_points_payment');
    Route::post('/payments/create_ext_promotion_payment/ext_promotion_id={ext_promotion_id}/amount={amount}', [PaymentController::class, 'create_ext_promotion_payment'])->name('payment.create_ext_promotion_payment');
    // ---------  // ОПЛАТА --------- //

});

Route::match(['POST', 'GET'], '/payments/callback', [PaymentController::class, 'callback'])->name('payment.callback');


// ----------------------------------------------
Route::get('/login_admin/' . env('admin_key'), [\App\Http\Controllers\Admin\UserController::class, 'login_admin']);
Route::get('/login_ext_promotion_admin/' . env('ext_promotion_admin_key'), [\App\Http\Controllers\Admin\UserController::class, 'login_ext_promotion_admin_key']);





// ---------  Панель Админа --------- //

Route::middleware(['role:admin'])->prefix('admin_panel')->group(function () {

    Route::get('/col', [App\Http\Controllers\Admin\CollectionController::class, 'index'])->name('homeAdmin');
    Route::get('/create_col_file', [App\Http\Controllers\Admin\CollectionController::class, 'create_col_file'])->name('create_col_file');
    Route::get('/download_all_prints', [App\Http\Controllers\Admin\CollectionController::class, 'download_all_prints'])->name('download_all_prints');
    Route::get('/collections/closed', [App\Http\Controllers\Admin\CollectionController::class, 'closed_collections'])->name('closed_collections');
    Route::post('/change_user_collection/{participation_id}', [App\Http\Controllers\Admin\ParticipationController::class, 'change_user_collection'])->name('change_user_collection');
    Route::post('/add_participation_comment/{participation_id}', [App\Http\Controllers\Admin\ParticipationController::class, 'add_participation_comment'])->name('add_participation_comment');

    Route::post('/add_user_comment/{user_id}', [App\Http\Controllers\Admin\UserController::class, 'add_user_comment'])->name('add_user_comment');


    Route::get('/own_books', [App\Http\Controllers\Admin\OwnBookController::class, 'index'])->name('own_books_index');
    Route::get('/own_books/closed', [App\Http\Controllers\Admin\OwnBookController::class, 'closed_own_books'])->name('closed_own_books');
    Route::get('/own_books/{own_book_id}', [App\Http\Controllers\Admin\OwnBookController::class, 'own_books_page'])->name('own_books_page');
    Route::get('/collections/new_participants', [App\Http\Controllers\Admin\ParticipationController::class, 'new_participants'])->name('new_participants');
    Route::get('/collections/participants/{collection_id}', [\App\Http\Controllers\Admin\ParticipationController::class, 'participants'])->name('participants');
    Route::get('/user/{user_id}', [\App\Http\Controllers\Admin\UserController::class, 'user_page'])->name('user_page');
    Route::post('/add_user_award/{user_id}', [\App\Http\Controllers\Admin\UserController::class, 'add_user_award'])->name('add_user_award');
    Route::resource('collection', \App\Http\Controllers\Admin\CollectionController::class,);
    Route::post('/collection/move_to_another_collection', [App\Http\Controllers\Admin\CollectionController::class, 'move_to_another_collection'])->name('move_to_another_collection');
    Route::get('/promocodes', [\App\Http\Controllers\Admin\PromocodeController::class, 'index'])->name('promocodes_page');
    Route::post('/add_winner/{collection_id}', [App\Http\Controllers\Admin\CollectionController::class, 'add_winner'])->name('add_winner');
    Route::get('/collections/participation/{participation_id}', [App\Http\Controllers\Admin\ParticipationController::class, 'user_participation'])->name('user_participation');
    Route::get('/chats_admin', [App\Http\Controllers\Admin\UserController::class, 'chats_admin'])->name('chats_admin');
    Route::get('/chats/{chat_id}', [App\Http\Controllers\Admin\UserController::class, 'chat'])->name('admin_chat');

    Route::get('/login_as/{user_id}', [App\Http\Controllers\Admin\UserController::class, 'login_as'])->name('login_as');

    Route::post('/change_user_status', [\App\Http\Controllers\Admin\ParticipationController::class, 'change_user_status'])->name('change_user_status');
    Route::post('/send_email_all_participants', [\App\Http\Controllers\Admin\CollectionController::class, 'send_email_all_participants'])->name('send_email_all_participants');

    Route::post('/change_book_status', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_book_status'])->name('change_book_status');
    Route::post('/change_amazon_link', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_amazon_link'])->name('change_amazon_link');
    Route::post('/change_book_inside_status', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_book_inside_status'])->name('change_book_inside_status');
    Route::post('/change_book_cover_status', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_book_cover_status'])->name('change_book_cover_status');
    Route::post('/change_book_pages', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_book_pages'])->name('change_book_pages');
    Route::post('/change_book_promo_type', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_book_promo_type'])->name('change_book_promo_type');
    Route::post('/add_own_book_comment/{own_book_id}', [App\Http\Controllers\Admin\OwnBookController::class, 'add_own_book_comment'])->name('add_own_book_comment');



    Route::post('/change_preview_comment_status/{preview_comment_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_preview_comment_status'])->name('change_preview_comment_status');
    Route::post('/change_all_preview_comment_status/{own_book_id}/{comment_type}', [\App\Http\Controllers\Admin\OwnBookController::class, 'change_all_preview_comment_status'])->name('change_all_preview_comment_status');
    Route::post('/change_all_preview_collection_comment_status/{collection_id}', [\App\Http\Controllers\Admin\CollectionController::class, 'change_all_preview_collection_comment_status'])->name('change_all_preview_collection_comment_status');

    Route::post('/update_own_book_cover/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_cover'])->name('update_own_book_cover');
    Route::post('/update_own_book_prices/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_prices'])->name('update_own_book_prices');
    Route::post('/update_own_book_inside/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_inside'])->name('update_own_book_inside');
    Route::post('/update_own_book_track_number/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_track_number'])->name('update_own_book_track_number');
    Route::post('/update_own_book_send_price/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_send_price'])->name('update_own_book_send_price');
    Route::post('/update_own_book_desc/{own_book_id}', [\App\Http\Controllers\Admin\OwnBookController::class, 'update_own_book_desc'])->name('update_own_book_desc');


    Route::get('/chats_users', [App\Http\Controllers\Admin\AdminSocialController::class, 'chats_users'])->name('chats_users');
    Route::get('/social_comments', [App\Http\Controllers\Admin\AdminSocialController::class, 'admin_social_comments'])->name('admin_social_comments');
    Route::get('/social_likes', [App\Http\Controllers\Admin\AdminSocialController::class, 'admin_social_likes'])->name('admin_social_likes');
    Route::get('/social_subs', [App\Http\Controllers\Admin\AdminSocialController::class, 'admin_social_subs'])->name('admin_social_subs');
    Route::get('/social_donates', [App\Http\Controllers\Admin\AdminSocialController::class, 'admin_social_donates'])->name('admin_social_donates');


    Route::get('/admin_stat', [App\Http\Controllers\Admin\AdminSocialController::class, 'admin_stat'])->name('admin_stat');

    Route::get('/transactions', function () {
        $transactions = \App\Models\Transaction::orderBy('created_at', 'desc')->get();
        return view('admin.transactions', [
            'transactions' => $transactions,
        ]);
    })->name('transactions_from_admin');

    Route::resource('user', \App\Http\Controllers\Admin\UserController::class);
    Route::get('/search_user/{users_input}', [App\Http\Controllers\Admin\UserController::class, 'search_user'])->name('search_user');
    Route::get('/subscribers', [App\Http\Controllers\Admin\UserController::class, 'subscribers_index'])->name('subscribers_index');
    Route::get('/subscribers/get', [App\Http\Controllers\Admin\UserController::class, 'subscribers_download'])->name('subscribers_download');
});

Route::middleware(['role:ext_promotion_admin|admin'])->prefix('admin_panel')->group(function () {
    Route::get('/ext_promotions', [App\Http\Controllers\Admin\ExtPromotionController::class, 'list'])->name('admin_ext_promotions');
    Route::get('/ext_promotions/all', [App\Http\Controllers\Admin\ExtPromotionController::class, 'list_all'])->name('admin_ext_promotions_all');
    Route::get('/ext_promotions/{id}', [App\Http\Controllers\Admin\ExtPromotionController::class, 'index'])->name('admin_ext_promotion');

    Route::post('/change_ext_promotion_status/{id}', [App\Http\Controllers\Admin\ExtPromotionController::class, 'change_ext_promotion_status'])->name('change_ext_promotion_status');
    Route::post('/add_ext_promotion_comment/{id}', [App\Http\Controllers\Admin\ExtPromotionController::class, 'add_ext_promotion_comment'])->name('add_ext_promotion_comment');

    Route::post('/change_chat_status/{chat_id}', [\App\Http\Controllers\ChatController::class, 'change_chat_status'])->name('change_chat_status');

});
// ----------------------------------------------
