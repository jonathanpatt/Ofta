<?php
/**
 * Form validator
 *
 * PHP Version 5
 *
 * @category Ofta
 * @package  Ofta
 * @author   Jonathan Patt <jonathanpatt@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license BSD License
 * @link     https://github.com/jonathanpatt/Ofta
 */

require_once 'ErrorCenter.class.php';

/**
 * Class to validate POST or GET input
 *
 * Runs sets of user defined validations on POST/GET input and displays
 * an error page on failure of any validation.
 *
 * @category Ofta
 * @package  Ofta
 * @author   Jonathan Patt <jonathanpatt@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license BSD License
 * @link     https://github.com/jonathanpatt/Ofta
 */
class FormValidator
{
    /**
     * the form data to be validated
     *
     * @var array
     */
    protected $data;
    
    /**
     * ErrorCenter object to store validation errors
     *
     * @var ErrorCenter
     */
    protected $errors;
    
    /**
     * initialize FormValidator
     *
     * @param string $method POST or GET
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    function __construct($method = 'POST')
    {
        if ($method == 'POST') {
            $this->data = $_POST;
        } else if ($method == 'GET') {
            $this->data = $_GET;
        } else {
            trigger_error(
                'FormValidator only accepts POST and GET.', E_USER_ERROR
            );
        }
        
        $this->errors = new ErrorCenter();
    }
    
    /**
     * generate human version of form input element name
     *
     * @param string $name        form input element name
     * @param string $displayName optional display name override
     *
     * @return string
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    protected function displayName($name, $displayName = null)
    {
        if ($displayName) {
            return $displayName;
        } else {
            return ucwords(str_replace(array('_', '-'), ' ', $name));
        }
    }
    
    /**
     * run validations and show error page on failure
     *
     * @param string $path   path to error page template
     * @param string $prefix prefix added to each error
     * @param string $suffix suffix added to each error
     *
     * @return void
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function validate($path, $prefix = '<li>', $suffix = "</li>\n")
    {
        $this->errors->errorPage($path, null, $prefix, $suffix);
    }
    
    /**
     * validate email address
     *
     * @param string $name        form input element name
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate IP address
     *
     * @param string $name        form input element name
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate phone number
     *
     * @param string $name        form input element name
     * @param string $displayName optional display name override
     *
     * @return array
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate URL
     *
     * @param string $name        form input element name
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate integer
     *
     * @param string $name        form input element name
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate floating point number
     *
     * @param string $name        form input element name
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate input is less than a specified number
     *
     * @param string $name        form input element name
     * @param string $number      what input must be less than
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate input is less than or equal to a specified number
     *
     * @param string $name        form input element name
     * @param string $number      what input must be less than or equal to
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate input is equal to a specified number
     *
     * @param string $name        form input element name
     * @param string $number      what input must be equal to
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate input is greater than a specified number
     *
     * @param string $name        form input element name
     * @param string $number      what input must be greater than
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate input is greater than or equal to a specified number
     *
     * @param string $name        form input element name
     * @param string $number      what input must be greater than or equal to
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate input is filled out
     *
     * @param string $name        form input element name
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate input is one of a set of accepted options
     *
     * @param string $name        form input element name
     * @param array  $array       array of accepted options
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate input is not among a set of disallowed options
     *
     * @param string $name        form input element name
     * @param array  $array       array of disallowed options
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate that input is greater than a specified number of characters
     *
     * @param string $name        form input element name
     * @param array  $minLength   minimum character length
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate that input is less than a specified number of characters
     *
     * @param string $name        form input element name
     * @param array  $maxLength   maximum character length
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate the presence of an identical confirmation field
     * with the same name as the original field but with
     * "_confirmation" appended to the end of the name
     *
     * @param string $name        form input element name
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate that a checkbox is checked
     *
     * @param string $name        form input element name
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
    
    /**
     * validate that a checkbox is unchecked
     *
     * @param string $name        form input element name
     * @param string $displayName optional display name override
     *
     * @return bool
     * 
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
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
