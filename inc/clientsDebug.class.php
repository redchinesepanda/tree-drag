<?php

class clientsDebug {
	protected bool $debug = false;
	private function getDebug(): bool {
		return $this->debug;
	}
	public function showLog(array $messages):void {
		if ($this->getDebug()) {
			array_unshift($messages, '<pre>');
			array_push($messages, '</pre>');
			echo implode('<br />', $messages);
		}
	}
}

?>