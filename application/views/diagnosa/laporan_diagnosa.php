<?php
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
					<th>Tanggal</th>
					<th>No.Registrasi</th>
					<th>Hasil Diagnosa</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$No=0;
				foreach($data_diagnosa as $key => $val){
				$No++;
				$get=$this->db->query("select * from mza_dataset where NoDiagnosa='".$val->NoDiagnosa."'");
					?>
					<tr>
						<td><?php echo $val->Tanggal; ?></td>
						<td><?php echo $val->NoDiagnosa; ?></td>
						<?php 
							$diagnosa	= $this->m_query->get_array("select * from mza_diagnosa WHERE IdDiagnosa='".$val->IdDiagnosa."'")->row();
							$detail_diagnosa = $this->m_query->get_detail_diagnosa($val->IdDiagnosa);
							$penyakit = $this->m_query->get_penyakit();
							foreach ($detail_diagnosa as $val) {
								foreach ($penyakit as $p) {
									$hasil_bagi[$val->IdGejala]['idPkt_'.$p->IdPenyakit] = $this->m_query->get_klasifikasi($val->IdGejala,$p->IdPenyakit)->hasil_bagi;
								}
							}
							foreach ($penyakit as $p) {
								$hasil_kali[$p->IdPenyakit] = array_product(array_column($hasil_bagi, 'idPkt_'.$p->IdPenyakit));
							}
							arsort($hasil_kali);
							$max = array_keys($hasil_kali, max($hasil_kali));
							$id_penyakit_tertingggi = $max[0];
							$val_tertinggi = $hasil_kali[$max[0]];
							$hasilnya = $this->m_query->get_penyakit_by_id($id_penyakit_tertingggi);
						?>


						<td><?php echo $hasilnya->KdPenyakit." - ".$hasilnya->Penyakit; ?></td>

						<td>
						<?php
						if($get->num_rows()<=0){
						?>
						<a href="<?php echo site_url('diagnosa/simpan_dataset/'.$val->IdDiagnosa.'/'.$hasilnya->IdPenyakit); ?>" class='btn btn-xs btn-primary'>Simpan Dataset</a><?php
						}
						?>
						<a href="<?php echo site_url('diagnosa/klasifikasi/'.$val->IdDiagnosa.'/lap'); ?>" class='btn btn-xs btn-success'>Report Detail</a>
						
						</td>
					</tr>
					<?php
					unset($hasil_kali);
					unset($hasil_bagi);
					unset($max);
				}
				?>
			</tbody>
		</table>
	</div>
</section>