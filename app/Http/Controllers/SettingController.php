<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $template = \App\Models\Setting::getWaTemplate();
        $reminderDays = \App\Models\Setting::getReminderDays();
        $companyName = \App\Models\Setting::getCompanyName();
        $companyAddress = \App\Models\Setting::where('key', 'company_address')->first()?->value ?? '';
        $companyPhone = \App\Models\Setting::where('key', 'company_phone')->first()?->value ?? '';

        return view('admin.settings.index', compact('template', 'reminderDays', 'companyName', 'companyAddress', 'companyPhone'));
    }

    public function update(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'wa_template' => 'required|string',
            'reminder_days' => 'required|integer|min:1|max:30',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:50',
        ]);
        
        \App\Models\Setting::updateOrCreate(
            ['key' => 'wa_template'],
            ['value' => $request->wa_template, 'setting_group' => 'notification', 'description' => 'WhatsApp reminder message template']
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'reminder_days'],
            ['value' => $request->reminder_days, 'setting_group' => 'notification', 'description' => 'Days before service date to send reminder']
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'company_name'],
            ['value' => $request->company_name, 'setting_group' => 'company', 'description' => 'Company name for reports and member cards']
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'company_address'],
            ['value' => $request->company_address, 'setting_group' => 'company', 'description' => 'Company address']
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'company_phone'],
            ['value' => $request->company_phone, 'setting_group' => 'company', 'description' => 'Company phone/WhatsApp']
        );

        return back()->with('success', 'Settings updated successfully.');
    }
}
