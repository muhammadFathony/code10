<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_update extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_api_update');
	}

	public function index()
	{
		$response  = array("error" => FALSE);
		$firstname = htmlentities($this->input->post('firstname', TRUE));
		$lastname  = htmlentities($this->input->post('lastname', TRUE));			
		$address   = htmlentities($this->input->post('address', TRUE));
		$date 	   = htmlentities($this->input->post('date', TRUE));
		$id_user   = htmlentities($this->input->post('id_user', TRUE));
		$gender   = htmlentities($this->input->post('gender', TRUE));

		if (!$firstname || !$lastname || !$address || !$date) {
			$response["error"] = TRUE;
			$response["message"] = "required field is empty";
			$this->output->set_content_type('application/json')->set_output(json_encode($response)); 
		} else {
			// $this->db->where('id_user', $id_user);
			// $check = $this->db->get('user');
			// if ($check->num_rows() > 0 ) {
				$data = $this->M_api_update->api_update($firstname, $lastname, $address, $date, $id_user, $gender);
				$response["error"] = FALSE;
				$response["message"] = "Update Successfully";
				$this->output->set_content_type('application/json')->set_output(json_encode($response));
			// } else {
			// 	$response["error"] = TRUE;
			// 	$response["message"] = "user unknown";
			// 	$this->output->set_content_type('application/json')->set_output(json_encode($response)); 
			// }
			
		}
	}

}

/* End of file API_update.php */
/* Location: ./application/controllers/API_update.php */