<?php

namespace W;

class Helper
{
    /**
     * s => 13,15-17,20*,...
     * @param  string $s
     * @return array
     */
    public static function parseDates(string $s): array
    {
        $dates = [];

        $s = self::trim($s);

        if (empty($s)) {
            return $dates;
        }
        $dateIntervals = explode(",", trim($s));

        foreach ($dateIntervals as $possibleInterval) {
            if (strpos($possibleInterval, '-') === false) {
                // date is simple date, no interval
                $dates[] = (int)$possibleInterval;
            } else {
                list($start, $end) = explode("-", $possibleInterval);
                for($i = (int)$start; $i <= $end; $i++) {
                    $dates[] = $i;
                }
            }
        }

        return $dates;
    }

    public static function trim(string $s): string
    {
        $s = trim($s);
        $s = str_replace([' ', '*'], '', $s);

        return $s;
    }

    public static function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        $radius = 6371;
        $dLat = deg2rad($lat2-$lat1);
        $dLon = deg2rad($lon2-$lon1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $radius * $c; // Distance in KMs

        return $d;
    }

    protected static function deg2rad($deg)
    {
        return $deg * (pi() / 180);
    }
}