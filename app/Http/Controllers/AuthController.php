<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use App\Notifications\Note;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }


    public function user()
    {
        return response()->json([
            'status' => 'success',
            'data' => auth()->user()->only('id', 'name', 'permissions', 'telephone')
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    protected function respondWithToken($token)
    {

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',

            //    'expires_in' => auth()->factory()->getTTL()*60
        ], 200)->header('Authorization', $token);
    }





    public function resetPassword(Request $request)
    {
        // $user = User::where('reset', $request->code)->first();
        // if ($user == null) {
        //     return 'invalid link';
        // }
        // $user->update(['password' => bcrypt($request->new_password)]);
        // return ('password changed you can login with new password');
    }

    public function account_activation(Request $request)
    {
        // if (Auth::user()->r_password === $request->serial) {
        //     Auth::user()->account_state = 'active';
        //     Auth::user()->save();
        //     return 'account activated';
        // } else {
        //     return response()->json(['error' => 'incorrect serial'], 404);
        // }
    }
}
