<?php

	/*
		RaGEWEB 2
	*/

	class Index extends controller implements controller_interface {

		public function __construct() {
			parent::__construct(get_class());
		}

		public function execute() {
			global $application;

			if (isset($_SESSION['master_email'])) {
				if (isset($_SESSION['habbo']['id'])) {
					$application->direct('me', false);
				} else {
					$application->direct('characters', false);
				}
				exit;
			}

			parent::getView()->set('general->imgkey', rand(1, 7));

			parent::getView()->execute();
		}
	}
?>