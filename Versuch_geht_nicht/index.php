<?php
################################################################
# Skript zum Auslesen der Syslogdatei einer Innovaphone IP800
# by Daniel Naetscher
################################################################

# Zeitmessung
$start	= microtime(1);

# Einfuegen der anderen Seiten
#
include('config/config.inc.php'); 			// Konfigurationsdatei
include('config/var_get.inc.php');  		        // Site zum holen der Varibalen aus der Adresse
include('config/functions.php');  		        // Funktionen 
include('config/javascript.php');  		        // Funktionen 
require(SMARTY_DIR.'Smarty.class.php');   	        // Smarty Class

# Varibalen definieren
#
$ausgabe		= array();
$files 			= array();
$open_call_ids          = array();
$calls                  = array();
$calls_ids              = array();
$calls_id               = 0;
$tabelle_a              = array();
$error                  = "";
$error_calls            = 0;

# Verarbeitung der Logfiles
#

# Logfiles finden
$files = func_find_logfiles($log['path']);

# Aktuelle Logfile auswaehlen
if ($logfile == "standard")
    {
        # Letzte Logfile auswaehlen
        $logfile = $files[0];
    }

# Ungueltige logfile
if (!in_array($logfile, $files) || !is_file($log['path']."/".$logfile))
    {
        # Fehlermeldung
        $error = "Logdatei ".$logfile." ist nicht vorhanden! Es wurde die letzte Logfile ausgewaehlt!";
        
        # Letzte Logfile auswaehlen
        $logfile = $files[0];
    }

# Listbox der Logfiles erstellen
$ausgabe['logfiles'] = func_listbox_logfiles($files, $logfile, $_owner);

# Listbox Eigentuemer erzeugen
$ausgabe['owner']   = func_listbox_owner($owner, $_owner, $logfile);

# Telefonbuch einlesen
$phonebook = func_telebuch($telbuch);

