<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Place;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        if (isset($input['userimg'])) { // no existing - FileList or null
            $input['userimg'] = $input['userimg']['0'];
        }
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'user_place_id' => 'exists:places,placeid',
            'userimg' => 'mimes:jpg,bmp,png,gif',
        ])->validate();

        $input['password'] = Hash::make($input['password']);
        $input['role_id'] = 1; //user / passenger - no matter what from client

        return User::create($input);
    }
}
