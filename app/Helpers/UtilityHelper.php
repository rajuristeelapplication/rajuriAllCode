<?php

namespace App\Helpers;

class UtilityHelper
{
     /**
     * Genrate Unique code
     *
     * @param $tableName
     * @param $columnName
     * @return string
     */
    public static function generateUniqueCode($tableName, $columnName)
    {

        $randomNumber = rand(11111, 99999);

        self::checkUniqueCode($tableName, $columnName, $randomNumber);

        return  $randomNumber;
    }

    /**
     * Generate Order Number
     *
     * @return bool
     */
    public static function generateOrderNumber()
    {
        $tableName = 'orders';
        $columnName = 'orderNumber';
        $randomNumber = '';
        do
        {
            $randomNumber = rand(1111111111, 9999999999);
            $code = \DB::table($tableName)->select($columnName)->where($columnName, $randomNumber)->count();

        } while($code != 0);

        return $randomNumber;
    }


    /**
     * Check random code exist then regenerate
     *
     * @param $tableName
     * @param $columnName
     * @param $randomNumber
     * @return bool
     */
    public static function checkUniqueCode($tableName, $columnName, $randomNumber)
    {
        do
        {
            $code = \DB::table($tableName)->select($columnName)->where($columnName, $randomNumber)->count();

            if($code !=0)
            {
                self::checkUniqueCode($tableName, $columnName, $randomNumber);
            }

        }while($code != 0);
    }



    public static function getDateFormatNotification($fieldName, $colunName)
    {
        $dateFormat = config('constant.schedule_date_format');


        return 'CASE
                    WHEN ' . $fieldName . ' between date_sub(now(), INTERVAL 24 hour) and now()
                    THEN DATE_FORMAT(' . $fieldName . ', "' . config('constant.schedule_time_format') . '")

                    WHEN datediff(now(), ' . $fieldName . ') = 1
                        THEN "yesterday"

                    WHEN datediff(now(), ' . $fieldName . ') <= 7
                        THEN DATE_FORMAT(' . $fieldName . ', "%a")

                    ELSE DATE_FORMAT(' . $fieldName . ', "' . $dateFormat . '")
                END as ' . $colunName . '';
    }

    /**
     * Get user readable date and time.
     *
     * @param int $id
     *
     * @return query result
     */
    public static function getDateFormat($fieldName, $colunName)
    {
        $dateFormat = config('constant.DATE_TIME_FORMAT');

        return 'CASE
                    WHEN ' . $fieldName . ' between date_sub(now(), INTERVAL 60 second) and now()
                        THEN concat(second(TIMEDIFF(now(), ' . $fieldName . ')), " sec ago")

                    WHEN ' . $fieldName . ' between date_sub(now(), INTERVAL 60 minute) and now()
                        THEN concat(minute(TIMEDIFF(now(), ' . $fieldName . ')), " m ago")

                    WHEN ' . $fieldName . ' between date_sub(now(), INTERVAL 24 hour) and now()
                        THEN concat(hour(TIMEDIFF(NOW(), ' . $fieldName . ')), " h ago")

                    WHEN datediff(now(), ' . $fieldName . ') = 1
                        THEN "1 day ago"

                    WHEN datediff(now(), ' . $fieldName . ') = 2
                        THEN "2 day ago"

                    WHEN datediff(now(), ' . $fieldName . ') = 3
                        THEN "3 day ago"

                     WHEN datediff(now(), ' . $fieldName . ') = 4
                        THEN "4 day ago"

                     WHEN datediff(now(), ' . $fieldName . ') = 5
                        THEN "5 day ago"

                    WHEN datediff(now(), ' . $fieldName . ') = 6
                        THEN "6 day ago"

                    WHEN ' . $fieldName . ' between date_sub(now(), INTERVAL 1 WEEK) and now()
                        THEN " 1 Week ago"

                    WHEN ' . $fieldName . ' between date_sub(now(), INTERVAL 2 WEEK) and now()
                        THEN " 2 Week ago"

                    WHEN ' . $fieldName . ' between date_sub(now(), INTERVAL 3 WEEK) and now()
                        THEN " 3 Week ago"

                    WHEN ' . $fieldName . ' between date_sub(now(), INTERVAL 4 WEEK) and now()
                        THEN " 4 Week ago"


                   WHEN ' . $fieldName . ' between date_sub(now(), INTERVAL 1 MONTH) and now()
                        THEN " 1 month ago"

                    WHEN ' . $fieldName . ' between date_sub(now(), INTERVAL 2 MONTH) and now()
                        THEN " 2 month ago"

                    ELSE DATE_FORMAT(' . $fieldName . ', "' . $dateFormat . '")
                END as ' . $colunName . '';
    }
}
