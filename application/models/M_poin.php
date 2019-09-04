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

	public function api_penarikan($obj)
	{
		$this->db->where('rekening', $obj['rekening']);
		$getbank = $this->db->get('rekening_customer')->row();
		$data = array('id_penarikan' => $this->generate_idpenarikan(),
					  'id_user' => $obj['id_user'],
					  'nominal_penarikan' => $obj['nominal'],
					  'id_bank' => $getbank->id_bank,
					  'rekening' => $obj['rekening'],
					  'status' => 1,
					  'status_transaksi' => 0	
		);

		
		$result = $this->db->insert('penarikan_poin', $data);
		
		return $result;
	}

	public function api_withdraw($obj)
	{
		$nominal = $obj['nominal_penarikan'];
		$this->db->set('nominal_penarikan', $nominal);
		$this->db->set('status', 0);
		$this->db->where('id_penarikan', $obj['id_penarikan']);
		$this->db->where('id_user', $obj['id_user']);
		$update_status = $this->db->update('penarikan_poin');
		
			if ($update_status == TRUE) {
				$this->db->set('poin', "poin - $nominal", FALSE);
				$this->db->where('id_user', $obj['id_user']);
				$withdraw = $this->db->update('poin');

				return $withdraw;
			} else {
				echo 'error';
			}
		return $update_status;
	}

	public function api_histori($id_user)
	{
		$this->db->select('penarikan_poin.nominal_penarikan, penarikan_poin.status, penarikan_poin.status_transaksi, penarikan_poin.created_at, akun_bank.nama_bank, akun_bank.gambar, rekening_customer.nama, rekening_customer.rekening, (CASE WHEN penarikan_poin.status = 0 THEN "selesai" WHEN penarikan_poin.status = 1 THEN "proses" ELSE "Batal" END) as statusnew,  (CASE WHEN penarikan_poin.status_transaksi = 0 THEN "penarikan"  ELSE "Dapat" END) as st_transaksi, DATE_FORMAT(penarikan_poin.created_at, "%d-%m-%Y") as tanggal ');
		$this->db->from('penarikan_poin');
		$this->db->join('akun_bank', 'penarikan_poin.id_bank = akun_bank.id_bank', 'inner');
		$this->db->join('rekening_customer', 'penarikan_poin.rekening = rekening_customer.rekening', 'inner');
		$this->db->where('penarikan_poin.id_user', $id_user);
		$data = $this->db->get();

		return $data;
	}

}

/* End of file M_poin.php */
/* Location: ./application/models/M_poin.php */
