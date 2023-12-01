<?php

namespace IP800\models;

use JsonSerializable;

class Owner implements JsonSerializable
{
    /** @var Owner[] */
    public static $owners = [];

    /** @var OfficeBranch[] */
    public $officeBranches = [];

    /** * @var string[] */
    private $nebenstellen = [];

    /**
     * @var string
     */
    private $name;

    public function __construct( $name)
    {
        $this->name = $name;
    }

    public static function init()
    {
        self::$owners[] = new Owner('Alle Anrufe');
        $Person 1 = new Owner('Person 1 (907071234)');
        self::$owners[] = $Person 1;
        $Person 1->nebenstellen = array("10","11","12","15","16","17","18","19","20","30","32","33","36","38","50","51","52","53","55","57","58","59","78");
        $Person 1->officeBranches = [
            '907071234' => 'Person 1 (907071234)',
            # Anschluss Person 1
            '8821435907071234' => 'Person 1 (907071234)',
            # Anschluss Person 1 (Gruppe)
            '88' => 'Person 1 (907071234)',
            # Anschluss Person 1
            '89004921435907075678' => 'Person 1 Starlink (907075678)',
            # Anschluss Person 1 (Gruppe)
            '89' => 'Person 1 Starlink (907075678)',
            # Anschluss Person 1
            '907076789' => 'Person 1 KD (907076789)',
            # Anschluss Person 1
            '9021435907076789' => 'Person 1 KD (907076789)',
            # Anschluss Person 1 (Gruppe)
            '90' => 'Person 1 KD (907076789)',
            # Anschluss Person 1 (Gruppe)
            '93' => 'Person 1 Handy (Mobilfunk GSM)',
            # Anschluss Person 1 FAX
            '91' => 'Person 1 FAX (907078901)',
            # Anschluss Person 1 FAX (Gruppe)
            '91' => 'Person 1 FAX (907078901)',
            # Anschluss Person 1 FAX
            '907078901' => 'Person 1 FAX (907078901)',
            # Anschluss Person 1 FAX
            '9121435907078901' => 'Person 1 FAX (907078901)',
            # Anschluss Person 1 Wohnung
            '9099427' => 'Wohnung (9099427)',
            # Anschluss Person 1 Wohnung
            '86214359099427' => 'Wohnung (9099427)',
            # Anschluss Person 1 Wohnung (Gruppe)
            '86' => 'Wohnung (9099427)',
            # Anschluss Person 1 Werkstatt
            '907071111' => 'Werkstatt (907071111)',
            /* Anschluss Person 1 Werkstatt*/
            '8721435907071111' => 'Werkstatt (907071111)',
            # Anschluss Person 1 Werkstatt (Gruppe)
            '87' => 'Werkstatt (907071111)',
            // Anschluss Person 1 SIP
            '907075678' => 'Person 1 Starlink (907075678)',
        ];


        $eltern = new Owner('Eltern');
        self::$owners[] = $eltern;
        $eltern->nebenstellen= ["22","35"];
        $eltern->officeBranches = [
            # Anschluss Eltern
            '907073456' => 'Eltern Privat (907073456)',
            # Anschluss Eltern (Gruppe)
            '83' => 'Eltern Privat (907073456)'
        ];

        $Person 2 = new Owner('Person 2');
        self::$owners[] = $Person 2;
        $Person 2->nebenstellen = ["48","37","14","39"];
        $Person 2->officeBranches = [
            # Anschluss Person 2
            '907072345' => 'Person 2 (907072345)',
            # Anschluss Person 2
            '8521435907072345' => 'Person 2 (907072345)',
            # Anschluss Person 2 (Gruppe)
            '85' => 'Person 2 (907072345)'
        ];

        $quelle = new Owner('Quelle');
        self::$owners[] = $quelle;
        $quelle->nebenstellen = ["24","31","34","21"];
        $quelle->officeBranches = [
                # Anschluss Person 3 FAX (Gruppe)
                '92' => 'Person 3 FAX (907078888)',
                # Anschluss Person 3 FAX
                '907078888' => 'Person 3 FAX (907078888)',
                # Anschluss Person 3
                '907074567' => 'Otto-Shop (907074567)',
                # Anschluss Person 3 (Gruppe)
                '84' => 'Otto-Shop (907074567)'
            ];


    }

    public function __toString()
    {
        return $this->name;
    }

    public function __serialize()
    {
        return [$this->name];
    }


    public function isBranchOffice( $phoneNumber)
    {
        foreach ($this->officeBranches as $number => $name) {
            if ($phoneNumber == $number) return true;
        }

        return false;
    }

    public function isNebenstelle( $phoneNumber)
    {
        foreach ($this->nebenstellen as $number) {
            if ($phoneNumber == $number) return true;
        }

        return false;
    }

    public function jsonSerialize()
    {
        return $this->name;
    }
}

Owner::init();