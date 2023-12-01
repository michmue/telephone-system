<?php
date_default_timezone_set('Europe/Berlin');

use IP800\api\ContactAPI;
use IP800\IndexController;
use IP800\Logfiles;
use IP800\Router;

require_once __DIR__ . '/vendor/autoload.php';


$list = Router::findRoute();
$ROUTE = $list[0];
$data = $list[1];
switch ($ROUTE) {
    case Router::$ROUTE_API_CONTACT_NEW: {
        ContactAPI::createContact($data);
        break;
    }

    case Router::$ROUTE_API_CONTACT_EDIT: {
        ContactAPI::editContact($data);
        break;
    }

    case Router::$ROUTE_API_CONTACT_DELETE: {
        ContactAPI::deleteContact($data);
        break;
    }

    case Router::$ROUTE_INDEX: {
        $requestedYear = isset($_GET['logYear']) ? $_GET['logYear'] : null;
        $requestedLogfileName = isset($_GET['logName']) ? $_GET['logName'] : null;

        IndexController::show($requestedLogfileName, $requestedYear);
        break;
    }

    //TODO: currently no usage, probably broken
    case Router::$ROUTE_GET_LOGFILE: {
        $logfiles = Logfiles::getInstance();
        $logEntriesJSON = Logfiles::getLogEntriesJSON($data);;
        header('Content-Type: application/json; charset=utf-8');
        echo $logEntriesJSON;
        break;
    }

    case Router::$ROUTE_POST_NEW_CONTACT: {
        echo "ROUTE_POST_NEW_CONTACT";
        ContactAPI::createContact($data);
        break;
    }

    case Router::$ROUTE_PUT_EDITED_CONTACT: {
        echo "ROUTE_PATCH_CONTACT";
        ContactAPI::editContact($data);
        break;
    }
}