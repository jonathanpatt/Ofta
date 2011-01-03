<?php
    /**
    * OftaErrorCenter
    * Extension of NotificationCenter, adding error-specific functionality
    */
    
    require_once('OftaNotificationCenter.php');

    class OftaErrorCenter extends OftaNotificationCenter {
        function errorPage($path, $type = null, $prefix = '<li>', $suffix = "</li>\n") {
            if ($OftaErrors = $this->format($type, $prefix, $suffix)) {
                require $path;
                die;
            }
        }
    }
?>