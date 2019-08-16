<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_api_update extends CI_Model {

	public function api_update($firstname, $lastname, $address, $date, $id_user, $gender){


		$this->db->set('firstname', $firstname);
		$this->db->set('lastname', $lastname);		
		$this->db->set('address', $address);
		$this->db->set('date', $date);
		$this->db->set('gender', $gender);
		$this->db->where('id_user', $id_user);
		$data = $this->db->update('user');

		return $data;
	}

}

/* End of file M_api_update.php */
/* Location: ./application/models/M_api_update.php */