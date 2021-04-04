<?php
/* ---------------------------------------------
 * SPK SAW
 * Author: Zunan Arif Rahmanto - 15111131
 * ------------------------------------------- */

/* ---------------------------------------------
 * Konek ke database & load fungsi-fungsi
 * ------------------------------------------- */
require_once('includes/init.php');

/* ---------------------------------------------
 * Load Header
 * ------------------------------------------- */
$judul_page = 'Perankingan Menggunakan Metode SAW';
require_once('head.php');
require_once('navbar.php');
/* ---------------------------------------------
 * Set jumlah digit di belakang koma
 * ------------------------------------------- */
$digit = 4;

/* ---------------------------------------------
 * Fetch semua kriteria
 * ------------------------------------------- */
$query = $pdo->prepare('SELECT id_kriteria, nama, type, bobot
	FROM kriteria ORDER BY urutan_order ASC');
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
$kriterias = $query->fetchAll();

/* ---------------------------------------------
 * Fetch semua alternatif (alternatif)
 * ------------------------------------------- */
$query2 = $pdo->prepare('SELECT id_alternatif, nama_alternatif FROM alternatif');
$query2->execute();			
$query2->setFetchMode(PDO::FETCH_ASSOC);
$alternatifs = $query2->fetchAll();


/* >>> STEP 1 ===================================
 * Matrix Keputusan (X)
 * ------------------------------------------- */
$matriks_x = array();
$list_kriteria = array();
foreach($kriterias as $kriteria):
	$list_kriteria[$kriteria['id_kriteria']] = $kriteria;
	foreach($alternatifs as $alternatif):
		
		$id_alternatif = $alternatif['id_alternatif'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		// Fetch nilai dari db
		$query3 = $pdo->prepare('SELECT nilai FROM nilai_alternatif
			WHERE id_alternatif = :id_alternatif AND id_kriteria = :id_kriteria');
		$query3->execute(array(
			'id_alternatif' => $id_alternatif,
			'id_kriteria' => $id_kriteria,
		));			
		$query3->setFetchMode(PDO::FETCH_ASSOC);
		if($nilai_alternatif = $query3->fetch()) {
			// Jika ada nilai kriterianya
			$matriks_x[$id_kriteria][$id_alternatif] = $nilai_alternatif['nilai'];
		} else {			
			$matriks_x[$id_kriteria][$id_alternatif] = 0;
		}

	endforeach;
endforeach;

/* >>> STEP 3 ===================================
 * Matriks Ternormalisasi (R)
 * ------------------------------------------- */
$matriks_r = array();
foreach($matriks_x as $id_kriteria => $nilai_alternatifs):
	
	$tipe = $list_kriteria[$id_kriteria]['type'];
	foreach($nilai_alternatifs as $id_alternatifx => $nilai) {
		if($tipe == 'benefit') {
			$nilai_normal = $nilai / max($nilai_alternatifs);
		} elseif($tipe == 'cost') {
			$nilai_normal = min($nilai_alternatifs) / $nilai;
		}
		
		$matriks_r[$id_kriteria][$id_alternatifx] = $nilai_normal;
	}
	
endforeach;


/* >>> STEP 4 ================================
 * Perangkingan
 * ------------------------------------------- */
$ranks = array();
foreach($alternatifs as $alternatif):

	$total_nilai = 0;
	foreach($list_kriteria as $kriteria) {
	
		$bobot = $kriteria['bobot'];
		$id_alternatif = $alternatif['id_alternatif'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		$nilai_r = $matriks_r[$id_kriteria][$id_alternatif];
		$total_nilai = $total_nilai + ($bobot * $nilai_r);

	}
	
	$ranks[$alternatif['id_alternatif']]['id_alternatif'] = $alternatif['id_alternatif'];
	$ranks[$alternatif['id_alternatif']]['nama_alternatif'] = $alternatif['nama_alternatif'];
	$ranks[$alternatif['id_alternatif']]['nilai'] = $total_nilai;
	
endforeach;
 
?>

<div class="container pt-5 pb-5">
    <div class="row">   
        <div class="col">	
			<h1 class="text-center"><?php echo $judul_page; ?></h1>
			<br>
			
			<!-- STEP 1. Matriks Keputusan(X) ==================== -->	
			<div class="card">
				<div class="card-header">	
					<h3>Step 1: Matriks Keputusan (X)</h3>
					<hr class="bg-primary">
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-bordered">
							<thead class="table-primary">
								<tr class="super-top">
									<th rowspan="2" class="super-top-left">No. alternatif</th>
									<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
								</tr>
								<tr>
									<?php foreach($kriterias as $kriteria ): ?>
										<th scope="col"><?php echo $kriteria['nama']; ?></th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach($alternatifs as $alternatif): ?>
									<tr>
										<td><?php echo $alternatif['nama_alternatif']; ?></td>
										<?php						
										foreach($kriterias as $kriteria):
											$id_alternatif = $alternatif['id_alternatif'];
											$id_kriteria = $kriteria['id_kriteria'];
											echo '<td>';
											echo $matriks_x[$id_kriteria][$id_alternatif];
											echo '</td>';
										endforeach;
										?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container pb-5">
    <div class="row">   
        <div class="col">			
			<!-- STEP 2. Bobot Preferensi (W) ==================== -->
			<div class="card ">
				<div class="card-header bg-success">
					<h3>Step 2: Bobot Preferensi (W)</h3>			
					<hr class="bg-primary">
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-bordered">
							<thead class="table-primary">
								<tr>
									<th scope="col">nama Kriteria</th>
									<th scope="col">Type</th>
									<th scope="col">Bobot (W)</th>						
								</tr>
							</thead>
							<tbody>
								<?php foreach($kriterias as $hasil): ?>
									<tr>
										<td><?php echo $hasil['nama']; ?></td>
										<td>
										<?php
										if($hasil['type'] == 'benefit') {
											echo 'Benefit';
										} elseif($hasil['type'] == 'cost') {
											echo 'Cost';
										}							
										?>
										</td>
										<td><?php echo $hasil['bobot']; ?></td>							
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container pb-5">
    <div class="row">   
        <div class="col">			
			<!-- Step 3: Matriks Ternormalisasi (R) ==================== -->
			<div class="card">
				<div class="card-header">
					<h3>Step 3: Matriks Ternormalisasi (R)</h3>			
					<hr class="bg-primary">
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-bordered" id="table_id">
							<thead class="table-primary">
								<tr class="super-top">
									<th rowspan="2" class="super-top-left">No. alternatif</th>
									<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
								</tr>
								<tr>
									<?php foreach($kriterias as $kriteria ): ?>
										<th scope="col"><?php echo $kriteria['nama']; ?></th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach($alternatifs as $alternatif): ?>
									<tr>
										<td><?php echo $alternatif['nama_alternatif']; ?></td>
										<?php						
										foreach($kriterias as $kriteria):
											$id_alternatif = $alternatif['id_alternatif'];
											$id_kriteria = $kriteria['id_kriteria'];
											echo '<td>';
											echo round($matriks_r[$id_kriteria][$id_alternatif], $digit);
											echo '</td>';
										endforeach;
										?>
									</tr>
								<?php endforeach; ?>				
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div>
	</div>
</div>
			
			

<div class="container pb-5">
    <div class="row">   
        <div class="col">
			<!-- Step 4: Perangkingan ==================== -->
			<?php		
			$sorted_ranks = $ranks;		
			// Sorting
			if(function_exists('array_multisort')):
				$nama = array();
				$nilai = array();
				foreach ($sorted_ranks as $key => $row) {
					$nama[$key]  = $row['nama_alternatif'];
					$nilai[$key] = $row['nilai'];
				}
				array_multisort($nilai, SORT_DESC, $nama, SORT_ASC, $sorted_ranks);
			endif;
			?>		
			<div class="card">
				<div class="card-header">
					<h3>Step 4: Perangkingan (V)</h3>			
					<hr class="bg-primary">
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-bordered" id="table_id">
							<thead class="table-primary">					
								<tr>
									
									<th class="super-top-left">No. alternatif</th>
									<th scope="col">Ranking</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($sorted_ranks as $alternatif ): ?>
									<tr>
										<td><?php echo $alternatif['nama_alternatif']; ?></td>
										<td><?php echo round($alternatif['nilai'], $digit); ?></td>											
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>			
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
require_once('foot.php');