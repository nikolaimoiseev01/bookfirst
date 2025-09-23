<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Pages\Account\Chat\ChatsPage;
use App\Livewire\Pages\Account\Chat\CreateChatPage;
use App\Livewire\Pages\Account\Collection\CollectionsPage;
use App\Livewire\Pages\Account\Collection\ParticipationCreatePage;
use App\Livewire\Pages\Account\Collection\ParticipationPage;
use App\Livewire\Pages\Account\ExtPromotion\ExtPromotionsPage;
use App\Livewire\Pages\Account\OwnBook\OwnBooksPage;
use App\Livewire\Pages\Account\PurchasesPage;
use App\Livewire\Pages\Account\SettingsPage;
use App\Livewire\Pages\Account\SubscribtionsPage;
use App\Livewire\Pages\Account\Work\WorkCreateFromFilePage;
use App\Livewire\Pages\Account\Work\WorkCreateManualPage;
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
use App\Livewire\Pages\Portal\OwnBookApplicationPage;
use App\Livewire\Pages\Portal\OwnBookPage;
use App\Livewire\Pages\Portal\OwnBooksReleasedPage;
use App\Livewire\Pages\Social\UserPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


//region Auth Routes

Route::middleware('guest')->group(function () {
    Route::get('register', RegisterPage::class)
        ->name('auth.register');

    Route::get('login', LoginPage::class)
        ->name('auth.login');

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

Route::get('/user/{id}', UserPage::class)->name('social.user');

Route::middleware(['auth', 'verified'])->prefix('account')->group(function () {
    Route::get('collections', CollectionsPage::class)->name('account.collections');
    Route::get('collections/{collection_id}/participation/create', ParticipationCreatePage::class)->name('account.participation.create');
    Route::get('collections/participation/{participation_id}', ParticipationPage::class)->name('account.participation.edit');
    Route::get('own-books', OwnBooksPage::class)->name('account.own_books');
    Route::get('own-book/{id}', OwnBookPage::class)->name('account.own_book');
    Route::get('ext-promotions', ExtPromotionsPage::class)->name('account.ext_promotions');
    Route::get('ext-promotion/{id}', ExtPromotionPage::class)->name('account.ext_promotion');
    Route::get('works', WorksPage::class)->name('account.works');
    Route::get('works/create-manual', WorkCreateManualPage::class)->name('account.works.create.manual');
    Route::get('works/create-from-file', WorkCreateFromFilePage::class)->name('account.works.create.file');
    Route::get('chats', ChatsPage::class)->name('account.chats');
    Route::get('chats/create', CreateChatPage::class)->name('account.chat_create');
    Route::get('subscriptions', SubscribtionsPage::class)->name('account.subscriptions');
    Route::get('purchases', PurchasesPage::class)->name('account.purchases');
    Route::get('settings', SettingsPage::class)->name('account.settings');
});


Route::get('login_as_admin_' .  env('LOGIN_AS_ADMIN'), function() {
    Auth::loginUsingId(2);
    return redirect('/admin');
});


Route::middleware(['role:admin'])->group(function () {

});

