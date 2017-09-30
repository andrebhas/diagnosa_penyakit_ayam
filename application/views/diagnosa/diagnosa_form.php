<section id="partner" class="home-section paddingbot-60">	
	<div class="container marginbot-50">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
						<h2 class="h-bold">Form Input Diagnosa</h2>
						<p>Diagnosa Penyakit Ayam Broiler &amp; Petelur</p>
					</div>
				</div>
				<div class="divider-short"></div>
			</div>
		</div>
	</div>
		
	<div class="container">
		<form class="form-horizontal" method="post" action="<?php echo site_url('diagnosa/diagnosa_save'); ?>">
			<input type="hidden" id="IdDiagnosa" name="IdDiagnosa" value="<?php echo $IdDiagnosa; ?>"/>
			<div class="form-group">
				<label for="NoDiagnosa" class="col-sm-2 control-label">No.Diagnosa</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="NoDiagnosa" name="NoDiagnosa" placeholder="Kode Gejala..." value="<?php echo $NoDiagnosa; ?>" readonly="">
				</div>
			</div>
			
			<!--
			<div class="form-group">
				<label for="Nama" class="col-sm-2 control-label">Nama Ayam</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="Nama" name="Nama" placeholder="Nama Ayam..." value="<?php echo $Nama; ?>" required="">
				</div>
			</div>
			-->
			
			<div class="form-group">
				<label for="Tanggal" class="col-sm-2 control-label">Tanggal</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="Tanggal" name="Tanggal" placeholder="Tanggal Diagnosa..." value="<?php echo $Tanggal; ?>" readonly="">
				</div>
			</div>
			
			<?php
			$gejala	= $this->m_query->get_array("select g.IdGejala, g.KdGejala, g.Gejala from mza_gejala g order by g.IdGejala asc")->result();
			foreach($gejala as $key => $vgej){
				$diagnosa = $this->m_query->get_array("select * from mza_datasetdetail where IdDataset='".$IdDiagnosa."' and IdGejala='".$vgej->IdGejala."'");
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
					$ChechkedN = "";
				}
				?>
				<div class="form-group">
					<label class="col-sm-offset-2 col-sm-8 control-label" style="text-align: left; border-bottom: 1px solid #ccc;">
						<?php echo $vgej->KdGejala.", ".$vgej->Gejala; ?>
					</label>
					<div class="checkbox col-sm-1">
						<label><input type="checkbox" value="Y" name="IdGejala_<?php echo $vgej->IdGejala; ?>" <?php echo $ChechkedY; ?>>Ya</label>
					</div>
				</div>
				<?php
			}
			?>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					<button type="submit" name="btnSave" value="ok" class="btn btn-success"><i class=" glyphicon glyphicon-saved"></i> Diagnosa</button>
				</div>
			</div>
		</form>
	</div>
</section>	