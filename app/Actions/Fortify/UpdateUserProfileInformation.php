<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        if (isset($input['userimg'])) {
            if (is_array($input['userimg'])) { // there is a new image - take the first image from the FileList
                $input['userimg'] = $input['userimg']['0'];
            } else {
                //error_log(str_replace(config('app.url') . '/storage',storage_path('app/public'),$input['userimg']));
                if (isset($input['userimg_filedelete'])) { // it is not FileList - it could be string -> check if an image on a userimg string URL should be deleted
                    if ($input['userimg_filedelete'] === '1') {
                        $input['userimg'] = null;
                    }
                }
            }
        }
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'user_place_id' => 'exists:places,placeid',
            'userimg' => [
                'nullable',
                Rule::unless(is_file(str_replace(config('app.url') . '/storage',storage_path('app/public'),$input['userimg'])),
                'mimes:jpg,bmp,png,gif')
            ],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'useraddress' => $input['useraddress'],
                'phone' => $input['phone'],
                'user_place_id' => $input['user_place_id'],
                'userimg' => $input['userimg']
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
            'useraddress' => $input['useraddress'],
            'phone' => $input['phone'],
            'user_place_id' => $input['user_place_id'],
            'userimg' => $input['userimg']
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
