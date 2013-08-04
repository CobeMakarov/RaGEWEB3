<?php

	/*
		RaGEWEB 2
	*/

	class Disconnected extends controller implements controller_interface {

		public function __construct() {
			parent::__construct(get_class());
		}

		public function execute() {
			global $application;

			$view = parent::getView();

			$view->set('general->imgkey', rand(1, 7));

			switch($_GET['reason']) {
				case 'error':
					$view->set('error->title', 'Packet Error');
					$view->set('error->info', 'Usually a packet error occurs when you encountered a event in the client that the emulator didn\'t respond to. This usually means it hasn\'t been coded yet. It isn\'t your fault!');
				break;

				case 'connection_failed':
					$view->set('error->title', 'Cannot Connect');
					$view->set('error->info', 'This error means that something is blocking the port or the server just isn\'t online! Do not fret, the server will be back on soon!');
				break;
			}

			foreach($_POST as $key => $value) {
				$view->set('error->' . $key, $value);
			}

			$view->execute();
		}
	}
?>