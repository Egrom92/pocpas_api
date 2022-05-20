<?php

namespace App\Http\Controllers;

use App\Helpers\SubscriberHelper;
use App\Models\Subscriber;
use DateTime;
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
        $state = $subscriber->master_password === $master_password;

        if ($state) {
            $subscriber->authorization_state = true;
            $subscriber->authorization_time = new DateTime();
            $subscriber->save();
        }

        return $state;
    }

    public function addPassword($tg_id, Request $request): array
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();

        if (!SubscriberHelper::sessionCheck($subscriber)) {
            return  SubscriberHelper::subRes();
        }

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
            return SubscriberHelper::subRes(['password' => $newPassword, 'status' => true]);
        } else {
            return SubscriberHelper::subRes(['password' => $hasKeyword->password, 'status' => false]);
        }

    }

    public function deletePassword($tg_id, Request $request): array
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();

        if (!SubscriberHelper::sessionCheck($subscriber)) {
            return  SubscriberHelper::subRes();
        }

        $passwordList = json_decode($subscriber->password_list);

        $key = array_search($request->input('keyword'), array_column((array)$passwordList, 'keyword'));

        if ($key !== false) {
            array_splice($passwordList, $key, 1);
            $subscriber->password_list = json_encode($passwordList);
            $subscriber->save();
            return SubscriberHelper::subRes(true);
        } else {
            return SubscriberHelper::subRes(false);
        }
    }

    public function editPassword($tg_id, Request $request): array
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();

        if (!SubscriberHelper::sessionCheck($subscriber)) {
            return  SubscriberHelper::subRes();
        }

        $passwordList = json_decode($subscriber->password_list);

        $key = array_search($request->input('keyword'), array_column((array)$passwordList, 'keyword'));

        if ($key !== false) {
            $newPassword = Str::random(10);
            $passwordList[$key]->password = $newPassword;
            $response = $newPassword;
            $subscriber->password_list = json_encode($passwordList);
            $subscriber->save();
            return SubscriberHelper::subRes($response);
        } else {
            return SubscriberHelper::subRes(false);
        }
    }

    public function getPassword($tg_id, Request $request): array
    {

        $subscriber = Subscriber::where('tg_id', $tg_id)->first();

        if (!SubscriberHelper::sessionCheck($subscriber)) {
            return  SubscriberHelper::subRes();
        }

        $password = collect(json_decode($subscriber->password_list))->firstWhere('keyword', $request->input('keyword'));
        if ($password) {
            return SubscriberHelper::subRes($password);
        } else {
            return SubscriberHelper::subRes(false);
        }
    }

    public function getAllPassword($tg_id): array
    {
        $subscriber = Subscriber::where('tg_id', $tg_id)->first();

        if (!SubscriberHelper::sessionCheck($subscriber)) {
            return  SubscriberHelper::subRes();
        }

        return $subscriber->password_list ?
            SubscriberHelper::subRes(collect(json_decode($subscriber->password_list))) :
            SubscriberHelper::subRes(false);
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
