<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class diagnosa extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('Fpdf_gen');
	}
	
	public function index(){
		
		$IdDiagnosa	= $this->uri->segment(3);
		$diagnosadesc	= $this->m_query->get_array("select * from mza_diagnosa order by IdDiagnosa desc");
		if($diagnosadesc->num_rows()>0){
			$Kode 		= explode("MReg",$diagnosadesc->row()->NoDiagnosa);
			$NoDiagnosa	= "MReg".$this->m_query->nomor($Kode[1]+1);
		}else{
			$NoDiagnosa	= "MReg".$this->m_query->nomor(1);
		}
		$diagnosa		= $this->m_query->get_array("select * from mza_diagnosa WHERE IdDiagnosa='".$IdDiagnosa."'");
		if($diagnosa->num_rows()>0){
			$file['IdDiagnosa']	= $diagnosa->row()->IdDiagnosa;
			$file['NoDiagnosa']	= $diagnosa->row()->NoDiagnosa;
			$file['Nama']		= $diagnosa->row()->Nama;
			$file['Tanggal']	= $this->m_query->tgl($diagnosa->row()->Tanggal);
		}else{
			$file['IdDiagnosa']	= "";
			$file['NoDiagnosa']	= $NoDiagnosa;
			$file['Nama']		= "";
			$file['Tanggal']	= date("d-m-Y");
		}

		$file['page']	= "diagnosa/diagnosa_form";
		$this->load->view('themes',$file);
	}
	
	public function diagnosa_save(){
		$IdDiagnosa	= $this->m_query->replace($this->input->post("IdDiagnosa"));
		$NoDiagnosa	= $this->m_query->replace($this->input->post("NoDiagnosa"));
		$Nama		= $this->m_query->replace($this->input->post("Nama"));
		$Tanggal	= $this->m_query->tgl($this->m_query->replace($this->input->post("Tanggal")));
		
		if($IdDiagnosa==""){
			$query = "insert into mza_diagnosa set NoDiagnosa='$NoDiagnosa', Nama='$Nama', Tanggal='$Tanggal'";
		}else{
			$query = "update mza_diagnosa set NoDiagnosa='$NoDiagnosa', Nama='$Nama', Tanggal='$Tanggal' WHERE IdDiagnosa='$IdDiagnosa'";
		}
		$this->m_query->get_save("$query");
		$diagnosa	= $this->m_query->get_array("select * from mza_diagnosa where NoDiagnosa='$NoDiagnosa'")->row();
		$IdDiagnosa	= $diagnosa->IdDiagnosa;
		$this->m_query->get_save("delete from mza_diagnosadetail WHERE IdDiagnosa='$IdDiagnosa'");
		$gejala	= $this->m_query->get_array("select g.IdGejala, g.KdGejala, g.Gejala from mza_gejala g order by g.IdGejala asc")->result();
		foreach($gejala as $key => $value){
			$IdGejala	= $value->IdGejala;
			$Status		= $this->input->post("IdGejala_".$value->IdGejala);
			if($Status=="Y"){ $Status = "Y"; }else{ $Status = "N"; }
			$this->m_query->get_save("insert into mza_diagnosadetail set IdDiagnosa='$IdDiagnosa', IdGejala='$IdGejala', Status='$Status'");
		}
		redirect("diagnosa/klasifikasi/".$IdDiagnosa);
	}
	
	// public function hasil_diagnosa(){
	// 	$IdDiagnosa	= $this->uri->segment(3);
	// 	$diagnosa	= $this->m_query->get_array("select * from mza_diagnosa WHERE IdDiagnosa='".$IdDiagnosa."'")->row();
	// 	$file['IdDiagnosa']	= $diagnosa->IdDiagnosa;
	// 	$file['NoDiagnosa']	= $diagnosa->NoDiagnosa;
	// 	$file['Nama']		= $diagnosa->Nama;
	// 	$file['Tanggal']	= $diagnosa->Tanggal;
	// 	$file['getPenyakit']	= $this->db->query("select 
	// 		a . *,
	// 		(select count(e.IdDataset) from mza_dataset e where e.IdPenyakit=a.IdPenyakit) as Jml,
	// 		(select count(b.IdDataset) from mza_dataset b) as Pembagi
	// 		from
	// 		mza_penyakit a
	// 		inner join
	// 		mza_penyakitgejala b ON b.IdPenyakit = a.IdPenyakit
	// 		inner join
	// 		mza_gejala c ON c.IdGejala = b.IdGejala
	// 		inner join
	// 		mza_diagnosadetail d ON d.IdGejala = c.IdGejala
	// 		and d.`Status` = 'Y' and d.IdDiagnosa='".$diagnosa->IdDiagnosa."'
	// 		group by a.IdPenyakit
	// 		order by a.IdPenyakit asc")->result();
	// 	$file['page']	  	= "diagnosa/diagnosa_hasil";
	// 	$this->load->view('themes',$file);
	// }

	public function klasifikasi($IdDiagnosa)
	{
		$diagnosa	= $this->m_query->get_array("select * from mza_diagnosa WHERE IdDiagnosa='".$IdDiagnosa."'")->row();
		$detail_diagnosa = $this->m_query->get_detail_diagnosa($IdDiagnosa);
		$penyakit = $this->m_query->get_penyakit();
		
		foreach ($detail_diagnosa as $val) {
			foreach ($penyakit as $p) {
				$hasil_bagi[$val->IdGejala]['idPkt_'.$p->IdPenyakit] = $this->m_query->get_klasifikasi($val->IdGejala,$p->IdPenyakit)->hasil_bagi;
			}
		}
		//echo json_encode($hasil_bagi);
		foreach ($penyakit as $p) {
			$hasil_kali[$p->IdPenyakit] = array_product(array_column($hasil_bagi, 'idPkt_'.$p->IdPenyakit));
		}
		arsort($hasil_kali);
		//print_r($hasil_kali);
		$max = array_keys($hasil_kali, max($hasil_kali));
		$id_penyakit_tertingggi = $max[0];
		$val_tertinggi = $hasil_kali[$max[0]];
		
		echo $id_penyakit_tertingggi."  => ".$val_tertinggi ;

		$data = array(
			'IdDiagnosa' => $diagnosa->IdDiagnosa,
			'NoDiagnosa' => $diagnosa->NoDiagnosa,
			'Nama' => $diagnosa->Nama,
			'Tanggal' => $diagnosa->Tanggal,
			'detail' => $detail_diagnosa,
			'dataPenyakit' => $penyakit
		);
		//echo json_encode($data);

		// Array ( 
		// 	[1] => Array ( [1] => 0.1033 [2] => 0.0067 [3] => 0.0133 [4] => 0.0100 [5] => 0.1533 [6] => 0.0900 [8] => 0.1100 [9] => 0.0867 )[2] => Array ( [1] => 0.0800 [2] => 0.0100 [3] => 0.0100 [4] => 0.0133 [5] => 0.1500 [6] => 0.0067 [8] => 0.0067 [9] => 0.0100 )[3] => Array ( [1] => 0.0800 [2] => 0.0100 [3] => 0.0233 [4] => 0.0100 [5] => 0.0133 [6] => 0.0100 [8] => 0.0067 [9] => 0.0100 )[4] => Array ( [1] => 0.0667 [2] => 0.0133 [3] => 0.0133 [4] => 0.0200 [5] => 0.0100 [6] => 0.0133 [8] => 0.0100 [9] => 0.0067 )[5] => Array ( [1] => 0.1333 [2] => 0.0933 [3] => 0.0100 [4] => 0.0100 [5] => 0.1567 [6] => 0.1100 [8] => 0.1000 [9] => 0.0767 )
		// )

	}
	
	public function detail_perhitungan(){
		$IdDiagnosa	= $this->uri->segment(3);
		$diagnosa	= $this->m_query->get_array("select * from mza_diagnosa WHERE IdDiagnosa='".$IdDiagnosa."'")->row();
		$file['IdDiagnosa']	= $diagnosa->IdDiagnosa;
		$file['NoDiagnosa']	= $diagnosa->NoDiagnosa;
		$file['Nama']		= $diagnosa->Nama;
		$file['Tanggal']	= $diagnosa->Tanggal;
		$file['getPenyakit']	= $this->db->query("select 
			a . *,
			(select count(e.IdDataset) from mza_dataset e where e.IdPenyakit=a.IdPenyakit) as Jml,
			(select count(b.IdDataset) from mza_dataset b) as Pembagi
			from
			mza_penyakit a
			inner join
			mza_penyakitgejala b ON b.IdPenyakit = a.IdPenyakit
			inner join
			mza_gejala c ON c.IdGejala = b.IdGejala
			inner join
			mza_diagnosadetail d ON d.IdGejala = c.IdGejala
			and d.`Status` = 'Y' and d.IdDiagnosa='".$diagnosa->IdDiagnosa."'
			group by a.IdPenyakit
			order by a.IdPenyakit asc")->result();
		$file['page']	  	= "diagnosa/detail_perhitungan";
		$this->load->view('themes',$file);
	}
	
	public function simpan_dataset(){
		$IdDiagnosa	= $this->uri->segment(3);
		$diagnosa	= $this->m_query->get_array("select * from mza_diagnosa WHERE IdDiagnosa='".$IdDiagnosa."'");
		if($diagnosa->num_rows()>0){
			$NoDiagnosa	= $diagnosa->row()->NoDiagnosa;
			$IdPenyakit	= $diagnosa->row()->IdPenyakit;
			$get=$this->db->query("select * from mza_dataset where NoDiagnosa='".$NoDiagnosa."'");
			if($get->num_rows()<=0){
				$this->db->query("INSERT INTO mza_dataset SET NoDiagnosa='".$NoDiagnosa."', IdPenyakit='".$IdPenyakit."'");
			}ELSE{
				$this->db->query("UPDATE mza_dataset SET IdPenyakit='".$IdPenyakit."' where NoDiagnosa='".$NoDiagnosa."'");
			}
			$get=$this->db->query("select * from mza_dataset where NoDiagnosa='".$NoDiagnosa."'");
			
			$gejala	= $this->m_query->get_array("select * from mza_diagnosadetail WHERE IdDiagnosa='".$IdDiagnosa."'")->result();
			foreach($gejala as $ket => $val){
				$valid	= $this->db->query("select * from mza_datasetdetail where IdDataset='".$get->row()->IdDataset."'
					and IdGejala='".$val->IdGejala."'");
				if($valid->num_rows()>0){
					$this->db->query("UPDATE mza_datasetdetail SET Status='".$val->Status."' where IdDataset='".$get->row()->IdDataset."'
						and IdGejala='".$val->IdGejala."'");
				}else{
					$this->db->query("INSERT INTO mza_datasetdetail SET Status='".$val->Status."' , IdDataset='".$get->row()->IdDataset."'
						, IdGejala='".$val->IdGejala."'");
				}
			}
			redirect('diagnosa/laporan');
		}else{
			redirect('diagnosa/laporan');
		}
		
	}
	
	public function laporan(){
		$file['qdata']	  	= $this->db->query("select 
			a . *, b.KdPenyakit, b.Penyakit, date_format(a.Tanggal,'%d-%m-%Y') as Tgl
			from
			mza_diagnosa a
			inner join
			mza_penyakit b ON b.IdPenyakit = a.IdPenyakit
			order by a.IdDiagnosa asc")->result();
		$file['page']	  	= "diagnosa/laporan_diagnosa";
		$this->load->view('themes',$file);
	}
	
	public function print_diagnosa(){
		$this->fpdf->Ln(7);
		$this->fpdf->SetFont('Arial','B',14);
		$this->fpdf->Cell(0,6,strtoupper('universitas jember'),0,1,'C');
		$this->fpdf->SetFont('Arial','',10);
		$this->fpdf->Cell(0,4,'Jl.-',0,1,'C');
		$this->fpdf->SetFont('Arial','B',12);
		$this->fpdf->Cell(0,5,'LAPORAN DIAGNOSA',0,1,'C');
		
		$qdata	  	= $this->db->query("select 
			a . *, b.KdPenyakit, b.Penyakit, date_format(a.Tanggal,'%d-%m-%Y') as Tgl
			from
			mza_diagnosa a
			inner join
			mza_penyakit b ON b.IdPenyakit = a.IdPenyakit
			order by a.IdDiagnosa asc")->result();
		$No=0;
		$this->fpdf->SetFont('Arial','',10);
		$this->fpdf->Cell(10,5,'No','LTR',0);
		$this->fpdf->Cell(25,5,'Tanggal','LTR',0);
		$this->fpdf->Cell(35,5,'No.Diagnosa','LTR',0);
		$this->fpdf->Cell(80,5,'Penyakit','LTR',0);
		$this->fpdf->Cell(0,5,'Bobot','LTR',1);
		
		foreach($qdata as $key => $val){
			$No++;
			$this->fpdf->Cell(10,5,$No,'LTR',0);
			$this->fpdf->Cell(25,5,$val->Tgl,'TR',0);
			$this->fpdf->Cell(35,5,$val->NoDiagnosa,'TR',0);
			$this->fpdf->Cell(80,5,$val->Penyakit,'TR',0);
			$this->fpdf->Cell(0,5,number_format($val->Nilai,5,",","."),'TR',1,'R');
		}
		$this->fpdf->Cell(0,1,'','T',1);
		echo $this->fpdf->Output("laporan transfer.pdf",'D');
	}
	
}
