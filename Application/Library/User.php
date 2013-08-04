<?php
	/*
		RaGEWEB 2
	*/

	class user {
		public $id, $username, $email, $credits, $motto, $look, $client_key, $ip, $gender, 
		$rank, $last_active, $respect, $daily_respect, $home, $name_changes, $guild, $badges = array();

		public function __construct($application, $id) {
			$this->id = $id;

			$application->database->prepare('SELECT * FROM server_users WHERE id = ?', array($id));

			$user = $application->database->execute();

			while($u = $user->to_array()) {
				foreach($u as $k => $v) {
					$this->$k = $v;
				}

				$this->ip = $_SERVER['REMOTE_ADDR'];
				$this->respect = $u['respect_points'];
				$this->home = $u['home_room'];
				$this->name_changes = $u['active_name_changes'];
				$this->guild = $u['primary_guild'];
			}

			$application->database->prepare('SELECT * FROM server_user_badges WHERE user = ?', 
				array($id));

			$badges = $application->database->execute();

			while($b = $badges->to_array()) {
				$this->badges[] = $b['code'];
			}
		}

		public function set_sso($sso) {
			global $application;

			$application->database->prepare('UPDATE server_users SET client_key = ? WHERE id = ?', array($sso, $this->id));
			$application->database->execute();
		}
	}
?>