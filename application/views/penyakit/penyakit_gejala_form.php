<section id="partner" class="home-section paddingbot-60">	
	<div class="container marginbot-50">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
						<h2 class="h-bold">Form Input atau Edit</h2>
						<p>Gejala Penyakit <?php $Penyakit; ?> Ayam Broiler &amp; Petelur</p>
					</div>
				</div>
				<div class="divider-short"></div>
			</div>
		</div>
	</div>
		
	<div class="container">
		<form class="form-horizontal" method="post" action="<?php echo site_url('penyakit/penyakit_gejala_save'); ?>">
			<input type="hidden" id="IdPGejala" name="IdPGejala" value="<?php echo $IdPGejala; ?>"/>
			<input type="hidden" id="IdPenyakit" name="IdPenyakit" value="<?php echo $IdPenyakit; ?>"/>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Gejala Penyakit</label>
				<div class="col-sm-10">
					<?php
					$No=0;
					foreach($qgejala as $key => $row){
						$No++;
						if($IdGejala==$row->IdGejala){ $Chechked = "checked";
						}else{ $Chechked = ""; }
						?>
						<div class="radio">
							<label>
								<input type="radio" name="IdGejala" id="IdGejala<?php echo $No; ?>" value="<?php echo $row->IdGejala; ?>" <?php echo $Chechked; ?>>
								<?php echo $row->Gejala; ?>
							</label>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					<button type="submit" class="btn btn-success"><i class=" glyphicon glyphicon-saved"></i> Gejala Penyakit</button>
					<a href="<?php echo site_url('penyakit'); ?>" class="btn btn-danger"><i class=" glyphicon glyphicon-th-list"></i> Penyakit</a>
				</div>
			</div>
		</form>
	</div>
</section>	
