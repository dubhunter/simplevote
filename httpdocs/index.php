<?php
/**
 * index.php :: dbdMVC Bootstrap Sample File
 *
 * @version 1.4
 * @author Will Mason <get@willmason.me>
 * @copyright Copyright (c) 2013 by Will Mason
 */
define('DBD_DOC_ROOT', dirname(__FILE__));
define('DBD_APP_DIR', realpath(DBD_DOC_ROOT . '/../dbdApp') . '/');
define('DBD_MVC_DIR', '/var/www/dbdMVC2/');

require_once(DBD_MVC_DIR."dbdMVC.php");

dbdRequest::addRewrite('#^/v1/votes/([0-9]+)?$#i', '/v1VoteResultsController?to=$1');

dbdMVC::setErrorController('SVError');
dbdMVC::setFallbackController('SVController');

dbdMVC::setControllerSuffix('Controller');

dbdMVC::setAppName('Simple Vote');

dbdMVC::run(DBD_APP_DIR);