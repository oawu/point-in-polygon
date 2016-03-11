<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Main extends CI_Controller {

	// init DB table
	public function init () {
		$this->marker_model->init ();
	}

	// Create DB demo data
	public function demo () {
		for ($i = 0; $i < 1000; $i++)
			$this->marker_model->initDemoData ();
	}

	// index page
	public function index () {
        // Render view
		return $this->load->view ('main');
	}

	// Ajax method
	public function ajax () {
		$northEast = $this->input->post ('northEast', true);
		$southWest = $this->input->post ('southWest', true);
		$polygon   = $this->input->post ('polygon', true);

		// Check parameters
		if (!((count ($polygon) > 2) && ($northEast['lat'] > $southWest['lat']) && ($northEast['lng'] > $southWest['lng'])))
			return $this->output->set_content_type ('application/json')
					            ->set_output (json_encode (array (
									'status' => false
								)));

		// Get markers by bounds
		$markers = $this->marker_model->get_by_bounds ($northEast, $southWest);

		// Filter markers by polygon
		$markers = array_filter ($markers, function ($marker) use ($polygon) {
					return is_in_polygon ($marker, $polygon);
				});
		$markers = array_splice ($markers, 0);

		// Output markers json
		return $this->output->set_content_type ('application/json')
				            ->set_output (json_encode (array (
								'status' => true,
								'markers' => $markers
							)));
	}
}
