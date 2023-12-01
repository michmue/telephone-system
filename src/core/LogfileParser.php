<?php

namespace IP800\core;

use DateTime;
use IP800\Config;
use IP800\models\Call;
use IP800\models\CallType;
use IP800\models\Contact;
use IP800\models\MSN;
use IP800\models\Owner;

class LogfileParser {
    private static $callsClosed = [];

    /** * @var Call[] */
    private static $calls = [];
    private static $phonebook;

    /**
     * @param Logfile $logfile
     * @return Call[]
     */
    public function parse(Logfile $logfile)
    {
        $logFilesRes = fopen($logfile->absoluteFilePath, 'r');

        while ($line = fgets($logFilesRes)) {
                $line = mb_convert_encoding($line, 'UTF-8', 'Windows-1252');
                if ($this->isLineRelevant($line)) {
                    $this->parseLogfileLine($line);
                }
        }
        fclose($logFilesRes);

        return self::$callsClosed;
    }

    /**
     * @param string $line
     * @return void
     * @throws \Exception
     */
    private function parseLogfileLine($line)
    {
        if ($this->isCallBeginning($line)) {
            $call = new Call();
            $callerId = $this->parseCallerId($line);

            $call->callerId = $callerId;

            $call->phoneStationIP = $this->parsePhoneStationIP($line);
            $call->callTimeBegin = $this->parseCallTimeBegin($line);

            list($sourceContact, $destinationContact) = $this->parseContacts($line);
            $call->destinationMSN = $this->findMSN($destinationContact->phoneNumber);

            $call->sourceContact = $sourceContact;
            $call->destinationContact = $destinationContact;
            $call->callType = $this->parseCallType($call);
            $call->includedOwners = $this->parseOwner($sourceContact, $destinationContact, $call->callType);

            self::$calls[$callerId] = $call;
        } else if ($this->isCallAccepted($line)) {
            $callerId = $this->parseCallerId($line);
            $call = self::$calls[$callerId];

            // override group call destination number with accepted branchOffice number
            $call->destinationContact = $this->parseContacts($line)[1];
//            $call->sourceContact->msn = $this->findMSN($call->sourceContact->phoneNumber);
//            $call->destinationContact->msn = $this->findMSN($call->destinationContact->phoneNumber);
            $call->callAccepted = true;

        } else if ($this->isCallEnding($line)) {
            $callerId = $this->parseCallerId($line);
            if (array_key_exists($callerId, self::$calls)) {
                $call = self::$calls[$callerId];

                $call->callFinished = true;
                $call->callTimeEnd = $this->parseCallTimeEnd($line);
                $dateInterval = $call->callTimeBegin->diff($call->callTimeEnd);
                $call->callTimeDuration = new DateTime($dateInterval->format("%H:%I:%S"));
                self::$callsClosed[] = $call;
                unset(self::$calls[$callerId]);
            }
        }
    }

    /**
     * @param string $line
     * @return bool
     */
    private function isCallBeginning($line)
    {
        return strpos($line, 'setup->')  !== false;
    }

    /**
     * @param string $line
     * @return bool
     */
    private function isCallAccepted($line)
    {
        return strpos($line, '<-conn')  !== false;
    }

    /**
     * @param $line
     * @return bool
     */
    private function isCallEnding($line)
    {
        return strpos($line, 'rel->') !== false;
    }

    /**
     * @param $line
     * @return bool
     */
    private function isLineRelevant($line)
    {
        return $this->isCallBeginning($line) || $this->isCallAccepted($line) || $this->isCallEnding($line);
    }

    /**
     * @param $line
     * @return int|string
     */
    private function parseCallerId($line)
    {
        preg_match('/PBX0 ([\d]{1,2})/', $line, $call_id);
        if (sizeof($call_id) == 0) return -1;

        return $call_id[1];
    }

    /**
     * @param string $line
     * @return string
     */
    private function parsePhoneStationIP($line)
    {
        preg_match('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/', $line, $matches);
        return $matches[0];
    }

    /**
     * @param string $line
     * @return DateTime
     * @throws \Exception
     */
    private function parseCallTimeBegin($line)
    {
        preg_match('/([\d]{1,2})-([\d]{1,2})-([\d]{1,4})\s+([\d]{1,2}):([\d]{1,2}):([\d]{1,2})/', $line, $matches);
        $call_time = $matches[0];

        return new DateTime($call_time);
    }

    /**
     * @param Call $call
     * @return CallType
     */
    private function parseCallType(Call $call)
    {
        $amt = Config::$AMT;
        # Anzahl der Nummern ermitteln

        $sourcePhoneNumber = $call->sourceContact->phoneNumber;
        $destinationPhoneNumber = $call->destinationContact->phoneNumber;

        $len_source = strlen(strval($sourcePhoneNumber));
        $len_destination = strlen(strval($destinationPhoneNumber));

        if ($len_source == 2 && $len_destination == 2) {
            # Intern -> Intern
            $type = CallType::$INTERN;
        } elseif ($len_source == 2 && $len_destination > 2) {
            # Intern -> Extern
            $type = CallType::$OUT_GOING;
        } elseif (($len_source > 2 || $len_source == 1 || empty($source)) && ($len_destination == 2 || $destinationPhoneNumber == 89004921435907075678 || in_array($destinationPhoneNumber, $amt))) {
            # Extern -> Intern bzw. SIP -> Intern
            $type = CallType::$IN_COMING;
        } else {
            # Typ unbekannt (wie kann dass passieren?!?!)
            $type = CallType::$UNKNOWN;
        }

        unset($len_destination, $len_source);
        return $type;
    }

