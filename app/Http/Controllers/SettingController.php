<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;
        $setting = Setting::first();
        $user = User::find($userId);
        return view('setting.index-attendance', compact('setting', 'user'));
    }

    public function indexUser()
    {
        $userId = auth()->user()->id;
        $setting = Setting::first();
        $user = User::find($userId);
        return view('setting.index', compact('setting', 'user'));
    }

    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        $validate = [
            'attendance_start_time' => 'required',
            'attendance_end_time' => 'required',
        ];

        $request->validate($validate);

        $id = $request->id;

        DB::beginTransaction();
        try {
            $setting = Setting::find($id);
            $setting->update($request->all());

            DB::commit();
            return redirect()->route('setting.index-attendance')->with('success', 'Setting berhasil diubah');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('setting.index-attendance')->with('error', 'Setting gagal diubah');
        }
    }

    public function updateUser(UpdateSettingRequest $request)
    {
        $validate = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'nullable|min:8',
            're_password' => 'nullable|same:password',
            'school_name' => 'required|min:3',
        ];

        $request->validate($validate);
        $password = $request->password;

        $userId = auth()->user()->id;

        DB::beginTransaction();
        try {
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->school_name = $request->school_name;
            if ($password) {
                $user->password = bcrypt($password);
            }
            $user->save();

            DB::commit();
            return redirect()->route('setting.index')->with('success', 'Setting berhasil diubah');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('setting.index')->with('error', 'Setting gagal diubah');
        }
    }

    public function destroy(Setting $setting)
    {
        //
    }
}
