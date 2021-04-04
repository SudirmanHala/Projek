
<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$errors = array();
$sukses = false;

$username = (isset($_POST['username'])) ? trim($_POST['username']) : '';
$password = (isset($_POST['password'])) ? trim($_POST['password']) : '';
$password2 = (isset($_POST['password2'])) ? trim($_POST['password2']) : '';
$nama = (isset($_POST['nama'])) ? trim($_POST['nama']) : '';
$email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
$alamat = (isset($_POST['alamat'])) ? trim($_POST['alamat']) : '';
$role = (isset($_POST['role'])) ? trim($_POST['role']) : '';

if(isset($_POST['submit'])):		
	
	// Validasi Username
	if(!$username) {
		$errors[] = 'Username tidak boleh kosong';
	}		
	// Validasi Password
	if(!$password) {
		$errors[] = 'Password tidak boleh kosong';
	}		
	// Validasi Password 2
	if($password != $password2) {
		$errors[] = 'Password harus sama keduanya';
	}		
	// Validasi Nama
	if(!$nama) {
		$errors[] = 'Nama tidak boleh kosong';
	}		
	// Validasi Email
	if(!$email) {
		$errors[] = 'Email tidak boleh kosong';
	}
	// Validasi role
	if(!$role) {
		$errors[] = 'Role tidak boleh kosong';
	}
	
	// Cek Username
	if($username) {
		$query = $pdo->prepare('SELECT username FROM user WHERE user.username = :username');
		$query->execute(array('username' => $username));
		$result = $query->fetch();
		if(!empty($result)) {
			$errors[] = 'Username sudah digunakan';
		}
	}
	
	
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		
		$handle = $pdo->prepare('INSERT INTO user (username, password, nama, email, alamat, role) VALUES (:username, :password, :nama, :email, :alamat, :role)');
		$handle->execute( array(
			'username' => $username,
			'password' => sha1($password),
			'nama' => $nama,
			'email' => $email,
			'alamat' => $alamat,
			'username' => $username,
			'role' => $role
		) );
		$sukses = "<strong>{$username}</strong> berhasil ditambahkan.";
	
	endif;

endif;
?>

<?php
$judul_page = 'Tambah User';
require_once('head.php');
require_once('navbar.php');
?>

<div class="container pt-5 pb-5">
	<div class="row ">
		<div class="col col-md-8 mx-auto">
			<div class="card mb-5">
			<div class="card-header pt-2 bg-primary text-white">
						<h3> <i class="fa fa-user" ></i>Tambah User</h3>
							<?php if(!empty($errors)): ?>
							
								<div class="alert alert-danger" role="alert">
									<p><strong>Error:</strong></p>
									<ul>
										<?php foreach($errors as $error): ?>
											<li><?php echo $error; ?></li>
										<?php endforeach; ?>
									</ul>
								</div>
								
							<?php endif; ?>
							
							<?php if($sukses): ?>
							
								<div class="alert alert-success" role="alert">
									<p><?php echo $sukses; ?></p>
								</div>	
							
							<?php else: ?>
			</div>		
				<div class="card-body">
								<form action="tambah-user.php" method="post">
									<div class="form-group ">					
										<label for="username">Username <span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="username" value="<?php echo $username; ?>">
									</div>
									<div class="form-group">					
										<label for="password">Password <span class="text-danger">*</span></label>
										<input type="password" class="form-control" name="password">
									</div>
									<div class="form-group">					
										<label>Password Lagi <span class="text-danger">*</span></label>
										<input type="password" class="form-control" name="password2">
									</div>
									<div class="form-group">					
										<label>Nama <span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="nama" value="<?php echo $nama; ?>">
									</div>
									<div class="form-group">					
										<label>Email <span class="text-danger">*</span></label>
										<input type="email" class="form-control" name="email" value="<?php echo $email; ?>">
									</div>
									<div class="form-group">					
										<label>Alamat</label>
										<textarea type="text" class="form-control" name="alamat" value="<?php echo $alamat; ?>"></textarea>
									</div>
									<div class="form-group">					
										<label>Role</label>
										<select name="role" class="form-control" >
											<option value="2" <?php selected($role, 2); ?>>Petugas</option>
											<option value="1" <?php selected($role, 1); ?>>Administrator</option>						
										</select>
									</div>
									
									<div class="form-group">
										<button type="submit" name="submit" value="submit" class="btn btn-outline-primary">Tambah User</button>
									</div>
								</form>
								<?php endif; ?>			
							</div>	
				</div>
		</div>
	</div>	
</div>
</div>
<?php
require_once('foot.php');?>