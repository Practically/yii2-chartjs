<?php

declare(strict_types=1);

namespace practically\chartjs\helpers;

/**
 * Helper class for chart data manipulation
 *
 * @copyright 2024 Practically.io. All rights reserved
 * @package practically/chartjs
 * @since 2.0.0
 */
class ChartDataHelper
{

    /**
     * Fills missing dates in the given data array with default values.
     *
     * @param array $data The data array.
     * @param int $from The starting date in Unix timestamp format.
     * @param int $to The ending date in Unix timestamp format.
     * @param string $interval The interval between dates (e.g. 'day', 'week', 'month').
     * @param string $format The date format to use (default is 'M Y').
     *  (see https://www.yiiframework.com/doc/guide/2.0/en/output-formatting#date-and-time)
     * @return array The data array with missing dates filled.
     */
    public static function fillDates(array $data, int $from, int $to, string $interval, string $format = 'M Y'): array
    {
        $filledData = [];
        $defaultData = self::generateDateDefaults($from, $to, $interval, $format);

        foreach ($defaultData as $key) {
            $filledData[$key] = array_key_exists($key, $data) ? $data[$key] : 0;
        }

        return $filledData;
    }

    /**
     * Generates an array of date defaults based on the given parameters.
     *
     * @param int $from The starting date in Unix timestamp format.
     * @param int $to The ending date in Unix timestamp format.
     * @param string $interval The interval to increment the date by (e.g. '+1 day', '+1 week', '+1 month').
     * @param string $format The format of the date to be generated (e.g. 'Y-m-d', 'F j, Y').
     *  (see https://www.yiiframework.com/doc/guide/2.0/en/output-formatting#date-and-time)
     *  USE PHP FORMAT as this now uses date() function due to compatibility issues with locale
     * @return array An array of date defaults generated based on the given parameters.
     */
    public static function generateDateDefaults(int $from, int $to, string $interval, string $format): array
    {
        $date = $from;
        $defaults = [];
        
        while ($date <= $to) {
            $defaults[] = date($format, $date);
            $date = strtotime($interval, $date);
        }

        return $defaults;
    }

    /**
     * Maps a data set to the configured labels
     *
     * @param array $myArray The array containing the data.
     * @return array The mapped data.
     */
    public static function mapDataToLabels(array $labels, array $myArray): array
    {
        $mappedData = [];
        foreach ($labels as $label) {
            $mappedData[] = $myArray[$label] ?? 0;
        }

        return $mappedData;
    }
}
