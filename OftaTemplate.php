<?php
	class OftaTemplate {
		private $template;
		
		function __construct($path) {
			$this->template = file_get_contents($path);
		}
		
		public function set($tag, $value) {
			$this->template = str_ireplace(
				'{'.$tag.'}',
				$value,
				$this->template
			);
		}
		
		public function template() {
			return $this->template;
		}
		
		public function publish() {
			echo $this->template;
		}
	}
?>