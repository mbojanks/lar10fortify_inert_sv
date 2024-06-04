<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use CodeZero\LocalizedRoutes\Facades\LocaleConfig;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $sharedProps = [
            // lazy load common props
            'user' => function() use($request) {
                //Auth::user()
                if (!$request->user()) {
                    return;
                }

                return array_merge(
                  $request->user()->toArray(),
                  [
                    'two_factor_enabled' => Features::enabled(Features::twoFactorAuthentication()) && ! is_null($request->user()->two_factor_secret),
                  ]
                );
            },
            /* for csrf in fetch
            '_token' => function () {
                return Session::token();
            },
            '_session' => function () {
                return Session::all();
            },
            */
        ];
        if (config('fortify.localized.i18n')) {
            $sharedProps['locale'] = fn () => App::getLocale();
            $sharedProps['locales'] = LocaleConfig::getLocales();
        }
        return array_merge(parent::share($request), $sharedProps);
    }
}
