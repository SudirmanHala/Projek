<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$errors = array();
$sukses = false;

$nama = (isset($_POST['nama'])) ? trim($_POST['nama']) : '';
$alamat = (isset($_POST['alamat'])) ? trim($_POST['alamat']) : '';
$kriteria = (isset($_POST['kriteria'])) ? $_POST['kriteria'] : array();


if(isset($_POST['submit'])):	
	
	// Validasi
	if(!$nama) {
		$errors[] = 'Nomor alternatif tidak boleh kosong';
	}	
	
	
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		
		$handle = $pdo->prepare('INSERT INTO alternatif (nama, alamat, tanggal_input) VALUES (:nama, :alamat, :tanggal_input)');
		$handle->execute( array(
			'nama' => $nama,
			'alamat' => $alamat,
			'tanggal_input' => date('Y-m-d')
		) );
		$sukses = "alternatif no. <strong>{$nama}</strong> berhasil dimasukkan.";
		$id_alternatif = $pdo->lastInsertId();
		
		// Jika ada kriteria yang diinputkan:
		if(!empty($kriteria)):
			foreach($kriteria as $id_kriteria => $nilai):
				$handle = $pdo->prepare('INSERT INTO nilai_alternatif (id_alternatif, id_kriteria, nilai) VALUES (:id_alternatif, :id_kriteria, :nilai)');
				$handle->execute( array(
					'id_alternatif' => $id_alternatif,
					'id_kriteria' => $id_kriteria,
					'nilai' =>$nilai
				) );
			endforeach;
		endif;
		
		redirect_to('list-alternatif.php?status=sukses-baru');		
		
	endif;

endif;
?>

<?php
$judul_page = 'Tambah alternatif';
require_once('head.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
		<div class="main-content the-content">
			<h1>Tambah alternatif</h1>
			
			<?php if(!empty($errors)): ?>
			
				<div class="msg-box warning-box">
					<p><strong>Error:</strong></p>
					<ul>
						<?php foreach($errors as $error): ?>
							<li><?php echo $error; ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				
			<?php endif; ?>			
			
			
				<form action="tambah-alternatif.php" method="post">
					<div class="field-wrap clearfix">					
						<label>NIK <span class="red">*</span></label>
						<input type="number" name="id_alternatif" value="<?php echo $id_alternatif; ?>">
					</div>	
					<div class="field-wrap clearfix">					
						<label>Nama <span class="red">*</span></label>
						<input type="text" name="nama" value="<?php echo $nama; ?>">
					</div>					
					<div class="field-wrap clearfix">					
						<label>Alamat</label>
						<textarea name="alamat" cols="30" rows="2"><?php echo $alamat; ?></textarea>
					</div>			
					
					<h3>Nilai Kriteria</h3>
					<?php
					$query = $pdo->prepare('SELECT id_kriteria, nama_kriteria, ada_pilihan FROM kriteria ORDER BY urutan_order ASC');			
					$query->execute();
					// menampilkan berupa nama_kriteria field
					$query->setFetchMode(PDO::FETCH_ASSOC);
					
					if($query->rowCount() > 0):
					
						while($kriteria = $query->fetch()):							
						?>
						
							<div class="field-wrap clearfix">					
								<label><?php echo $kriteria['nama_kriteria']; ?></label>
								<?php if(!$kriteria['ada_pilihan']): ?>
									<input type="number" step="0.001" name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">								
								<?php else: ?>
									
									<select name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">
										<option value="0">-- Pilih Variabel --</option>
										<?php
										$query3 = $pdo->prepare('SELECT * FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria ORDER BY urutan_order ASC');			
										$query3->execute(array(
											'id_kriteria' => $kriteria['id_kriteria']
										));
										// menampilkan berupa nama_kriteria field
										$query3->setFetchMode(PDO::FETCH_ASSOC);
										if($query3->rowCount() > 0): while($hasl = $query3->fetch()):
										?>
											<option value="<?php echo $hasil['nilai']; ?>"><?php echo $hasl['nama']; ?></option>
										<?php
										endwhile; endif;
										?>
									</select>
									
								<?php endif; ?>
							</div>	
						
						<?php
						endwhile;
						
					else:					
						echo '<p>Kriteria masih kosong.</p>';						
					endif;
					?>
					
					<div class="field-wrap clearfix">
						<button type="submit" name="submit" value="submit" class="button">Tambah alternatif</button>
					</div>
				</form>
					
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('foot.php');