<?php namespace Data\Weather;

class Helper {

    /**
     * A text representation of the due point conditions
     *
     * @param $duePoint
     * @return string
     */
    public static function weatherCondition($duePoint)
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
            return 'OK';
        } elseif ($duePoint > 13) {
            return 'Comfortable';
        } elseif ($duePoint > 10) {
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