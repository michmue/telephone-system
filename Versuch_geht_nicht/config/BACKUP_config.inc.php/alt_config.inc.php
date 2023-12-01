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

# Wichtig: Vollst�ndigen Pfad angeben! Ein \ muss doppelt geschrieben werden!
# z.B.  $log['path']	= "D:\\wwwroot\\IP400\\logs";

# Pfad zu den Logdateien
$log['path']	= "D:\\Gemeinsame Dateien\\Syslog";

# Pfad zum Telefonbuch
$telbuch		= "D:\\Gemeinsame Dateien\\PowerISDNMonitor Daten\\cpnumber.dat";

# Pfad f�r das Archiv
$archiv_pfad	= "D:\\Gemeinsame Dateien\\IP400";

# Smarty
define('SMARTY_DIR', 'C:/inetpub/wwwroot/IP400/tpl/');

# Doppelte Telefongespr�che nicht ausgeben
# (Werden anhand der Uhrzeit ermittelt)
#
$rem_double		= TRUE;

# Anrufe der T�rsprechstelle umwandeln
#
#
$edit_door		= TRUE;
$door			= array();
$door['nr']		= "41";
$door['text']	= "T�rsprechstelle";

# Archivfunktion
#

# Wenn eine Logfile, die �lter als ein Tag ist, ge�ffnet wird, dann wird 
# von dieser ein CSV-File erstellt. Somit wird Rechenaufwand gespart, 
# da sich diese Logfile nie wieder �ndern wird. Bei sp�teren aufrufen
# wird nur noch die CSV-File ge�ffnet. Spart bei einer gro�en Logfile
# (ca. 5 MB) gute 10 Sekunden alleine bei der Auswertung!
$archiv			= TRUE;
$auto_refresh	= TRUE;		// Automatisches neuschreiben des Archivs wenn die index.php oder das Telefonbuch ge�ndert wurde

# Eigent�mer der Anschl�sse
$owner[]		= array();

$owner[0]		= "Alle Anrufe";
$owner[1]		= "Person 1 (907071234)";
$owner[2]		= "Person 2 (907072345)";
$owner[3]		= "Eltern (907073456)";	
$owner[4]		= "Person 3 (907074567)";


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

# Anschluss Person 3
$amt[] 			= 907074567;
$amt_name[]		= "Person 3<br />(907074567)";
$amt_owner[]	= 4;

# Anschluss Person 3 (Gruppe)
$amt[] 			= 84;
$amt_name[]		= "Person 3<br />(907074567)";
$amt_owner[]	= 4;

# Anschluss Frei
$amt[] 			= 907072345;
$amt_name[]		= "Person 2<br />(907072345)";
$amt_owner[]	= 2;

# Anschluss Frei (Gruppe)
$amt[] 			= 85;
$amt_name[]		= "Person 2<br />(907072345)";
$amt_owner[]	= 2;

# Anschluss Person 1 Werkstatt
$amt[] 			= 9099427;
$amt_name[]		= "Wohnung<br />(9099427)";
$amt_owner[]	= 1;

# Anschluss Person 1 Werkstatt (Gruppe)
$amt[] 			= 86;
$amt_name[]		= "Wohnung<br />(9099427)";
$amt_owner[]	= 1;

# Anschluss Person 1 FAX
$amt[] 			= 907078901;
$amt_name[]		= "Person 1 FAX<br />(907078901)";
$amt_owner[]	= 1;

# Anschluss Person 1 Wohnung
$amt[] 			= 982066;
$amt_name[]		= "Werkstatt<br />(982066)";
$amt_owner[]	= 1;

# Anschluss Person 1 Wohnung (Gruppe)
$amt[] 			= 87;
$amt_name[]		= "Werkstatt<br />(982066)";
$amt_owner[]	= 1;

# Anschluss Eltern
$amt[] 			= 907073456;
$amt_name[]		= "Eltern<br />(907073456)";
$amt_owner[]	= 3;

# Anschluss Eltern (Gruppe)
$amt[] 			= 83;
$amt_name[]		= "Eltern<br />(907073456)";
$amt_owner[]	= 3;

# Anschluss Person 3 FAX
$amt[] 			= 907078888;
$amt_name[]		= "Person 3 FAX<br />(907078888)";
$amt_owner[]	= 4;

# Unbekannter Anrufer
$unbekannt		= array("TEL1", "TEL2", "Unbekannt", "R");

# Nebenstellen
#
$nebenstellen 	= array();

# Nebenstellen f�r Eigent�mer 0 <= nur ein Dummywert!!!! Keine Nebenstelleneintrag n�tig!!!!
$nebenstellen[0]= array();

# Nebenstellen f�r Eigent�mer 1
$nebenstellen[1]= array("10","11","12","15","16","17","32","33");

# Nebenstellen f�r Eigent�mer 2
$nebenstellen[2]= array("21","13");

# Nebenstellen f�r Eigent�mer 3
$nebenstellen[3]= array("22","35");

# Nebenstellen f�r Eigent�mer 4
$nebenstellen[4]= array("24","31","34");

?>