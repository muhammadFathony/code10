<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

	public function index()
	{
		$response = array("error" => FALSE);

		$firstname = $this->input->post('firstname');
		$lastname = $this->input->post('lastname');
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$enkripsi = hash("sha256", $password);

		$this->db->where('email', $email);
		$db = $this->db->get('users');

		if ($db->num_rows() > 0) {
			$response["error"] = TRUE;
			$response["message"] = "User already existed";
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			 $obj = array('firstname' => $firstname,
			 			  'lastname' => $lastname,
			 			  'email' => $email,
			 			  'password' => $enkripsi 
			 			);
			 $query = $this->db->insert('users', $obj);

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

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */