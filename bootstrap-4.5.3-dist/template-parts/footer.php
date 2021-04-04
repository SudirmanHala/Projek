
	<footer id="footer">
		<div class="container">
			<p>Dibuat oleh <a href="https://github.com/zunan-umby" target="_blank">SH.</a></p>
		</div>
	</footer>

	</div><!-- #page -->
	<script type="text/javascript" src="./assets/js/jquery-3.5.1.js"></script>
	<script type="text/javascript" src="./assets/js/datatables.js"></script>	
</body>
</html>
<?php
if(isset($pdo)) {
	// Tutup Koneksi
	$pdo = null;
}
?>