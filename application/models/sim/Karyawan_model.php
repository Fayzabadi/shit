<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan_model extends CI_Model {
	
	public function __construct() {
		$this->load->database();
	}
	
	// Listing
	public function listKaryawan() {
		$this->db->select('*');
		$this->db->from('s_karyawan');
		$this->db->join('s_jabatan','s_jabatan.id_jabatan = s_karyawan.id_jabatan');
		$this->db->order_by('nama','ASC');
		$query = $this->db->get(); //simpan database yang udah di get alias ambil ke query
        if ($query->num_rows() >0){
            foreach ($query->result() as $data) {
                # code...
                $k[] = $data;
            }
        return $k;
        }
	}
	
	// Semua
	public function semua_karyawan($limit, $start) {
		$this->db->select('*');
		$this->db->from('s_karyawan');
		$this->db->where(array('status_staff'=>'Yes'));
		$this->db->limit($limit, $start);
		$this->db->order_by('nama','ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	
	// Semua
	public function total_karyawan() {
		$this->db->select('*');
		$this->db->from('s_karyawan');
		$this->db->where(array('status_staff'=>'Ya'));
		$this->db->order_by('nama','ASC');
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	// Listing Besar
	public function listing_besar() {
		$this->db->select('*');
		$this->db->from('s_karyawan');
		$this->db->where(array('status_staff'=>'Ya','ukuran' => 'Besar'));
		$this->db->order_by('id_staff','DESC');
		$query = $this->db->get();
		return $query->result();
	}
	
	// Besar
	public function total_besar() {
		$this->db->select('*');
		$this->db->from('s_karyawan');
		$this->db->where(array('status_staff'=>'Ya','ukuran' => 'Besar'));
		$this->db->order_by('id_staff','DESC');
		$query = $this->db->get();
		return $query->num_rows();
	}
		
	// Detail
	public function detail($id_staff) {
		$this->db->select('*');
		$this->db->from('s_karyawan');
		$this->db->where('id_staff',$id_staff);
		$this->db->order_by('nama','DESC');
		$query = $this->db->get();
		return $query->row();
	}

	//Create Code NIP UNIK Otomatis
	function get_nip(){
		$query = $this->db->query("SELECT MAX(RIGHT(nip,4)) AS kd_max FROM s_karyawan WHERE DATE(tanggal)=CURDATE()");
        $kd = "";
        if($query->num_rows()>0){
            foreach($query->result() as $k){
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }else{
            $kd = "0001";
        }
        date_default_timezone_set('Asia/Jakarta');
        return date('dmy').$kd;
	}
	
	
	// Tambah
	public function tambah($data) {
		$this->db->insert('s_karyawan',$data);
	}
	
	// Edit
	public function edit($data) {
		$this->db->where('id_staff',$data['id_staff']);
		$this->db->join('s_jabatan','s_jabatan.id_jabatan = s_karyawan.id_jabatan','LEFT');
		unset($data['nip']);
		$this->db->update('s_karyawan',$data);
	}
	
	// Check delete
	public function check($id_staff) {
		$query = $this->db->get_where('gaji_pokok','task',array('id_staff' => $id_staff));
		return $query->num_rows();
	}
	
	// Delete
	public function delete($data) {
		$this->db->where('id_staff',$data['id_staff']);
		$this->db->delete('s_karyawan',$data);
	}
}