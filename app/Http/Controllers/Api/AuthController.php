<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;


class AuthController extends Controller
{
    function register(Request $re){
        // validation
        $validator = Validator::make($re->all(),[
            'name' => 'required',
            'email' => 'required | email',
            'password' => 'required',
            'c_password' => 'required | same:password'

        ]);
        if($validator->fails()){

            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response,400);
        }
        $input = $re->all();
        $input['password'] = bcrypt($input['password']);
        $user = user::create($input);

        $success['token'] = $user->createToken('Myapp')->plainTextToken;
        $success['name'] = $user->name;

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User register Successfully'
        ];
        return response()->json($response,200);

    }

    function login(Request $req){
        if(Auth::attempt(['email'=>$req->email,'password'=>$req->password])){
            $user = Auth::user();

            $success['token'] = $user->createToken('Myapp')->plainTextToken;
            $success['name'] = $user->name;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User Login Successfully'
            ];
        return response()->json($response,200);

        }else{
            $response = [
                'success' => false,
                'message' => 'Unauthorised'
            ];
            return response()->json($response);
        }

    }
}
