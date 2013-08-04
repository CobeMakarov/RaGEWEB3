<?php

	/*
		RaGEWEB 2
	*/

	class Head extends controller implements controller_interface {

		public function __construct() {
			
		}

		public function execute() {
			$base = 'http://www.habbo.nl/habbo-imaging/avatarimage?figure=';
		    $figure = $_GET['figure'];

		    // Create image instances
		    $src = imagecreatefrompng($base.$figure);
		    
		    imagealphablending($src, true); // setting alpha blending on
		    imagesavealpha($src, true); // save alphablending setting (important)
		    
		    $dest = imagecreate(54, 65);
		    
		    // Copy
		    imagecopy($dest, $src, 0, 0, 6, 8, 54, 51);
		    
		    imagealphablending($dest, true); // setting alpha blending on
		    imagesavealpha($dest, true); // save alphablending setting (important)
		    
		    // Output and free from memory
		    header('Content-Type: image/png');
		    
		    $outputString  = imagepng($dest);    
		    $outputString .= "(c) sulake!"; // copyright shit, should work![/B]
		    
		    echo $outputString;

		    imagedestroy($dest);
		    imagedestroy($src);
		}
	}
?>