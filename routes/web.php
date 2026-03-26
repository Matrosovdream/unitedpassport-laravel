<?php

/**
 * Web routes for HTTP requests.
 *
 * Route files are loaded from routes/web/ directory.
 */

foreach (glob(__DIR__ . '/web/*.php') as $file) {
    require $file;
}
