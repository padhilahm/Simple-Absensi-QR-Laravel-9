<?php

namespace App\Services;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SettingService
{
    protected Setting $setting;
    protected User $user;

    public function __construct(Setting $setting, User $user)
    {
        $this->setting = $setting;
        $this->user = $user;
    }

    public function updateUser($request)
    {
        $password = $request->password;
        $userId = auth()->user()->id;
        DB::beginTransaction();
        try {
            $user = $this->user->find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->school_name = $request->school_name;
            if ($password) {
                $user->password = bcrypt($password);
            }
            if ($request->hasFile('photo')) {
                // delete old photo
                $oldPhoto = public_path('storage/images/' . $user->photo);
                if (file_exists($oldPhoto)) {
                    @unlink($oldPhoto);
                }

                // upload new photo
                $photo = $request->file('photo');
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('storage/images'), $photoName);
                $user->photo = $photoName;
            }
            $user->save();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
