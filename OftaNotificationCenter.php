<?php
class OftaNotificationCenter
{
    protected $messages = array();
    
    public function add($message, $type = null)
    {
        if ($type) {
            $this->messages[$type][] = $message;
        } else {
            $this->messages['general'][] = $message;
        }
    }
    
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
    
    public function format($type = null, $prefix = '<li>',
        $suffix = "</li>\n"
    ) {
        $formattedMessages = '';
        
        foreach ($this->messages($type) as $message) {
            $formattedMessages .= $prefix.$message.$suffix;
        }
        
        return $formattedMessages;
    }
}
?>