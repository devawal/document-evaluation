<?php
namespace AppBundle\Services;

class Helpers
{
    /**
     * Date diffrence
     *
     * @param string $start
     * @param string $end
     * @return array
     */
    public static function dateInterval($start, $end)
    {
        $date1 = new \DateTime($start);
        $date2 = $date1->diff(new \DateTime($end));

        return array(
                'days' => $date2->days,
                'y' => $date2->y,
                'm' => $date2->m,
                'd' => $date2->d,
                'h' => $date2->h,
                'i' => $date2->i,
                's' => $date2->s,
            );
    }

    /**
     * Format input CSV data
     *
     * @param array $csvData
     * @return array
     */
    public static function formatInputData($csvData)
    {
        $output = array();
        // Define country code
        $countryCode = array('lt', 'pl', 'fr', 'de', 'uk', 'es', 'it');
        foreach ($csvData as $key => $val) {
            // Get only the county code you want
            if (in_array($val[1], $countryCode)) {
                $output[$val[1]][] = $val[4];
            }
        }

        return $output;
    }
}