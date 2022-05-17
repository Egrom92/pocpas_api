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

    public function addPassword($tg_id, Request $request): array
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();
        $passwordList = json_decode($subscriber->password_list);

        $hasKeyword = collect($passwordList)->firstWhere('keyword', $request->input('keyword'));

        if (!$hasKeyword) {
            $newPassword = Str::random(10);
            $passwordList[] = (object)[
                'keyword' => $request->input('keyword'),
                'password' => $newPassword
            ];
            $subscriber->password_list = json_encode($passwordList);
            $subscriber->save();
            return ['pass' => $newPassword, 'status' => true];
        } else {
            return ['pass' => $hasKeyword->password, 'status' => false];
        }

    }

    public function deletePassword($tg_id, Request $request): bool
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();
        $passwordList = json_decode($subscriber->password_list);

        $key = array_search($request->input('keyword'), array_column((array)$passwordList, 'keyword'));

        if ($key !== false) {
            array_splice($passwordList, $key, 1);
            $subscriber->password_list = json_encode($passwordList);
            $subscriber->save();
            return true;
        } else {
            return false;
        }
    }

    public function editPassword($tg_id, Request $request): bool|string
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();
        $passwordList = json_decode($subscriber->password_list);

        $key = array_search($request->input('keyword'), array_column((array)$passwordList, 'keyword'));

        if ($key !== false) {
            $newPassword = Str::random(10);
            $passwordList[$key]->password = $newPassword;
            $response = $newPassword;
            $subscriber->password_list = json_encode($passwordList);
            $subscriber->save();
            return $response;
        } else {
            return false;
        }
    }

    public function getPassword($tg_id, Request $request)
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();

        $password = collect(json_decode($subscriber->password_list))->firstWhere('keyword', $request->input('keyword'));
        if ($password) {
            return $password;
        } else {
            return false;
        }
    }

    public function getAllPassword($tg_id): bool|\Illuminate\Support\Collection
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();
        return $subscriber->password_list ? collect(json_decode($subscriber->password_list)) : false;
    }

    public function store(Request $request): string
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
