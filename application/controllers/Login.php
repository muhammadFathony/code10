<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		$response = array("error" => FALSE);

		$email = htmlspecialchars($this->input->post('email'));
		$password = htmlspecialchars($this->input->post('password'));

		$enkripsi = hash("sha256", $password);

		if (isset($email) && isset($password)) {
			$this->db->where('email', $email);
			$this->db->where('password', $enkripsi);
			$query = $this->db->get('users');

			if ($query->num_rows() > 0 ) {
				// while ($row = $query->result_array()) {
				// 	$response["error"] = FALSE;
			 //    	$response["message"] = "Login Successfull";
			 //    	$response["data"]["firstname"] = $row['firstname'];
			 //    	$response["data"]["lastname"] = $row['lastname'];
			 //    	$response["data"]["email"] = $row['email'];
				// }
				foreach ($query->result_array() as $key ) {
					$response["error"] = FALSE;
			    	$response["message"] = "Login Successfull";
			    	$response["data"]["firstname"] = $key['firstname'];
			    	$response["data"]["lastname"] = $key['lastname'];
			    	$response["data"]["email"] = $key['email'];
				}
				$this->output->set_content_type('application/json')->set_output(json_encode($response));
			} else {

				$response["error"] = TRUE;
	    		$response["message"] = "Incorrect Email or Password!";

	    		$this->output->set_content_type('application/json')->set_output(json_encode($response));
			}
		}
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */