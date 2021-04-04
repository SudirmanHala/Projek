<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;
$result = '';

$id_user = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_user) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$query = $pdo->prepare('SELECT id_user FROM user WHERE user.id_user = :id_user');
	$query->execute(array('id_user' => $id_user));
	$result = $query->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	}
}
?>

<?php
$judul_page = 'Hapus User';
require_once('head.php');
require_once('navbar.php');
?>
<div class="container pt-5 pb-5">	
	<div class="col pt-5">
			<h1 class="text-center"><?php echo $judul_page; ?></h1>
			
			<?php if($ada_error): ?>
			
				<?php echo '<p class="text-center">'.$ada_error.'</p>'; ?>			

			<?php elseif(!empty($result)): ?>
				<?php
				$handle = $pdo->prepare('DELETE FROM user WHERE id_user = :id_user');				
				$handle->execute(array(
					'id_user' => $result['id_user']
				));
				echo '<p class="text-center">Data berhasil dihapus</p>';				
				?>
			<?php endif; ?>
			
		</div>
	</div><!-- .container -->
</div><!-- .main-content-row -->
</div>

<?php
require_once('foot.php');
?>