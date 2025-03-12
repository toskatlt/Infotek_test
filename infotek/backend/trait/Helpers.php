<?php

namespace app\trait;

use DateInterval;
use DateTime;
use Exception;

trait Helpers
{
    public static function uuid(): string
    {
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
    }

    public static function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * @throws Exception
     */
    public static function nowAddMinutes($minute = 1): string
    {
        $time = (new DateTime())->add(new DateInterval('PT' . $minute . 'M'));

        return $time->format('Y-m-d H:i:s');
    }

    public static function declOfNum($num, $titles) {
        $cases = array(2, 0, 1, 1, 1, 2);

        return $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    }

    /**
     * Check that $text is uuid
     *
     * @param $text
     * @return bool
     */
    public static function isUUID($text): bool {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
        return preg_match($pattern, $text) === 1;
    }
}