<?php

/**
 * Provides a command line interface for running the Resque
 * workers. Run via:
 *
 *     ./elefant resque/run
 *
 * Based on the PHP-Resque command line tool available at:
 *
 * https://github.com/chrisboulton/php-resque/blob/master/bin/resque
 */

if (! $this->cli) {
	die ('Must be run from the command line.');
}

$page->layout = false;

require_once 'apps/resque/lib/Resque.php';
require_once 'apps/resque/lib/Resque/Redis.php';
require_once 'apps/resque/lib/Resque/Worker.php';

$QUEUE = Appconf::resque ('Resque', 'queue');
if (empty ($QUEUE)) {
	die ("Set QUEUE env var containing the list of queues to work.\n");
}

$REDIS_BACKEND = Appconf::resque ('Resque', 'backend');
$REDIS_BACKEND_DB = Appconf::resque ('Resque', 'backend_db');
if (! empty ($REDIS_BACKEND)) {
	if (empty ($REDIS_BACKEND_DB)) 
		Resque::setBackend($REDIS_BACKEND);
	else
		Resque::setBackend($REDIS_BACKEND, $REDIS_BACKEND_DB);
}

$logLevel = 0;
$LOGGING = Appconf::resque ('Resque', 'logging');
if ($LOGGING === 'normal') {
	$logLevel = Resque_Worker::LOG_NORMAL;
} elseif ($LOGGING === 'verbose') {
	$logLevel = Resque_Worker::LOG_VERBOSE;
}

$interval = 5;
$INTERVAL = Appconf::resque ('Resque', 'sleep_interval');
if (! empty ($INTERVAL)) {
	$interval = $INTERVAL;
}

$count = 1;
$COUNT = Appconf::resque ('Resque', 'workers');
if (! empty ($COUNT) && $COUNT > 1) {
	$count = $COUNT;
}

$PREFIX = Appconf::resque ('Resque', 'prefix');
if (! empty ($PREFIX)) {
    fwrite (STDOUT, '*** Prefix set to ' . $PREFIX . "\n");
    Resque_Redis::prefix ($PREFIX);
}

if ($count > 1) {
	for ($i = 0; $i < $count; ++$i) {
		$pid = Resque::fork ();
		if ($pid == -1) {
			die ("Could not fork worker " . $i . "\n");
		}
		// Child, start the worker
		elseif (!$pid) {
			$queues = explode (',', $QUEUE);
			$worker = new Resque_Worker ($queues);
			$worker->logLevel = $logLevel;
			fwrite (STDOUT, '*** Starting worker ' . $worker . "\n");
			$worker->work ($interval);
			break;
		}
	}
}
// Start a single worker
else {
	$queues = explode (',', $QUEUE);
	$worker = new Resque_Worker ($queues);
	$worker->logLevel = $logLevel;

	$PIDFILE = Appconf::resque ('Resque', 'pid_file');
	if ($PIDFILE) {
		file_put_contents ($PIDFILE, getmypid ()) or
			die ('Could not write PID information to ' . $PIDFILE);
	}

	fwrite (STDOUT, '*** Starting worker ' . $worker . "\n");
	$worker->work ($interval);
}

?>