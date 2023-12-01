<?php
namespace IP800;

use IP800\core\Logfile;
use IP800\core\LogfileParser;
use IP800\ext\DateTimeFilter;
use IP800\models\Owner;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;


class IndexController
{
    /**
     * @param string $requestedLogfileName
     * @param string $requestedYear
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function show($requestedLogfileName, $requestedYear)
    {
        $start	= microtime(1);
        $logs = Logfile::getLogs();
        $log = self::getRequestedOrLatestLog($logs, $requestedLogfileName);
        $requestedYear = self::getRequestedOrLatestYear($requestedYear, $log);
        $logsByYear = self::getLogsByYear($logs);

        $twig = self::getConfiguredTwig();

        $logfileParser = new LogfileParser();
        $mCalls = $logfileParser->parse($log);

        $zeit = round(microtime(1) - $start, 3);

        echo $twig->render("index.twig", [
            'calls' => $mCalls,
            'owners' => Owner::$owners,
            'time' => $zeit,
            'logfiles' => $logs,
            'logfile' => $log,
            'logsByYear' => $logsByYear,
            'requestedYear' => $requestedYear,
            'requestedLogfileName' => $requestedLogfileName
        ]);
    }

    /**
     * @param array $logfiles
     * @param $requestedLogfileName
     * @return Logfile
     */
    public static function getRequestedOrLatestLog(array $logfiles, $requestedLogfileName)
    {
        foreach ($logfiles as $log) {
            if ($requestedLogfileName === $log->fileName) {
                $logfile = $log;
            }
        }

        if (!isset($logfile)) {
            $logfile = $logfiles[0];
        }
        return $logfile;
    }

    /**
     * @param $requestedYear
     * @param Logfile $logfile
     * @return mixed|string
     */
    public static function getRequestedOrLatestYear($requestedYear, Logfile $logfile)
    {
        if (!isset($requestedYear)) {
            $requestedYear = $logfile->date->format('Y');
        }
        return $requestedYear;
    }

    /**
     * @return Environment
     */
    public static function getConfiguredTwig()
    {
        $loader = new FilesystemLoader("./src/view/");
        $twig = new Environment($loader, [
            'debug' => false,
            'charset' => 'UTF-8',
            'auto_reload' => true,
        ]);
        $twig->addFilter(new DateTimeFilter());
        return $twig;
    }

    /**
     * @param array $logfiles
     * @return array
     */
    public static function getLogsByYear(array $logfiles)
    {
        foreach ($logfiles as $log) {
            $year = $log->date->format("Y");
            $logsByYear[$year][] = $log;
        }

        $logsByYear = array_reverse($logsByYear, true);
        return $logsByYear;
    }
}
