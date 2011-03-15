<?php
/**
 * Error page generator
 *
 * PHP Version 5
 *
 * @category Ofta
 * @package  Ofta
 * @author   Jonathan Patt <jonathanpatt@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license BSD License
 * @link     https://github.com/jonathanpatt/Ofta
 */

require_once 'NotificationCenter.class.php';

/**
 * Error class that extends NotificationCenter
 *
 * Adds an error page method which displays if any errors exist.
 *
 * @category Ofta
 * @package  Ofta
 * @author   Jonathan Patt <jonathanpatt@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license BSD License
 * @link     https://github.com/jonathanpatt/Ofta
 */
class ErrorCenter extends NotificationCenter
{
    /**
     * Checks to see if any errors of the optionally specified type exist
     * and if so, display an error page.
     *
     * @param string $path   path to the error page template
     * @param string $type   the error type to display
     * @param string $prefix the prefix to prepend to each error
     * @param string $suffix the suffix to append to each error
     *
     * @return void
     */
    public function errorPage($path, $type = null,
        $prefix = '<li>', $suffix = "</li>\n"
    ) {
        if ($OftaErrors = $this->format($type, $prefix, $suffix)) {
            include $path;
            die;
        }
    }
}
?>
