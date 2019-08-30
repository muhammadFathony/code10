<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_promo extends CI_Model {

	public function generate_promo()
	{
		date_default_timezone_set('Asia/Jakarta');
		$b = date("Ym");
    	 $this->db->select('RIGHT(id_promo,4) as kode', FALSE);
		  $this->db->order_by('nomor','DESC');    
		  $this->db->limit(1);    
		  $query = $this->db->get('promo');      //cek dulu apakah ada sudah ada kode di tabel.    
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

	public function simpan($obj, $nama_upload)
	{
		$data = array('id_promo' => $this->generate_promo(),
					  'judul'=> $obj['judul'],
					  'isi' => $obj['isi'],
					  'diskon' => $obj['diskon'],
					  'gambar' => $nama_upload,
					  'status' => 1
					   );
		$data1 = $this->db->insert('promo', $data);
		return $data1;
	}

	public function api_Getpromo()
	{
		$this->db->select('*');
		$this->db->from('promo');
		$this->db->where('status', 1);
		$data = $this->db->get();

		return $data;
	}

}

/* End of file M_promo.php */
/* Location: ./application/models/M_promo.php */
