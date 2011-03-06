<?php
require_once 'OftaNotificationCenter.php';

class OftaErrorCenter extends OftaNotificationCenter
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