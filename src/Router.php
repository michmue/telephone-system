<?php
namespace IP800;

use Exception;

class Router {

    public static $ROUTE_INDEX = 0;
    public static $ROUTE_GET_LOGFILE = 1;
    public static $ROUTE_POST_NEW_CONTACT = 2;
    public static $ROUTE_PUT_EDITED_CONTACT = 3;
    public static $ROUTE_API_CONTACT_NEW = 4;
    public static $ROUTE_API_CONTACT_EDIT = 5;
    public static $ROUTE_API_CONTACT_DELETE = 6;

    public static function findRoute()
    {
        $REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
        $PATH_INFO = self::getPath();

        if ($PATH_INFO === "/api/contact/new") {
            $body = json_decode(file_get_contents('php://input'), true);
            return [self::$ROUTE_API_CONTACT_NEW, $body];
        }

        if ($PATH_INFO === "/api/contact/edit") {
            $body = json_decode(file_get_contents('php://input'), true);
            return [self::$ROUTE_API_CONTACT_EDIT, $body];
        }

        if ($PATH_INFO === "/api/contact/delete") {
            $body = json_decode(file_get_contents('php://input'), true);
            return [self::$ROUTE_API_CONTACT_DELETE, $body];
        }

        if (isset($_SERVER['QUERY_STRING']))
            $QUERY_STRING = $_SERVER['QUERY_STRING'];


        if (isset($REQUEST_METHOD) && $REQUEST_METHOD == "GET" &&
            isset($QUERY_STRING)) {

            parse_str($QUERY_STRING, $querys);
            if (isset($querys['logfile'])) {
                return [self::$ROUTE_GET_LOGFILE, $querys['logfile']];
            }
        }


        if (isset($REQUEST_METHOD) && $REQUEST_METHOD == "POST") {
            $body = json_decode(file_get_contents('php://input'), true);
            return [self::$ROUTE_POST_NEW_CONTACT, $body];
        }


        if (isset($REQUEST_METHOD) && $REQUEST_METHOD == "PATCH") {
            $body = json_decode(file_get_contents('php://input'), true);
            return [self::$ROUTE_PUT_EDITED_CONTACT, $body];
        }


        if ($REQUEST_METHOD == "GET")
            return [self::$ROUTE_INDEX, null];


        throw new Exception("unknown route");
    }

    /**
     * @return string
     */
    public static function getPath()
    {
        $pathWithQuery = $_SERVER['REQUEST_URI'];

        $query_string = "";
        if (isset($_SERVER['QUERY_STRING'])) {
            $query_string = $_SERVER['QUERY_STRING'];
        }
        return str_replace($query_string, "", $pathWithQuery);
    }
}
