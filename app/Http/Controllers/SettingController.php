<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Services\SettingService;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Requests\UpdateSettingUserRequest;

class SettingController extends Controller
{
    protected Setting $setting;
    protected User $user;
    protected SettingService $settingService;

    public function __construct(Setting $setting, User $user, SettingService $settingService)
    {
        $this->setting = $setting;
        $this->user = $user;
        $this->settingService = $settingService;
    }

    public function index()
    {
        $setting = $this->setting->first();
        $user = $this->user->find(auth()->user()->id);
        return view('setting.index-attendance', compact('setting', 'user'));
    }

    public function indexUser()
    {
        $setting = $this->setting->first();
        $user = $this->user->find(auth()->user()->id);
        return view('setting.index', compact('setting', 'user'));
    }

    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        // update setting
        $setting =  $this->setting->find($request->id);
        $setting->update($request->all());

        if ($setting) {
            return redirect()->route('setting.index-attendance')->with('success', 'Setting berhasil diubah');
        } else {
            return redirect()->route('setting.index-attendance')->with('error', 'Setting gagal diubah');
        }
    }

    public function updateUser(UpdateSettingUserRequest $request)
    {
        $updateUser = $this->settingService->updateUser($request);

        if ($updateUser) {
            return redirect()->route('setting.index')->with('success', 'User berhasil diubah');
        } else {
            return redirect()->route('setting.index')->with('error', 'User gagal diubah');
        }
    }
}
