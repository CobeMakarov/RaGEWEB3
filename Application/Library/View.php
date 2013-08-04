<?php
	/*
		RaGEWEB 2
	*/

	class view_object {

		public $public_dir, $theme;

		private $skeleton, $css_string, $js_string, $parameters = array();

		public function __construct() {
			global $application;

			$this->public_dir = $application->config->site->path;
			$this->theme = $application->config->site->theme;

			$this->skeleton = file_get_contents('./Public/' . $this->theme . '/Views/skeleton.html');
			$this->css_string = '<link rel="stylesheet type="text/css" href="./Public/' . $this->theme . '/CSS/[file].css" />' . "\n";
			$this->js_string = '<script src="./Public/' . $this->theme . '/JS/[file].js"></script>' . "\n";

			$this->initDefault();
		}

		private function initDefault() {
			global $application;

			$this->parameters['site->title'] = $application->config->site->title;
			$this->parameters['site->theme'] = $this->theme;
			$this->parameters['site->path'] = $this->public_dir;
		}

		public function set($key, $value) {
			$this->parameters[$key] = $value;
		}

		public function append($key, $value) {
			$this->parameters[$key] .= $value;
		}

		public function setBody($view) {
			$this->skeleton = str_replace('{$page->body}', $view, $this->skeleton);
		}

		public function getContents($title) {
			return file_get_contents('./Public/' . $this->theme . '/Views/' . $title . '.html');
		}

		public function formatAsset($title, $type) {
			return str_replace('[file]', $title, $this->{$type . '_string'});
		}

		public function execute() {
			foreach($this->parameters as $key => $value) {
				$this->skeleton = str_replace('{$' . $key . '}', $value, $this->skeleton);
			}

			die($this->skeleton);
		}
	}

?>