<?php
	/*
		RaGEWEB 2
	*/

	class application {

		public $config, $database, $is_authenicated, $user, $routes, $url;

		public function __construct() {

			$this->loadConfig();
			$this->loadDatabase();
			$this->loadInterfaces();
			$this->loadLibrary();

			$this->cleanRequests();
			$this->loadUser();

			$this->url = $_GET['request'];
		}

		private function loadConfig() {
			foreach(glob('Config/*.php') as $File) {
				include $File;
			}

			$this->config = new stdClass;
			$this->routes = $routes;

			foreach($config as $key => $value) {
				$this->config->$key = null;

				foreach($config[$key] as $lk => $lv) {
					$this->config->$key->$lk = $lv;
				} 
			}
		}

		private function loadDatabase() {
			include_once 'Application/Database/' . $this->config->database->driver . '/Driver.php';

			$fake = strtolower($this->config->database->driver) . '_database_driver';

			$this->database = new $fake($this);
		}

		private function loadInterfaces() {
			foreach(glob('Application/Interfaces/*.php') as $File) {
				include $File;
			}
		}

		private function loadLibrary() {
			foreach(glob('Application/Library/*.php') as $File) {
				include $File;
			}
		}

		private function loadUser() {
			if (isset($_SESSION['habbo']['id'])) {
				$this->user = new user($this, $_SESSION['habbo']['id']);
				$this->is_authenicated = true;
			} else {
				$this->is_authenicated = false;
			}
		}

		public function cleanRequests() {
			foreach($_POST as $key => $value) {
				$_POST[$key] = stripslashes(trim($value));
			}

			foreach($_GET as $key => $value) {
				$_GET[$key] = stripslashes(trim($value));
			}

			$debri = explode('?', $_SERVER['REQUEST_URI']);

			if (isset($debri[1])) {
				$string = $debri[1];

		        if (strpos($string, '&')) { ## Multiple GETS 
		            $request = explode('&', $string);

		            $randomKey = 0;

		            foreach($request as $line) {
		                if (!strpos($string, '=')) {
		                    $_GET[$randomKey] == $line; ## {url}?value1&value2

		                    $randomKey++;
		                    continue;
		                } else {
		                    $requestExplode = explode('=' , $line);

		                    $_GET[$requestExplode[0]] = $requestExplode[1]; ## $_GET[key] = value;
		                }
		            }
		        } else {
		            if (!strpos($string, '=')) {
		                $_GET[0] = $string; ## {url}?value
		            } else {
		                $requestExplode = explode('=' , $string);

		                $_GET[$requestExplode[0]] = $requestExplode[1]; ## $_GET[key] = value;
		            }
		        }
	        }
		}

		public function route() {
			$controller_name = null;
			$required_function = null;

			if (count(explode('/', $this->url)) >= 1) { //Do we have hanging function calls
				$debri = explode('/', $this->url);

				$controller_name = $debri[0];
				$required_function = $debri[1];
			} else {
				$controller_name = $this->url; 
			}

			if ($this->url == '' || strlen($this->url) <= 1) { // is the request undefined?
				$controller_name = 'Index';
			} 

			if (in_array($this->url, $this->routes)) { // are we dealing with a special route?
				$controller_name = $routes[$this->url];
			}

			if (!file_exists('Application/Themes/' . $this->config->site->theme . '/Controllers/' . $controller_name . '.php'))
			{
				$controller_name = 'Error';
				$required_function = 'controller_not_found';
			}

			include_once 'Application/Themes/' . $this->config->site->theme . '/Controllers/' . $controller_name . '.php';

			$controller = new $controller_name();

			if (!is_null($required_function)) {
				if(method_exists($controller, $required_function)) {
					$controller->{$required_function}();
				}
			} else {
				$controller->execute();
			}
		}

		public function direct($controller, $leave) {
			if ($leave) { //take them to a whole new page
				header('Location: ' . $controller);
			} else { //used before controller is parsed
				$this->url = $controller;
				$this->route();
			}
		}
	}
?>