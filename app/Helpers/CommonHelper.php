<?php

namespace App\Helpers;

use Carbon\Carbon;


class CommonHelper
{
    /**
     *
     * Genrate Random 8 Digit Password
     *
     * @param $request
     * @return
     **/
    public static function randomPassword($len = 8)
    {
        //enforce min length 8
        if ($len < 8)
            $len = 8;

        //define character libraries - remove ambiguous characters like iIl|1 0oO
        $sets = array();
        $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        $sets[] = '23456789';
        $sets[]  = '!@#$%^&*';
        // $sets[]  = '~!@#$%^&*(){}[],./?';

        $password = '';

        //append a character from each set - gets first 4 characters
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }

        //use all characters to fill up to $len
        while (strlen($password) < $len) {
            //get a random set
            $randomSet = $sets[array_rand($sets)];

            //add a random char from the random set
            $password .= $randomSet[array_rand(str_split($randomSet))];
        }

        //shuffle the password string before returning!
        return str_shuffle($password);
    }
}
