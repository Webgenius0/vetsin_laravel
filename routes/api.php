<?php

use App\Http\Controllers\Api\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\DynamicPageController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\SitesettingController;
use App\Http\Controllers\Api\SocialLinkController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PropertyListingController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\NotificationSettingsController;
use App\Http\Controllers\Api\DynamicInputController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//Social Login
Route::post('/social-login', [SocialAuthController::class, 'socialLogin']);

//Register API
Route::controller(RegisterController::class)->prefix('users/register')->group(function () {
    // User Register
    Route::post('/', 'userRegister');

    // Verify OTP
    Route::post('/otp-verify', 'otpVerify');

    // Resend OTP
    Route::post('/otp-resend', 'otpResend');
    //email exists check
    Route::post('/email-exists', 'emailExists');
});

//Login API
Route::controller(LoginController::class)->prefix('users/login')->group(function () {

    // User Login
    Route::post('/', 'userLogin');

    // Verify Email
    Route::post('/email-verify', 'emailVerify');

    // Resend OTP
    Route::post('/otp-resend', 'otpResend');

    // Verify OTP
    Route::post('/otp-verify', 'otpVerify');

    //Reset Password
    Route::post('/reset-password', 'resetPassword');
});

Route::controller(SitesettingController::class)->group(function () {
    Route::get('/site-settings', 'siteSettings');
});

//Dynamic Page
Route::controller(DynamicPageController::class)->group(function () {
    Route::get('/dynamic-pages', 'dynamicPages');
    Route::get('/dynamic-pages/single/{slug}', 'single');
});

//Social Links
Route::controller(SocialLinkController::class)->group(function () {
    Route::get('/social-links', 'socialLinks');
});

//FAQ APIs
Route::controller(FaqController::class)->group(function () {
    Route::get('/faq/all', 'FaqAll');
});


Route::group(['middleware' => ['jwt.verify']], function () {

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/data', 'userData');
        Route::post('/data/update', 'userUpdate');
        Route::post('/password/change', 'passwordChange');
        Route::post('/logout', 'logoutUser');
        Route::delete('/delete', 'deleteUser');
    });

    // Favorite Routes
    Route::controller(FavoriteController::class)->prefix('favorites')->group(function () {
        Route::post('/add', 'addToFavorites');
        Route::post('/remove', 'removeFromFavorites');
        Route::get('/my-favorites', 'getMyFavorites');
        Route::get('/favorited-by-me', 'getFavoritedByMe');
        Route::post('/check', 'checkIfFavorited');
        Route::get('/count', 'getFavoriteCount');
        Route::delete('/clear-all', 'clearAllFavorites');
    });

    // Profile Routes
    Route::controller(ProfileController::class)->prefix('profiles')->group(function () {
        Route::get('/random', 'getRandomProfiles');
        Route::get('/matching', 'getMatchingProfiles');
        Route::get('/details/{id}', 'getProfileDetails');
    });

    Route::controller(PropertyListingController::class)->group(function () {
        Route::get('/property-listings', 'index');
        Route::get('/property-listings/{id}', 'show');
        Route::post('/property-listings', 'store');
        Route::post('/property-listings/{id}', 'update');
        Route::delete('/property-listings/{id}', 'destroy');
        Route::get('/my-properties', 'myProperties');
    });

    // Notification routes
    Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
        Route::get('/', 'getNotifications');
        Route::get('/unread-count', 'getUnreadCount');
        Route::get('/stats', 'getNotificationStats');
        Route::post('/mark-as-read', 'markAsRead');
        Route::post('/mark-all-as-read', 'markAllAsRead');
        Route::post('/delete', 'deleteNotification');
        Route::post('/delete-all', 'deleteAllNotifications');
    });

    // Notification Settings routes
    Route::controller(NotificationSettingsController::class)->prefix('notification-settings')->group(function () {
        Route::post('/toggle', 'toggleNotifications');
    });
});

Route::get('/age-preferences', [DynamicInputController::class, 'age_preferences']);
Route::get('/prefered-property-types', [DynamicInputController::class, 'prefered_property_types']);
Route::get('/choose-your-identities', [DynamicInputController::class, 'choose_your_identities']);
Route::get('/budgets', [DynamicInputController::class, 'budgets']);
