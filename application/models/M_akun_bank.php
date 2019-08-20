<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akun_bank extends CI_Model {

	public function generate_id_bank()
	{
		date_default_timezone_set('Asia/Jakarta');
		$b = date("Ym");
    	 $this->db->select('RIGHT(id_bank,4) as kode', FALSE);
		  $this->db->order_by('nomor','DESC');    
		  $this->db->limit(1);    
		  $query = $this->db->get('akun_bank');      //cek dulu apakah ada sudah ada kode di tabel.    
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
		  $kodejadi = "KDB-".$b."-".$kodemax;    // hasilnya ODJ-9921-0001 dst.
		  return $kodejadi;  
	}

	public function tambah_akun_bank($obj, $nama_upload)
	{
		$data = array(
			'id_bank' => $obj['id_bank'],
			'nama_bank' => $obj['nama_bank'],
			'gambar' => $nama_upload
		 );
		$data = $this->db->insert('akun_bank', $data);

		return $data;
	}	

	public function daftar_bank()
	{
		$this->db->select('*');
		$this->db->from('akun_bank');
		$this->db->order_by('nama_bank', 'asc');
		$data = $this->db->get();

		return $data;
	}

	public function tambah_rekening($obj)
	{
		$data = $this->db->insert('rekening_customer', $obj);

		return $data;
	}

	public function daftar_rekening_customer()
	{
		$this->db->select('a.*, b.nama_bank,b.gambar, c.nama_customers, c.alamat');
		$this->db->from('rekening_customer a');
		$this->db->join('akun_bank b', 'a.id_bank = b.id_bank', 'inner');
		$this->db->join('customers c', 'a.id_customers = c.id_customers', 'inner');
		$data = $this->db->get();

		return $data;
	}

}

/* End of file M_akun_bank.php */
/* Location: ./application/models/M_akun_bank.php */