    /**
     * @param string $line
     * @return Contact[]
     */
    private function parseContacts($line)
    {
        # Telefonnummern des Anrufes ermitteln
        preg_match_all('/\(.*?\)/', $line, $matches);

        $sourcePhoneNumber = $this->selectNumbersOnly($matches[0][0]);
        $destinationPhoneNumber = $this->selectNumbersOnly($matches[0][1]);

        $sourcePhoneNumber = $this->removeLeadingZeroOrSix($sourcePhoneNumber);
        $destinationPhoneNumber = $this->removeLeadingZeroOrSix($destinationPhoneNumber);

        $sourceContact = new Contact();
        $sourceContact->phoneNumber = $sourcePhoneNumber;
        $sourceContact->msn = $this->findMSN($sourcePhoneNumber);
        $sourceContact->name = $this->findInPhonebook($sourcePhoneNumber);

        $destinationContact = new Contact();
        $destinationContact->phoneNumber = $destinationPhoneNumber;
        $destinationContact->msn = $this->findMSN($destinationPhoneNumber);
        $destinationContact->name = $this->findInPhonebook($destinationPhoneNumber);
        return [$sourceContact, $destinationContact];
    }

    /**
     * @param string $line
     * @return Contact
     */
    private function parseDestinationContact( $line)
    {
        return new Contact();
    }

    /**
     * @param string $line
     * @return DateTime
     * @throws \Exception
     */
    private function parseCallTimeEnd($line)
    {
        preg_match('/([\d]{1,2})-([\d]{1,2})-([\d]{1,4})\s+([\d]{1,2}):([\d]{1,2}):([\d]{1,2})/', $line, $matches);
        $call_time = $matches[0];

        return new DateTime($call_time);
    }

    /**
     * @param string $phoneNumber
     * @return string
     */
    public function removeLeadingZeroOrSix($phoneNumber)
    {
        preg_match_all('/([0-9])/', $phoneNumber, $tmp_array);
        if ($tmp_array[0][0] == "0" || $tmp_array[0][0] == "6") {
            $phoneNumber = substr($phoneNumber, 1);
        }
        return $phoneNumber;
    }

    /**
     * @param $tmp_preg_array
     * @param $tmp_num_sor
     * @return array
     */
    public function selectNumbersOnly($tmp_preg_array)
    {
        preg_match_all('/([0-9]+)/', $tmp_preg_array, $modifiedNumber);
        return $modifiedNumber[0][0];
    }

    /**
     * @param array|string $phoneNumber
     * @return MSN
     */
    private function findMSN($phoneNumber)
    {
        $amt = Config::$AMT;
        $amt_name = Config::$amt_name;
        $amt_owner = Config::$AMT_OWNER;
        $MSN = new MSN();
        if (in_array($phoneNumber, $amt)) {
            $x = array_search($phoneNumber, $amt);
            $MSN->officeBranchName = $amt_name[$x];
            $MSN->officeBranchOwner = $amt_owner[$x];
        } else {
            $MSN->officeBranchName = "";
            $MSN->officeBranchOwner = -2;
        }

        return $MSN;
    }

    /**
     * #[ArrayShape(['name' => "mixed|string", 'number' => ""])]
     * @param $phoneNumber
     * @return mixed|string
     */
    function findInPhonebook($phoneNumber)
    {
        if (!isset(self::$phonebook)) {
            self::$phonebook = Phonebook::create();
        }

        if (in_array($phoneNumber, self::$phonebook->callNumbers))
        {
            $x = array_search($phoneNumber, self::$phonebook->callNumbers);
            $name = self::$phonebook->names[$x];
        }
        elseif(in_array($phoneNumber, Config::ANONYMOUS))
        {
            $name = "Unbekannter Anrufer";
        }
        else
        {
            $name = "";
        }

        return $name;
    }

    private function parseOwner(Contact $sourceContact, Contact $destinationContact, CallType $callType)
    {
        $srcPhoneNumber = $sourceContact->phoneNumber;
        $destPhoneNumber = $destinationContact->phoneNumber;

        $owners = [];

        foreach (Owner::$owners as $owner) {
            if ($owner->isBranchOffice($srcPhoneNumber) || $owner->isBranchOffice($destPhoneNumber)) {
                $owners[] = $owner;
            }

            if ($owner->isNebenstelle($srcPhoneNumber) || $owner->isNebenstelle($destPhoneNumber)){
                $owners[] = $owner;
            }
        }


        return $owners;
    }

    private function isPhoneNumberFromOwner()
    {
    }
    
}
