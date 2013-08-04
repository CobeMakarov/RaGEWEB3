<?php

	/*
		RaGEWEB 2
	*/

	class Format implements controller_interface {

		public function execute() {
			global $application;

			echo "Loading SWF.. <br>";
			echo "------------------------------------------------------ <br>";
			echo "<br>";
			$content = file_get_contents('./Storage/March.txt');

			$new_content = array();

			$debri = explode("\n", $content);

			echo "Breaking Down SWF.. <br>";
			echo "------------------------------------------------------ <br>";
			echo "<br>";
			
			foreach($debri as $key => $value) { //rebuild in a new array
				$new_content[] = $value;
				if (strpos($value, "{")) {
					if(strlen(trim($debri[$key + 1])) >= 4) {
						$new_content[] = " ";
					}
				}

				//echo "Rebuilt Line #"  . count($new_content) . "<br>";
			}

			echo "<br>";
			echo "------------------------------------------------------ <br>";
			echo "<br>";

			file_put_contents('./Storage/New.txt', implode("\n", $new_content));
		}
	}
?>