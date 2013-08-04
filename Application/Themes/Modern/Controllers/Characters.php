<?php

	/*
		RaGEWEB 2
	*/

	class Characters extends controller implements controller_interface {

		public function __construct() {
			parent::__construct(get_class());
		}

		public function execute() {
			global $application;

			if(!isset($_SESSION['master_email'])) {
				$application->direct('index', false);
			}

			$view = parent::getView();
			
			$view->set('general->imgkey', rand(1, 7));

			$email = $_SESSION['master_email'];
			$sec_key = $_SESSION['sec_key'];

			$application->database->prepare('SELECT id, look, username, credits, motto FROM server_users WHERE email = ?', array($email));

			$characters = $application->database->execute();

			$widget = ' ';

			while($c = $characters->to_array()) {
				$w = new widget_object('character-widget');
					
				$w->set('character->look', $c['look']);
				$w->set('character->motto', $c['motto']);
				$w->set('character->username', $c['username']);
				$w->set('character->credits', $c['credits']);
				$w->set('character->string', base64_encode(sha1($c['id'])));
					
				$widget = $widget . $w->execute();

			}

			if (strlen($widget) <= 10) {
				$widget = 'No Characters<br><br>';
			}

			$view->set('user->email', $email);
			$view->set('user->seckey', $sec_key);
			$view->set('user->characters', $widget);

			$view->execute();
		}
	}
?>