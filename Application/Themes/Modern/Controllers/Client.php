<?php

	/*
		RaGEWEB 2
	*/

	class Client extends controller implements controller_interface {

		public function __construct() {
			parent::__construct(get_class());
		}

		public function execute() {
			global $application;

			if (isset($_GET['user'])) {
				$view = parent::getView();

				$application->database->prepare('SELECT id FROM server_users WHERE username = ?', array($_GET['user']));

				$id = $application->database->execute()->result;

				$key = generate_ticket(array($id, $_GET['user']));

				$application->database->prepare('UPDATE server_users SET client_key = ? WHERE id = ?', array($key, $id));
				$application->database->execute();

				$view->set('user->sso', $key);
				$view->set('rand->number', rand(0, 999));

				$view->execute();
			} else {
				if (!isset($_SESSION['master_email'])) {
					$application->direct('index', false);
					exit;
				} else if(!isset($_SESSION['habbo']['id'])) {
					$application->direct('characters', false);
					exit;
				}
				
				$view = parent::getView();

				$key = generate_ticket(array($application->user->id, $application->user->username));

				$application->user->set_sso($key);

				$view->set('user->sso', $key);
				$view->set('rand->number', rand(0, 999));

				$view->execute();
			}
		}
	}
?>