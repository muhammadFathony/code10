<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_delete extends CI_Controller {

	public function index()
	{
		$id_user = $this->input->post('id_user', TRUE);
		$response  = array("error" => FALSE);
		///if (isset($id_user)) {
			// $this->db->where('id_user', $id_user);
			// $check = $this->db->get('user');
			// if ($check->num_rows() > 0) {
				$this->db->where('id_user', $id_user);
				$response = $this->db->delete('user');
				// if ($response) {
					$response  = array("error" => FALSE);
					$response  = array("message" => "delete user Successfully");
					$this->output->set_content_type('application/json')->set_output(json_encode($response));
		// 		} else{
		// 			$response  = array("error" => TRUE);
		// 			$response  = array("message" => "gagal delete user");
		// 			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		// 		}
		// 	} else {
		// 		$response  = array("error" => TRUE);
		// 		$response  = array("message" => "User tidak ditemukan");
		// 		$this->output->set_content_type('application/json')->set_output(json_encode($response));
		// 	}		
		// } else {
		// 	$response  = array("error" => TRUE);
		// 	$response  = array("message" => "ID user kosong");
		// 	$this->output->set_content_type('application/json')->set_output(json_encode($response));
		// }
	}

}

/* End of file API_delete.php */
/* Location: ./application/controllers/API_delete.php */