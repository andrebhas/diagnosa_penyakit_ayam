<section id="partner" class="home-section paddingbot-60">	
	<div class="container marginbot-50">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
						<h2 class="h-bold">Master Data Penyakit &rarr; Gejala</h2>
						<p>Data Gejala-Gejala Penyakit Ayam Broiler &amp; Petelur</p>
					</div>
				</div>
				<div class="divider-short"></div>
			</div>
		</div>
	</div>
		
	<div class="container">
		<form class="form-horizontal" method="post" action="<?php echo site_url('penyakit/simpan_relasi_goejala'); ?>">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th></th>
						<?php
						foreach($qpenyakit as $key => $val){
							echo "<th title='".$val->Penyakit."'>".$val->KdPenyakit."</th>";
						}
						?>
					</tr>
				</thead>
				<?php
				foreach($qgejala as $key => $val){
					?>
					<tr>
						<th title="<?php echo $val->Gejala; ?>"><?php echo $val->KdGejala; ?></th>
						<?php
						foreach($qpenyakit as $key2 => $val2){
							if($this->m_query->getRelasi($val2->IdPenyakit, $val->IdGejala)->num_rows()>0){
								$check="checked=''";
							}else{
								$check="";
							}
							echo "<th title='".$val2->Penyakit." &rarr; ".$val->Gejala."' class='text-center'>
								<input type='checkbox' name='Gejala_Penyakit[]' value='".$val2->IdPenyakit."_".$val->IdGejala."' ".$check."/></th>";
						}
						?>
					</tr>
					<?php
				}
				?>
			</table>
			
			<div class="form-group">
				<div class="col-sm-12 text-center">
					<button type="submit" class="btn btn-success"><i class=" glyphicon glyphicon-saved"></i> Relasi Penyakit &amp; Gejala</button>
					<a href="<?php echo site_url('penyakit'); ?>" class="btn btn-danger"><i class=" glyphicon glyphicon-th-list"></i> Penyakit</a>
				</div>
			</div>
		</form>
		
	</div>
</section>	
