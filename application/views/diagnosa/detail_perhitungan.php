<?php
$this->m_query->get_array("UPDATE `mza_penyakit` set Hasil=0");
?>
<section id="partner" class="home-section paddingbot-60">	
	<div class="container marginbot-50">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
						<h2 class="h-bold">Report Diagnosa</h2>
						<p>Hasil Diagnosa Penyakit Ayam Broiler &amp; Petelur</p>
					</div>
				</div>
				<div class="divider-short"></div>
			</div>
		</div>
	</div>
		
	<div class="container">
		<form class="form-horizontal" method="post" action="">
			<div class="form-group">
				<label for="NoDiagnosa" class="col-sm-2 control-label">No.Diagnosa</label>
				<label for="NoDiagnosa" class="col-sm-10 control-label" style="text-align: left;">: <?php echo $NoDiagnosa; ?></label>
			</div>
			
			<div class="form-group">
				<label for="NoDiagnosa" class="col-sm-2 control-label">Tanggal Diagnosa</label>
				<label for="NoDiagnosa" class="col-sm-10 control-label" style="text-align: left;">: <?php echo $this->m_query->tgl($Tanggal); ?></label>
			</div>
			<ul>
				<?php
				$Max=0; $IdPenyakit=0;
				foreach($getPenyakit as $key => $val){
					$getGejala	= $this->db->query("select 
						a . *, b.KdGejala, (select 
						count(c.IdDataset)
						from
						mza_dataset c
						left join
						mza_datasetdetail d ON d.IdDataset = c.IdDataset
						where d.IdGejala=a.IdGejala and c.IdPenyakit='".$val->IdPenyakit."' 
						and d.`Status`=a.`Status`) as Status_Check
						from
						mza_diagnosadetail a
						inner join
						mza_gejala b ON b.IdGejala = a.IdGejala
						where
						a.IdDiagnosa ='".$IdDiagnosa."' and a.`Status`='Y'")->result();
					$PPenyakit		= round($val->Jml/$val->Pembagi,3);
					$Probabilitas	= $PPenyakit;
					?>
					<li>P(Class Penyakit = "<?php echo $val->KdPenyakit; ?>") = <?php echo $PPenyakit; ?>
						<ol>
							<?php
							foreach($getGejala as $key2 => $vgej){
								$Nilai	= round($vgej->Status_Check/$val->Jml,3);
								?>
								<li>P(<?php echo $vgej->KdGejala." (".$vgej->Status.") = '".$val->KdPenyakit."'"; ?>) = 
									<?php echo $Nilai; ?></li>
								<?php
								$Probabilitas	= $Probabilitas*$Nilai;
							}
							?>
						</ol>
						<h6>Hasil Probabilitas "<?php echo $val->Penyakit; ?>" adalah <?php echo ($Probabilitas); ?></h6>					
					</li>
					<?php
					$this->db->query("UPDATE mza_penyakit SET Hasil='".$Probabilitas."' WHERE IdPenyakit='".$val->IdPenyakit."'");
					if($Max<$Probabilitas){
						$Max = $Probabilitas;
						$IdPenyakit = $val->IdPenyakit;
					}
				}
				?>
			</ul>
			<center><?php
			$getData	= $this->db->query("select 
				*
				from
				mza_penyakit a
				WHERE a.Hasil>0
				order by a.Hasil desc limit 1");
				if($getData->num_rows()>0){
					
					echo "<br><a href='".site_url('diagnosa/hasil_diagnosa/'.$this->uri->segment(3))."' class='btn btn-success'>Kembali</a>";
				}else{
					echo "<h4>Hasil tidak ditemukan</h4>";
				}
				$this->db->query("UPDATE mza_diagnosa SET Nilai='".$Max."', IdPenyakit='".$IdPenyakit."' 
				WHERE IdDiagnosa='".$IdDiagnosa."'");
			?></center>
		</form>
	</div>
</section>