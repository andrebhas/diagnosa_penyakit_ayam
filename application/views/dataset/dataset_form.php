<?php
if($this->input->post('btnGetGejala')=="ok"){
	$IdPenyakit	= $this->input->post('IdPenyakit');
}
?>
<section id="partner" class="home-section paddingbot-60">	
	<div class="container marginbot-50">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
						<h2 class="h-bold">Form Input atau Edit</h2>
						<p>Dataset Penyakit Ayam Broiler &amp; Petelur</p>
					</div>
				</div>
				<div class="divider-short"></div>
			</div>
		</div>
	</div>
		
	<div class="container">
		<form class="form-horizontal" method="post" action="<?php echo site_url('dataset/dataset_form/'.$this->uri->segment(3)); ?>">
			<input type="hidden" id="IdDataset" name="IdDataset" value="<?php echo $IdDataset; ?>"/>
			<div class="form-group">
				<label for="NoDiagnosa" class="col-sm-2 control-label">No.Dataset</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="NoDiagnosa" name="NoDiagnosa" placeholder="Kode Gejala..." value="<?php echo $NoDiagnosa; ?>" readonly="">
				</div>
			</div>
			
			<div class="form-group">
				<label for="IdPenyakit" class="col-sm-2 control-label">Penyakit</label>
				<div class="col-sm-4">
					<select name="IdPenyakit" id="IdPenyakit" class="form-control" required="">
						<option>Pilih Penyakit</option>
						<?php
						foreach($qpenyakit as $key => $vpenyakit){
							if($IdPenyakit==$vpenyakit->IdPenyakit)
							echo "<option value='".$vpenyakit->IdPenyakit."' selected>".$vpenyakit->Penyakit."</option>";
							else
							echo "<option value='".$vpenyakit->IdPenyakit."'>".$vpenyakit->Penyakit."</option>";
						}
						?>
					</select>
				</div>
				<div class="col-sm-2">
					<button type="submit" name="btnGetGejala" value="ok" class="btn btn-success"><i class=" glyphicon glyphicon-list-alt"></i> Gejala</button>
				</div>
			</div>
			<?php
			$gejala	= $this->m_query->get_array("select * from mza_gejala order by IdGejala asc")->result();
			foreach($gejala as $key => $vgej){
				$diagnosa = $this->m_query->get_array("select * from mza_datasetdetail where IdDataset='".$IdDataset."' and IdGejala='".$vgej->IdGejala."'");
				if($diagnosa->num_rows()>0){
					if($diagnosa->row()->Status=="Y"){
						$ChechkedY = "checked=''";
						$ChechkedN = "";
					}else{
						$ChechkedY = "";
						$ChechkedN = "checked=''";
					}
				}else{
					$ChechkedY = "";
					$ChechkedN = "checked=''";
				}
				?>
				<div class="form-group">
					<label class="col-sm-offset-2 col-sm-8 control-label" style="text-align: left; border-bottom: 1px solid #ccc;">
						<?php echo $vgej->Gejala; ?>
					</label>
					<div class="radio col-sm-1">
						<label><input type="radio" value="Y" name="IdGejala_<?php echo $vgej->IdGejala; ?>" <?php echo $ChechkedY; ?>>Ya</label>
					</div>
					<div class="radio col-sm-1">
						<label><input type="radio" value="N" name="IdGejala_<?php echo $vgej->IdGejala; ?>" <?php echo $ChechkedN; ?>>Tidak</label>
					</div>
				</div>
				<?php
			}
			?>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					<?php
					if($IdPenyakit!=""){
						?>
						<button type="submit" name="btnSave" value="ok" class="btn btn-success"><i class=" glyphicon glyphicon-saved"></i> Dataset</button>
						<?php
					}
					?>
					<a href="<?php echo site_url('dataset'); ?>" class="btn btn-danger"><i class=" glyphicon glyphicon-th-list"></i> Dataset</a>
				</div>
			</div>
		</form>
	</div>
</section>	
<?php
if($this->input->post('btnSave')=="ok"){
	$IdDataset	= $this->input->post('IdDataset');
	$NoDiagnosa	= $this->input->post('NoDiagnosa');
	$IdPenyakit	= $this->input->post('IdPenyakit');
	
	if($IdDataset==""){
		$query = "insert into mza_dataset set NoDiagnosa='$NoDiagnosa', IdPenyakit='$IdPenyakit'";
	}else{
		$query = "update mza_dataset set NoDiagnosa='$NoDiagnosa', IdPenyakit='$IdPenyakit' WHERE IdDataset='$IdDataset'";
	}
	$this->m_query->get_save("$query");
	$diagnosa	= $this->m_query->get_array("select * from mza_dataset WHERE NoDiagnosa='$NoDiagnosa'")->row();
	$IdDataset	= $diagnosa->IdDataset;
	
	$mgejala	= $this->m_query->get_array("select * from mza_gejala order by IdGejala asc")->result();
	$this->m_query->mquery("delete from mza_datasetdetail where IdDataset='$IdDataset'");
	foreach($mgejala as $key => $vgej){
		$IdGejala	= $vgej->IdGejala;
		$Gejala		= $this->input->post("IdGejala_".$IdGejala);
		$this->m_query->get_save("insert into mza_datasetdetail set IdDataset='$IdDataset', IdGejala='$IdGejala', Status='$Gejala'");
	}
	redirect("dataset");
}
?>