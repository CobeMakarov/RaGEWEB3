<?php
	/*
		RaGEWEB 2
	*/
	class widget_object {
		private $content;

		public function __construct($title) {
			global $application;

			$this->content = file_get_contents('./Public/' . $application->config->site->theme . '/Views/Mini/' . $title . '.html');
		}

		public function set($key, $value) {
			$this->content = str_replace('${' . $key . '}', $value, $this->content);
		}

		public function execute() {
			return $this->content;
		}
	}

?>