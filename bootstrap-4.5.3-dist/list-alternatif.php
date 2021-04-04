<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$judul_page = 'List alternatif';
require_once('head.php');
require_once('navbar.php');
?>

	

<div class="container pt-5 pb-2">
    <div class="row">   
        <div class="col">
        
                <?php
                $status = isset($_GET['status']) ? $_GET['status'] : '';
                $msg = '';
                switch($status):
                    case 'sukses-baru':
                        $msg = 'Data alternatif baru berhasil ditambahkan';
                        break;
                    case 'sukses-hapus':
                        $msg = 'alternatif behasil dihapus';
                        break;
                    case 'sukses-edit':
                        $msg = 'alternatif behasil diedit';
                        break;
                endswitch;
                
                if($msg):
                    echo '<div class="alert alert-primary" role="alert">';
                    echo '<p><span class="fa fa-bullhorn"></span> &nbsp; '.$msg.'</p>';
                    echo '</div>';
                endif;
                ?>
            <div class="card mb-5">
                <div class="card-header">
                    <h3><i class="fa fa-user "></i> List Alternatif</h3>
                    <hr class="bg-primary">
                    <br>
                    <a class="btn btn-primary " href="tambah-user.php" role="button">Tambah Alternatif</a><br>
                    <br>      
                    <?php
                    $query = $pdo->prepare('SELECT * FROM alternatif');			
                    $query->execute();
                    // menampilkan berupa nama_alternatif field
                    $query->setFetchMode(PDO::FETCH_ASSOC);
                    
                    if($query->rowCount() > 0):
                    ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" >
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col">NIK</th>
                                    <th scope="col">Nama_alternatif</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col"class="text-center">Aksi</th>						
                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($hasil = $query->fetch()): ?>
                                    <tr>
                                        <td><?php echo $hasil['id_alternatif']; ?></td>	
                                        <td><?php echo $hasil['nama_alternatif']; ?></td>							
                                        <td><?php echo $hasil['alamat']; ?></td>							
                                        <!-- <td><a href="single-alternatif.php?id=<?php echo $hasil['id_alternatif']; ?>"><span class="fa fa-eye"></span> Detail</a></td>
                                        <td><a href="edit-alternatif.php?id=<?php echo $hasil['id_alternatif']; ?>"><span class="fa fa-pencil"></span> Edit</a></td>
                                        <td><a href="hapus-alternatif.php?id=<?php echo $hasil['id_alternatif']; ?>" class="red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></td> -->
                                    
                                        <td class="text-center"><a href="single-alternatif.php?id=<?php echo $hasil['id_alternatif']; ?>"><i class="fa fa-eye" data-toggle="tooltip" title="Lihat"> </i></a>
                                                                        <a href="edit-alternatif.php?id=<?php echo $hasil['id_alternatif']; ?>"><i class="fa fa-user-edit" data-toggle="tooltip" title="Edit"></i> </a>
                                                                        <a href="hapus-alternatif.php?id=<?php echo $hasil['id_alternatif']; ?>" ><i class="fa fa-trash-alt" data-toggle="tooltip" title="Delete"> </i></td>
                                                
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>    
                <!-- STEP 1. Matriks Keputusan(X) ==================== -->
                <?php
                // Fetch semua kriteria
                $query = $pdo->prepare('SELECT id_kriteria, nama, type, bobot FROM kriteria ORDER BY urutan_order ASC');
                $query->execute();			
                $kriterias = $query->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);
                
                // Fetch semua alternatif
                $query2 = $pdo->prepare('SELECT id_alternatif, nama_alternatif FROM alternatif');
                $query2->execute();			
                $query2->setFetchMode(PDO::FETCH_ASSOC);
                $alternatifs = $query2->fetchAll();			
                ?>
        
<div class="container ">
    <div class="row">   
        <div class="col">
            <div class="card mb-5">
                <div class="card-header">
                    <h3>Matriks Keputusan (X)</h3>
                    <hr class="bg-primary">
                </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered"   >
                            <thead class="table-primary">
                                <tr class="super-top">
                                    <th scope="col" rowspan="2" class="super-top-left">Nama alternatif</th>
                                    <th scope="col" colspan="<?php echo count($kriterias); ?>" class="text-xs-center font-weight-bold">Kriteria</th>
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
                                        // Ambil Nilai
                                        $query3 = $pdo->prepare('SELECT id_kriteria, nilai FROM nilai_alternatif
                                            WHERE id_alternatif = :id_alternatif');
                                        $query3->execute(array(
                                            'id_alternatif' => $alternatif['id_alternatif']
                                        ));			
                                        $query3->setFetchMode(PDO::FETCH_ASSOC);
                                        $nilais = $query3->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);
                                        
                                        foreach($kriterias as $id_kriteria => $values):
                                            echo '<td>';
                                            if(isset($nilais[$id_kriteria])) {
                                                echo $nilais[$id_kriteria]['nilai'];
                                                $kriterias[$id_kriteria]['nilai'][$alternatif['id_alternatif']] = $nilais[$id_kriteria]['nilai'];
                                            } else {
                                                echo 0;
                                                $kriterias[$id_kriteria]['nilai'][$alternatif['id_alternatif']] = 0;
                                            }
                                            
                                            if(isset($kriterias[$id_kriteria]['tn_kuadrat'])){
                                                $kriterias[$id_kriteria]['tn_kuadrat'] += pow($kriterias[$id_kriteria]['nilai'][$alternatif['id_alternatif']], 2);
                                            } else {
                                                $kriterias[$id_kriteria]['tn_kuadrat'] = pow($kriterias[$id_kriteria]['nilai'][$alternatif['id_alternatif']], 2);
                                            }
                                            echo '</td>';
                                        endforeach;
                                        ?>
                                        </pre>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                <?php else: ?>
                    <p>Maaf, belum ada data untuk alternatif.</p>
                <?php endif; ?>
            </div>   
            
        </div>
    </div>
</div>
<?php
require_once('foot.php');