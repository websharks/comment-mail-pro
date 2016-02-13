<?php
/**
 * Uninstaller.
 *
 * @since 150422 Rewrite.
 */
namespace WebSharks\CommentMail\Pro;

if (!defined('WPINC')) {
    exit('Do NOT access this file directly: '.basename(__FILE__));
}
require_once dirname(__FILE__).'/stub.php';

$GLOBALS[GLOBAL_NS.'_uninstalling']    = true;
$GLOBALS[GLOBAL_NS.'_autoload_plugin'] = false;
$GLOBALS[GLOBAL_NS]                    = new Plugin(false);
$GLOBALS[GLOBAL_NS]->uninstall();
