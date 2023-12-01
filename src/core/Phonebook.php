<?php

namespace IP800\core;

use IP800\Config;

class Phonebook {

    /**
     * @var array
     */
    public $callNumbers = [];
    /**
     * @var array
     */
    public $names = [];

    /**
     * @return Phonebook
     */
    static function create() {
        $phonebook = new Phonebook();
        $fp = fopen(Config::PATH_PHONEBOOK,"r");

        while($eintrag = fgetcsv($fp,500,";"))
        {
            for ($i = 0; $i < count($eintrag); $i++) {
                $eintrag[$i] = mb_convert_encoding($eintrag[$i], "UTF-8", "Windows-1252");
            }
            $phonebook->callNumbers[] = $eintrag[0];
            $phonebook->names[] = $eintrag[1];
        }

        fclose($fp);
        unset($fp, $eintrag);
        return $phonebook;
    }

}