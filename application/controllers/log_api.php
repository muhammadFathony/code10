<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_api extends CI_Controller {

	public function index()
	{
		$response = array("error" => FALSE);

		$id_student = $this->input->post('id_student');
		$id_control = $this->input->post('id_control');
		$id_location = $this->input->post('id_location');

		$this->db->where('id_student', $id_student);
		$db = $this->db->get('log_class');

		if ($db->num_rows() > 0) {
			$response["error"] = TRUE;
			$response["message"] = "User already existed";
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			  $obj = array('id_student' => $id_student,
			 			  'id_control' => $id_control,
			 			  'id_location' => $id_location
			 			);
			 $query = $this->db->insert('log_class', $obj);

			 if ($query) {
			 	$response["error"] = FALSE;
			 	$response["message"] = "Register Successfull";

			 	$this->output->set_content_type('application/json')->set_output(json_encode($response));
			 } else {
			 	$response["error"] = TRUE;
			 	$response["message"] = "Register Failure";

			 	$this->output->set_content_type('application/json')->set_output(json_encode($response));
			 }
		}
	}

}

/* End of file log_api.php */
/* Location: ./application/controllers/log_api.php */