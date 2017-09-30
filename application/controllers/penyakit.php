<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class penyakit extends CI_Controller{
	public function index(){
		$Search			= $this->m_query->replace($this->input->post("Search"));
		$file['qpenyakit']= $this->m_query->get_array("select * from mza_penyakit WHERE Penyakit like '%$Search%' order by IdPenyakit asc")->result();		
		$file['page']	= "penyakit/penyakit";
		$this->load->view('themes',$file);
	}
	
	
	public function penyakit_form(){
		$IdPenyakit	= $this->uri->segment(3);
		$penyakitdesc	= $this->m_query->get_array("select * from mza_penyakit order by IdPenyakit desc");
		if($penyakitdesc->num_rows()>0){
			$Kode 		= explode("PA",$penyakitdesc->row()->KdPenyakit);
			$KdPenyakit	= "PA".$this->m_query->nomor($Kode[1]+1);
		}else{
			$KdPenyakit	= "PA".$this->m_query->nomor(1);
		}
		$penyakit		= $this->m_query->get_array("select * from mza_penyakit WHERE IdPenyakit='".$IdPenyakit."'");
		if($penyakit->num_rows()>0){
			$file['IdPenyakit']	= $penyakit->row()->IdPenyakit;
			$file['KdPenyakit']	= $penyakit->row()->KdPenyakit;
			$file['Penyakit']	= $penyakit->row()->Penyakit;
			$file['Deskripsi']	= $penyakit->row()->Deskripsi;
			//$file['Solusi']	= $penyakit->row()->Solusi;
		}else{
			$file['IdPenyakit']	= "";
			$file['KdPenyakit']	= $KdPenyakit;
			$file['Penyakit']	= "";
			$file['Deskripsi']	= "";
			//$file['Solusi']	= "";
		}
		
		$file['page']	= "penyakit/penyakit_form";
		$this->load->view('themes',$file);
	}
	
	public function penyakit_save(){
		$IdPenyakit	= $this->m_query->replace($this->input->post("IdPenyakit"));
		$KdPenyakit	= $this->m_query->replace($this->input->post("KdPenyakit"));
		$Penyakit	= $this->m_query->replace($this->input->post("Penyakit"));
		$Deskripsi	= $this->m_query->replace($this->input->post("Deskripsi"));
		$Solusi		= $this->m_query->replace($this->input->post("Solusi"));
		$Foto		= $_FILES['Foto'];
		$xu		= 0; $FotoUpload="";
		for($x=0; $x<count($_FILES['Foto']['name']); $x++){
			$Upload	= "penyakit/".$Foto['name'][$x];
			if(move_uploaded_file($Foto['tmp_name'][$x], $Upload)){
				$xu++;
				if($xu==1){
					$FotoUpload = "[".$Foto['name'][$x]."]";
				}else{
					$FotoUpload = $FotoUpload.",[".$Foto['name'][$x]."]";
				}
			}
		}
				
		if($IdPenyakit==""){
			$query	= "Insert into mza_penyakit set KdPenyakit='$KdPenyakit', Penyakit='$Penyakit', Deskripsi='$Deskripsi',  Foto='".$FotoUpload."'";
		}else{
			$query	= "update mza_penyakit set KdPenyakit='$KdPenyakit', Penyakit='$Penyakit', Deskripsi='$Deskripsi', Foto='".$FotoUpload."' WHERE IdPenyakit='$IdPenyakit'";
		}
		$this->m_query->get_save("$query");
		redirect("penyakit");
	}
	
	public function penyakit_delete(){
		$IdPenyakit	= $this->uri->segment(3);
		$query		= "delete from mza_penyakit WHERE IdPenyakit='".$IdPenyakit."'";
		$this->m_query->mquery("$query");
		redirect("penyakit");
	}
	
	public function penyakit_gejala_form(){
		$IdPenyakit		= $this->uri->segment(3);
		$IdGejala		= $this->uri->segment(4);
		$penyakit		= $this->m_query->get_array("select * from mza_penyakit WHERE IdPenyakit='".$IdPenyakit."'");
		$penyakitgejala	= $this->m_query->get_array("select * from mza_penyakitgejala WHERE IdPenyakit='".$IdPenyakit."' and IdGejala='".$IdGejala."'");
		if($penyakitgejala->num_rows()>0){
			$file['IdPGejala']	= $penyakitgejala->row()->IdPGejala;
			$file['IdPenyakit']	= $penyakitgejala->row()->IdPenyakit;
			$file['IdGejala']	= $penyakitgejala->row()->IdGejala;
			$Where				= "where mpg.IdGejala is null or mg.IdGejala=$IdGejala";
		}else{
			$file['IdPGejala']	= "";
			$file['IdPenyakit']	= $IdPenyakit;
			$file['IdGejala']	= "";
			$Where				= "where mpg.IdGejala is null";
		}
		
		$file['Penyakit']	= $penyakit->row()->Penyakit;
		$file['qgejala']= $this->m_query->get_array("select 
			mg . *
			from
			mza_gejala mg
			left join
			mza_penyakitgejala mpg ON mpg.IdGejala = mg.IdGejala and mpg.IdPenyakit=$IdPenyakit $Where order by mg.IdGejala asc")->result();		
		$file['page']	= "penyakit/penyakit_gejala_form";
		$this->load->view('themes',$file);
	}
	
	public function penyakit_gejala_save(){
		$IdPGejala	= $this->m_query->replace($this->input->post("IdPGejala"));
		$IdPenyakit	= $this->m_query->replace($this->input->post("IdPenyakit"));
		$IdGejala	= $this->m_query->replace($this->input->post("IdGejala"));
		
		if($IdPGejala==""){
			$query	= "Insert into mza_penyakitgejala set IdPenyakit='$IdPenyakit', IdGejala='$IdGejala'";
		}else{
			$query	= "update mza_penyakitgejala set IdPenyakit='$IdPenyakit', IdGejala='$IdGejala' WHERE IdPGejala='$IdPGejala'";
		}
		$this->m_query->get_save("$query");
		redirect("penyakit");
	}
	
	public function penyakit_gejala_delete(){
		$IdPGejala	= $this->uri->segment(3);
		$query		= "delete from mza_penyakitgejala WHERE IdPGejala='".$IdPGejala."'";
		$this->m_query->mquery("$query");
		redirect("penyakit");
	}
	
	/**
	* Gejala Penyakit
	* 
	* @return
	*/
	
	public function gejala(){
		$Search			= $this->m_query->replace($this->input->post("Search"));
		$file['qgejala']= $this->m_query->get_array("select * from mza_gejala WHERE Gejala like '%$Search%' order by IdGejala asc")->result();		
		$file['page']	= "penyakit/gejala";
		$this->load->view('themes',$file);
	}
	
	public function gejala_form(){
		$IdGejala	= $this->uri->segment(3);
		$gejaladesc	= $this->m_query->get_array("select * from mza_gejala order by IdGejala desc");
		if($gejaladesc->num_rows()>0){
			$Kode 		= explode("PG",$gejaladesc->row()->KdGejala);
			$KdGejala	= "PG".$this->m_query->nomor($Kode[1]+1);
		}else{
			$KdGejala	= "PG".$this->m_query->nomor(1);
		}
		$gejala		= $this->m_query->get_array("select * from mza_gejala WHERE IdGejala='".$IdGejala."'");
		if($gejala->num_rows()>0){
			$file['IdGejala']	= $gejala->row()->IdGejala;
			$file['KdGejala']	= $gejala->row()->KdGejala;
			$file['Gejala']		= $gejala->row()->Gejala;
		}else{
			$file['IdGejala']	= "";
			$file['KdGejala']	= $KdGejala;
			$file['Gejala']		= "";
		}
		
		$file['page']	= "penyakit/gejala_form";
		$this->load->view('themes',$file);
	}
	
	public function gejala_save(){
		$IdGejala	= $this->m_query->replace($this->input->post("IdGejala"));
		$KdGejala	= $this->m_query->replace($this->input->post("KdGejala"));
		$Gejala		= $this->m_query->replace($this->input->post("Gejala"));
		
		if($IdGejala==""){
			$query	= "Insert into mza_gejala set KdGejala='$KdGejala', Gejala='$Gejala'";
		}else{
			$query	= "update mza_gejala set KdGejala='$KdGejala', Gejala='$Gejala' WHERE IdGejala='$IdGejala'";
		}
		$this->m_query->get_save("$query");
		redirect("penyakit/gejala");
	}
	
	public function gejala_delete(){
		$IdGejala	= $this->uri->segment(3);
		$query		= "delete from mza_gejala WHERE IdGejala='".$IdGejala."'";
		$this->m_query->mquery("$query");
		redirect("penyakit/gejala");
	}
	
	public function set_penyakit_gejala_form(){
		$file['qpenyakit']	= $this->m_query->get_array("select * from mza_penyakit order by IdPenyakit asc")->result();
		$file['qgejala']	= $this->m_query->get_array("select * from mza_gejala order by IdGejala asc")->result();
		$file['page']	= "penyakit/set_penyakit_gejala_form";
		$this->load->view('themes',$file);
	}
	
	public function simpan_relasi_goejala(){
		$qpenyakit	= $this->m_query->get_array("select * from mza_penyakit order by IdPenyakit asc")->result();
		$qgejala	= $this->m_query->get_array("select * from mza_gejala order by IdGejala asc")->result();
		
		$Gejala_Penyakit	= $this->input->post('Gejala_Penyakit');
		$Where	= "";
		for($data = 0; $data < count($Gejala_Penyakit); $data++){
			$VPost	= explode("_",$Gejala_Penyakit[$data]);
			if($Where==""){
				$Where	= "WHERE (IdPenyakit='".$VPost[0]."' and IdGejala='".$VPost[1]."')";
			}else{
				$Where	= $Where." or (IdPenyakit='".$VPost[0]."' and IdGejala='".$VPost[1]."')";
			}
			
			$getRelasi	= $this->m_query->getRelasi($VPost[0], $VPost[1]);
			if($getRelasi->num_rows()<=0){
				$this->db->query("INSERT INTO mza_penyakitgejala set IdPenyakit='".$VPost[0]."', IdGejala='".$VPost[1]."'");
			}else{
				$this->db->query("UPDATE mza_penyakitgejala set IdPenyakit='".$VPost[0]."', IdGejala='".$VPost[1]."' where IdPGejala='".$getRelasi->row()->IdPGejala."'");
			}
			
		}
		
		$Select	= $this->db->query("SELECT * FROM mza_penyakitgejala $Where")->result();
		$IdPGejala	= "";
		foreach($Select as $key => $value){
			if($IdPGejala==""){
				$IdPGejala	= $value->IdPGejala;
			}else{
				$IdPGejala	= $IdPGejala.", ".$value->IdPGejala;
			}
		}
		
		$this->db->query("DELETE FROM mza_penyakitgejala WHERE IdPGejala not in($IdPGejala)");
		
		redirect("penyakit");
		/*
		foreach($qgejala as $key => $val){
			foreach($qpenyakit as $key2 => $val2){
				
			}
		}
		*/
	}
	
}
