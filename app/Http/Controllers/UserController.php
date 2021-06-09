<?php

namespace App\Http\Controllers;

use App\Models\AccBond;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function index()
    {
        if (\request()->keys) {
            return  User::whereHas('roles', function ($q) {
                return $q->where('role_id', 107);
            })->get();
        }
    }


    public function store()
    {
        DB::beginTransaction();
        try {
            request()->validate([
                'email' => 'required|email|unique:users',
                'password2' => 'required|min:6'
            ]);

            $img = '';
            if (\request()->hasFile('img')) $img = \request()->img->store('user_images', ['disk' => 'public']);
            $user = User::create(request()->only('name', 'area', 'school', 'committee', 'telephone', 'telephone2', 'email',   'type', 'national_id', 'gender', 'password2') + ['img' => $img, 'password' => bcrypt(request()->password2), 'account_id' => Auth::user()->account_id]);
            $user->roles()->sync(request()->roles);
            if (\request()->tasks && \is_array(request()->tasks))  $user->tasks()->createMany(request()->tasks);
            DB::commit();
            return $user->load('roles:name', 'tasks');
        } catch (\Throwable $th) {
            DB::rollback();
            return \response()->json($th->getMessage(), 404);
        }
    }

    public function update(User $user)
    {

        if (\request()->add_key) {
            foreach (request()->person_ids as $id) {
                $user->persons()->syncWithoutDetaching([$id => ['percent' => 100]]);
            }
            return 'ok';
        }
        //return request();
        request()->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password2' => 'required|min:6'
        ]);
        DB::beginTransaction();
        try {
            $user->update(request()->only('email', 'name',  'account_state', 'telephone', 'telephone2', 'type', 'national_id', 'gender', 'password2') + ['password' => bcrypt(request()->password2)]);
            if (\request()->hasFile('img')) {
                $img = \request()->img->store('user_images', ['disk' => 'public']);
                $user->update(['img' => $img]);
            }
            $user->roles()->sync(request()->roles);
            $user->tasks()->delete();
            if (\request()->tasks && \is_array(\request()->tasks)) {
                $user->tasks()->createMany(request()->tasks);
            }
            DB::commit();
            return $user->load('roles:name', 'tasks');
        } catch (\Throwable $th) {
            DB::rollback();
            return \response()->json($th->getMessage(), 404);
        }
    }



    public function show(User $user)
    {
        return $user->load(['roles']);
    }



    public function destroy(User $user)
    {
        $user->delete();
        return ['success' => 'User was Deleted'];
    }
}
