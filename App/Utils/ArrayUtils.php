<?php
    namespace App\Utils;

    class ArrayUtils{
        public static function groupUserHistoryPerDay($data){
            $history = [];
            foreach ($data as $user){
                $history[$user['date']] = $user;
            }
            return $history;
        }
    }

