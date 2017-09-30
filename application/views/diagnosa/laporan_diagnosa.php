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
						<p>
							<a href="<?php echo site_url('diagnosa/print_diagnosa'); ?>">Print</a>
						</p>
					</div>
				</div>
				<div class="divider-short"></div>
			</div>
		</div>
	</div>
		
	<div class="container">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Tanggal</th>
					<th>No.Registrasi</th>
					<th>Penyakit</th>
					<th>Hasil</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$No=0;
				foreach($qdata as $key => $val){
				$No++;
				$get=$this->db->query("select * from mza_dataset where NoDiagnosa='".$val->NoDiagnosa."'");
					?>
					<tr>
						<td><?php echo $No; ?></td>
						<td><?php echo $val->Tgl; ?></td>
						<td><?php echo $val->NoDiagnosa; ?></td>
						<td><?php echo $val->KdPenyakit."-".$val->Penyakit; ?></td>
						<td><?php echo $val->Nilai; ?></td>
						<td>
						<?php
						if($get->num_rows()<=0){
						?>
						<a href="<?php echo site_url('diagnosa/simpan_dataset/'.$val->IdDiagnosa); ?>" class='btn btn-xs btn-primary'>Simpan Dataset</a><?php
						}
						?>
						<a href="<?php echo site_url('diagnosa/detail_perhitungan/'.$val->IdDiagnosa); ?>" class='btn btn-xs btn-success'>Perhitungan</a>
						
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
</section>