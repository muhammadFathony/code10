<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_register extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_api_register');
	}

	public function index()
	{
		$response  = array("error" => FALSE);
		$firstname = htmlentities($this->input->post('firstname', TRUE));
		$lastname  = htmlentities($this->input->post('lastname', TRUE));			
		$address   = htmlentities($this->input->post('address', TRUE));
		$date 	   = htmlentities($this->input->post('date', TRUE));
		$gender	   = htmlentities($this->input->post('gender', TRUE));

		if ($firstname != "" AND $lastname != "") {
			$obj = array('firstname' => $firstname,
						 'lastname' => $lastname,
						 'address' => $address,
						 'date' => $date,
						 'gender' => $gender );
			$data = $this->M_api_register->register($obj);
			try{
				$response["error"] = FALSE;
				$response["message"] = "Register Successfull";
			 	$this->output->set_content_type('application/json')->set_output(json_encode($response));
			} catch(Exception $e){
				$response["error"] = TRUE;
			 	$response["message"] = "Register Failure";

			 	$this->output->set_content_type('application/json')->set_output(json_encode($response));
			}
			

		} else {
			$response["error"] = TRUE;
			$response["message"] = "Failed";
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}


	}

}

/* End of file API_create.php */
/* Location: ./application/controllers/API_create.php */