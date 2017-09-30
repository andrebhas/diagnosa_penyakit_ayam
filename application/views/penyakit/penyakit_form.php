<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js') ?>"></script>
<section id="partner" class="home-section paddingbot-60">	
	<div class="container marginbot-50">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
						<h2 class="h-bold">Form Input atau Edit</h2>
						<p>Data Penyakit Ayam Broiler &amp; Petelur</p>
					</div>
				</div>
				<div class="divider-short"></div>
			</div>
		</div>
	</div>
		
	<div class="container">
		<form class="form-horizontal" method="post" action="<?php echo site_url('penyakit/penyakit_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" id="IdPenyakit" name="IdPenyakit" value="<?php echo $IdPenyakit; ?>"/>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Kode Penyakit</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="KdPenyakit" name="KdPenyakit" placeholder="Kode Penyakit..." value="<?php echo $KdPenyakit; ?>" readonly="">
				</div>
			</div>
			
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Penyakit</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="Penyakit" name="Penyakit" placeholder="Nama Penyakit..." value="<?php echo $Penyakit; ?>" required="">
				</div>
			</div>
			
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
				<div class="col-lg-8">
					<textarea name="Deskripsi" class="form-control"  id="Deskripsi" required="" ><?php echo $Deskripsi; ?></textarea>
					<script>
						CKEDITOR.replace( 'Deskripsi' );
					</script>
				</div>
			</div>

<!--	
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Solusi</label>
				<div class="col-sm-4">
					<textarea name="Solusi" class="form-control"  id="Solusi" required="" placeholder="Solusi Mengatasi Penyaki..."><?php echo $Solusi; ?></textarea>
				</div>
			</div>
-->			

			<div class="form-group">
				<label for="Foto" class="col-sm-2 control-label">Foto Penyakit</label>
				<div class="col-sm-4">
					<input type="file" class="form-control" id="Foto[]" name="Foto[]" multiple="" accept="image/*" placeholder="Foto Penyakit..." required="">
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-saved"></i> Submit</button>
					<a href="<?php echo site_url('penyakit'); ?>" class="btn btn-danger"><i class=" glyphicon glyphicon-th-list"></i> Back</a>
				</div>
			</div>
		</form>
	</div>
</section>	
