<?
# Konfigurationsdatei
# Wichtig:
# TRUE  -> Funktion aktiviert
# FALSE -> Funktion deaktiviert

# Definitionen der Variablen
$log 	= array();
$site	= array();

# Pfadangaben
#

# Greybox URL
$GB_url         = "http://".$_SERVER['HTTP_HOST']."/ip400/greybox/";

# Wichtig: Vollständigen Pfad angeben! Ein \ muss doppelt geschrieben werden!
# z.B.  $log['path']	= "D:\\wwwroot\\IP400\\logs";

# Pfad zu den Logdateien
$log['path']	= "\\\\FDSERVER\\Gemeinsame Dateien\\Syslog";

# Pfad zum Telefonbuch
$telbuch		= "\\\\FDSERVER\\Gemeinsame Dateien\\PowerISDNMonitor Daten\\cpnumber.dat";

# Pfad für das Archiv
$archiv_pfad	= "\\\\FDSERVER\\Gemeinsame Dateien\\IP400";

# Smarty
define('SMARTY_DIR', 'C:/inetpub/wwwroot/IP400/tpl/');

# Doppelte Telefongespräche nicht ausgeben
# (Werden anhand der Uhrzeit ermittelt)
#
$rem_double		= TRUE;

# Anrufe der Türsprechstelle umwandeln
#
#
$edit_door		= TRUE;
$door			= array();
$door['nr']		= "41";
$door['text']	= "Türsprechstelle";

# Archivfunktion
#

# Wenn eine Logfile, die älter als ein Tag ist, geöffnet wird, dann wird 
# von dieser ein CSV-File erstellt. Somit wird Rechenaufwand gespart, 
# da sich diese Logfile nie wieder ändern wird. Bei späteren aufrufen
# wird nur noch die CSV-File geöffnet. Spart bei einer großen Logfile
# (ca. 5 MB) gute 10 Sekunden alleine bei der Auswertung!
$archiv			= TRUE;
$auto_refresh	= TRUE;		// Automatisches neuschreiben des Archivs wenn die index.php oder das Telefonbuch geändert wurde

# Eigentümer der Anschlüsse
$owner[]		= array();

$owner[0]		= "Alle Anrufe";
$owner[1]		= "Person 1 (907071234)";
$owner[2]		= "Person 2 (907072345)";
$owner[3]		= "Eltern (907073456)";	
$owner[4]		= "Person 3 (907074567)";
#$owner[5]		= "Person 1 Starlink (907075678)";
#$owner[6]		= "Person 1 KD (907076789)";
#$owner[7]		= "Person 1 FAX (907078901)";


# Amtsnummern festlegen
#

$amt 			= array();
$amt_name		= array();
$amt_owner		= array();

# Anschluss Person 1
$amt[] 			= 907071234;
$amt_name[]		= "Person 1<br />(907071234)";
$amt_owner[]	= 1;

# Anschluss Person 1 (Gruppe)
$amt[] 			= 88;
$amt_name[]		= "Person 1<br />(907071234)";
$amt_owner[]	= 1;

# Anschluss Person 1
$amt[] 			= 89004921435907075678;
$amt_name[]		= "Person 1 Starlink<br />(907075678)";
$amt_owner[]	= 1;

# Anschluss Person 1 (Gruppe)
$amt[] 			= 89;
$amt_name[]		= "Person 1 Starlink<br />(907075678)";
$amt_owner[]	= 1;

# Anschluss Person 1
$amt[] 		        = 907076789;
$amt_name[]		= "Person 1 KD<br />(907076789)";
$amt_owner[]	= 1;

# Anschluss Person 1 (Gruppe)
$amt[] 		        = 90;
$amt_name[]		= "Person 1 KD<br />(907076789)";
$amt_owner[]	= 1;

# Anschluss Person 3
$amt[] 			= 907074567;
$amt_name[]		= "Otto-Shop<br />(907074567)";
$amt_owner[]	= 4;

# Anschluss Person 3 (Gruppe)
$amt[] 			= 84;
$amt_name[]		= "Otto-Shop<br />(907074567)";
$amt_owner[]	= 4;

# Anschluss Frei
$amt[] 			= 907072345;
$amt_name[]		= "Person 2<br />(907072345)";
$amt_owner[]	= 2;

# Anschluss Frei (Gruppe)
$amt[] 			= 85;
$amt_name[]		= "Person 2<br />(907072345)";
$amt_owner[]	= 2;

# Anschluss Person 1 Wohnung
$amt[] 			= 9099427;
$amt_name[]		= "Wohnung<br />(9099427)";
$amt_owner[]	= 1;

# Anschluss Person 1 Wohnung (Gruppe)
$amt[] 			= 86;
$amt_name[]		= "Wohnung<br />(9099427)";
$amt_owner[]	= 1;

# Anschluss Person 1 FAX
$amt[] 			= 91;
$amt_name[]		= "Person 1 FAX<br />(907078901)";
$amt_owner[]	= 1;

# Anschluss Person 1 Werkstatt
$amt[] 			= 982066;
$amt_name[]		= "Werkstatt<br />(982066)";
$amt_owner[]	= 1;

# Anschluss Person 1 Werkstatt (Gruppe)
$amt[] 			= 87;
$amt_name[]		= "Werkstatt<br />(982066)";
$amt_owner[]	= 1;

# Anschluss Person 1 SIP
$amt[] 			= 907075678;
$amt_name[]		= "Person 1 Starlink<br />(907075678)";
$amt_owner[]	= 1;

# Anschluss Person 1 KD
$amt[] 			= 907076789;
$amt_name[]		= "Person 1 KD<br />(907076789)";
$amt_owner[]    = 1;

# Anschluss Eltern
$amt[] 			= 907073456;
$amt_name[]		= "Eltern Privat<br />(907073456)";
$amt_owner[]	= 3;

# Anschluss Eltern (Gruppe)
$amt[] 			= 83;
$amt_name[]		= "Eltern Privat<br />(907073456)";
$amt_owner[]	= 3;

# Anschluss Person 3 FAX
$amt[] 			= 907078888;
$amt_name[]		= "Otto-Shop FAX<br />(907078888)";
$amt_owner[]	= 4;

# Unbekannter Anrufer
$unbekannt		= array("TEL1", "TEL2", "Unbekannt", "R", 0, 6);

# Nebenstellen
#
$nebenstellen 	= array();

# Nebenstellen für Eigentümer 0 <= nur ein Dummywert!!!! Keine Nebenstelleneintrag nötig!!!!
$nebenstellen[0]= array();

# Nebenstellen für Eigentümer 1
$nebenstellen[1]= array("10","11","12","15","16","17","18","19","20","30","32","33","36","50","51","52","53");

# Nebenstellen für Eigentümer 2
$nebenstellen[2]= array("21","13","37");

# Nebenstellen für Eigentümer 3
$nebenstellen[3]= array("22","35");

# Nebenstellen für Eigentümer 4
$nebenstellen[4]= array("24","31","34");

# Nebenstellen für Eigentümer 5
$nebenstellen[4]= array("");

# Nebenstellen für Eigentümer 6
$nebenstellen[4]= array("");

# Nebenstellen für Eigentümer 7
$nebenstellen[4]= array("");

?>