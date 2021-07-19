<?php
/**
 * Plugin Name: Mihdan Vacancies
 */

namespace Mihdan\Vacancies;

require_once __DIR__ . '/vendor/woocommerce/action-scheduler/action-scheduler.php';
require_once __DIR__ . '/vendor/autoload.php';

( new Main() )->setup_hooks();