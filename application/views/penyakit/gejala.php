<section id="partner" class="home-section paddingbot-60">	
	<div class="container marginbot-50">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
						<h2 class="h-bold">Master Data</h2>
						<p>Data Gejala-Gejala Penyakit Ayam Broiler &amp; Petelur</p>
					</div>
				</div>
				<div class="divider-short"></div>
			</div>
		</div>
	</div>
		
	<div class="container">
		<form class="form-horizontal" method="post" action="<?php echo site_url('penyakit/gejala'); ?>">
			
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Search</label>
				<div class="col-sm-5">
					<div class="input-group">
						<input type="text" class="form-control" id="Search" name="Search" value="<?php echo $this->input->post('Search'); ?>" placeholder="Search...">
						<div class="input-group-addon"><i class=" glyphicon glyphicon-search"></i></div>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					<button type="submit" class="btn btn-primary"><i class=" glyphicon glyphicon-search"></i> Search</button>
					<a href="<?php echo site_url('penyakit/gejala_form'); ?>" class="btn btn-warning"><i class=" glyphicon glyphicon-plus"></i> Gejala</a>
				</div>
			</div>
		</form>
		
		<div class="col-sm-12">
			<div class="wow lightSpeedIn" data-wow-delay="0.1s">
				<div class="section-heading text-center">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Kode Gejala</th>
								<th>Gejala</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$No=0;
							foreach($qgejala as $key => $row){ $No++;
								?>
								<tr>
									<td class="text-right"><?php echo $No; ?></td>
									<td class="text-right"><?php echo $row->KdGejala; ?></td>
									<td class="text-left"><?php echo $row->Gejala; ?></td>
									<td>
										<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
											<button type="button" onclick="location='<?php echo site_url('penyakit/gejala_form/'.$row->IdGejala); ?>'" class="btn btn-success"><i class=" glyphicon glyphicon-edit"></i> Edit</button>
											<!--<a href="<?php echo site_url('penyakit/gejala_delete/'.$row->IdGejala); ?>" class="btn btn-danger" onclick="return confirm('Yakin akan hapus data?'); "><i class=" glyphicon glyphicon-trash"></i> Delete</a>-->
										</div>
									</td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
			
	</div>
</section>	
