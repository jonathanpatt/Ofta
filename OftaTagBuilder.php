<?php
class OftaTagBuilder
{
    protected $closingSlash;
    
    function __construct($closingSlash = true)
    {
        $this->closingSlash = $closingSlash;
    }
    
    public function startTag($name, $attributes = array(), $isEmpty = false)
    {
        /* Add Tag Name */
        $output  = '<'.$name;
        
        /* Loop Through and Add Tag Attributes */
        foreach ($attributes as $key => $value) {
            $output .= ' '.$key.'="'.$value.'"';
        }
        
        /* Add Closing Slash if It's Set */
        if ($isEmpty && $this->closingSlash) {
            $output .= ' /';
        }
        
        $output .= '>';
        
        return $output;
    }
    
    public function endTag($name)
    {
        return '</'.$name.'>';
    }
    
    public function tag($name, $content, $attributes = array())
    {
        return $this->startTag($name, $attributes).$content.$this->endTag($name);
    }
    
    public function emptyTag($name, $attributes = array())
    {
        return $this->startTag($name, $attributes, true);
    }
}
?>