# Logdatei einlesen
#
$fp = fopen($log['path']."/".$logfile,"r");
while(!feof($fp))
    {
        # Inhalt der Zeile der Logdatei uebergeben
        $line = fgets($fp,200);
                
        # ID des Anrufes, wichtig bei gleichzeitigen Gespraechen
        $call_id = func_call_id($line);
        
        # Telefonanruf beginnt
        if (preg_match('/setup->/', $line))
            {
                # IP Adresse
                $call_ip = func_call_ip($line);
                
                # Uhrzeit des Anrufes ermitteln
                $call_time = func_call_time($line);
                
                # Telefonnummern
                $call_numbers = func_call_numbers($line);
                $call_sor_number = $call_numbers[0];
                $call_dest_number = $call_numbers[1];
                
                # ID des Anrufes uebergeben (offene Anrufe)
                array_push($open_call_ids, $call_id);
                
                # Anzahl der Anrufe um 1 erhoehen
                $calls_id++;
                
                # ID des Anrufes der eigentlichen ID zuordnen
                $calls_ids[$call_id] = $calls_id;
                
                # Werte uebergeben
                $calls[$calls_id]['ip']             = $call_ip;
                $calls[$calls_id]['begin']          = $call_time;
                $calls[$calls_id]['end']            = "";
                $calls[$calls_id]['duration']       = "";
                $calls[$calls_id]['accepted']       = FALSE;
                $calls[$calls_id]['accepted_nst']   = "";
                $calls[$calls_id]['source']         = $call_sor_number;
                $calls[$calls_id]['destination']    = $call_dest_number;
                $calls[$calls_id]['type']           = func_call_type($call_sor_number, $call_dest_number);
                $calls[$calls_id]['finished']       = FALSE;

                
                # Variablen loeschen
                unset($call_ip, $call_time, $call_numbers, $call_sor_number, $call_dest_number);
            }
            
        # Telefonanruf angenommen
        if (preg_match('/<-conn/', $line) && in_array($call_id, $open_call_ids))
            {
                # eigentliche Call-ID herrausfinden
                $tmp_call_id = $calls_ids[$call_id];
                
                # Nebenstelle, die den Anruf angenommen hat
                $call_numbers = func_call_numbers($line);
                if($calls[$tmp_call_id]['type'] == "in")
                    {
                        $tmp_accepted_num = $call_numbers[1];;
                    }
                  elseif($calls[$tmp_call_id]['type'] == "out")
                    {
                        $tmp_accepted_num = $call_numbers[0];;
                    }
                
                # Werte Uebergeben
                $calls[$tmp_call_id]['accepted']       = TRUE;
                $calls[$tmp_call_id]['accepted_nst']   = $tmp_accepted_num;
                
                # Variablen loeschen
                unset($tmp_call_id, $call_numbers, $tmp_accepted_num);
            }
            
        # Telefonanruf endet
        if (preg_match('/rel->/', $line) && in_array($call_id, $open_call_ids))
            {
                # eigentliche Call-ID herrausfinden
                $tmp_call_id = $calls_ids[$call_id];
                
                # Uhrzeit des Anrufes ermitteln
                $call_time = func_call_time($line);
                
                # Werte in einen Array packen
                $calls[$tmp_call_id]['end']            = $call_time;
                $calls[$tmp_call_id]['duration']       = func_calc_call_time($calls[$tmp_call_id]['begin'], $calls[$tmp_call_id]['end']);
                
                # Anruf ID entfernen (offene Anrufe)
                $tmp_key = array_search($call_id, $open_call_ids);
                unset($open_call_ids[$tmp_key]);
                $open_call_ids = array_values($open_call_ids);
                
                # Anruf ID entfernen (ID Zuordnung)
                unset($calls_ids[$call_id]);
                $calls_ids = array_values($calls_ids);
                
                # Anruf beendet
                $calls[$tmp_call_id]['finished']      = TRUE;
                
                # Ueberpruefen ob die Ziel-Telefonnummern gefunden wurde
                if(empty($calls[$tmp_call_id]['destination']))
                    {
                        # Zielnummer nicht vorhanden
                        
                        # Telefonnummern finden
                        $call_numbers = func_call_numbers($line);
                        $call_dest_number = $call_numbers[1];
                        
                        if(!empty($call_dest_number))
                            {
                                # Telefonnummer gefunden
                                # -> Bei ISDN nach extern
                                $calls[$tmp_call_id]['destination']    = $call_dest_number;
                                $calls[$tmp_call_id]['type'] = "out";
                            }
                    }
                
                # Variablen loeschen
                unset($tmp_call_id, $call_time, $tmp_key, $call_dest_number, $call_numbers);
            }
    }

# Datei schlieﬂen
fclose($fp);


# Verarbeitung der Anrufe
#

# Anzahl der Anrufe
$ausgabe['anrufe']      = count($calls);

# Anruf Array umdrehen
$calls                  = array_reverse($calls);

