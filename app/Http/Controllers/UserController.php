<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->json()->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create([
            'name' => $request->json()->get('name'),
            'email' => $request->json()->get('email'),
            'password' => Hash::make($request->json()->get('password'))
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function login(Request $request)
    {
        $credentials = $request->json()->all();
        // dd($credentials);
        // dd(JWTAuth::attempt($credentials));
        try{
            if (!$token = JWTAuth::attempt($credentials)){
                return response()->json(['error'=>'invalid credentials'],400);
            }
        }catch (JWTException $e){
            return response()->json(['error' => 'could_not_crete_token'],500);
        }

        return response()->json(compact('token'));
    }

    public function getAuthenticatedUser()
    {
        try{
            if(!$user = JWTAuth::parseToken()->authenticate())
            {
                return response()->json(['user not found'],400);
            }
        }catch (TokenExpiredException $e){
            return response()->json(['Token Expired'], 401);
        }catch (TokenInvalidException $e){
            return response()->json(['Token Invalid'], 401);
        }catch (JWTException $e){
            return response()->json(['Token_absent'], 401);
        }

        return response()->json(compact('user'));
    }
}
