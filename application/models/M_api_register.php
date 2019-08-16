<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_api_register extends CI_Model {

	public function register($obj){

		$data = $this->db->insert('user', $obj);
		return $data;
	}	

}

/* End of file M_api_register.php */
/* Location: ./application/models/M_api_register.php */