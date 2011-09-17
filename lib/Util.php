<?php

class Util {

    static $seasons = array(
        'winter', 'spring', 'summer', 'fall'
    );

//    static $months = array(
//        'winter' => array('Dec', 'Jan', 'Feb'),
//        'spring' => array('Mar', 'Apr', 'May'),
//        'summer' => array('Jun', 'Jul', 'Aug'),
//        'fall' => array('Sep', 'Oct', 'Nov')
//    );

    static function getSeason($date) {
        $month = date('m', $date);
        $season = intval($month/3);
        if ($season > 3) $season = 0;
        return self::$seasons[$season];
    }

}
