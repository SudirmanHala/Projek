<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>
<?php require_once('head.php'); ?>
<?php require_once('navbar.php'); ?>
<?php
$judul_page = 'List User';
?>


   
    <div class="container pt-5 mb-5">
        <div class="row">   
            <div class="col">
                <div class="card pb-5 mb-5 mt-5">
                    <div class="card-header">
                                <h3><i class="fa fa-user "></i> List User</h3>
                               <hr class="bg-primary">
                               <br>
                                <a class="btn btn-primary " href="tambah-user.php" role="button">Tambah User</a><br>
                                <?php
                                    $query = $pdo->prepare('SELECT id_user, username, nama, role FROM user');			
                                    $query->execute();
                                    // menampilkan berupa nama field
                                    $query->setFetchMode(PDO::FETCH_ASSOC);
                                    if($query->rowCount() > 0):
                                ?>	
                                <br>
                    </div>
                    <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
            
                                            <thead class="table-primary">
                                                                    <tr>
                                                                        <th scope="col">NO</th>
                                                                        <th scope="col">Username</th>
                                                                        <th scope="col">Nama</th>
                                                                        <th scope="col">Role</th>
                                                                        <th scope="col">Aksi</th>
                                                                    
                                                                    </tr>
                                                </thead>
                                        <tbody>
                                            <?php $no=1;?>
                                            <?php while($hasil = $query->fetch()): ?>
                                                            <tr>
                                                <td><?php echo  $no; ?></td>
                                                                <td><?php echo $hasil['username']; ?></td>
                                                                <td><?php echo $hasil['nama']; ?></td>
                                                                <td>
                                                                <?php
                                                                if($hasil['role'] == 1) {
                                                                    echo 'Administrator';
                                                                } elseif($hasil['role'] == 2) {
                                                                    echo 'Petugas';
                                                                }
                                                ?>
                                        
                                                                </td>
                                                                <td class="text-center"><a href="single-user.php?id=<?php echo $hasil['id_user']; ?>"><i class="fa fa-eye" data-toggle="tooltip" title="Lihat"> </i></a>
                                                                <a href="edit-user.php?id=<?php echo $hasil['id_user']; ?>"><i class="fa fa-user-edit" data-toggle="tooltip" title="Edit"></i> </a>
                                                                <a href="hapus-user.php?id=<?php echo $hasil['id_user']; ?>" ><i class="fa fa-trash-alt" data-toggle="tooltip" title="Delete"> </i></td>
                                                </tr>
                                            
                                                <?php $no++;?>
                                            <?php endwhile; ?>
                                        </tbody>
                                                
                                </table>

                                <?php else: ?>
                                    <p>Maaf, belum ada data untuk user.</p>
                                <?php endif; ?>
                                </div>
                    </div>               
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('foot.php'); ?>



   