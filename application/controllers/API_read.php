<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_read extends CI_Controller {

	public function index()
	{
		$result = array();
		$this->db->select('*');
		$this->db->from('user');
		$this->db->order_by('id_user', 'desc');
		$data = $this->db->get()->result();
		foreach ($data as $key ) {
			$result[] = $key;
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(array('result'=> $result)));
	}

}

/* End of file API_read.php */
/* Location: ./application/controllers/API_read.php */