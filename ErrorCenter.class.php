<?php
require_once 'NotificationCenter.class.php';

class ErrorCenter extends NotificationCenter
{
    function errorPage($path, $type = null,
        $prefix = '<li>', $suffix = "</li>\n"
    ) {
        if ($OftaErrors = $this->format($type, $prefix, $suffix)) {
            include $path;
            die;
        }
    }
}
?>
