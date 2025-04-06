<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Actions\ConfirmPassword;
use App\Contracts\DeletesUsers;
use Illuminate\Validation\ValidationException;

class CurrentUserController extends Controller
{
    //
    public function destroy(Request $request, StatefulGuard $guard)
    {
        $confirmed = app(ConfirmPassword::class)(
            $guard, $request->user(), $request->password
        );

        if (! $confirmed) {
            $validErr = ValidationException::withMessages([
                'password' => __('The provided password was incorrect.'),
            ]);
            $validErr->errorBag('deleteUser');
            throw $validErr;
        }

        //delete user -> maybe later within DB::transaction(function() use $user {})
        ($request->user()->fresh())->delete();
        //delete user role(s) ??

        $guard->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location(url('/'));
    }
}
