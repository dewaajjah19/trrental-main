<?php
define('BASE_PATH', __DIR__);
define('BASE_URL', 'http://localhost/trrental-main');

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/core/App.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Model.php';

$app = new App();
