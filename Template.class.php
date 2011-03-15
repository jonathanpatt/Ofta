<?php
/**
 * Simple templating
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
 * Template class which replaces variable tags in a static file
 *
 * Loads a static file containing variable tags, allows them to be replaced
 * with content, and outputs or returns the final code.
 *
 * @category Ofta
 * @package  Ofta
 * @author   Jonathan Patt <jonathanpatt@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license BSD License
 * @link     https://github.com/jonathanpatt/Ofta
 */
class Template
{
    /**
     * the template in its present state
     *
     * @var string
     */
    protected $template;
    
    /**
     * initialize Template with template file
     *
     * @param string $path path to template file
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    function __construct($path)
    {
        $this->template = file_get_contents($path);
    }
    
    /**
     * set the value of a tag
     *
     * @param string $tag   name of the tag
     * @param string $value the string to replace the tag with
     *
     * @return void
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function set($tag, $value)
    {
        $this->template = str_ireplace(
            '{' . $tag . '}',
            $value,
            $this->template
        );
    }
    
    /**
     * return the template in its present state
     *
     * @return string
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function template()
    {
        return $this->template;
    }
    
    /**
     * output the template in its present state
     *
     * @return void
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function publish()
    {
        echo $this->template;
    }
}
?>
