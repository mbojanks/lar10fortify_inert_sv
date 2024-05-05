<?php

namespace App\Providers;

use Inertia\Inertia;
use App\Models\Place;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PlaceResource;
use Illuminate\Support\Facades\Route;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use App\Actions\Fortify\UpdateUserProfileInformation;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        if (config('fortify.localized.i18n')) {
            Fortify::ignoreRoutes();
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //if (Fortify::$registersRoutes) {
        if (config('fortify.localized.i18n')) {
            Route::localized(
                function (): void {
                    $this->routes();
                }
            );
        } /* NO NEED FOR THIS BECAUSE NOT IGNORED else {
            $this->routes();
        } */
        //}
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function() {
            return Inertia::render('Auth/Login');
        });

        Fortify::registerView(function() {
            return Inertia::render('Auth/Register', ['optUrl' => config('app.url') . "/places/search", 'idUrl' => config('app.url') . "/places"]);
        });

        Fortify::verifyEmailView(function () {
            return Inertia::render('User/Show', [
                "message" => __('Confirmation email was sent to your email address you entered in the registration form.')
            ]);
        });
    }

    private function routes(): void {
        Route::group(
            [
                'namespace' => 'Laravel\Fortify\Http\Controllers',
                'domain' => config('fortify.domain', null),
                'prefix' => config('fortify.prefix'),
            ],
            function (): void {
                $this->loadRoutesFrom(__DIR__ . '/../../routes/fortify.php');
            }
        );
    }
}
