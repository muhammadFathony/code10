<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('Config_tcpdf');
		
		$this->load->model('M_user_rule');
		
	}
	

	public function index()
	{
		$data = $this->M_user_rule->user_rule();
		
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
		
	}

	function view_table()
	{
		$data['table'] = $this->M_user_rule->user_rule();
		$this->load->view('view_table', $data);
		
	}

	function report()
	{
		//$data['content'] = $this->view_table();
		$data['label'] = $this->M_user_rule->user_rule();
		$this->load->view('report', $data);
	}

	function webpust()
	{
		$this->load->view('Form');
	}

	function sendMessage($app_id) {
	    $content      = array(
	        "en" => 'Sicora Notification'
	    );
	    $headings = array(
	    	"en" => 'Combo Putra'
	    );
	    $fields = array(
	        'app_id' => $app_id,
	        'included_segments' => array(
	            'All'
	        ),
	        'data' => array(
	            "foo" => "bar"
	        ),
	        'contents' => $content,
	        'headings' => $headings
	    );
	    
	    $fields = json_encode($fields);
	    print("\nJSON sent:\n");
	    print($fields);
	    
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        'Content-Type: application/json; charset=utf-8',
	        'Authorization: Basic NGRmOTBlYjMtMjFiZC00NGMyLWIxYTMtMWI5MmYxNmU1NzU2'
	    ));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
	    curl_setopt($ch, CURLOPT_POST, TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    
	    $response = curl_exec($ch);
	    curl_close($ch);
	    
	    return $response;
	}

	function response(){
		if ($this->input->post('notif')) {
			$app_id = $this->input->post('app_id');
			$response = $this->sendMessage($app_id);
			$return["allresponses"] = $response;
			$return = json_encode($return);

			$data = json_decode($response, true);
			print_r($data);
			$id = $data['id'];
			print_r($id);

			print("\n\nJSON received:\n");
			print($return);
			print("\n");
		}

	}

	function sendMessageByid($heading, $message, $user_id, $app_id){
		$content = array(
			"en" => $message
			);

		$heading = array(
			"en" => $heading
		);
		
		$fields = array(
			'app_id' => $app_id,
			'include_player_ids' => array($user_id),
			'contents' => $content,
			'headings' => $heading,

		);
		
		$fields = json_encode($fields);
    	print("\nJSON sent:\n");
    	print($fields);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}
	
	function responseByid(){

		if ($this->input->post('notif')) {
			$user_id = $this->input->post('user_id');
			$app_id = $this->input->post('app_id');
			$heading = $this->input->post('heading');
			$message = $this->input->post('message');

			$response = $this->sendMessageByid($heading, $message, $user_id, $app_id);
			$return["allresponses"] = $response;
			$return = json_encode( $return);
			
			print("\n\nJSON received:\n");
			print($return);
			print("\n");
		}
	}
}

/* End of file coba.php */
/* Location: ./application/controllers/coba.php */
