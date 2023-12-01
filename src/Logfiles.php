<?php

namespace IP800;

/*IMPR: CallEntry vs calls[call_id] from IndexController?
 *      Can we use CallEntry as replacement for calls[] ?
 *      CallEntry represents one line in the log file but calls[] differs between call accepted / not acc. / unknown?
*/

use IP800\core\Logfile;
use IP800\core\LogfileParser;
use IP800\models\CallType;

class Logfiles
{

    /**
     * @var Logfiles
     */
    private static $instance = null;
    const logfilePath = Config::PATH_LOG;
    /**
     * @var array<int, Logfile>
     */
    public static $logfiles = [];


    private function __construct()
    {
        $logfiles = self::findLogfiles(self::logfilePath);
        self::readLogfile($logfiles[0]);
    }

    /**
     * @return Logfiles
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Logfiles();
        }

        return self::$instance;
    }

    /**
     * @param array<string> $logfiles
     * @return Logfile
     */
    private static function readLogfile($log)
    {
        $logfile = new Logfile();

        $fp = fopen(self::logfilePath . "/" . $log, "r");
        if ($fp) {

            $parser = new LogfileParser();
            $mCalls = $parser->parse($fp);
//                $callEntry = self::parseLogfileLine($line);
//                $logfile->callEntries[] = $callEntry;

        }


        Logfiles::$logfiles[$log] = $logfile;
        return $logfile;
    }

    /**
     * @param $dir
     * @return array
     */
    function findLogfiles($dir)
    {
        # Variablen definieren
        $files = array();

        # Öffnen des Verzeichnisses
        $dp = opendir($dir);

        # Durchlaufen des Verzeichnisses
        while ($file = readdir($dp)) {
            if ($file != "." && $file != ".." && is_file($dir . "/" . $file)) {
                # Alle Dateiname in einen Array schreiben
                array_push($files, $file);
            }
        }
        # Arry sortieren
        sort($files, SORT_STRING);
        $files = array_reverse($files);

        # Verzeichniss schließen
        closedir($dp);

        unset($dp, $file);
        return $files;
    }

    /**
     * @param string $logfileName
     * @return string
     */
    public static function getLogEntriesJSON($logfileName)
    {
        $logfile = self::readLogfile($logfileName);
        return $logfile->getJSON();
    }
}