# Ausgabe der Anrufe vorbereiten
for($i = 0; $i < count($calls); $i++)
    {
        # Eigentuemer des Anrufes ermitteln
        if(!func_check_owner($_owner, $calls[$i]['type'], $calls[$i]['source'], $calls[$i]['destination']))
            {
                # Ausgewaelter Eigentuemer stimmt nicht mit dem Eigentuemer des Anrufes ueberein
                
                # Anzahl der Anrufe um 1 verringern 
                $ausgabe['anrufe']--;
                
                # -> Ausgabe dieses Anrufes abbrechen
                continue;
            }
        
        # Ueberpruefen ob der Anruf nicht beendet ist oder der Typ unbekannt ist
        if(!$calls[$i]['finished'] || $calls[$i]['type'] == "unknown")
            {
                # Anzahl der Anrufe um 1 verringern 
                $ausgabe['anrufe']--;
                
                # Anruf nicht beendet bzw fehlerhaft
                $error_calls++;

                # -> Ausgabe dieses Anrufes abbrechen
                continue;
            }
        
        # Telefonnummern den richtigen Spalten in der Tabelle zuordnen
        if ($calls[$i]['type'] == "int")
            {
                # Internes Gespraech
                #
                # Spalte Anrufer
                $tabelle_a[$i]['sor_telenr']	= "";
                # Spalte eingehende MSN
                $tabelle_a[$i]['amt']		= "";
                # Spalte Intern
                $tabelle_a[$i]['int']		= $calls[$i]['source'];
                # Spalte gewaelte Nummer
                $tabelle_a[$i]['dest_telenr']	= func_telbuch_search($calls[$i]['destination'], $phonebook);
            }
          elseif ($calls[$i]['type'] == "in")
            {
                # eingehendes Gespraech
                #
                # Spalte Anrufer
                $tabelle_a[$i]['sor_telenr']	= func_telbuch_search($calls[$i]['source'], $phonebook);
                # Spalte eingehende MSN
                $tmp_msn                        = func_msn_search($calls[$i]['destination']);
                $tabelle_a[$i]['amt']		= $tmp_msn['name'];
                unset($tmp_msn);
                # Spalte Intern
                $tabelle_a[$i]['int']		= $calls[$i]['accepted_nst']; 
                # Spalte gewaelte Nummer
                $tabelle_a[$i]['dest_telenr']	= "";
            }
          elseif ($calls[$i]['type'] == "out")
            {
                # ausgehendes Gespraech
                #
                # Spalte Anrufer
                $tabelle_a[$i]['sor_telenr']	= "";
                # Spalte eingehende MSN
                $tabelle_a[$i]['amt']		= "";
                # Spalte Intern
                $tabelle_a[$i]['int']		= $calls[$i]['source']; 
                # Spalte gewaelte Nummer
                $tabelle_a[$i]['dest_telenr']	= func_telbuch_search($calls[$i]['destination'], $phonebook);
            }
          else
            {
                # Sonstiges Gespraech
                #
                # Spalte Anrufer
                $tabelle_a[$i]['sor_telenr']	= func_telbuch_search($calls[$i]['source'], $phonebook);
                # Spalte eingehende MSN
                $tabelle_a[$i]['amt']		= "";
                # Spalte Intern
                $tabelle_a[$i]['int']		= ""; 
                # Spalte gewaelte Nummer
                $tabelle_a[$i]['dest_telenr']	= func_telbuch_search($calls[$i]['destination'], $phonebook);
            }
            
        # Anruf angenommen?
        if ($calls[$i]['accepted'])
            {
                # Anruf angenommen
                $tabelle_a[$i]['typ']		= "A";
            }
          else
            {
                # Anruf nicht angenommen
                $tabelle_a[$i]['typ']		= "";
            }
            
        # Anrufdauer
        $tabelle_a[$i]['dauer']           = $calls[$i]['duration'];
        # Anrufuhrzeit
        $tabelle_a[$i]['uhrzeit']         = $calls[$i]['begin'];
    }

# Zeitmessung
$zeit	=  round(microtime(1) - $start, 3);

# Smartyausgabe vorbereiten
#
# Variablen uebergeben
$smarty = new Smarty;   
$smarty->assign('listbox', $ausgabe['logfiles']); 					// Auswahlbox ¸bergeben
$smarty->assign('listbox_owner', $ausgabe['owner']);                                    // Auswahlbox Eigentuemer
$smarty->assign('inhalte', $tabelle_a);    						// Tabelleninhalt (Telefongespr‰che etc.) ¸bergeben
//$smarty->assign('inhalt_file', $ausgabe['inhalt_file']); 				// Logfile ¸bergeben
$smarty->assign('logfile', func_logfile_name($logfile));	                        // Aktuelle Logfilename ¸bergeben
$smarty->assign('anrufe', "Anzahl der der Anrufe: ".$ausgabe['anrufe']);		// Anzahl der Anrufe ¸bergeben
$smarty->assign('zeit', $zeit);								// Zeit uebergeben
$smarty->assign('jahr', date("Y"));							// Aktuelles Jahr uebergeben
$smarty->assign('archiv', "");						                // Archivtext uebergeben
$smarty->assign('error', $error);				                        // Fehlermeldung uebergeben
$smarty->assign('refresh_link', "");						        // Refresh-Link
$smarty->assign('owner', $owner[$_owner]);				                // Eigent¸mer ausgeben
$smarty->assign('index_link', "");				                        // Link auf dem Bild
$smarty->assign('double', "Fehlerhafte/Unvollst&auml;ndige Anrufe: ".$error_calls);	// Link auf dem Bild
$smarty->assign('java', $java_script);						        // Javascript

# Template ausgeben
$smarty->display('anruferliste.tpl');							// Template angeben
?>