<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_user');
		$this->load->model('M_promo');
		$this->load->model('M_akun_bank');
		$this->load->model('M_poin');
		$this->load->model('M_pengajuan');
	}

	public function login()
	{
		$response = array("error" => TRUE,
						  "message" => 'Silahkan Login');

		$obj = array(
				'username' => htmlentities($this->input->post('username', TRUE)),
				'password' => htmlentities($this->input->post('password', TRUE))
			);

		$verify = $this->M_user->api_auth_validation($obj);

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
					$data = $this->M_user->api_register($obj);
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

		$data = $this->M_user->api_auth_validation_email($obj);
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
		$data = $this->M_promo->api_Getpromo()->result();
		
		$check = $this->M_promo->api_Getpromo();
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
			$data = $this->M_user->api_get_profil($id_user);
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
			$data = $this->M_user->api_cek_poin($id_user);
			if ($data->num_rows() > 0 ) {
				$response['error'] = FALSE;
				$response['message'] = "Sukses";
				$response['data'] = $data->row();
				$this->output->set_content_type('application/json')->set_output(json_encode($response));
			} else {
				$response['error'] = TRUE;
				$response['message'] = 'Data Kosong';
				$this->output->set_content_type('application/json')->set_output(json_encode($response));
			}
			
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
		$check = $this->M_akun_bank->api_daftar_bank();
		if ($check->num_rows() > 0 ) {
			$data = $check->result();
			$response['error'] = FALSE;
			$response['message'] = 'Sukses';
			$response['databank'] = $data;
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
		$id_user = htmlentities($this->input->post('id_user', TRUE));
		$obj = array('id_user' => $id_user,
					 'rekening' => htmlentities($this->input->post('rekening', TRUE)),
					 'id_bank' => htmlentities($this->input->post('id_bank', TRUE)),
					 'nama' => htmlentities($this->input->post('nama', TRUE))
		 );
		if ($obj['rekening'] == "" || $obj['id_bank'] == "") {
			$response['error'] = TRUE;
			$response['message'] = 'Field tidak boleh kosong';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			
				$data = $this->M_akun_bank->api_tambah_rekening($obj);
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
		$id_user = htmlentities($this->input->post('id_user', TRUE));
		$check = $this->M_akun_bank->api_daftar_rekening_customer($id_user);
		if ($check->num_rows() > 0) {
			$data = $check->result();
			$response['error'] = FALSE;
			$response['message'] = 'Sukses';
			$response['datarekening'] = $data;
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$response['error'] = TRUE;
			$response['message'] = 'Tidak ada rekening';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function penarikan()
	{
		$response = array('error' => TRUE,
						  'message' => 'Gagal'
				);

		$obj = array('id_user' => htmlentities($this->input->post('id_user', TRUE)),
				'nominal' => htmlentities($this->input->post('nominal', TRUE)),
				'rekening' => htmlentities($this->input->post('rekening', TRUE)),
			);

		$minimal = 50000;

		if ($obj['rekening'] == "" || $obj['id_user'] == "") {
			$response['error'] = TRUE;
			$response['message'] = 'Field ada yang kosong';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else{
			if ($obj['nominal'] > $minimal) {
				$data = $this->M_poin->api_penarikan($obj);
				$response['error'] = FALSE;
				$response['message'] = 'Sukses';
				$this->output->set_content_type('application/json')->set_output(json_encode($response));
			} else {
				$response['error'] = TRUE;
				$response['message'] = 'Minimal Penarikan Rp 50.000';
				$this->output->set_content_type('application/json')->set_output(json_encode($response));
			}
		}
	}

	public function withdraw()
	{
		$response = array('error' => TRUE,
						  'message' => 'Gagal'
				);

		$obj = array('id_penarikan' => htmlentities($this->input->post('id_penarikan', TRUE)),
					 'id_user' => htmlentities($this->input->post('id_user', TRUE)),
					 'nominal_penarikan' => htmlentities($this->input->post('nominal_penarikan', TRUE)),
		 );
		
		$this->db->where('id_penarikan', $obj['id_penarikan']);
		$this->db->where('status_transaksi', 0);
		$cek = $this->db->get('penarikan_poin')->row();

		if ($cek->status == 0) {
			$response['error'] = TRUE;
			$response['message'] = 'Transaksi sudah selesai';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {
			$data = $this->M_poin->api_withdraw($obj);
			$response['error'] = FALSE;
			$response['message'] = 'Berhasil';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}
	}

	public function histori()
	{
		$response = array('error' => TRUE,
						  'message' => 'Gagal'
				);

		//$id_user = 'NIU-201908-000001';
		$id_user = $this->input->post('id_user');
		$data = $this->M_poin->api_histori($id_user);
		if ($data->num_rows() > 0) {
			$response['error'] = FALSE;
			$response['message'] = 'Berhasil';
			$response['datahistory'] = $data->result();
			$this->output->set_content_type('application/json')->set_output(json_encode($response));	
		} else {
			$response['error'] = TRUE;
			$response['message'] = 'Data Kosong';
			$response['datahistory'] = $data->result();
			$this->output->set_content_type('application/json')->set_output(json_encode($response));	
		}
				
	}

	public function pengajuan()
	{
		$response = array('error' => TRUE,
						  'message' => 'Gagal'
				);
		
		// jenis pinjaman = KPR, KPA, Multiguna TOC
		$obj = array('id_user' => htmlentities($this->input->post('id_user', TRUE)),
					 'jenis_pinjaman' => htmlentities($this->input->post('jenis_pinjaman', TRUE)),
					 'nama_lengkap' => htmlentities($this->input->post('nama_lengkap', TRUE)),
					 'ktp' => htmlentities($this->input->post('ktp', TRUE)),
					 'npwp' => htmlentities($this->input->post('npwp', TRUE)),
					 'status_kawin' => htmlentities($this->input->post('status_kawin', TRUE)),
					 'jumlah_anak' => htmlentities($this->input->post('jumlah_anak', TRUE)),
					 'alamat' => htmlentities($this->input->post('alamat', TRUE)),
					 'telp' => htmlentities($this->input->post('telp', TRUE)),
					//batas data agunan
					 'jenis_agunan' => htmlentities($this->input->post('jenis_agunan', TRUE)),
					 'luas_tanah' => htmlentities($this->input->post('luas_tanah', TRUE)),
					 'merkseritahun' => htmlentities($this->input->post('merkseritahun', TRUE)),
					 'nilai_agunan' => htmlentities($this->input->post('nilai_agunan', TRUE)),
					 'lokasi_agunan' => htmlentities($this->input->post('lokasi_agunan', TRUE)),
					//batas rencana pengajuan kredit
					'besar_pinjaman' => htmlentities($this->input->post('besar_pinjaman', TRUE)),
					'jangka_waktu' => htmlentities($this->input->post('jangka_waktu', TRUE)),
					// pekerjaan dan waktu
					'nama_tempat_kerja' => htmlentities($this->input->post('nama_tempat_kerja', TRUE)),
					'bidang_usaha' => htmlentities($this->input->post('bidang_usaha', TRUE)),
					'jabatan' => htmlentities($this->input->post('jabatan', TRUE)),
					'nominal_penghasilan' => htmlentities($this->input->post('nominal_penghasilan', TRUE)),
					'penghasilan_lain' => htmlentities($this->input->post('penghasilan_lain', TRUE))
		 );

		$nmfile 					= 'ktp'.$obj['nama_lengkap'] . "_" .time();
		$config['file_name'] 		= $nmfile; 
		$config['upload_path'] = "./assets/images";
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		//$config['encrypt_name']	= TRUE;
		$config['max_size'] = 10000;
		$this->load->library('upload', $config);

		if ($obj['jenis_pinjaman'] == "" || $obj['ktp'] == ""  || $obj['jenis_agunan'] == "" || $obj['besar_pinjaman'] == "" || $obj['jangka_waktu'] == "" || $obj['nama_tempat_kerja'] == "" || $obj['nominal_penghasilan'] == "" || $this->upload->do_upload("foto_ktp") == "" ) {
			$response['error'] = TRUE;
			$response['message'] = 'Field ada yang kosong';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		} else {

			$this->upload->do_upload("foto_ktp");
			$data = $this->upload->data();
			$ktp = $data['file_name'];

			$config['image_library'] = 'gd2';
			$config['source_image'] = './assets/images/'.$data['file_name'];
			$config['width'] = 600;
			$config['height'] = 400;
			$config['quality'] = '50%';
			$config['new_image'] = './assets/thumb/ktp/'.$data['file_name'];

			$this->load->library('image_lib', $config);
			$this->image_lib->resize();

			if ($this->upload->do_upload("foto_ktp") != "") {
				$nmslip 					= 'slip'.$obj['nama_lengkap'] . "_" .time();
				$config1['file_name'] 		= $nmslip; 
				$config1['upload_path'] = "./assets/images";
				$config1['allowed_types'] = 'gif|jpg|png|jpeg';
				//$config['encrypt_name']	= TRUE;
				$config1['max_size'] = 10000;
				$this->upload->initialize($config1);

				$this->upload->do_upload("foto_slipgaji");
				$data1 = $this->upload->data();
				$slipgaji = $data1['file_name'];
				$config2['image_library'] = 'gd2';
				$config2['source_image'] = './assets/images/'.$data1['file_name'];
				$config2['width'] = 600;
				$config2['height'] = 400;
				$config2['quality'] = '50%';
				$config2['new_image'] = './assets/thumb/slip/'.$data1['file_name'];

				$this->image_lib->initialize($config2);
				$this->image_lib->resize();

				$nmrekkoran 					= 'rek'.$obj['nama_lengkap'] . "_" .time();
				$config3['file_name'] 		= $nmrekkoran; 
				$config3['upload_path'] = "./assets/images";
				$config3['allowed_types'] = 'gif|jpg|png|jpeg';
				//$config['encrypt_name']	= TRUE;
				$config3['max_size'] = 10000;
				$this->upload->initialize($config3);

				$this->upload->do_upload("foto_rekkoran");
				$data3 = $this->upload->data();
				$rekkoran = $data3['file_name'];
				$config4['image_library'] = 'gd2';
				$config4['source_image'] = './assets/images/'.$data3['file_name'];
				$config4['width'] = 600;
				$config4['height'] = 400;
				$config4['quality'] = '50%';
				$config4['new_image'] = './assets/thumb/rekkoran/'.$data3['file_name'];

				$this->image_lib->initialize($config4);
				$this->image_lib->resize();
			} else {
				$response['error'] = TRUE;
				$response['message'] ='Silahkan foto upload ktp';
				$this->output->set_content_type('application/json')->set_output(json_encode($response));
			}

			$data = $this->M_pengajuan->simpan_pengajuan($obj, $ktp, $slipgaji, $rekkoran);
			$response['error']= FALSE;
			$response['message'] = 'Berhasil';
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}
	}

}

/* End of file API.php */
/* Location: ./application/controllers/API.php */
