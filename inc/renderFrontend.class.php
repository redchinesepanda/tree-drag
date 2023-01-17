<?php

class renderFrontend {
	public static function printObjects(array $clientObjects)  {
		$output = array();
		foreach ($clientObjects as $clientObject){
			array_push($output, '<div class="item" id="' . $clientObject->getID() . '">');
			array_push($output, '<div class="data">');
			array_push($output, '<h6><span class="item-id">#' . $clientObject->getID() . '</span> <span class="item-name">' . $clientObject->getName() . '</span></h6>');
			array_push($output, '<p>' . $clientObject->getDescription() . '</p>');
			array_push($output, '</div>');
			array_push($output, '<div class="children" data-id="' . $clientObject->getID() . '">');
			$children = $clientObject->getChildren();
			if (!empty($children)) {
				$children_output = renderFrontend::printObjects($children);
				array_push($output, implode('', $children_output));
			}
			array_push($output, '</div>');
			array_push($output, '</div>');
		}
		return $output;
	}
}

?>