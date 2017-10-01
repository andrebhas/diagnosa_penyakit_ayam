<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_query extends CI_Model{
	function get_array($query){
		$result = $this->db->query("$query");
		return $result;
	}
	
	function get_save($query){
		$result = $this->db->query("$query");
		return $result;
	}
	
	function mquery($query){
		$this->db->query("$query");
	}
	
	function nomor($number){
		$Length = strlen($number);
		if($Length==1){ $Nol="00";
		}elseif($Length==2){ $Nol="0";
		}else{ $Nol=""; }
		return $Nol.$number;
	}
	
	function replace($text){
		return str_replace("'","''",$text);
	}
	
	function tgl($date){
		$Tgl = explode("-",$date);
		return $Tgl[2]."-".$Tgl[1]."-".$Tgl[0];
	}
	
	function convert_time($second){
		$SJ		= 3600;
		$SM		= 60;
		
		$J		= $second%$SJ;
		$SJam	= $second-$J;
		$Jam	= round($SJam/$SJ,3);
		
		$M		= $J%$SM;
		$SMenit	= $J-$M;
		$Menit	= round($SMenit/$SM,3);
		
		
		
		if($Jam>0){
			$timer = $Jam." jam, ".$Menit." menit, ".$M." detik";
		}else if($Jam<=0 && $Menit>0){
			$timer = $Menit." menit, ".$M." detik";
		}else{
			$timer = round($second,3)." detik";
		}
		return $timer;
	}
	
	public function getRelasi($IdPenyakit, $IdGejala){
		$return = $this->db->query("SELECT * FROM mza_penyakitgejala WHERE IdPenyakit='".$IdPenyakit."' and IdGejala='".$IdGejala."'");
		return $return;
	}

	function get_dt_diagnosa()
	{
		$sql = "select * from mza_diagnosa WHERE status_simpan = 0";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_dt_diagnosa_by_id($id)
	{
		$sql = "select * from mza_diagnosa WHERE IdDiagnosa = ".$id;
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_detail_diagnosa($id_diagnosa)
	{
		$sql = "SELECT mza_diagnosadetail.IdDiagnosa, mza_diagnosadetail.IdGejala, 
					   mza_diagnosadetail.Status, mza_gejala.KdGejala, mza_gejala.Gejala
				FROM mza_diagnosadetail
				JOIN mza_gejala ON mza_gejala.IdGejala = mza_diagnosadetail.IdGejala
				WHERE `IdDiagnosa` = $id_diagnosa AND Status = 'Y'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_penyakit()
	{
		$sql = "SELECT * FROM mza_penyakit";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_penyakit_by_id($idP)
	{
		$sql = "SELECT * FROM mza_penyakit where idPenyakit = $idP";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_klasifikasi($id_gejala,$id_penyakit)
	{
		$sql = "
			SELECT 
				(
					SELECT COUNT(b.IdPenyakit)
					FROM mza_datasetdetail a
					JOIN mza_dataset b ON b.IdDataset = a.IdDataset
					JOIN mza_penyakit c ON c.IdPenyakit = b.IdPenyakit
					JOIN mza_gejala d ON d.IdGejala = a.IdGejala
					where a.IdGejala =".$id_gejala." AND a.Status = 'Y' AND b.IdPenyakit = ".$id_penyakit."
				) / COUNT(mza_datasetdetail.IdGejala) as hasil_bagi
			FROM mza_datasetdetail
			JOIN mza_dataset ON mza_dataset.IdDataset = mza_datasetdetail.IdDataset
			JOIN mza_penyakit ON mza_penyakit.IdPenyakit = mza_dataset.IdPenyakit
			JOIN mza_gejala ON mza_gejala.IdGejala = mza_datasetdetail.IdGejala
			WHERE mza_datasetdetail.IdGejala =".$id_gejala;
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_detail_dataset_by_idDtSet($idDataset)
	{
		$sql = "
			SELECT mza_datasetdetail.IdDataset, mza_datasetdetail.IdGejala, mza_datasetdetail.Status, mza_gejala.KdGejala, mza_gejala.Gejala
			FROM mza_datasetdetail 
			JOIN mza_gejala ON mza_datasetdetail.IdGejala = mza_gejala.IdGejala
			WHERE mza_datasetdetail.Status = 'Y' AND mza_datasetdetail.IdDataset = ".$idDataset;
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_dataset_by_NoDiagnosa($noD)
	{
		$sql = "SELECT * FROM `mza_dataset` WHERE `NoDiagnosa` = '$noD'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function insert_dataset($data)
	{
		$this->db->insert('mza_dataset', $data);
	}

	function update_datadiagnosa($idDiagnosa)
	{
		$this->db->set('status_simpan', 1);
		$this->db->where('IdDiagnosa', $idDiagnosa);
		$this->db->update('mza_diagnosa');
	}

	function insert_datasetDetail($iddataset,$idgejala,$status)
	{
		$sql = "INSERT INTO `mza_datasetdetail` (`IdDataset`, `IdGejala`, `Status`) VALUES ('$iddataset', '$idgejala', '$status')";
		$this->db->query($sql);
	}
	
}
?>