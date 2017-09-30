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
		<form class="form-horizontal" method="post" action="<?php echo site_url('penyakit'); ?>">
			
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
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary"><i class=" glyphicon glyphicon-search"></i> Search</button>
					<?php
					if($this->session->userdata('login')=="ok"){
						?>
						<a href="<?php echo site_url('penyakit/penyakit_form'); ?>" class="btn btn-warning"><i class=" glyphicon glyphicon-plus"></i> Penyakit</a>
						<a href="<?php echo site_url('penyakit/set_penyakit_gejala_form'); ?>" class="btn btn-warning"><i class="glyphicons glyphicons-th-list"></i> Relasi Gejala</a>
						<?php
					}
					?>
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
								<th>Kode Penyakit</th>
								<th>Penyakit</th>
								<?php
								if($this->session->userdata('login')=="ok"){
									?>
									<th>Action</th>
									<?php
								}
								?>
							</tr>
						</thead>
						<tbody>
							<?php
							$No=0;
							foreach($qpenyakit as $key => $row){ $No++;
								?>
								<tr data-toggle="collapse" class="accordion-toggle collapsed" data-target="#gejala<?php echo $No; ?>" style="cursor: pointer;">
									<td class="text-right"><?php echo $No; ?></td>
									<td class="text-right"><?php echo $row->KdPenyakit; ?></td>
									<td class="text-left"><?php echo $row->Penyakit; ?></td>
									<?php
									if($this->session->userdata('login')=="ok"){
										?>
										<td>
											<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
												<button type="button" onclick="location='<?php echo site_url('penyakit/penyakit_form/'.$row->IdPenyakit); ?>'" class="btn btn-success"><i class=" glyphicon glyphicon-edit"></i> Edit</button>
												<!--<button type="button" onclick="location='<?php echo site_url('penyakit/penyakit_gejala_form/'.$row->IdPenyakit); ?>'" class="btn btn-warning"><i class=" glyphicon glyphicon-plus"></i> Gejala</button>-->
												<!--<a href="<?php echo site_url('penyakit/penyakit_delete/'.$row->IdPenyakit); ?>" class="btn btn-danger" onclick="return confirm('Yakin akan hapus data?'); "><i class=" glyphicon glyphicon-trash"></i> Delete</a>-->
											</div>
										</td>
										<?php
									}
									?>
								</tr>
								<tr style="display: none;">
									<td colspan="4">
										<div id="gejala<?php echo $No; ?>" class="accordian-body collapse" style="padding: 0px;" aria-expanded="false">
											<?php
											$query = $this->m_query->get_array("select mpg.IdPGejala, mpg.IdPenyakit, mg.IdGejala, mg.KdGejala, mg.Gejala from mza_penyakitgejala mpg inner join mza_gejala mg on mg.IdGejala=mpg.IdGejala where mpg.IdPenyakit='".$row->IdPenyakit."'");
											if($query->num_rows()<=0){
												echo "<h3>Gejala Penyakit Belum Diatur.</h3>";
											}else{
												?>
												<table class="table">
													<thead>
														<tr>
															<th>No</th>
															<th>Kode Gejala</th>
															<th>Nama Gejala</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$NoG=0;
														foreach($query->result() as $kgej => $vgej){
															$NoG++;
															?>
															<tr>
																<td class="text-right"><?php echo $NoG; ?></td>
																<td class="text-right"><?php echo $vgej->KdGejala; ?></td>
																<td class="text-left"><?php echo $vgej->Gejala; ?></td>
																<?php
																if($this->session->userdata('login')=="ok"){
																	?>
																	<td>
																		<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
																			<button type="button" onclick="location='<?php echo site_url('penyakit/penyakit_gejala_form/'.$vgej->IdPenyakit."/".$vgej->IdGejala); ?>'" class="btn btn-success"><i class=" glyphicon glyphicon-edit"></i> Edit</button>
																			<!--<a href="<?php echo site_url('penyakit/penyakit_gejala_delete/'.$vgej->IdPGejala); ?>" class="btn btn-danger" onclick="return confirm('Yakin akan hapus data?'); "><i class=" glyphicon glyphicon-trash"></i> Delete</a>-->
																		</div>
																	</td>
																	<?php
																}
																?>
															</tr>
															<?php
														}
														?>
													</tbody>
												</table>
												<?php
											}
											?>
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
