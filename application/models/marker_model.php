<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Marker_model extends CI_Model {
    // 產生 markers table
    public function init () {
        $this->db->query (
            "CREATE TABLE `markers` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(255) DEFAULT NULL,
              `lat` double DEFAULT NULL,
              `lng` double DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
        );
    }

    // 產生 Demo 用的資料
    public function initDemoData () {
        $name = substr (str_shuffle (implode ('', array_merge (
                                          range ('A', 'Z'),
                                          range ('a', 'z'),
                                          range ('0', '9'),
                                          array ('_', '+')))), 0, 10);
        $data = array (
            'name' => $name,
            'lat'  => 25.04 + (rand (-9999, 9999) / 100000),
            'lng'  => 121.55 + (rand (-9999, 9999) / 100000)
        );

        $this->db->insert ('markers', $data);
        return $this->db->insert_id ();
    }

    // 依照範圍取得 markers
    public function get_by_bounds ($northEast, $southWest) {
        if (!(isset ($southWest['lat']) && isset ($northEast['lat']) && isset ($southWest['lng']) && isset ($northEast['lng'])))
            return array ();

        return $this->db->from ('markers')
                        ->where ("lat BETWEEN " . $southWest['lat'] . " AND " . $northEast['lat'] . " AND lng BETWEEN " . $southWest['lng'] . " AND " . $northEast['lng'] . "")
                        ->get ()
                        ->result_array ();
    }
}
