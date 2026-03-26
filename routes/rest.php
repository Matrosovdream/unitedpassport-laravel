<?php

/**
 * REST routes for webhooks and external calls.
 * Prefix: /rest
 *
 * Route files are loaded from routes/rest/ directory.
 */

foreach (glob(__DIR__ . '/rest/*.php') as $file) {
    require $file;
}
