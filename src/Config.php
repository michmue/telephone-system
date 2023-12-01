<?php
namespace IP800;

class Config
{
    const PATH_LOG = "res/logs";
    const PATH_PHONEBOOK = "res/cpnumber.dat";
    const ANONYMOUS = ["TEL1", "TEL2", "Unbekannt", "R", 0, 6];
    const OWNER = [
        "Alle Anrufe",
        "Person 1 (907071234)",
        "Person 2 (907072345)",
        "Eltern (907073456)",
        "Person 3 (907074567)",
//        "Person 1 Starlink (907075678)";
//        "Person 1 KD (907076789)";
//        "Person 1 FAX (907078901)";
//        "Person 3 FAX (907078888)";
//        "Person 1 (Mobilfunk)";
    ];

    public static $AMT = [];
    public static $amt_name = [];
    public static $AMT_OWNER = [];
    public static $NEBENSTELLEN = [];

    public static function init()
    {
        # Anschluss Person 1
        Config::$AMT[] = 907071234;
        Config::$amt_name[] = "Person 1 (907071234)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1
        Config::$AMT[] = 8821435907071234;
        Config::$amt_name[] = "Person 1 (907071234)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 (Gruppe)
        Config::$AMT[] = 88;
        Config::$amt_name[] = "Person 1 (907071234)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1
        Config::$AMT[] = 89004921435907075678;
        Config::$amt_name[] = "Person 1 Starlink (907075678)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 (Gruppe)
        Config::$AMT[] = 89;
        Config::$amt_name[] = "Person 1 Starlink (907075678)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1
        Config::$AMT[] = 907076789;
        Config::$amt_name[] = "Person 1 KD (907076789)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1
        Config::$AMT[] = 9021435907076789;
        Config::$amt_name[] = "Person 1 KD (907076789)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 (Gruppe)
        Config::$AMT[] = 90;
        Config::$amt_name[] = "Person 1 KD (907076789)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 (Gruppe)
        Config::$AMT[] = 93;
        Config::$amt_name[] = "Person 1 Handy (Mobilfunk GSM)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 3
        Config::$AMT[] = 907074567;
        Config::$amt_name[] = "Otto-Shop (907074567)";
        Config::$AMT_OWNER[] = 4;

        # Anschluss Person 3 (Gruppe)
        Config::$AMT[] = 84;
        Config::$amt_name[] = "Otto-Shop (907074567)";
        Config::$AMT_OWNER[] = 4;

        # Anschluss Person 2
        Config::$AMT[] = 907072345;
        Config::$amt_name[] = "Person 2 (907072345)";
        Config::$AMT_OWNER[] = 2;

        # Anschluss Person 2
        Config::$AMT[] = 8521435907072345;
        Config::$amt_name[] = "Person 2 (907072345)";
        Config::$AMT_OWNER[] = 2;

        # Anschluss Person 2 (Gruppe)
        Config::$AMT[] = 85;
        Config::$amt_name[] = "Person 2 (907072345)";
        Config::$AMT_OWNER[] = 2;

        # Anschluss Person 1 Wohnung
        Config::$AMT[] = 9099427;
        Config::$amt_name[] = "Wohnung (9099427)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 Wohnung
        Config::$AMT[] = 86214359099427;
        Config::$amt_name[] = "Wohnung (9099427)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 Wohnung (Gruppe)
        Config::$AMT[] = 86;
        Config::$amt_name[] = "Wohnung (9099427)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 FAX
        Config::$AMT[] = 91;
        Config::$amt_name[] = "Person 1 FAX (907078901)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 Werkstatt
        Config::$AMT[] = 907071111;
        Config::$amt_name[] = "Werkstatt (907071111)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 Werkstatt
        Config::$AMT[] = 8721435907071111;
        Config::$amt_name[] = "Werkstatt (907071111)";
        Config::$AMT_OWNER[] = 1;


        # Anschluss Person 1 Werkstatt (Gruppe)
        Config::$AMT[] = 87;
        Config::$amt_name[] = "Werkstatt (907071111)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 SIP
        Config::$AMT[] = 907075678;
        Config::$amt_name[] = "Person 1 Starlink (907075678)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 KD
        Config::$AMT[] = 907076789;
        Config::$amt_name[] = "Person 1 KD (907076789)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Eltern
        Config::$AMT[] = 907073456;
        Config::$amt_name[] = "Eltern Privat (907073456)";
        Config::$AMT_OWNER[] = 3;

        # Anschluss Eltern (Gruppe)
        Config::$AMT[] = 83;
        Config::$amt_name[] = "Eltern Privat (907073456)";
        Config::$AMT_OWNER[] = 3;

        # Anschluss Person 3 FAX (Gruppe)
        Config::$AMT[] = 92;
        Config::$amt_name[] = "Person 3 FAX (907078888)";
        Config::$AMT_OWNER[] = 4;

        # Anschluss Person 3 FAX
        Config::$AMT[] = 907078888;
        Config::$amt_name[] = "Person 3 FAX (907078888)";
        Config::$AMT_OWNER[] = 4;

        # Anschluss Person 1 FAX (Gruppe)
        Config::$AMT[] = 91;
        Config::$amt_name[] = "Person 1 FAX (907078901)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 FAX
        Config::$AMT[] = 907078901;
        Config::$amt_name[] = "Person 1 FAX (907078901)";
        Config::$AMT_OWNER[] = 1;

        # Anschluss Person 1 FAX
        Config::$AMT[] = 9121435907078901;
        Config::$amt_name[] = "Person 1 FAX (907078901)";
        Config::$AMT_OWNER[] = 1;


        // HINT: office branches containing 'phoneNumbers' which belong to one of the owners [0]='Alle Anrufe', [1]='Person 1 (907071234)'
        //       it's to filter calls belonging to only one owner but owner can have multiple 'phone extension numbers'
        Config::$NEBENSTELLEN[0] = [];
        Config::$NEBENSTELLEN[1] = ["10", "11", "12", "15", "16", "17", "18", "19", "20", "30", "32", "33", "36", "38", "50", "51", "52", "53", "55", "57", "58", "59", "78"];
        Config::$NEBENSTELLEN[2] = ["48", "37", "14", "39"];
        Config::$NEBENSTELLEN[3] = ["22", "35"];
        Config::$NEBENSTELLEN[4] = ["24", "31", "34", "21"];
        Config::$NEBENSTELLEN[5] = [""];
        Config::$NEBENSTELLEN[6] = [""];
        Config::$NEBENSTELLEN[7] = [""];
        Config::$NEBENSTELLEN[8] = [""];
    }
}

Config::init();