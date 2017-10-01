<section id="partner" class="home-section paddingbot-60">	
	<div class="container marginbot-50">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
						<h2 class="h-bold">Master Data</h2>
						<p>Dataset Penyakit Ayam Broiler &amp; Petelur</p>
					</div>
				</div>
				<div class="divider-short"></div>
			</div>
		</div>
	</div>
		
	<div class="container">
		<form class="form-horizontal" method="post" action="<?php echo site_url('dataset'); ?>">
			
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Search</label>
				<div class="col-sm-5">
					<div class="input-group">
						<input type="text" class="form-control" id="Search" name="Search" value="<?php echo $this->input->post('Search'); ?>" placeholder="Search Penyakit ... ">
						<div class="input-group-addon"><i class=" glyphicon glyphicon-search"></i></div>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					<button type="submit" class="btn btn-primary"><i class=" glyphicon glyphicon-search"></i> Search</button>
					<a href="<?php echo site_url('diagnosa/laporan'); ?>" class="btn btn-warning"><i class=" glyphicon glyphicon-plus"></i> Dataset</a>
				</div>
			</div>
		</form>
		
		<div class="col-sm-12">
			<div class="wow lightSpeedIn" data-wow-delay="0.1s">
				<div class="section-heading text-center">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>No.Dataset</th>
								<th>Gejala</th>
								<th>Penyakit</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($qdataset as $key => $row){ ?>
								<tr>
									<td class="text-right"><?php echo $row->NoDiagnosa; ?></td>
									<td class="text-left"><?php 
										$gejala = $this->m_query->get_detail_dataset_by_idDtSet($row->IdDataset);
										foreach ($gejala as $key => $val) {
											echo $val->KdGejala." - ".$val->Gejala."<br>";
										}
									?></td>
									<td class="text-left"><?php echo $row->Penyakit; ?></td>
									<td>
										<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
											<button type="button" onclick="location='<?php echo site_url('dataset/dataset_form/'.$row->IdDataset); ?>'" class="btn btn-success"><i class=" glyphicon glyphicon-edit"></i> Edit</button>
											<a href="<?php echo site_url('dataset/dataset_delete/'.$row->IdDataset); ?>" class="btn btn-danger" onclick="return confirm('Yakin akan hapus data?'); "><i class=" glyphicon glyphicon-trash"></i> Delete</a>
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
