<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Exception;

use App\Models\Person;

class PersonController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'	=> 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'avatar' => 'required|mimes:jpg,jpeg,png|max:1024',
		]);
        

        try {
            $person = new Person;
            $person->name = $request->name;
            $person->organization_id = $request->organization_id;
            $person->email = $request->email;
            $person->phone = $request->phone;
            
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $path = $image->store('public/person/avatar');
                $person->avatar = url(Storage::url('person/avatar')) . '/' . basename($path);
            }
            
            $person->save();
            
            DB::commit();

            return redirect()->back()->withSuccess('Person created');
        } catch (\Exception $e) {
            DB::rollback();

            dd($e->getMessage());

            return redirect()->back()->withError('Failed to create person');
        } 
    }

    public function edit($person_id)
    {
        $person = Person::find($person_id);

        return $person;
    }

    public function update(Request $request, $person_id)
    {
        $person = Person::find($person_id);

        if (!$person)
            return redirect()->back()->withError('Person not found');

        $request->validate([
            'name'	=> 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $old_photo = $person->avatar;

            $person->name = $request->name;
            $person->email = $request->email;
            $person->phone = $request->phone;
            
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $path = $image->store('public/person/avatar');
                $person->avatar = url(Storage::url('person/avatar')) . '/' . basename($path);

                if (Storage::disk('public')->exists('person/avatar/' . basename($old_photo))) {
                    Storage::disk('public')->delete('person/avatar/' . basename($old_photo));
                }
            }
            
            $person->save();
            
            DB::commit();

            return redirect()->back()->withSuccess('Person updated');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withError('Failed to update person');
        } 
    }

    public function destroy($person_id)
    {
        $person = Person::find($person_id);

        if (!$person) {
            return redirect()->back()->withError('Person not found!');
        }

        try {
            if (Storage::disk('public')->exists('person/avatar/' . basename($person->avatar))) {
                Storage::disk('public')->delete('person/avatar/' . basename($person->avatar));
            }
    
            $person->delete();

            return redirect()->back()->withSuccess('Person deleted');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
