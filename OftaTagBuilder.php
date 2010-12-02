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
		
		public function startTag($name, $attributes, $isEmpty = false) {
			/* Add Tag Name */
			$output  = '<'.$name;
			
			/* Loop Through and Add Tag Attributes */
			foreach ($attributes as $key => $value) {
				$output .= ' '.$key.'="'.$value.'"';
			}
			
			/* Close Start Tag */
			if (!$isEmpty) {
				$output .= '>';
			} else {
				/* Add Closing Slash if It's Set */
				if ($this->closingSlash) {
					$output .= ' /';
				}
				
				$output .= '>';
			}
			
			echo $output;
		}
		
		public function endTag($name) {
			echo '</'.$name.'>';
		}
		
		public function tag($name, $attributes, $content = null) {
			if ($content) {
				$this->startTag($name, $attributes);
				echo $content;
				$this->endTag($name);
			} else {
				$this->startTag($name, $attributes, true);
			}
		}
	}
?>