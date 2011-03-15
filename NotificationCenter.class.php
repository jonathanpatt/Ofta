<?php
/**
 * Notification message manager
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
 * Class to manage notification messages
 *
 * Stores and returns categorized notification messages.
 *
 * @category Ofta
 * @package  Ofta
 * @author   Jonathan Patt <jonathanpatt@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license BSD License
 * @link     https://github.com/jonathanpatt/Ofta
 */
class NotificationCenter
{
    /**
     * array of notification messages
     *
     * @var array
     */
    protected $messages = array();
    
    /**
     * adds a notification message
     *
     * @param string $message message text
     * @param string $type    message type
     *
     * @return void
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function add($message, $type = null)
    {
        if ($type) {
            $this->messages[$type][] = $message;
        } else {
            $this->messages['general'][] = $message;
        }
    }
    
    /**
     * returns notification messages as array
     *
     * @param string $type type of message to return
     *
     * @return array
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function messages($type = null)
    {
        if ($type && $type != 'all') {
            return $this->messages[$type];
        } else {
            $messages = array();
            
            foreach ($this->messages as $key => $value) {
                foreach ($value as $message) {
                    $messages[] = $message;
                }
            }
            
            return $messages;
        }
    }
    
    /**
     * returns notification messages as a formatted string
     *
     * @param string $type   type of message to return
     * @param string $prefix string to prefix each message with
     * @param string $suffix string to suffix each message with
     *
     * @return string
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function format($type = null, $prefix = '<li>',
        $suffix = "</li>\n"
    ) {
        $formattedMessages = '';
        
        foreach ($this->messages($type) as $message) {
            $formattedMessages .= $prefix . $message . $suffix;
        }
        
        return $formattedMessages;
    }
}
?>
