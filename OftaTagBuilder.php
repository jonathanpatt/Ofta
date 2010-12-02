<?php
	/**
	* OftaTagBuilder
	* Simple HTML tag builder.
	*/

	class OftaTagBuilder {
		private $closingSlash;
		
		function __construct($closingSlash = true) {
			$this->closingSlash	= $closingSlash;
		}
		
		public function tag($name, $attributes, $content = null) {
			/* Add Tag Name */
			$output  = '<'.$name;
			
			/* Loop Through and Add Tag Attributes */
			foreach ($attributes as $key => $value) {
				$output .= ' '.$key.'="'.$value.'"';
			}
			
			/* Add Tag Content if It Exists */
			if ($content) {
				$output .= '>'.$content.'</'.$name;
			} else {
				/* Add Closing Slash if It's Set */
				if ($this->closingSlash) {
					$output .= ' /';
				}
			}
			
			/* Finish Constructing Tag */
			$output .= '>';
			
			echo $output;
		}
	}
?>