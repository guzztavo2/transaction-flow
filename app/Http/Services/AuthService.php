<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Validator;

class AuthService extends Service implements Validator
{
    private Validator $validator;

    public function __construct(Validator $validator){
        $this->validator = $validator;

    }

    public function register(Array $request){
        $this->validator->validate($request, [
            "name" => ['required', 'max:100', 'string'],
            "email" => ['required', 'max:100', 'string'],
            "confirm_password" => ['required', 'max:100', 'string'],
            "password" => ['required', 'max:100', 'string'],
            "bank" => ['required', 'max:100', 'string'],
            "agency" => ['required', 'max:100', 'string'],
            "number_account" => ['required', 'max:100', 'string']
        ]);

    
        

    }
    
    public function login(Array $request){
        $this->validator->validate($request, [
            "name" => ['required', 'max:100', 'string'],
            "email" => ['required', 'max:100', 'string'],
            "confirm_password" => ['required', 'max:100', 'string'],
            "password" => ['required', 'max:100', 'string']
        ]);

    }
    
    public function changePassword(Array $request){
       $this->validator->validate($request, [
            "name" => ['required', 'max:100', 'string'],
            "email" => ['required', 'max:100', 'string'],
            "confirm_password" => ['required', 'max:100', 'string'],
            "password" => ['required', 'max:100', 'string']
        ]);

    }
    
    public function resetPassword(Array $request){
       $this->validator->validate($request, [
            "name" => ['required', 'max:100', 'string'],
            "email" => ['required', 'max:100', 'string']
        ]);

    }
    
}
 