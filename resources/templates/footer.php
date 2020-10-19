	<footer id="footer" class="footer bg-dark">
		<div class="container text-light">
			<a class='text-light' href="<?=$config["urls"]["intranet_home"] ?>/impressum">Impressum</a> | Bei Anmerkungen oder Problemen: Email an <a class='text-light' href="mailto:guardian@thral.de?subject=Fehlerbericht%20Intranet" >guardian@thral.de</a>
		</div>
	</footer>
</body>
<script>
	window.onload=hideLoader;

	// Fix for dropdown overflowing table 	
	$('.table-responsive').on('show.bs.dropdown', function (e) {
		var heightTR = $(this).find('tr').height();
		var heightDD = $(this).find('.dropdown-menu').height();
		$(this).find('.fixed-table-body').css('min-height', heightTR + heightDD);
	});
	$('.table-responsive').on('hide.bs.dropdown', function (e) {
		$(this).find('.fixed-table-body').css('min-height', '');
	});
</script>
</html>