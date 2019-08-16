<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user_rule extends CI_Model {

	public function User_rule()
	{
		
		$query = $this->db->get('user_rule');
		return $query->result_array();
		
	}

}

/* End of file ModelName.php */
