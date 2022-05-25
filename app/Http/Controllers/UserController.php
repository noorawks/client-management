<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?: null;

        $users = User::when($search, function ($query) use ($search) {
                        return $query->where('name', 'LIKE', '%' . $search . '%')
                                    ->orWhere('email', 'LIKE', '%' . $search . '%');
                    })
                    ->orderByDesc('created_at')
                    ->paginate(10);

        return view('user.index', compact('users', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
			'name'	=> 'required|max:255',
            'email' => 'required|unique:users,email|email',
		]);

        $getAccountManagerRoleIDs = Role::where('name', 'Account Manager')->first();

        DB::beginTransaction();

        try {
            $user = new User;
            $user->role_id = $getAccountManagerRoleIDs ? $getAccountManagerRoleIDs->id : null;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            
            DB::commit();

            return redirect()->route('user.index')->withSuccess('Account manager created');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('user.index')->withError('Failed to create account manager');
        } 
    }

    public function getUser($user_id)
    {
        $user = User::find($user_id);
        return $user;
    }

    public function edit($user_id)
    {
        $user = User::find($user_id);
        return view('profile', compact('user'));
    }

    public function update(Request $request, $user_id)
    {
        $user = user::find($user_id);

        if (!$user)
            return redirect()->back()->withError('Account manager not found');

        $request->validate([
            'name'	=> 'required|max:255',
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)]
        ]);

        DB::beginTransaction();

        try {
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->password)
                $user->password = Hash::make($request->password);

            $user->save();
            
            DB::commit();

            return redirect()->back()->withSuccess('Account updated');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withError('Failed to update account');
        } 
    }

    public function destroy()
    {
        
    }
}
