<?php

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::redirect('/', '/'.App::getLocale(), 301);
if (config('fortify.localized.i18n')) {
    Route::localized(
        function (): void {
            routesToLocalize();
        }
    );
} else {
    routesToLocalize();
}

function routesToLocalize() {
    Route::get('/', function () {
        return Inertia::render('User/Show'/*, [
            'user' => Auth::user()
        ]*/);
    });
    Route::get('/contact', function() {
        return Inertia::render('Contact/Show');
    });
    Route::get('/company/{id}', function($id) {
        return Inertia::render('Company/Edit', ['companyid' => $id]);
    });
    Route::get('/user/profile-information', function() {
        return Inertia::render('User/Profile',['optUrl' => config('app.url') . "/places/search", 'idUrl' => config('app.url') . "/places"]);
    });
    Route::get('/user/profile', function() {
        return Inertia::render('User/UserProfile',['optUrl' => config('app.url') . "/places/search", 'idUrl' => config('app.url') . "/places"]);
    });
}

// API-like - no translations
Route::get('/places/search', [PlaceController::class, 'search']);
Route::get('places/{place}', [PlaceController::class, 'show']);
Route::get('/aitest/imgcreate', [PlaceController::class, 'aitest']);


Route::fallback(\CodeZero\LocalizedRoutes\Controllers\FallbackController::class);
