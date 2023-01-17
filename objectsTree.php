<?php require_once('require.php'); ?>
<html>
	<?php require_once('template-parts/head-admin.php'); ?>
	<body>
		<?php require_once('template-parts/header.php'); ?>
		<?php
			if ($permission) {
				require_once('template-parts/content-admin.php');
			} else {
		?>
			<div class="message">Авторизуйтесь</div>
		<?php
			}
		?>
		<?php require_once('template-parts/footer.php'); ?>
	</body>
</html>