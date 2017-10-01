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



	



	public function klasifikasi($IdDiagnosa,$lap=null)
	{
		$diagnosa	= $this->m_query->get_array("select * from mza_diagnosa WHERE IdDiagnosa='".$IdDiagnosa."'")->row();
		$detail_diagnosa = $this->m_query->get_detail_diagnosa($IdDiagnosa);
		$penyakit = $this->m_query->get_penyakit();
		/* 
			1. menghitung hasil bagi antara idGejala yg "ya" dengan jumlah dataset yang memiliki gejala yang di inputkan
			Loop i ---> P(idGejala = i | Y= ya) = hasil_bagi  
		*/
		foreach ($detail_diagnosa as $val) {
			foreach ($penyakit as $p) {
				$hasil_bagi[$val->IdGejala]['idPkt_'.$p->IdPenyakit] = $this->m_query->get_klasifikasi($val->IdGejala,$p->IdPenyakit)->hasil_bagi;
			}
		}
		//print_r($hasil_bagi);

		/* 
			2. mengalikan semua hasil bagi sesuai jenis penyakit  
		*/
		foreach ($penyakit as $p) {
			$hasil_kali[$p->IdPenyakit] = array_product(array_column($hasil_bagi, 'idPkt_'.$p->IdPenyakit));
		}
		arsort($hasil_kali);
		//print_r($hasil_kali);

		/* 
			3. mengambil peringkat idPenyakit tertinggi (hasil akhlir)  
		*/
		$max = array_keys($hasil_kali, max($hasil_kali));
		$id_penyakit_tertingggi = $max[0];
		$val_tertinggi = $hasil_kali[$max[0]];
		//echo $id_penyakit_tertingggi."  => ".$val_tertinggi ;

		/* 
			4. menampilkan hasil diagnosa  
		*/
		$hasil_diagnosa = $this->m_query->get_penyakit_by_id($id_penyakit_tertingggi);
		$data = array(
			'page' => "diagnosa/diagnosa_hasil",
			'display' => "none",
			'IdDiagnosa' => $diagnosa->IdDiagnosa,
			'NoDiagnosa' => $diagnosa->NoDiagnosa,
			'Nama' => $diagnosa->Nama,
			'Tanggal' => $diagnosa->Tanggal,
			'detail' => $detail_diagnosa,
			'dataPenyakit' => $penyakit,
			'hasil_diagnosa_all' => $hasil_kali,
			'hasil_diagnosa' =>  $hasil_diagnosa,
			'nilai_diagnosa' => $val_tertinggi,
			'lap' => $lap,
		);
		$this->load->view('themes',$data);
	}
	
	public function simpan_dataset($IdDiagnosa,$IdPenyakit){
		$dt_diagnosa = $this->m_query->get_dt_diagnosa_by_id($IdDiagnosa);
		$dataset = array(
			"NoDiagnosa" => $dt_diagnosa->NoDiagnosa,
			"IdPenyakit" => $IdPenyakit,
		);
		$this->m_query->insert_dataset($dataset);
		$this->m_query->update_datadiagnosa($IdDiagnosa);
		$IdDataset = $this->m_query->get_dataset_by_NoDiagnosa($dt_diagnosa->NoDiagnosa)->IdDataset;
		$detail_diagnosa = $this->m_query->get_detail_diagnosa($IdDiagnosa);
		foreach($detail_diagnosa as $val){
			$this->m_query->insert_datasetDetail($IdDataset,$val->IdGejala,$val->Status);
		}
		
		redirect('dataset');
	}
	
	public function laporan(){
		$file['data_diagnosa'] 	= $this->m_query->get_dt_diagnosa();
		$file['page']	= "diagnosa/laporan_diagnosa";
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
