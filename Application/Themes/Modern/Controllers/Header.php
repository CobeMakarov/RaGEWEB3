<?php

	/*
		RaGEWEB 2
	*/

	class Header implements controller_interface{

		public function execute() {
			$key = $_GET[0];

			$last_date = null;
			$file_name = '';

			if ($key == 'stable') {
				foreach(glob('./Storage/Headers/Stable/*') as $file) {
					if (filectime($file) > $last_date) {
						$last_date = filectime($file);
						$file_name = $file;
					}
				}
			} else {

			}

			echo file_get_contents($file_name);
		}
	}
?>