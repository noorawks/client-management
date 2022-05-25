<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Exception;

use App\Models\User;
use App\Models\Organization;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?: null;

        $organizations = Organization::with('persons:id,organization_id,name')
                                    ->when($search, function ($query) use ($search) {
                                        return $query->where('name', 'LIKE', '%' . $search . '%')
                                                ->orWhere(function ($q) use ($search) {
                                                    $q->whereHas('persons', function ($q2) use ($search) {
                                                        $q2->where('name', 'LIKE', '%' . $search . '%');
                                                    });
                                                });
                                    })
                                    ->when(!Auth::user()->isAdmin, function ($query) {
                                        return $query->where('user_id', Auth::id());
                                    })
                                    ->orderByDesc('created_at')
                                    ->paginate(5);

        return view('organization.index', compact('organizations', 'search'));
    }

    public function create()
    {
        $account_managers = User::join('roles', 'roles.id', '=', 'users.role_id')
                                    ->where('roles.name', 'Account Manager')
                                    ->select('users.id', 'users.name')
                                    ->get();
                                    
        return view('organization.create', compact('account_managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
			'name'	=> 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'url' => 'nullable|url',
            'logo' => 'required|mimes:jpg,jpeg,png|max:1024',
		]);

        DB::beginTransaction();

        try {
            $organization = new Organization;
            $organization->user_id = $request->user_id;
            $organization->name = $request->name;
            $organization->email = $request->email;
            $organization->phone = $request->phone;
            $organization->url = $request->url;
            $organization->logo = $request->logo;
            
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $path = $image->store('public/organization/logo');
                $organization->logo = url(Storage::url('organization/logo')) . '/' . basename($path);
            }
            
            $organization->save();
            
            DB::commit();

            return redirect()->route('organization.index')->withSuccess('Organization created');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('organization.index')->withError('Failed to create organization');
        } 
    }

    public function show($organization_id)
    {
        $organization = Organization::with('persons')
                                    ->when(!Auth::user()->isAdmin, function ($query) {
                                        return $query->where('user_id', Auth::id());
                                    })
                                    ->find($organization_id);

        if (!$organization)
            return redirect('organization')->withError('Organization not found');

        return view('organization.show', compact('organization'));
    }

    public function edit($organization_id)
    {
        $organization = Organization::when(!Auth::user()->isAdmin, function ($query) {
                                        return $query->where('user_id', Auth::id());
                                    })
                                    ->find($organization_id);

        $account_managers = User::join('roles', 'roles.id', '=', 'users.role_id')
                                ->where('roles.name', 'Account Manager')
                                ->select('users.id', 'users.name')
                                ->get();
        
        if (!$organization)
            return redirect()->route('organization.index')->withError('Organization not found!');

        return view('organization.edit', compact('organization', 'account_managers'));
    }

    public function update(Request $request, $organization_id)
    {
        $organization = Organization::when(!Auth::user()->isAdmin, function ($query) {
                                        return $query->where('user_id', Auth::id());
                                    })
                                    ->find($organization_id);

        if (!$organization)
            return redirect('organization')->withError('Organization not found');

        $request->validate([
            'name'	=> 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'url' => 'nullable|url',
        ]);

        DB::beginTransaction();

        try {
            $old_photo = $organization->logo;

            $organization->name = $request->name;
            $organization->user_id = $request->user_id;
            $organization->email = $request->email;
            $organization->phone = $request->phone;
            $organization->url = $request->url;
            
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $path = $image->store('public/organization/logo');
                $organization->logo = url(Storage::url('organization/logo')) . '/' . basename($path);

                if (Storage::disk('public')->exists('organization/logo/' . basename($old_photo))) {
                    Storage::disk('public')->delete('organization/logo/' . basename($old_photo));
                }
            }
            
            $organization->save();
            
            DB::commit();

            return redirect()->route('organization.show', $organization->id)->withSuccess('Organization updated');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('organization.show', $organization->id)->withError('Failed to update organization');
        } 
    }

    public function destroy($organization_id)
    {
        $organization = Organization::with('persons')
                                    ->when(!Auth::user()->isAdmin, function ($query) {
                                        return $query->where('user_id', Auth::id());
                                    })
                                    ->find($organization_id);

        if (!$organization)
            return redirect()->route('organization.index')->withError('Organization not found!');
        
        DB::beginTransaction();

        try {
            if (Storage::disk('public')->exists('organization/logo/' . basename($organization->logo)))
                Storage::disk('public')->delete('organization/logo/' . basename($organization->logo));
            
            //delete all avatars from persons data
            foreach ($organization->persons()->pluck('avatar') as $avatar) {
                if (Storage::disk('public')->exists('person/avatar/' . basename($avatar)))
                    Storage::disk('public')->delete('person/avatar/' . basename($avatar));
            }
            
            $organization->delete();

            DB::commit();

            return redirect()->route('organization.index')->withSuccess('Organization deleted');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('organization.index')->withSuccess('Failed to delete organization');
        }
    }
}
