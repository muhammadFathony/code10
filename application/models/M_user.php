<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

	public function generate_user()
	{
		date_default_timezone_set('Asia/Jakarta');
		$b = date("Ym");
    	 $this->db->select('RIGHT(id_user,4) as kode', FALSE);
		  $this->db->order_by('nomor','DESC');    
		  $this->db->limit(1);    
		  $query = $this->db->get('user');      //cek dulu apakah ada sudah ada kode di tabel.    
		  if($query->num_rows() <> 0){      
		   //jika kode ternyata sudah ada.      
		   $data = $query->row();      
		   $kode = intval($data->kode) + 1;    
		  }
		  else {      
		   //jika kode belum ada      
		   $kode = 1;    
		  }
		  $kodemax = str_pad($kode, 6, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
		  $kodejadi = "NIU-".$b."-".$kodemax;    // hasilnya ODJ-9921-0001 dst.
		  return $kodejadi;  
	}

	public function generate_customers()
	{
		date_default_timezone_set('Asia/Jakarta');
		$b =date("Ym");
    	 $this->db->select('RIGHT(id_customers,4) as kode', FALSE);
		  $this->db->order_by('nomor','DESC');    
		  $this->db->limit(1);    
		  $query = $this->db->get('customers');      //cek dulu apakah ada sudah ada kode di tabel.    
		  if($query->num_rows() <> 0){      
		   //jika kode ternyata sudah ada.      
		   $data = $query->row();      
		   $kode = intval($data->kode) + 1;    
		  }
		  else {      
		   //jika kode belum ada      
		   $kode = 1;    
		  }
		  $kodemax = str_pad($kode, 6, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
		  $kodejadi = "NIC-".$b."-".$kodemax;    // hasilnya ODJ-9921-0001 dst.
		  return $kodejadi;  
	}	

	public function generate_referal($username)
	{
		date_default_timezone_set('Asia/Jakarta');
		$b = date("Ym");
    	 $this->db->select('nomor as kode', FALSE);
		  $this->db->order_by('nomor','DESC');    
		  $this->db->limit(1);    
		  $query = $this->db->get('user');      //cek dulu apakah ada sudah ada kode di tabel.    
		  if($query->num_rows() <> 0){      
		   //jika kode ternyata sudah ada.      
		   $data = $query->row();      
		   $kode = intval($data->kode) + 1;    
		  }
		  else {      
		   //jika kode belum ada      
		   $kode = 1;    
		  }
		  $kodemax = $kode; 
		  $kodejadi = $username.$kodemax;    
		  return $kodejadi;  
	}

	public function register($obj)
	{

		$password = password_hash($obj['password'], PASSWORD_DEFAULT);
		$id_user = $this->generate_user();
		$id_customers = $this->generate_customers();
		$data = array('nama_user' => $obj['nama_lengkap'],
					  'username' => $obj['username'],
					  'telp' => $obj['telp'],
					  'email' => $obj['email'],
					  'id_user' => $id_user,
					  'password' => $password,
					  'id_customers'=> $id_customers,
					  'referal' => $obj['referal']
			);

		$result = $this->db->insert('user', $data);
		// if ($result) {
		// 	$data1 = array('id_customers' => $id_customers,
		// 				   'ktp'=>'-',
		// 				   'nama_customers'=> $obj['nama_lengkap'],
		// 				   'jenis_kelamin'=> 2,
		// 				   'status' => 2,
		// 				   'nomor_kk' => '-',
		// 				   'ttl' => date("Y-m-d"),
		// 				   'alamat' => '-'
		// 				);
		// 	$result1 = $this->db->insert('customers', $data1);
		// 	return $result1;
		// }

		return $result;
	}

	public function auth_validation($obj)
	{
		$this->db->where('username', $obj['username']);
		$data = $this->db->get('user')->row();

		return $data;
	}

	public function auth_validation_email($obj)
	{
		$this->db->where('email', $obj['email']);
		$data = $this->db->get('user')->row();

		return $data;
	}

	public function get_profil($id_user)
	{
		$this->db->select('nomor, id_user, nama_user, username, email, telp, referal');
		$this->db->where('id_user', $id_user);
		$data = $this->db->get('user');

		return $data->row();
	}

	public function cek_poin($id_user)
	{
		$this->db->select('id_user, nama_user, username, email, id_customers, referal, poin');
		$this->db->from('user');
		$this->db->where('id_user', $id_user);
		$data = $this->db->get();

		return $data->row();
	}

}

/* End of file M_user.php */
/* Location: ./application/models/M_user.php */