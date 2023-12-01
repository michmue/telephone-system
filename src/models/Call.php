<?php

namespace IP800\models;

use DateTime;

class Call
{
//    public string $logfileName;
//    public string $fileName;

    /**
     * @var string
     */
    public $phoneStationIP;

    public $acceptedCallNumber;
    /**
     * @var CallType
     */
    public $callType;

    public $callAccepted = false;
    public $callFinished = false;

    /**
     * @var DateTime
     */
    public $callTimeBegin;
    /**
     * @var DateTime
     */
    public $callTimeEnd;
    /**
     * @var DateTime
     */
    public $callTimeDuration;

    public $callerId;
    /**
     * @var Contact
     */
    public $sourceContact;
    /**
     * @var Contact
     */
    public $destinationContact;
    /**
     * @var MSN
     */
    public $destinationMSN;
    /** * @var Owner[] */
    public $includedOwners;

    /**
     * @return string
     */
    public function getAcceptedPhoneNumber()
    {
        $phoneNumber = "";
        if ($this->callType == CallType::$IN_COMING) {
            $phoneNumber = $this->destinationContact->phoneNumber;
        } elseif ($this->callType == CallType::$OUT_GOING) {
            $phoneNumber = $this->sourceContact->phoneNumber;
        }

        # Werte Uebergeben
//        $calls[$tmp_call_id]['accepted'] = TRUE;
//        $calls[$tmp_call_id]['accepted_nst'] = $tmp_accepted_num;
        return $phoneNumber;
    }
}