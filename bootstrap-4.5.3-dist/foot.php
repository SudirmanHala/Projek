
<div class="container mt-5">
<footer class="footer bg-primary text-white text-center fixed-bottom  p-1">

			<p>Dibuat oleh SH.</a></p>
		
</footer>
</div>	
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	<script type="text/javascript" src="assets/js/bootstrap.bundle.min.js"></script>
	<!-- <script type="text/javascript" src="assets/js/jquery-3.5.1.slim.min.js"></script> -->
	<script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>	
	<script type="text/javascript" src="assets/js/dataTables.bootstrap4.min.js"></script>

	<script type="text/javascript" src="assets/js/main.js"></script>

	<!-- <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script> -->
	<script>$(document).ready( function () {
    $('.table').DataTable();
} );</script>
	
</body>
</html>
<?php
if(isset($pdo)) {
	// Tutup Koneksi
	$pdo = null;
}
?>