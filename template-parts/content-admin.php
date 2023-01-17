<div class="content">
	<?php if ($permission) { ?>
	<div class="help">
		<ul>
			<li>Чтобы изменить родителя или сделать обьект корневым нужно перетянуть его в лоток существующего объекта или дерева объектов</li>
			<li>Для редактироваия имени или описания объекта выполните двойной щелчек по названию объекта</li>
		</ul>
	</div>
	<?php } ?>
	<div id="0" class="item tree">
		<div class="data">
			<h6>Дерево Объектов</h6>
			<p>Элемент по умолчанию</p>
		</div>
		<div class="children" data-id="0">
			<?php
				$messages = array('<pre>');
				array_push($messages, 'objectsTree');
				$clientsDB = new clientsDB;
				if (!$clientsDB->connectDB()) {
					array_push($messages, '$clientsDB->getDBError(): ' . $clientsDB->getDBError());
				}
				$clientsTree = new clientsTree($clientsDB);
				$clientsTree->loadObjects();
				$clientObjects = $clientsTree->getObjects();
				$clientsTree->showLog($messages);
				echo implode('', renderFrontend::printObjects($clientObjects));
			?>
		</div>
	</div>
</div>