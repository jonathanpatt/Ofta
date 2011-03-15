<?php
/**
 * HTML tag builder
 *
 * PHP Version 5
 *
 * @category Ofta
 * @package  Ofta
 * @author   Jonathan Patt <jonathanpatt@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license BSD License
 * @link     https://github.com/jonathanpatt/Ofta
 */

/**
 * Class to build HTML tags in PHP
 *
 * Generates HTML tags with all PHP, resulting in prettier code.
 *
 * @category Ofta
 * @package  Ofta
 * @author   Jonathan Patt <jonathanpatt@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license BSD License
 * @link     https://github.com/jonathanpatt/Ofta
 */
class TagBuilder
{
    /**
     * flag to indicate if empty tags should self close
     *
     * @var bool
     */
    protected $closingSlash;
    
    /**
     * initializes TagBuilder
     *
     * @param bool $closingSlash indicates if empty tags should self close
     *
     * @return void
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    function __construct($closingSlash = true)
    {
        $this->closingSlash = $closingSlash;
    }
    
    /**
     * generates an opening (or empty) HTML tag
     *
     * @param string $name       type of HTML tag
     * @param array  $attributes associative array of attributes
     * @param bool   $isEmpty    indicates whether tag is empty or not
     *
     * @return string
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function startTag($name, $attributes = array(), $isEmpty = false)
    {
        $output  = '<' . $name;
        
        foreach ($attributes as $key => $value) {
            if (is_string($key)) {
                $output .= ' ' . $key . '="' . $value . '"';
            } else {
                $output .= ' ' . $value;
            }
        }
        
        if ($isEmpty && $this->closingSlash) {
            $output .= ' /';
        }
        
        $output .= '>';
        
        return $output;
    }
    
    /**
     * generates a closing HTML tag
     *
     * @param string $name type of HTML tag
     *
     * @return string
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function endTag($name)
    {
        return '</' . $name . '>';
    }
    
    /**
     * generates an HTML tag
     *
     * @param string $name       type of HTML tag
     * @param string $content    the tag's content
     * @param array  $attributes associative array of attributes
     *
     * @return string
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function tag($name, $content, $attributes = array())
    {
        return $this->startTag($name, $attributes) .
            $content . $this->endTag($name);
    }
    
    /**
     * generates an empty HTML tag
     *
     * @param string $name       type of HTML tag
     * @param array  $attributes associative array of attributes
     *
     * @return string
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function emptyTag($name, $attributes = array())
    {
        return $this->startTag($name, $attributes, true);
    }
}
?>
