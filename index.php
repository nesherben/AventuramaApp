<?php
require_once('config.php');

error_reporting(E_ALL);
ini_set('ignore_repeated_errors', TRUE);
ini_set('display_errors', TRUE);
ini_set('log_errors', TRUE);
ini_set('default_charset', 'UTF-8');
ini_set("error_log", "php-error.log");
error_log("-------------------------------------App iniciada");

require_once('vendor/autoload.php');

require_once('Classes/errorMessages.php');
require_once('Classes/successMessages.php');
require_once('Classes/session.php');
require_once('libs/fpdf.php');
require_once('libs/db.php');
require_once('libs/controller.php');
require_once('libs/model.php');
require_once('libs/view.php');
require_once('libs/app.php');

require_once('Models/userModel.php');

require_once('Classes/sessionController.php');
require_once('Controllers/login.php');
require_once('Controllers/errorController.php');

$app = new App();
