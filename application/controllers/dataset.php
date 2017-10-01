<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class dataset extends CI_Controller {


	function __construct(){
        parent::__construct();
        $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
	}
	

	public function index(){
		$Search			= $this->m_query->replace($this->input->post("Search"));
		$file['qdataset']= $this->m_query->get_array("select ds.IdDataset, ds.NoDiagnosa, ds.IdPenyakit, p.Penyakit from mza_dataset ds inner join mza_penyakit p on p.IdPenyakit=ds.IdPenyakit WHERE ds.NoDiagnosa like '%$Search%' or p.Penyakit like '%$Search%' order by ds.IdDataset DESC")->result();		
		$file['page']	= "dataset/dataset";
		$this->load->view('themes',$file);
	}
	
	public function dataset_form(){
		$IdDataset	= $this->uri->segment(3);
		$datasetdesc	= $this->m_query->get_array("select * from mza_dataset WHERE NoDiagnosa like 'RD%' order by IdDataset desc");
		if($datasetdesc->num_rows()>0){
			$Kode 		= explode("RD",$datasetdesc->row()->NoDiagnosa);
			$NoDiagnosa	= "RD".$this->m_query->nomor($Kode[1]+1);
		}else{
			$NoDiagnosa	= "RD".$this->m_query->nomor(1);
		}
		
		$dataset		= $this->m_query->get_array("select * from mza_dataset WHERE IdDataset='".$IdDataset."'");
		if($dataset->num_rows()>0){
			$file['IdDataset']	= $dataset->row()->IdDataset;
			$file['NoDiagnosa']	= $dataset->row()->NoDiagnosa;
			$file['IdPenyakit']	= $dataset->row()->IdPenyakit;
		}else{
			$file['IdDataset']	= "";
			$file['NoDiagnosa']	= $NoDiagnosa;
			$file['IdPenyakit']	= "";
		}
		$file['qpenyakit']= $this->m_query->get_array("select * from mza_penyakit order by IdPenyakit asc")->result();
		$file['page']	= "dataset/dataset_form";
		$this->load->view('themes',$file);
	}
	
	public function dataset_delete(){
		$IdDataset	= $this->uri->segment(3);
		$this->m_query->mquery("delete from mza_datasetdetail WHERE IdDataset='".$IdDataset."'");
		$this->m_query->mquery("delete from mza_dataset WHERE IdDataset='".$IdDataset."'");
		redirect("dataset");
	}
	
}
