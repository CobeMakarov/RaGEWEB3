<?php

	/*
		RaGEWEB 2
	*/

	class Me extends controller implements controller_interface {

		public function __construct() {
			parent::__construct(get_class());
		}

		public function execute() {
			global $application;

			if (!isset($_SESSION['master_email'])) {
				$application->direct('index', false);
				exit;
			} else if(!isset($_SESSION['habbo']['id'])) {
				$application->direct('characters', false);
				exit;
			}

			$view = parent::getView();

			$view->set('general->imgkey', rand(1, 7));

			$view->set('user->seckey', $_SESSION['sec_key']);
			$view->set('user->username', $application->user->username);
			$view->set('user->motto', $application->user->motto);
			$view->set('user->credits', $application->user->credits);
			$view->set('user->look', $application->user->look);

			if (count($application->user->badges) == 0) {
				$view->set('user->badges', 'No Badges, why don\'t you earn some?');
			} else {
				$badges = '';

				foreach($application->user->badges as $b) {
					$badges .= '<img src="http://habboo-a.akamaihd.net/c_images/album1584/' . $b . '.gif" rel="tooltip" title="' . $b . '" style="margin-right: 28px;"/>';
				}

				$view->set('user->badges', $badges);
			}

			/*
				TODO: Clean this up? :/
			*/
			$application->database->prepare('SELECT id, username, look FROM server_users WHERE email = ? AND id <> ?', 
				array($_SESSION['master_email'], $application->user->id));

			$characters = $application->database->execute();

			$z = '';
			while($c = $characters->to_array()) {
				$w = new widget_object('mini-character-widget');

				$w->set('character->username', $c['username']);
				$w->set('character->look', $c['look']);
				$w->set('character->string', base64_encode(sha1($c['id'])));

				$z .= $w->execute();
			}

			if ($characters->num_rows == 0) {
				$z = 'No Other Characters, Why dont you create some more?';
			}

			$view->set('user->characters', $z);
			$view->execute();
		}
	}
?>