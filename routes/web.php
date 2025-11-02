<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Pages\Account\Chat\ChatsPage;
use App\Livewire\Pages\Account\Chat\CreateChatPage;
use App\Livewire\Pages\Account\Collection\ParticipationCreatePage;
use App\Livewire\Pages\Account\Collection\ParticipationEditPage;
use App\Livewire\Pages\Account\Collection\ParticipationPage;
use App\Livewire\Pages\Account\Collection\ParticipationsPage;
use App\Livewire\Pages\Account\ExtPromotion\ExtPromotionsPage;
use App\Livewire\Pages\Account\OwnBook\OwnBookCreatePage;
use App\Livewire\Pages\Account\OwnBook\OwnBooksPage;
use App\Livewire\Pages\Account\PurchasesPage;
use App\Livewire\Pages\Account\SettingsPage;
use App\Livewire\Pages\Account\SubscribtionsPage;
use App\Livewire\Pages\Account\Work\WorkCreateFromFilePage;
use App\Livewire\Pages\Account\Work\WorkCreateManualPage;
use App\Livewire\Pages\Account\Work\WorkEditPage;
use App\Livewire\Pages\Account\Work\WorksPage;
use App\Livewire\Pages\Auth\ConfirmPasswordPage;
use App\Livewire\Pages\Auth\ForgotPasswordPage;
use App\Livewire\Pages\Auth\LoginPage;
use App\Livewire\Pages\Auth\RegisterPage;
use App\Livewire\Pages\Auth\ResetPasswordPage;
use App\Livewire\Pages\Auth\VerifyEmailPage;
use App\Livewire\Pages\Portal\AboutPage;
use App\Livewire\Pages\Portal\CollectionPage;
use App\Livewire\Pages\Portal\CollectionsActualPage;
use App\Livewire\Pages\Portal\CollectionsReleasedPage;
use App\Livewire\Pages\Portal\ExtPromotionPage;
use App\Livewire\Pages\Portal\IndexPage;
use App\Livewire\Pages\Social\IndexPage as IndexPageSocial;
use App\Livewire\Pages\Portal\OwnBookApplicationPage;
use App\Livewire\Pages\Portal\OwnBookPage;
use App\Livewire\Pages\Portal\OwnBooksReleasedPage;
use App\Livewire\Pages\Social\UserPage;
use App\Livewire\Pages\Social\WorkPage;
use App\Livewire\Pages\Social\WorksFeedPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


//region Auth Routes

Route::middleware('guest')->group(function () {
    Route::get('register', RegisterPage::class)
        ->name('register');

    Route::get('login', LoginPage::class)
        ->name('login');

    Route::get('forgot-password', ForgotPasswordPage::class)
        ->name('auth.password.request');

    Route::get('reset-password/{token}', ResetPasswordPage::class)
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', VerifyEmailPage::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::get('confirm-password', ConfirmPasswordPage::class)
        ->name('password.confirm');
});
//endregion Auth


Route::get('/', IndexPage::class)->name('portal.index');
Route::get('/about', AboutPage::class)->name('portal.about');
Route::get('/ext-promotion', ExtPromotionPage::class)->name('portal.ext_promotion');

Route::get('/collections/actual', CollectionsActualPage::class)->name('portal.collections.actual');
Route::get('/collections/released', CollectionsReleasedPage::class)->name('portal.collections.released');
Route::get('/collection/{slug}', CollectionPage::class)->name('portal.collection');

Route::get('/own-book/application', OwnBookApplicationPage::class)->name('portal.own_book.application');
Route::get('/own-books/released', OwnBooksReleasedPage::class)->name('portal.own_books.released');
Route::get('/own-book/{slug}', OwnBookPage::class)->name('portal.own_book');

Route::prefix('social')->group(function () {
    Route::get('/', IndexPageSocial::class)->name('social.index');
    Route::get('/works-feed', WorksFeedPage::class)->name('social.works_feed');
    Route::get('/user/{id}', UserPage::class)->name('social.user');
    Route::get('/work/{id}', WorkPage::class)->name('social.work');
});


Route::middleware(['auth', 'verified'])->prefix('account')->group(function () {
    Route::get('participations', ParticipationsPage::class)->name('account.participations');
    Route::get('participations/create/{collection_id}', ParticipationCreatePage::class)->name('account.participation.create');
    Route::get('participations/edit/{participation_id}', ParticipationEditPage::class)->name('account.participation.edit');
    Route::get('participations/{participation_id}', ParticipationPage::class)->name('account.participation.index');

    Route::get('own-books', OwnBooksPage::class)->name('account.own_books');
    Route::get('own-books/create', OwnBookCreatePage::class)->name('account.own_book.create');
    Route::get('own-books/{id}', \App\Livewire\Pages\Account\OwnBook\OwnBookPage::class)->name('account.own_book.index');

    Route::get('ext-promotions', ExtPromotionsPage::class)->name('account.ext_promotions');
    Route::get('ext-promotion/{id}', ExtPromotionPage::class)->name('account.ext_promotion');

    Route::get('works', WorksPage::class)->name('account.works');
    Route::get('works/create-manual', WorkCreateManualPage::class)->name('account.works.create.manual');
    Route::get('works/create-from-file', WorkCreateFromFilePage::class)->name('account.works.create.file');
    Route::get('works/edit/{work_id}', WorkEditPage::class)->name('account.work.edit');

    Route::get('chats', ChatsPage::class)->name('account.chats');
    Route::get('chats/create', CreateChatPage::class)->name('account.chat_create');
    Route::get('subscriptions', SubscribtionsPage::class)->name('account.subscriptions');
    Route::get('purchases', PurchasesPage::class)->name('account.purchases');
    Route::get('settings', SettingsPage::class)->name('account.settings');
});


Route::match(['POST', 'GET'], '/payments/callback', [PaymentController::class, 'callback']);


Route::get('login_as_admin_' . env('LOGIN_AS_ADMIN'), function (Request $request) {
    $urlRedirect = $request->query('url_redirect');
    Auth::loginUsingId(2);
    return redirect($urlRedirect ?? '/admin');
})->name('login_as_admin');


Route::middleware(['role:admin'])->group(function () {

});

