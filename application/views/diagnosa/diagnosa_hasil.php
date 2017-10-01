<section id="partner" class="home-section paddingbot-60">	
	<div class="container marginbot-50">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2">
				<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
						<h2 class="h-bold">Report Diagnosa</h2>
					</div>
				</div>
				<div class="divider-short"></div>
				<div class="wow lightSpeedIn" data-wow-delay="0.1s">
					<div class="section-heading text-center">
						<p class="h-bold">Kode Diagnosa : <?php echo $NoDiagnosa; ?> | Tanggal : <?php echo $this->m_query->tgl($Tanggal); ?></p>
						<h4 class="h-bold">Ayam Terdiagnosa Penyakit <span class="text-danger"><?php echo $hasil_diagnosa->Penyakit ?></span> </h4>
						<button class="btn btn-success btn-lg" data-toggle="modal" data-target="#detailHitung"> Detail</button>
						<?php if ($lap != null) {
						?>
								<a class="btn btn-success btn-lg" href="<?php echo base_url('index.php/diagnosa/laporan' )?>">Back</a>
						<?php
						}?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-12 " >
				<div class="section-heading">
					<hr>
						<h5 class="h-bold">Penyakit <span class="text-danger"><?php echo $hasil_diagnosa->Penyakit ?></span></h5>
					<hr>
				</div>
				<div>
					<?php echo $hasil_diagnosa->Deskripsi?>
				</div>
			</div>
		</div>	
	</div>
</section>

<!-- Modal detailHitung -->
<div class="modal fade" id="detailHitung" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Detail Diagnosa</h4>
        </div>
        <div class="modal-body">
			<form class="form-horizontal">
				<div class="form-group">
				<label class="control-label col-sm-2">Kode Diagnosa:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" disabled value="<?php echo $NoDiagnosa; ?>">
				</div>
				</div>
				<div class="form-group">
				<label class="control-label col-sm-2" >Tanggal:</label>
				<div class="col-sm-4"> 
				<input type="text" class="form-control" disabled value="<?php echo $this->m_query->tgl($Tanggal); ?>">
				</div>
				</div>				
			</form>
			<table class="table table-bordered">
				<tr>
					<td>Gejala Yang dipilih</td>
					<td>
						<?php
							foreach ($detail as $val) {
								echo "- ".$val->Gejala."<br>";
						}?>
					</td>
				</tr>
				<tr>
					<td>Hasil Perhitungan <br> naive bayes</td>
					<td>
						<?php
							foreach ($hasil_diagnosa_all as $key=>$val) {
								echo "- ".$this->m_query->get_penyakit_by_id($key)->Penyakit.
									 "<span class='text-danger'> (Prbaboilitas : ".json_encode($val).")</span><br>";
						}?>
					</td>
				</tr>
				<tr>
					<td>Probabilitas <br> Tertinggi</td>
					<td>
						<?php echo $hasil_diagnosa->Penyakit."<span class='text-danger'> (Prbaboilitas : ".json_encode($nilai_diagnosa).")</span><br>"; ?>
					</td>
				</tr>
			</table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>