<section id="partner" class="home-section paddingbot-60">	
	<div class="container marginbot-50">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
						<h2 class="h-bold">Form Input atau Edit</h2>
						<p>Data Gejala-Gejala Penyakit Ayam Broiler &amp; Petelur</p>
					</div>
				</div>
				<div class="divider-short"></div>
			</div>
		</div>
	</div>
		
	<div class="container">
		<form class="form-horizontal" method="post" action="<?php echo site_url('penyakit/gejala_save'); ?>">
			<input type="hidden" id="IdGejala" name="IdGejala" value="<?php echo $IdGejala; ?>"/>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Kode Gejala</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="KdGejala" name="KdGejala" placeholder="Kode Gejala..." value="<?php echo $KdGejala; ?>" readonly="">
				</div>
			</div>
			
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Gejala</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="Gejala" name="Gejala" placeholder="Nama Gejala..." value="<?php echo $Gejala; ?>" required="">
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					<button type="submit" class="btn btn-success"><i class=" glyphicon glyphicon-saved"></i> Submit</button>
					<a href="<?php echo site_url('penyakit/gejala'); ?>" class="btn btn-danger"><i class=" glyphicon glyphicon-th-list"></i> Back</a>
				</div>
			</div>
		</form>
	</div>
</section>	
