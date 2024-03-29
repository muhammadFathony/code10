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

	public function api_register($obj)
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

		if ($result) {
			$data1 = array('id_customers' => $id_customers,
						   'ktp' => '-',
						   'nama_customers' => $obj['nama_lengkap'],
						   'jenis_kelamin' => 0,
						   'status' => 0,
						   'npwp' => '-',
						   'ttl'=> '',
						   'alamat' => '-'
				 );
			$result1 = $this->db->insert('customers', $data1);
				if ($result1) {
					$data_poin = array(
					  'id_user' => $id_user,
					  'poin' => 0,
						 );
					$poin = $this->db->insert('poin', $data_poin);

					return $poin;
				}
			return $result1;
		}
		return $result;
	}

	public function api_auth_validation($obj)
	{
		$this->db->where('username', $obj['username']);
		$data = $this->db->get('user')->row();

		return $data;
	}

	public function api_auth_validation_email($obj)
	{
		$this->db->where('email', $obj['email']);
		$data = $this->db->get('user')->row();

		return $data;
	}

	public function api_get_profil($id_user)
	{
		$this->db->select('nomor, id_user, nama_user, username, email, telp, referal');
		$this->db->where('id_user', $id_user);
		$data = $this->db->get('user');

		return $data->row();
	}

	public function api_cek_poin($id_user)
	{
		$this->db->select('user.id_user, user.nama_user, user.username, user.email, user.id_customers, user.referal, poin.poin');
		$this->db->from('user');
		$this->db->join('poin', 'user.id_user = poin.id_user', 'inner');
		$this->db->where('user.id_user', $id_user);
		$data = $this->db->get();

		return $data;
	}

	public function signup($obj)
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

		if ($result) {
			$data1 = array('id_customers' => $id_customers,
						   'ktp' => '-',
						   'nama_customers' => $obj['nama_lengkap'],
						   'jenis_kelamin' => 0,
						   'status' => 0,
						   'npwp' => '-',
						   'ttl'=> '',
						   'alamat' => '-'
				 );
			$result1 = $this->db->insert('customers', $data1);
				if ($result1) {
					$data_poin = array(
					  'id_user' => $id_user,
					  'poin' => 0,
						 );
					$poin = $this->db->insert('poin', $data_poin);

					return $poin;
				}
			return $result1;
		}
		return $result;
	}

	public function get_listuser()
	{
		$this->db->select('*');
		$this->db->from('user');
		$data = $this->db->get();

		return $data;
	}

	public function edit_user($obj)
	{
		$this->db->set('nama_user', $obj['nama_user']);
		$this->db->set('username', $obj['username']);
		$this->db->set('email', $obj['email']);
		$this->db->set('telp', $obj['telp']);
		$this->db->set('level', $obj['level']);
		$this->db->where('id_user', $obj['id_user']);
		$data = $this->db->update('user');

		return $data;
	}

	public function delete_user($id_user)
	{
		$this->db->where('id_user', $id_user);
		$data = $this->db->delete('user');

		return $data;
	}
}

/* End of file M_user.php */
/* Location: ./application/models/M_user.php */
