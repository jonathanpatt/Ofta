<?php
    /**
    * OftaFormValidator
    * Simple server-side form validator.
    */

    class OftaFormValidator {
        private $data;
        
        function __construct($method = 'POST') {
            if ($method = 'POST') {
                $this->data = $_POST;
            } else if ($method = 'GET') {
                $this->data = $_GET;
            } else {
                // TODO: ERROR HERE
            }
        }
        
        
        /* Addresses */
        
        public function email($name) {
            if (!preg_match("/^[\w!#$%&\'*+\/=?^`{|}~.-]+@(?:[a-z\d][a-z\d-]*(?:\.[a-z\d][a-z\d-]*)?)+\.(?:[a-z][a-z\d-]+)$/iD", $this->data[$name])) {
                //Error
                return false;
        	}
        	return true;
        }
        
        public function ip($name) {
            if (!filter_var($this->data[$name], FILTER_VALIDATE_IP)) {
                //Error
                return false;
        	}
        	return true;
        }
        
        public function phone($name) {
            if (!preg_match("/^[\(]?(\d{0,3})[\)]?[\s]?[\-]?(\d{3})[\s]?[\-]?(\d{4})[\s]?[x]?(\d*)$/", $this->data[$name], $matches)) {
                //Error
                return false;
        	}
        	return true;
        }
        
        public function url($name) {
            if (!filter_var($this->data[$name], FILTER_VALIDATE_URL)) {
                //Error
                return false;
        	}
        	return true;
        }
        
        
        /* Number Types */
        
        public function integer($name) {
            if (!filter_var($this->data[$name], FILTER_VALIDATE_INT)) {
                if (!($this->data[$name] == "0")) {
                    //Error
                    return false;
                }
        	}
        	return true;
        }
        
        public function float($name) {
            if (!filter_var($this->data[$name], FILTER_VALIDATE_FLOAT)) {
                if (!($this->data[$name] == "0")) {
                    //Error
                    return false;
                }
        	}
        	return true;
        }
        
        
        /* Number Conditions */
        
        public function lessThan($name, $number) {
            if (!($this->data[$name] < $number)) {
                // Error
                return false;
            }
            return true;
        }
        
        public function lessThanOrEqualTo($name, $number) {
            if (!($this->data[$name] <= $number)) {
                // Error
                return false;
            }
            return true;
        }
        
        public function equalTo($name, $number) {
            if (!($this->data[$name] == $number)) {
                // Error
                return false;
            }
            return true;
        }
        
        public function greaterThan($name, $number) {
            if (!($this->data[$name] > $number)) {
                // Error
                return false;
            }
            return true;
        }
        
        public function greaterThanOrEqualTo($name, $number) {
            if (!($this->data[$name] >= $number)) {
                // Error
                return false;
            }
            return true;
        }
        
        
        /* Presence */
        
        public function presence($name) {
            if (trim($this->data[$name]) == '') {
                // Error
                return false;
            }
            return true;
        }
        
        public function inclusion($name, $array) {
            if (!in_array($this->data[$name], $array)) {
                // Error
                return false;
            }
            return true;
        }
        
        public function exclusion($name, $array) {
            if (in_array($this->data[$name], $array)) {
                // Error
                return false;
            }
            return true;
        }
        
        
        /* Length */
        
        public function minLength($name, $minLength) {
            if (strlen($this->data[$name]) < $minLength) {
                // Error
                return false;
            }
            return true;
        }
        
        public function maxLength($name, $maxLength) {
            if (strlen($this->data[$name]) > $maxLength) {
                // Error
                return false;
            }
            return true;
        }
        
        
        /* Misc */
        
        public function confirmation($name) {
            if ($this->data[$name] != $this->data[$name.'_confirmation']) {
                // Error
                return false;
            }
            return true;
        }
        
        public function checked($name) {
            if (!isset($this->data[$name])) {
                // Error
                return false;
            }
            return true;
        }
        
        public function unchecked($name) {
            if (isset($this->data[$name])) {
                // Error
                return false;
            }
            return true;
        }
    }
?>