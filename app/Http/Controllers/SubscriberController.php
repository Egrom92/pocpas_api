<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriberController extends Controller
{
    public function index($tg_id): bool
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();
        return (bool)$subscriber;
    }

    public function masterPasswordControl($tg_id, $master_password = null): bool
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();
        return $subscriber->master_password === $master_password;
    }

    public function addPassword($tg_id, Request $request)
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();
        $passwordList = json_decode($subscriber->password_list);
        $newPassword = Str::random(10);
        $passwordList[] = [
            'site_name' => $request->site,
            'password' => $newPassword
        ];
        $subscriber->password_list = json_encode($passwordList);
        Log::info($subscriber);
        $subscriber->save();
        return $newPassword;
    }

    public function store(Request $request)
    {
        $sbsc = new Subscriber;

        $sbsc->tg_id = $request->tg_id;
        $sbsc->first_name = $request->first_name;
        $sbsc->last_name = $request->last_name;
        $sbsc->username = $request->username;
        $sbsc->language_code = $request->language_code;
        $sbsc->master_password = $request->master_password;

        $sbsc->save();

        return 'Новый пользователь создан';
    }
}
