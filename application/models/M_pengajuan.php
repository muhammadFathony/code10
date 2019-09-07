<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengajuan extends CI_Model {

	public function generate_pengajuan()
	{
		date_default_timezone_set('Asia/Jakarta');
		$b = date("Ym");
    	 $this->db->select('RIGHT(id_pengajuan,4) as kode', FALSE);
		  $this->db->order_by('nomor','DESC');    
		  $this->db->limit(1);    
		  $query = $this->db->get('pengajuan');      //cek dulu apakah ada sudah ada kode di tabel.    
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
		  $kodejadi = "NPC-".$b."-".$kodemax;    // hasilnya ODJ-9921-0001 dst.
		  return $kodejadi;  
	}

	public function jenis_pengajuan()
	{
		$data = $this->db->get('jenis_pinjaman')->result();

		return $data;
	}

	public function simpan_customer($obj)
	{
		date_default_timezone_set('Asia/Jakarta');
		$waktu = date("Y-m-d H:i:s");
		$id_customers = $this->session->userdata('id_customers');
		$this->db->set('ktp',$obj['nik']);
		$this->db->set('jenis_kelamin',$obj['jenis_kelamin']);
		$this->db->set('status',$obj['status']);
		$this->db->set('alamat',$obj['alamat']);
		$this->db->set('jumlah_anak',$obj['jml_anak']);
		$this->db->set('updated_at',$waktu);
		$this->db->set('npwp',$obj['npwp']);
		// $this->db->set('',$obj['']);
		// $this->db->set('',$obj['']);
		// $this->db->set('',$obj['']);
		$this->db->where('id_customers', $obj['idc']);
		$data = $this->db->update('customers');

		return $data;
	}

	public function simpan_user($obj)
	{
		date_default_timezone_set('Asia/Jakarta');
		$waktu = date("Y-m-d H:i:s");
		
		$this->db->set('telp', $obj['telp']);
		$this->db->set('updated_at',$waktu);
		$this->db->where('id_customers', $obj['idc']);
		$data = $this->db->update('user');

		return $data;
	}

	public function simpan_pengajuan($obj, $ktp, $slipgaji, $rekkoran)
	{
		$data = array('id_pengajuan' => $this->generate_pengajuan(),
					 'id_user' => $obj['id_user'],
					 'id_jenis' => $obj['jenis_pinjaman'],
					 'jenis_agunan' => $obj['jenis_agunan'],
					 'luas_tanah' => $obj['luas_tanah'],
					 'merkseritahun' => $obj['merkseritahun'],
					 'nilai_agunan' => $obj['nilai_agunan'],
					 'lokasi_agunan' => $obj['lokasi_agunan'],
					 'besar_pinjaman' => $obj['besar_pinjaman'],
					 'jangka_waktu' => $obj['jangka_waktu'],
					 'nama_tempat_kerja' => $obj['nama_tempat_kerja'],
					 'bidang_usaha' => $obj['bidang_usaha'],
					 'jabatan' => $obj['jabatan'],
					 'nominal_penghasilan' => $obj['nominal_penghasilan'],
 					 'penghasilan_lain' => $obj['penghasilan_lain'],
 					 'foto_slipgaji' => $slipgaji,
 					 'foto_rekkoran' => $rekkoran
		 );
		//simpan tabel pengajuan;
		$pengajuan = $this->db->insert('pengajuan', $data);
			if ($pengajuan) {
				$this->db->where('id_user', $obj['id_user']);
				$getid = $this->db->get('user')->row();

				$data1 = array('nama_customers' => $obj['nama_lengkap'],
							   'ktp' => $obj['ktp'],
							   'npwp' => $obj['npwp'],
							   'status' => $obj['status_kawin'],
							   'alamat' => $obj['alamat'],
							   'jumlah_anak' => $obj['jumlah_anak'],
							   'no_hp' => $obj['telp'],
							   'foto_ktp' => $ktp,
							   );
				$this->db->where('id_customers', $getid->id_customers);
				$lengkapi_data = $this->db->update('customers', $data1);

				return $lengkapi_data;
			}
		return $pengajuan;
	}

	public function getlengkapi($id_customers)
	{
		$this->db->select('user.nama_user, user.username, user.email, user.telp, customers.ktp, customers.jenis_kelamin, customers.status, customers.npwp, customers.ttl, customers.alamat, customers.jumlah_anak');
		$this->db->from('user');
		$this->db->join('customers', 'customers.id_customers = user.id_customers', 'inner');
		$this->db->where('user.id_customers', $id_customers);
		$data = $this->db->get()->row();

		return $data;
	}

}

/* End of file M_pengajuan.php */
/* Location: ./application/models/M_pengajuan.php */