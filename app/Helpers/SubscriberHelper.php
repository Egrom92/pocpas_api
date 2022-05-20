<?php


namespace App\Helpers;

use App\Models\Subscriber;
use DateTime;
use Illuminate\Support\Carbon;

class SubscriberHelper
{
    public const TIME_LIMIT = 1;

    public static function sessionCheck(Subscriber $subscriber): bool
    {
        $to = Carbon::parse(new DateTime());
        $from = Carbon::parse($subscriber->authorization_time);

        $timeDuration = $to->diffInMinutes($from);

        if ($timeDuration >= self::TIME_LIMIT) {
            $subscriber->authorization_state = false;
            $subscriber->save();
            return false;
        }

        return true;
    }

    public static function subRes($response = null): array
    {
        return ['session' => (boolean)$response, 'response_data' => $response];
    }
}
