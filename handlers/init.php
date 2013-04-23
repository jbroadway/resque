<?php

/**
 * Initialize the Resque library so your apps can
 * call `Resque::enqueue()` to queue jobs.
 * 
 * Usage:
 * 
 *     <?php
 *     
 *     $this->run ('resque/init');
 *     
 *     Resque::enqueue ('queue_name', 'JobName', array ('arg1' => 'value'));
 *     
 *     ?>
 */

if (self::$called['resque/init'] > 1) {
	return;
}

require_once 'apps/resque/lib/Resque.php';
require_once 'apps/resque/lib/Resque/Redis.php';
Resque::setBackend (Appconf::resque ('Resque', 'backend'));
Resque_Redis::prefix (Appconf::resque ('Resque', 'prefix'));

?>