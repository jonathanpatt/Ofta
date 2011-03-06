<?php
require_once 'ErrorCenter.class.php';

class FormValidator
{
    protected $data;
    protected $errors;
    
    function __construct($method = 'POST')
    {
        if ($method == 'POST') {
            $this->data = $_POST;
        } else if ($method == 'GET') {
            $this->data = $_GET;
        } else {
            trigger_error(
                'OftaFormValidator only accepts POST and GET.', E_USER_ERROR
            );
        }
        
        $this->errors = new OftaErrorCenter();
    }
    
    protected function displayName($name, $displayName = null)
    {
        if ($displayName) {
            return $displayName;
        } else {
            return ucwords(str_replace(array('_', '-'), ' ', $name));
        }
    }
    
    
    public function validate($path, $prefix = '<li>', $suffix = "</li>\n")
    {
        $this->errors->errorPage($path, null, $prefix, $suffix);
    }
    
    
    /* Addresses */
    
    public function email($name, $displayName = null)
    {
        $matches = preg_match(
            "/^[\w!#$%&\'*+\/=?^`{|}~.-]+@(?:[a-z\d][a-z\d-]*" .
            "(?:\.[a-z\d][a-z\d-]*)?)+\.(?:[a-z][a-z\d-]+)$/iD",
            $this->data[$name]
        );
        
        if (trim($this->data[$name]) != '' && !$matches) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> is not a valid email address.'
            );
            return false;
        }
        return true;
    }
    
    public function ip($name, $displayName = null)
    {
        if (trim($this->data[$name]) != ''
            && !filter_var($this->data[$name], FILTER_VALIDATE_IP)
        ) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> is not a valid IP address.'
            );
            return false;
        }
        return true;
    }
    
    public function phone($name, $displayName = null)
    {
        $matches = preg_match(
            "/^[\(]?(\d{0,3})[\)]?[\s]?[\-]?" .
            "(\d{3})[\s]?[\-]?(\d{4})[\s]?[x]?(\d*)$/",
            $this->data[$name], $phone_pieces
        );
        
        if (trim($this->data[$name]) != '' && !$matches) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> is not a valid telephone number.'
            );
            return false;
        }
        return $phone_pieces;
    }
    
    public function url($name, $displayName = null)
    {
        if (trim($this->data[$name]) != ''
            && !filter_var($this->data[$name], FILTER_VALIDATE_URL)
        ) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> is not a valid URL.'
            );
            return false;
        }
        return true;
    }
    
    
    /* Number Types */
    
    public function integer($name, $displayName = null)
    {
        if (trim($this->data[$name]) != ''
            && !filter_var($this->data[$name], FILTER_VALIDATE_INT)
        ) {
            if (!($this->data[$name] == "0")) {
                $this->errors->add(
                    '<b>' .
                    $this->displayName($name, $displayName) .
                    '</b> must be an integer.'
                );
                return false;
            }
        }
        return true;
    }
    
    public function float($name, $displayName = null)
    {
        if (trim($this->data[$name]) != ''
            && !filter_var($this->data[$name], FILTER_VALIDATE_FLOAT)
        ) {
            if (!($this->data[$name] == "0")) {
                $this->errors->add(
                    '<b>' .
                    $this->displayName($name, $displayName) .
                    '</b> must be a decimal number.'
                );
                return false;
            }
        }
        return true;
    }
    
    
    /* Number Conditions */
    
    public function lessThan($name, $number, $displayName = null)
    {
        if (trim($this->data[$name]) != ''
            && !($this->data[$name] < $number)
        ) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> must be less than <b>' . $number . '</b>.'
            );
            return false;
        }
        return true;
    }
    
    public function lessThanOrEqualTo($name, $number, $displayName = null)
    {
        if (trim($this->data[$name]) != ''
            && !($this->data[$name] <= $number)
        ) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> must be less than or equal to <b>' . $number . '</b>.'
            );
            return false;
        }
        return true;
    }
    
    public function equalTo($name, $number, $displayName = null)
    {
        if (trim($this->data[$name]) != ''
            && !($this->data[$name] == $number)
        ) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> must be equal to <b>' . $number . '</b>.'
            );
            return false;
        }
        return true;
    }
    
    public function greaterThan($name, $number, $displayName = null)
    {
        if (trim($this->data[$name]) != ''
            && !($this->data[$name] > $number)
        ) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> must be greater than <b>' . $number . '</b>.'
            );
            return false;
        }
        return true;
    }
    
    public function greaterThanOrEqualTo($name, $number, $displayName = null)
    {
        if (trim($this->data[$name]) != ''
            && !($this->data[$name] >= $number)
        ) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> must be greater than or equal to <b>' .
                $number . '</b>.'
            );
            return false;
        }
        return true;
    }
    
    
    /* Presence */
    
    public function presence($name, $displayName = null)
    {
        if (trim($this->data[$name]) == '') {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> is a required field.'
            );
            return false;
        }
        return true;
    }
    
    public function inclusion($name, $array, $displayName = null)
    {
        if (!in_array($this->data[$name], $array)) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> is not one of the accepted options.'
            );
            return false;
        }
        return true;
    }
    
    public function exclusion($name, $array, $displayName = null)
    {
        if (in_array($this->data[$name], $array)) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> contains a disallowed value.'
            );
            return false;
        }
        return true;
    }
    
    
    /* Length */
    
    public function minLength($name, $minLength, $displayName = null)
    {
        if (trim($this->data[$name]) != ''
            && strlen($this->data[$name]) < $minLength
        ) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> must be ' . $minLength . ' characters or longer.'
            );
            return false;
        }
        return true;
    }
    
    public function maxLength($name, $maxLength, $displayName = null)
    {
        if (trim($this->data[$name]) != ''
            && strlen($this->data[$name]) > $maxLength
        ) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> must be ' . $maxLength . ' characters or shorter.'
            );
            return false;
        }
        return true;
    }
    
    
    /* Misc */
    
    public function confirmation($name, $displayName = null)
    {
        if ($this->data[$name] != $this->data[$name . '_confirmation']) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> does not match its confirmation field.'
            );
            return false;
        }
        return true;
    }
    
    public function checked($name, $displayName = null)
    {
        if (!isset($this->data[$name])) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> must be checked.'
            );
            return false;
        }
        return true;
    }
    
    public function unchecked($name, $displayName = null)
    {
        if (isset($this->data[$name])) {
            $this->errors->add(
                '<b>' .
                $this->displayName($name, $displayName) .
                '</b> must be unchecked.'
            );
            return false;
        }
        return true;
    }
}
?>
