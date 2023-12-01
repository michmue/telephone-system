<?php

namespace IP800\api;

use IP800\Config;
use IP800\core\Phonebook;

class ContactAPI
{
    public static function createContact($body)
    {
        $name = $body['name'];
        $phoneNumber = $body['phoneNumber'];

        $newLine = $phoneNumber . ";" . $name . ";0\n";
        $newLine = mb_convert_encoding($newLine, "Windows-1252", "UTF-8");

        $datei=fopen(Config::PATH_PHONEBOOK,"a");
        fwrite($datei, $newLine);
        fclose($datei);
    }

    public static function editContact($body)
    {
        $name = $body['name'];
        $phoneNumber = $body['phoneNumber'];

        $phonebook = Phonebook::create();

        if (in_array($phoneNumber, $phonebook->callNumbers))
        {
            $x = array_search($phoneNumber, $phonebook->callNumbers);
            $lines = file(Config::PATH_PHONEBOOK);
            $newLine = $phoneNumber . ";" . $name . ";0\n";
            $newLine = mb_convert_encoding($newLine, "Windows-1252", "UTF-8");
            $lines[$x] = $newLine;
            $newFile=implode("", $lines);
            $datei=fopen(Config::PATH_PHONEBOOK,"w+");
            fwrite($datei, $newFile);
            fclose($datei);
        }
    }

    public static function deleteContact($body)
    {
        $phoneNumber = $body['phoneNumber'];

        $phonebook = Phonebook::create();

        if (in_array($phoneNumber, $phonebook->callNumbers))
        {
            $foundNumberIndex = array_search($phoneNumber, $phonebook->callNumbers);

            $lines = file(Config::PATH_PHONEBOOK);
            unset($lines[$foundNumberIndex]);
            $newFile=implode("", $lines);
            $datei=fopen(Config::PATH_PHONEBOOK,"w+");
            fwrite($datei, $newFile);
            fclose($datei);
        }
    }

}