<?php

namespace IP800\core;

use DateTime;
use IP800\Config;

class Logfile
{
    /**
     * @var DateTime
     */
    public $date;
    public $fileName;
    public $absoluteFilePath;
    public $fileExtension;
    public $dirName;
    public $dirPath;

    /** @var Logfile[] */
    public static $logfiles;

    /**
     * @param string $directory
     * @return Logfile[]
     */
    public static function getLogs()
    {
        $directory = Config::PATH_LOG;
        $dirRes = opendir($directory);

        while ($file = readdir($dirRes)) {
            if ($file == '.' || $file == '..')
                continue;

            preg_match('/(\d\d\d\d)-(\d\d)-(\d\d)/', $file, $matches);
            $logfile = new Logfile();
            $year = $matches[1];
            $day = $matches[2];
            $month = $matches[3];

            $pathInfo = pathinfo($directory . '/' . $file);
            $logfile->fileName = $pathInfo['filename'];
            $logfile->fileExtension = $pathInfo['extension'];
            $logfile->date = date_create("$year-$day-$month");
            $logfile->absoluteFilePath = realpath($directory . '/' . $file);

            $dirPath = dirname(realpath($directory . '/' . $file));
            $folders = explode(DIRECTORY_SEPARATOR, $dirPath);
            $logfile->dirName = end($folders);
            $logfile->dirPath = $dirPath;

            $logfiles[] = $logfile;
            unset($logfile);
        }
        closedir($dirRes);

        self::$logfiles = $logfiles;
        return self::$logfiles;
    }
}