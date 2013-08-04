<?php
	/*
		RaGEWEB 2
	*/

	class controller {

		private $controller, $xml, $theme, $view;

		public function __construct($controller) {
			$this->controller = $controller;

			$base = simplexml_load_file('Maps/Controller_Base.xml');

			if (!$base) {
				die("Can't find Maps/Controller_Base.xml");
			}

			foreach($base->controller as $node) {
				if(strtolower($node->id) == strtolower($controller)) {
					$this->xml = $node;
				}
			}

			$this->view = new view_object();

			$this->view->set('site->location', $this->xml->title);
			
			$this->load_models();
			$this->load_views();
		}

		private function load_models() {
			global $application;

			if (count($this->xml->model) >= 1) {
				foreach($this->xml->model as $model) {
					include_once 'Application/Themes/' . $application->config->site->theme . '/Models/' . $model . '.php';
				}
			}
		}

		private function load_views() {
			global $application;

			$base = simplexml_load_file('Maps/' . $application->config->site->theme . '_Map.xml');

			if (!$base) {
				die("Can't find Maps/" . $application->config->site->theme . "_Map.xml");
			}

			$html = "";
			$css = "";
			$js = "";

			foreach($base->view as $node) {
				if(strtolower($node->id) == strtolower($this->controller)) {
					$this->theme = $node;
				}
			}

			foreach($this->theme->html as $view) {
				$html .= "\r\n" . $this->view->getContents($view);
			}

			foreach($this->theme->css as $sheet) {
				$css .= "\r\n\t\t" . $this->view->formatAsset($sheet, 'css');
			}

			foreach($this->theme->js as $script) {
				$js .= "\r" . $this->view->formatAsset($script, 'js');
			}

			$this->view->setBody($html);
			$this->view->set('page->css', $css);
			$this->view->set('page->js', $js);
		}

		public function getView() {
			return $this->view;
		}
	}
?>