<?php namespace App\Data\Weather;

class Helper {

    /**
     * A text representation of the conditions
     *
     * @param $duePoint
     * @param $temperature
     * @return string
     */
    public static function weatherCondition($duePoint, $temperature)
    {
        if ($duePoint > 26) {
            return 'Severe';
        } elseif ($duePoint > 24) {
            return 'Extremely Uncomfortable';
        } elseif ($duePoint > 21) {
            return 'Very Humid, Uncomfortable';
        } elseif ($duePoint > 18) {
            return 'Somewhat Uncomfortable';
        } elseif ($duePoint > 16) {
            if ($temperature > 24) {
                return 'Hot';
            } elseif ($temperature > 22) {
                return 'Warm';
            }
            return 'OK';
        } elseif ($duePoint > 13) {
            if ($temperature > 24) {
                return 'Hot But Comfortable';
            } elseif ($temperature > 22) {
                return 'Warm But Comfortable';
            }
            return 'Comfortable';
        } elseif ($duePoint > 10) {
            if ($temperature > 24) {
                return 'Hot But Comfortable';
            } elseif ($temperature > 22) {
                return 'Warm But Comfortable';
            }
            return 'Very Comfortable';
        } else {
            return 'Dry';
        }
    }

    /**
     * @param float   $temperature
     * @param integer $humidity
     * @return float
     */
    public static function calculateDuePoint($temperature, $humidity)
    {
        return $temperature - ((100 - $humidity) / 5);
    }

    /**
     * @param float $temp
     * @return float
     */
    public static function convertFtoC($temp)
    {
        return round(($temp - 32) / 1.8, 1);
    }
}