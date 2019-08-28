<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_poin extends CI_Model {

	public function generate_idpenarikan(){
		date_default_timezone_set('Asia/Jakarta');
		$b = date("Ym");
    	 $this->db->select('RIGHT(id_penarikan,4) as kode', FALSE);
		  $this->db->order_by('nomor','DESC');    
		  $this->db->limit(1);    
		  $query = $this->db->get('penarikan_poin');      //cek dulu apakah ada sudah ada kode di tabel.    
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
		  $kodejadi = "POI-".$b."-".$kodemax;    // hasilnya ODJ-9921-0001 dst.
		  return $kodejadi;  
	}

	public function penarikan($obj)
	{
		$data = array('id_penarikan' => $this->generate_idpenarikan(),
					  'id_user' => $obj['id_user'],
					  'nominal_penarikan' => $obj['nominal'],
					  'id_bank' => $obj['id_bank'],
					  'rekening' => $obj['rekening'],	
		);

		
		$result = $this->db->insert('penarikan_poin', $data);
		
		return $result;
	}

}

/* End of file M_poin.php */
/* Location: ./application/models/M_poin.php */
