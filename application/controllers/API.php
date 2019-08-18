<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_user');
		$this->load->model('M_promo');
		$this->load->model('M_akun_bank');
	}

	public function login()
	{
		$response = array("error" => TRUE,
						  "message" => 'Silahkan Login');

		$obj = array(
				'username' => htmlentities($this->input->post('username', TRUE)),
				'password' => htmlentities($this->input->post('password', TRUE))
			);

		$verify = $this->M_user->auth_validation($obj);

		if ($obj['username'] != "" && $obj['password'] != "") {
			if ($verify) {
				$hash_password = $verify->password;
				$hash = password_verify($obj['password'], $hash_password);
				if ($hash) {
					$response["error"] = FALSE;
					$response["message"] = "Login berhasil";
					$response["data"]["username"] = $verify->username;
					$response["data"]["nama_lengkap"] = $verify->nama_user;
					$response["data"]["email"] = $verify->email;
					$response["data"]["id_user"] = $verify->id_user;
					$this->output->set_content_type('application/json')->set_output(json_encode($response)); 
				} else {
					$response["error"] = TRUE;
					$response["message"] = "Password salah";
					$this->output->set_content_type('application/json')->set_output(json_encode($response));
				}
			
			} else {
				$response["error"] = TRUE;
				$response["message"] = "Akun tidak ditemukan";
				$this->output->set_content_type('application/json')->set_output(json_encode($response));
			}
		} else {
			$response["error"] = TRUE;
			$response["message"] = "Field tidak boleh Kosong";
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}
		
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function register()
	{
		$response = array("error" => TRUE,
						 "message" => 'Please Register');
		$username = htmlentities($this->input->post('username', TRUE));
		$obj =  array(
			'nama_lengkap' => htmlentities($this->input->post('nama_lengkap', TRUE)),
			'username' => $username,
			'email' => htmlentities($this->input->post('email', TRUE)),
			'password' => htmlentities($this->input->post('password', TRUE)),
			'telp' => htmlentities($this->input->post('telp', TRUE)),
			'referal' => $this->M_user->generate_referal($username)
		);

		$this->db->where('username', $obj['username']);
		$this->db->where('email', $obj['email']);
		$check = $this->db->get('user');
		if ($check->num_rows() > 0) {
			$response["error"] = TRUE;
			$response["message"]= 'Akun sudah ada';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$this->db->where('username', $obj['username']);
			$check1 = $this->db->get('user');
			
			$this->db->where('email', $obj['email']);
			$check2 = $this->db->get('user');
			if ($check1->num_rows() > 0) {
				$response["error"] = TRUE;
				$response["message"]= 'username sudah digunakan';
				$this->output->set_content_type('application/json')->set_output(json_encode($response));
			} elseif ($check2->num_rows() > 0) {
				$response["error"] = TRUE;
				$response["message"]= 'email sudah digunakan';
				$this->output->set_content_type('application/json')->set_output(json_encode($response));
			} else {
				if ($obj['username'] != "" && $obj['email'] != "" && $obj['password'] != "") {
					$response["error"] = FALSE;
					$response["message"] = 'Registration Success';
					$data = $this->M_user->register($obj);
					$this->output->set_content_type('application/json')->set_output(json_encode($response));
				} else {
					$response["error"] = TRUE;
					$response["message"] = 'Field tidak boleh kosong';
				
					$this->output->set_content_type('application/json')->set_output(json_encode($response));
				}
			}
			
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function api_authemail()
	{
		$response = array("error" => TRUE,
						 "message" => 'Please Register');

		$obj = array('email' => htmlentities($this->input->post('email', TRUE)),
					 'username' => htmlentities($this->input->post('username', TRUE))
					 );

		$data = $this->M_user->auth_validation_email($obj);
		$this->db->where('email', $obj['email']);
		$check2 = $this->db->get('user');

		if ($check2->num_rows() > 0) {
			$response["error"] = FALSE;
			$response["message"]= 'email ditemukan';
			$response["data"]["username"] = $data->username;
			$response["data"]["nama_lengkap"] = $data->nama_user;
			$response["data"]["email"] = $data->email;
			$response["data"]["id_user"] = $data->id_user;
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$response["error"] = TRUE;
			$response["message"]= 'email tidak ditemukan';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}

	}

	public function Getpromo()
	{
		$response= array('error' => true,
						 'Message' => 'Promo Kosong');
		$data = $this->M_promo->Getpromo()->result();
		
		$check = $this->M_promo->Getpromo();
		if ($check->num_rows() > 0 ) {
			$response['error'] = FALSE;
			$response['Message'] = 'Berhasil';
			$response['dataPromo'] = $data;
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$response['error'] = TRUE;
			$response['Message'] = 'Silahkan buat promo';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}
	}

	public function promodua()
	{
		$response = array(
				'error' => true, 
				'message' => 'field kosong' 
			);
		$obj =  array(
			'id_promo' => htmlentities($this->input->post('id_promo', TRUE)),
			'judul' => htmlentities($this->input->post('judul', TRUE)),
			'isi' => htmlentities($this->input->post('isi', TRUE)),
			'diskon' => htmlentities($this->input->post('diskon', TRUE)),
			
		);
		$nmfile 					= $obj['judul'] . "_" .time();
		$config['file_name'] 		= $nmfile; 
		$config['upload_path'] = "./assets/images";
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		//$config['encrypt_name']	= TRUE;
		$config['max_size'] = 10000;
		$this->load->library('upload', $config);

		if ($this->upload->do_upload("file") !="") {
			$this->upload->do_upload("file");
			$data = $this->upload->data();
			$nama_upload = $data['file_name'];

			$config['image_library'] = 'gd2';
			$config['source_image'] = './assets/images/'.$data['file_name'];
			$config['width'] = 600;
			$config['height'] = 400;
			$config['quality'] = '50%';
			$config['new_image'] = './assets/thumb/'.$data['file_name'];

			$this->load->library('image_lib', $config);
			$this->image_lib->resize();
			$data = $this->M_promo->simpan($obj, $nama_upload);
			$response['error'] = FALSE;
			$response['message'] = 'sukses';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$response = array(
				'error' => true, 
				'message' => 'field kosong' 
			);
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}
		//$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function get_profil()
	{
		$response = array('error' => TRUE,
						 'message' => 'Id user kosong'
						);
		$id_user = htmlentities($this->input->post('id_user', TRUE));
		if ($id_user == "") {
			$response['error'] = TRUE;
			$response['message'] = 'Id User Kosong';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$data = $this->M_user->get_profil($id_user);
			$response['error'] = FALSE;
			$response['message'] = 'Berhasil';
			$response['data'] =  $data;
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}
	}

	public function cek_poin()
	{
		$response = array('error' => TRUE,
						 'message' => 'Id user kosong'
						);
		$id_user = htmlentities($this->input->post('id_user', TRUE));

		if ($id_user == "") {
			$response['error'] = TRUE;
			$response['message'] = 'Gagal mengambil data';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$data = $this->M_user->cek_poin($id_user);
			$response['error'] = FALSE;
			$response['message'] = "Sukses";
			$response['data'] = $data;
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}
	}

	public function tambah_bank()
	{
		$response = array('error' => TRUE,
						  'message' => 'Gagal'
					);
		$obj = array('id_bank' => $this->M_akun_bank->generate_id_bank(),
					 'nama_bank' => htmlentities($this->input->post('nama_bank',TRUE)),
		 			);
		$nmfile 					= 'Bank'.$obj['nama_bank'] . "_" .time();
		$config['file_name'] 		= $nmfile; 
		$config['upload_path'] = "./assets/images";
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		//$config['encrypt_name']	= TRUE;
		$config['max_size'] = 10000;
		$this->load->library('upload', $config);

		if ($obj['nama_bank'] != "") {
			$this->upload->do_upload("file");
			$data = $this->upload->data();
			$nama_upload = $data['file_name'];

			$config['image_library'] = 'gd2';
			$config['source_image'] = './assets/images/'.$data['file_name'];
			$config['width'] = 600;
			$config['height'] = 400;
			$config['quality'] = '50%';
			$config['new_image'] = './assets/thumb/'.$data['file_name'];

			$this->load->library('image_lib', $config);
			$this->image_lib->resize();
			$data = $this->M_akun_bank->tambah_akun_bank($obj, $nama_upload);
			$response['error'] = FALSE;
			$response['message'] = 'Sukses';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$response['error'] = TRUE;
			$response['message'] = 'Nama bank kosong';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}
		
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function daftar_bank()
	{
		$response = array('error' => TRUE,
						  'message' => 'Tidak ada data'
					);
		$check = $this->M_akun_bank->daftar_bank();
		if ($check->num_rows() > 0 ) {
			$data = $check->result();
			$response['error'] = FALSE;
			$response['message'] = 'Sukses';
			$response['data'] = $data;
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$response['error'] = TRUE;
			$response['message'] = 'Data Kosong';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function tambah_rekening()
	{
		$response = array('error' => TRUE,
						  'message' => 'Gagal'
						);
		$obj = array('id_customers' => htmlentities($this->input->post('id_customers', TRUE)),
					 'rekening' => htmlentities($this->input->post('rekening', TRUE)),
					 'id_bank' => htmlentities($this->input->post('id_bank', TRUE))
		 );
		if ($obj['rekening'] == "" || $obj['id_bank'] == "") {
			$response['error'] = TRUE;
			$response['message'] = 'Field tidak boleh kosong';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$data = $this->M_akun_bank->tambah_rekening($obj);
			$response['error'] = FALSE;
			$response['message'] = 'Sukses';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function daftar_rekening_customer()
	{
		$response = array('error' => TRUE,
						  'message' => 'Data Kosong'
		);

		$check = $this->M_akun_bank->daftar_rekening_customer();
		if ($check->num_rows() > 0) {
			$data = $check->result();
			$response['error'] = FALSE;
			$response['message'] = 'Sukses';
			$response['data'] = $data;
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$response['error'] = TRUE;
			$response['message'] = 'Tidak ada rekening';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

}

/* End of file API.php */
/* Location: ./application/controllers/API.php */