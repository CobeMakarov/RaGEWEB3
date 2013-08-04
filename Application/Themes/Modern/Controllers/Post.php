<?php

	/*
		RaGEWEB 2
	*/

	class Post implements controller_interface {

		public function execute() {
			global $application;
		}

		public function online() {
			//echo rand(0, 150);
			global $application;

			$application->database->prepare('SELECT `value` FROM server_stats WHERE `key` = ?', array('online_count'));

			echo $application->database->execute()->result;
		}

		public function leave() {
			session_destroy();
		}

		public function index() {
			global $application;
			
			$operation = $_POST['operation'];

			switch($operation) {
				case 'start_register':
					$w = new widget_object('register-one');

					echo $w->execute();
				break;

				case 'continue_register':
					$key = $_POST['key'];

					$application->database->prepare('SELECT NULL FROM `web_keys` WHERE `key` = ?', array($key));
					$res = $application->database->execute();

					if ($res->num_rows == 0) {
						echo 'err';
					} else {

						$application->database->prepare('DELETE FROM `web_keys` WHERE `key` = ?', array($key));
						$application->database->execute();

						$w = new widget_object('register-two');

						echo $w->execute();
					}
				break;

				case 'finish_register':
					$email = $_POST['email'];
					$password = $_POST['password'];

					if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						die('err;The email provided is invalid!');
					}

					if (strlen($password) < 6) {
						die('err;The password provided is too short!');
					}

					if (strlen($password) > 32) {
						die('err;The password provided is too long!');
					}

					$application->database->prepare('SELECT NULL FROM web_users WHERE email = ?', array($email));

					if ($application->database->execute()->num_rows >= 1) {
						die('err;An account by that email already exists!');
					}

					$exp = explode('@', $email);

					$sec_key = substr(sha1($exp[0] . $application->config->site->salt . $password . rand(0, 999)), 0, 7);

					$real_password = sha1($password . $application->config->site->salt);

					$application->database->prepare('INSERT INTO web_users (email, password, secret_key) VALUES (?, ?, ?)', 
						array($email, $real_password, $sec_key));

					$application->database->execute();

					$w = new widget_object('register-three');
					$w->set('user->seckey', $sec_key);

					echo $w->execute();

					$_SESSION['master_email'] = $email;
					$_SESSION['sec_key'] = $sec_key;
				break;

				case 'show_login':
					$w = new widget_object('index');

					echo $w->execute();
				break;

				case 'show_characters':
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

					$z = new widget_object('characters');

					$z->set('user->email', $email);
					$z->set('user->seckey', $sec_key);
					$z->set('user->characters', $widget);

					echo $z->execute(); 
				break;

				case 'start_login':
					$key = $_POST['sec_key'];

					if (strlen($key) != 7) {
						die('err;The secret key provided is invalid!');
					}

					$application->database->prepare('SELECT email FROM web_users WHERE secret_key = ?', array($key));

					$res = $application->database->execute();

					if ($res->num_rows == 1) {
						$_SESSION['master_email'] = $res->result;
						$_SESSION['sec_key'] = $key;
						
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

						$z = new widget_object('characters');

						$z->set('user->email', $email);
						$z->set('user->seckey', $sec_key);
						$z->set('user->characters', $widget);

						echo $z->execute(); 
					} else {
						echo 'err;The secret key provided is invalid!';
					}
				break;

				case 'create_character':
					$name = $_POST['username'];
					$email = $_SESSION['master_email'];

					$looks = array(
						'hd-180-1.ch-210-66.lg-270-82.sh-290-91.hr-100', 
						'hr-110-45.hd-180-6.ch-3030-62.lg-270-64.sh-300-64.cc-260-62',
						'hr-165-31.hd-180-1.ch-266.lg-285-64.sh-290-62.wa-2001',
						'hr-893-34.hd-180-1.ch-255-62.lg-3116-63-62',
						'hr-515-33.hd-600-1.ch-635-70.lg-716-66-62.sh-735-68');

					$application->database->prepare('SELECT NULL FROM site_users WHERE username = ?', array($name));

					if ($application->database->execute()->num_rows >= 1) {
						die('err;A user with that username already exists!');
					}

					$application->database->prepare('SELECT NULL FROM site_users WHERE email = ?', array($email));

					if ($application->database->execute()->num_rows >= 3) {
						die('err;You have already reached the maximum character capacity for your email address!');
					}

					$application->database->prepare('INSERT INTO server_users (username, email, ip_address, credits, look) VALUES (?, ?, ?, ?, ?)',
						array($name, $email, $_SERVER['REMOTE_ADDR'], rand(100, 4999), $looks[rand(0, count($looks) - 1)]));

					$application->database->execute();

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

					$z = new widget_object('characters');

					$z->set('user->email', $email);
					$z->set('user->seckey', $sec_key);
					$z->set('user->characters', $widget);

					echo $z->execute(); 
				break;

				case 'activate_user':
					$key = $_POST['string'];
					$email = $_SESSION['master_email'];

					$application->database->prepare('SELECT * FROM server_users WHERE email = ?', array($email));

					$users = $application->database->execute();

					while($u = $users->to_array()) {
						if (sha1($u['id']) == base64_decode($key)) {
							$_SESSION['habbo']['id'] = $u['id'];
						}
					}
				break;

				case 'delete_user':
					$key = $_POST['string'];
					$email = $_SESSION['master_email'];

					$application->database->prepare('SELECT * FROM server_users WHERE email = ?', array($email));

					$users = $application->database->execute();

					$user_id = 0;

					while($u = $users->to_array()) {
						if (sha1($u['id']) == base64_decode($key)) {
							$user_id = $u['id'];
						}
					}

					$application->database->prepare('DELETE FROM server_users WHERE id = ?', array($user_id));
					$application->database->execute();
				break;

				case 'start_fallback':
					$w = new widget_object('fallback-form');

					echo $w->execute();
				break;

				case 'finish_fallback':
					$email = $_POST['email'];
					$password = $_POST['password'];

					if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						die('err;Incorrect login details!');
					}

					if (strlen($password) < 6 || strlen($password) > 32) {
						die('err;Incorrect login details!');	
					}

					$application->database->prepare('SELECT * FROM web_users WHERE email = ?', array($email));

					$res = $application->database->execute();

					if ($res->num_rows == 0) {
						die('err;Email does not exist!');
					}

					while($r = $res->to_array()) {
						if ($r['password'] == sha1($password . $application->config->site->salt)) {
							$_SESSION['master_email'] = $email;
							$_SESSION['sec_key'] = $c['secret_key'];
						} else {
							die('err;Password is incorrect!');
						}
					}
				break;
			}
		}
	}
?>