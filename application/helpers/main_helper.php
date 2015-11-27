<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

if (!function_exists ('is_in_polygon')) {
    function is_in_polygon ($point, $polygon) {
        $x = $y = array ();
        $polygon_count = count ($polygon);
        foreach ($polygon as $val) {
            array_push ($x, $val['lng']);
            array_push ($y, $val['lat']);
        }

        $i = $j = $c = 0;
        for ($i = 0, $j = $polygon_count - 1; $i < $polygon_count; $j = $i++)
            if ((($y[$i] > $point['lat'] != ($y[$j] > $point['lat'])) && ($point['lng'] < ($x[$j] - $x[$i]) * ($point['lat'] - $y[$i]) / ($y[$j] - $y[$i]) + $x[$i])))
                $c = !$c;
        return $c;
    }
}
