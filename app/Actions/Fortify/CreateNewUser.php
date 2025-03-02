<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
        Validator::make($input, [
            'Username' => ['required', 'string', 'regex:/^[a-zA-Z]+$/'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => ['required', 'string', 'min:12', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/'],
            'passwordConfirmation' => ['required', 'same:password'],
        ])->validate();
        
        return User::create([
            'name' => $input['Username'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
