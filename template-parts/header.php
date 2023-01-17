<header>
	<div class="logo"><a href="/"></a></div>
	<div class="menu">
		<a href="/">Главная</a>
		<?php if ($permission) { ?>
		<a href="/objectsTree.php">Дерево объектов</a>
		<?php } ?>
	</div>
	<div class="login">
		<?php if ($permission) { ?>
		<a href="/#logout" class="logout-button">Выход</a>
		<?php } else { ?>
		<a href="/#login" class="login-button">Вход</a>
		<?php } ?>
	</div>
</header>