<?
# Hauptscript für das Auslesen einer Syslogfile der Inovaphone IP400
#
####################################################################
#  Skript by:                                                      #
#  Daniel Nätscher                                                 #
####################################################################

# Zeitmessung
$start	= microtime(1);

# Einfügen der anderen Seiten
include('config/config.inc.php'); 			// Konfigurationsdatei
include('config/var_get.inc.php');  		// Site zum holen der Varibalen aus der Adresse
require(SMARTY_DIR.'Smarty.class.php');   	// Smarty Class

# Globale Varibalen definieren
$ausgabe		= array();
$w_archiv		= FALSE;
$use_archiv 	= FALSE;
$go_archiv 		= FALSE;
$archiv_text	= "Archiv: <font color=\"#FF0000\">unbenutzt</font>";
$count_double	= 0;

# Durchsuchen des Ordners nach möglichen Logfiles
#

# Variablen definieren
$files 				= array();

# Öffnen des Verzeichnisses
$dp 	= opendir ($log['path']);

# Durchlaufen des Verzeichnisses
while ($file = readdir ($dp)) 
	{
    if ($file != "." && $file != ".." && is_file($log['path']."\\".$file)) 
		{
		# Alle Dateiname in einen Array schreiben
        array_push($files, $file);
    	}
	}

# Verzeichniss schließen
closedir($dp);

if ($archiv)
	{
	# Durchsuchen des Archivordners nach möglichen Logfiles
	#
	
	# Variablen definieren
	$files_archiv 		= array();
	
	# Öffnen des Verzeichnisses
	$dp_archiv 	= opendir ($archiv_pfad);
	
	# Durchlaufen des Archivverzeichnisses 
	while ($file_archiv = readdir ($dp_archiv)) 
		{
		if ($file_archiv != "." && $file_archiv != ".." && is_file($archiv_pfad."\\".$file_archiv)) 
			{
			# Alle Dateiname in einen Array schreiben
			array_push($files_archiv, $file_archiv);
			}
		}
	
	# Verzeichniss schließen
	closedir($dp_archiv);
	
	# Arrays der Logdateien verbinden und sortieren
	#
	# Logdatei-Arrays verbinden
	$files		= array_merge($files,$files_archiv);
	
	# Doppelte Einträge in dem Logdatei-Array entfernen
	$files		= array_unique($files);
	
	# Logdatei-Arry sortieren
	natcasesort($files);
	
	# Indexe wieder in die richtige Reihenfolge bringen und den Array umdrehen
	$files		= array_values(array_reverse($files));
	}
  else
  	{
	$archiv_text= "Archiv: <font color=\"#FF0000\">ausgeschaltet</font>";
	}

# Logdatei bestimmen, die angezeigt werden soll
#
	
# Überprüfen ob eine Logfile ausgewählt wurde, wenn nicht wird die aktuelle genommen
if ($logfile == "standard")
	{
	# Aktuellen Logfilenamen zusammensetzen
	$logfile = "IP400_".date("Y-m-d").".txt";
	
	# Überprüfen ob Logfile nicht vorhanden ist, wenn ja die letzte Log auswählen
	if (!in_array($logfile, $files))
		{
		# Logfile auswählen
		$log_num = 0;
		$logfile = $files[$log_num];
		}
	}

if (!in_array($logfile, $files))
	{	
	# Überprüfen ob Logfile nicht vorhanden ist, wenn ja die letzte Log auswählen
	#
	
	# Fehlermeldung zuweisen
	$error	 = "Die von Ihnen angegebene Logfile ".$logfile." existiert nicht! Es wurde die aktuelleste Logfile ausgew&auml;lt!";
	
	# Logfile auswählen
	$log_num = sizeof($files)-1;
	$logfile = $files[$log_num];
	}

# Listbox (Logfiles)erzeugen
#

# Listbox öffnen
$ausgabe['listbox']	= "<form method=\"get\" action=\"index.php\">\n<input type=\"hidden\" name=\"_owner\" value=\"".$_owner."\">\n<select name=\"logfile\"  onChange=\"javascript:this.form.submit();\">\n";

# Schleife
for ($i = 0; $i<count($files); $i++)
	{
	if ($files[$i] == $logfile)
		{
		# Aktuelle Logfile als "ausgewählt" kennzeichnen
		$selected = " SELECTED ";
		}
	  else
	  	{
		$selected = " ";
		}
		
	# Anzeigename
	$datum 						= split('[-]', substr($files[$i],6,10));
	$file_name 					= date("d M Y", mktime(0,0,0,$datum[1],$datum[2],$datum[0]));
	$ausgabe['lst'][$files[$i]]	= $file_name;
	$ausgabe['listbox']    	   .= "<option".$selected."value=\"".$files[$i]."\">".$file_name."</option>\n";
	}
# Listbox (Logfiles) schließen
$ausgabe['listbox'] .= "</select>\n</form>\n";

# Listbox (Eigentümer) erzeugen
#
$ausgabe['listbox_e']	= "<form method=\"get\" action=\"index.php\">\n<input type=\"hidden\" name=\"logfile\" value=\"".$logfile."\">\n<select name=\"_owner\"  onChange=\"javascript:this.form.submit();\">\n";

# Schleife
for ($i = 0; $i<count($owner); $i++)
	{
	if ($_owner == $i)
		{
		# Aktueller Eigentümer als "ausgewählt" kennzeichnen
		$selected = " SELECTED ";
		}
	  else
	  	{
		$selected = " ";
		}
	# Anzeige
	$ausgabe['listbox_e']    	   .= "<option".$selected."value=\"".$i."\">".$owner[$i]."</option>\n";
	}

# Listbox (Eigentümer) schließen
$ausgabe['listbox_e'] .= "</select>\n</form>\n";	

# Wenn die ausgewählte Logfile älter als das aktuelle Datum ist -> Archivfunktion aktivieren
# 

# Funktion aktiviert
if ($archiv)
	{
	# Datum der Logfile ermitteln
	$datum_log		= split('[-]', substr($logfile,6,10));
	$datum_log 		= mktime(0,0,0,$datum_log[1],$datum_log[2],$datum_log[0]);
	$datum_diff		= time() - $datum_log;
	
	if ($datum_diff > 86400)
		{
		if (!file_exists($archiv_pfad."\\".$logfile))
			{
			# Wenn die Archivdate nicht vorhandne ist
			$w_archiv	= TRUE;		// Archiv wird geschrieben
			$use_archiv = FALSE;	// Archiv wird nicht benutzt
			
			# Refreshlink zusammenbauen
			$refresh_link = "&nbsp;";
			}
		  else
		  	{
			# Wenn die Archivdatei vorhanden ist 
			$w_archiv	= FALSE; 	// Archiv wird nicht geschrieben
			$use_archiv = TRUE;		// Achiv wird benutzt
			
			# Überprüfen ob die Orginal Logfile noch vorhanden ist
			if (file_exists($log['path']."\\".$logfile))
				{
				# Wenn die Orginal Logfile noch vorhandne ist
				# => Neu abspeichern ist erlaubt
				
				# Überprüfen ob die Scriptdatei / Telefonbuch sich geändert hat 
				# => Unbedingt neu abspeichern
				
				# Änderungsdatum der index.php und des Telefonbuches ermitteln
				$log_time			= fileatime($archiv_pfad."\\".$logfile);
				$diff_files 		= $log_time - getlastmod();
				$diff_telbuch		= $log_time - fileatime($telbuch);
				$diff_config		= $log_time - fileatime('config/config.inc.php');
				
				# Überprüfung der Zeitunterschiede
				if ($auto_refresh && ($diff_files < 0 || $diff_telbuch < 0 || $diff_config < 0))
					{
					$w_archiv	= TRUE; 	// Archiv wird geschrieben
					$use_archiv = FALSE;	// Achiv wird nicht benutzt
					
					# Refreshlink zusammenbauen
					$refresh_link = "<font color=\"#FF0000\">Archiv wurde automatisch neu gespeichert!</font>";
					}
				  else
				  	{
					# Refreshlink zusammenbauen
					$refresh_link = "<a href=\"?logfile=".$logfile."&refresh=TRUE\">Archiv neu abspeichern</a>";
					}
				}
			  else
			  	{
				# Wenn die Orginal Logfile nicht mehr vorhandne ist
				# => Neu abspeichern ist nicht erlaubt
				
				# Refreshlink zusammenbauen
				$refresh_link = "<font color=\"#FF0000\">Archiv kann nicht mehr neu abspeichert werden, da die orginal Logfile nicht mehr vorhanden ist!</font>";
				}
			}
		}
	# Refreshlink zusammenbauen
	# $refresh_link = "<a href=\"?logfile=".$logfile."&refresh=TRUE\">Archiv neu abspeichern</a>";
	}
  else
  	{
	# Refreshlink zusammenbauen
	$refresh_link = "&nbsp;";
	}

# Überprüfen ob das Archiv neu geschrieben werden soll
#
if ($use_archiv && $refresh)
	{
	# Alte Archivdatel löschen
	if (file_exists($log['path']."\\".$logfile))
		{
		# Alte Archivdatel löschen
		@unlink($archiv_pfad."\\".$logfile);
		# Wenn das Archiv neu geschrieben werden soll
		$w_archiv	= TRUE;		// Archiv wird geschrieben
		$use_archiv = FALSE;	// Archiv wird nicht benutzt
		}
	  else
	  	{
		$w_archiv	= FALSE;	// Archiv wird nicht geschrieben
		$use_archiv = TRUE;		// Archiv wird benutzt
		# Refreshlink zusammenbauen
		$refresh_link = "<font color=\"#FF0000\">Archiv konnte nicht mehr neu abspeichert werden! Es trat ein Fehler auf!</font>";
		}
	}

# Überprüfung ob das Archiv benutzt wird
# 
if (!$use_archiv)
	{
	# Normale Syslogdatei benutzen
	#
	
	# Telefonbuch auslesen
	#
	
	# Variablen definieren
	$tel_eintrag['nummer'] 	= array();
	$tel_eintrag['name']	= array();
	
	# Datei einlesen
	$fp = fopen($telbuch,"r");
	while($eintrag = fgetcsv($fp,500,";"))
		{
		array_push($tel_eintrag['nummer'],$eintrag[0]);
		array_push($tel_eintrag['name'],$eintrag[1]);	
		}
		
	# Archiv öffnen
	#
	if ($archiv && $w_archiv)
		{
		# Archivdatei öffnen
		$fp_archiv = fopen ($archiv_pfad."\\".$logfile,"w");
		$go_archiv = TRUE; // Archiv zum schreiben freigegeben
		}
		
	# Logdatei auswerten
	#
	
	# Variablen definieren
	$call_int 		= FALSE;
	$call_ext 		= FALSE;
	$call_in		= FALSE;
	$call_out		= FALSE;
	$new_call	 	= FALSE;
	$write_call		= FALSE;
	$line_counter	= 0;
	$counter		= 0;
	$call_number	= 0;
	$call_dest		= array();
	$call_owner		= array();
	$tabelle 		= array();
	$uhrzeit_beginn = array();
	$uhrzeit_ende	= array();
	$dauer			= array();
	
	# Logdatei einlesen
	$fp = fopen($log['path']."\\".$logfile,"r");
	while(!feof($fp))
		{
		$line 					 = fgets($fp,200);  
		$ausgabe['inhalt_file'] .= $line."<br>";
		# Auslesen des Start bzw. Endmerkmals
		$marker					 = ltrim(rtrim(substr($line,52,5)));
		
		# Startmarker überprüfen 
		if ($marker == "Alloc")
			{
			# Startmarkter gefunden
			#
			
			# Neuer Anruf
			$new_call	 					= TRUE;
			# Anruf schreiben
			$write_call						= FALSE;
			# Nummer des Anrufes, wichtig bei gleichzeitigen Gesprächen
			$call_number					= substr($line,50,1);
			$count_in_new_call[$call_number]= 0;
			# Startuhrzeit des Anrufes ermitteln
			$uhrzeit_beginn[$call_number]	= split('[:]', substr($line,10,9));
			# Anzahl der Anrufe um Eins erhöhen
			$call_numbers++;
			}
		  elseif ($marker == "Free" && $new_call)
			{
			# Endmerker gefunden
			#
			
			# Anruf ausgeben
			$write_call						= TRUE;
			# Nummer des Anrufes ermitteln
			$close_call						= substr($line,50,1);
			# Enduhrzeit ermitteln
			$uhrzeit_ende[$close_call]		= split('[:]', substr($line,10,9));
			# Anzahl der Anrufe um Eins verringern
			$call_numbers--;
			
			# Überprüfen ob noch anrufe in der "Warteschlange" sind
			if ($call_numbers > 0)
				{
				# Anrufe noch nicht beendet
				#
				
				# Neuer Anruf
				$new_call	 				= TRUE;
				}
			  else
				{
				# Alle Anrufe beendet
				#
				
				# Neuer Anruf
				$new_call	 				= FALSE;
				}
			}
		
		# Ein neuer Anruf
		# -> Daten auswerten
		if ($new_call)
			{
			# Aktuelle Nummer des Anrufes
			$akt_call_number					= substr($line,50,1);
			
			# Anruf angenommen
			if (ereg("Connect",$line) && $call_art[$akt_call_number] != "A") 
				{
				$call_art[$akt_call_number] = "A";
				}
			
			# Neuer Anruf vorhanden
			if ($new_call)
				{
				# Überprüfungen
				#
				
				# String Teilen
				#	
							
				# Aktuelle Anrufnummer ermitteln
				$count_in_new_call[$akt_call_number]++; 
				
				# Telefonnummer ermitteln (in der 2. Zeile)
				if ($count_in_new_call[$akt_call_number] == 2)
					{
					# Zieltelefonnummer ermitteln
					#
					
					$call_dest_tmp					= strchr($line,"CALL");
					if (!empty($call_dest_tmp))
						{
						# Nummer finden
						$call_dest_tmp					= split("->", $call_dest_tmp);		// bei -> teilen				
						$call_dest_tmp					= split("[/]", $call_dest_tmp[1]);	// den 2. Teil bei / teilen
						# Nur Nummer anzeigen wenn Namen uebermittelt wurde
						$call_dest_tmp_2				= strchr($call_dest_tmp[0], ":");
										
						if (!empty($call_dest_tmp))
							{
							$call_dest_tmp				= split(":", $call_dest_tmp[0]);		// bei : teilen					
							}
						$call_dest[$akt_call_number]	= rtrim($call_dest_tmp[0]);			// den 1. Teil von rechts die Leerstellen kürzen 
						}
					
					# Überprüfen ob ein Nummer gefunden wurde
					if (!empty($call_dest[$akt_call_number]))
						{
						$next_new_call					= FALSE;
						$dest_number[$akt_call_number]	= TRUE;
						}
					  else
						{
						# Keine Nummer gefunden => in der nächsten Zeile nochmals suchen
						$next_new_call					= TRUE;
						}
					
					# Anrufernummer ermitteln
					#
					
					$call_sor_tmp						= strchr($line,"PPP");
					$sor_number[$akt_call_number]		= TRUE;
					if (!empty($call_sor_tmp))
						{
						# Anrufer von extern ermitteln
						$call_sor_tmp				= split("[:]", $call_sor_tmp);
						$call_sor[$akt_call_number]	= $call_sor_tmp[1];
						if (empty($call_sor[$akt_call_number]))
							{
							$call_sor[$akt_call_number] = "Unbekannt";
							}
						}
					  else
						{
						$call_sor_tmp						= strchr($line,"GW"); 
						if (!empty($call_sor_tmp))
							{
							# Anrufer ermitteln
							$call_sor_tmp					= split("->", $call_sor_tmp);
							$call_sor_tmp					= split("[:]", $call_sor_tmp[0]);
							$call_sor[$akt_call_number]		= $call_sor_tmp[1];
							}
						  else
							{
							$call_sor_tmp					= strchr($line,"TEL");
							if (!empty($call_sor_tmp))
								{
								# Nebenstelle ermitteln wenn nur der Hoerer abgenommen wurde
								$call_sor_tmp					= split("->", $call_sor_tmp);
								$call_sor_tmp					= split("[:]", $call_sor_tmp[0]);
								$call_sor[$akt_call_number]	= $call_sor_tmp[1];
								}
							  else
								{
								$call_sor[$akt_call_number]	= $call_sor_tmp[0];
								}
							}
						}
					}
				  elseif ($next_new_call)
					{
					$call_dest_tmp					= strchr($line,"Connect");
					if (!empty($call_dest_tmp))
						{
						# Zielrufnummer finden
						$call_dest_tmp					= split("->", $call_dest_tmp);						
						$call_dest_tmp					= split("[/]", $call_dest_tmp[1]);
						$call_dest_tmp					= split(" ", $call_dest_tmp[0]);
						$call_dest[$akt_call_number]	= rtrim($call_dest_tmp[0]);
						}
					
					if (!empty($call_dest[$akt_call_number]))
						{
						$next_new_call					= FALSE;
						$dest_number[$akt_call_number]	= TRUE;
						}
					  else
						{
						$next_new_call					= TRUE;
						}
					}
				}
			}
		
		# Anruf ausgeben
		if ($write_call)
			{				
			# Counter erhöhen
			$counter++;
			$tmp_dest_suche = "";
			
			# Dauer ermitteln
			$uhrzeit_beginn[$close_call] 	= mktime($uhrzeit_beginn[$close_call][0],$uhrzeit_beginn[$close_call][1],$uhrzeit_beginn[$close_call][2],1,1,2006);
			$uhrzeit_ende[$close_call] 		= mktime($uhrzeit_ende[$close_call][0],$uhrzeit_ende[$close_call][1],$uhrzeit_ende[$close_call][2],1,1,2006);
			$dauer[$close_call] 			= $uhrzeit_ende[$close_call] - $uhrzeit_beginn[$close_call];
			
			# Anrufart ermitteln
			#
			
			# Anruf nach extern
			$tmp_call_art	= substr($call_dest[$close_call],0,1);
			if ($tmp_call_art == "0")
				{
				$check_int	= TRUE;
				}
			  else
				{
				$check_int	= FALSE;
				}
			
			# Ausgehender Anruf
			if ($check_int)
				{						
				$call_int[$close_call] 			= $call_sor[$close_call];
				$call_sor[$close_call] 			= "";
				}
			  else
				{
				$call_int[$close_call] 			= "";
				}
				
			# Eingehender Anruf
			if (in_array($call_dest[$close_call], $amt))
				{
				$x = array_search($call_dest[$close_call], $amt);
				$call_amt[$close_call]			= $amt_name[$x];
				$call_dest[$close_call]			= "";
				
				# Eigentümer ermitteln
				$call_owner[$close_call]		= $amt_owner[$x];
				$check_amt						= TRUE;
				}
			  else
				{
				$call_amt[$close_call]			= "";
				$check_amt						= FALSE;
				}
				
			# Anrufername ermitteln (sor_nummer)
			# Telefongespräche:
			# - von intern nach intern
			# - von extern
			if (!$check_int && !empty($call_sor[$close_call]))
				{
				if (in_array($call_sor[$close_call], $tel_eintrag['nummer']))
					{ 
					$x = array_search($call_sor[$close_call], $tel_eintrag['nummer']);
					$call_sor[$close_call]			= "<a href=\"telebuch.php?nr=".$tel_eintrag['nummer'][$x]."\" target=\"_blank\">".$tel_eintrag['name'][$x]." -></a><br />".$tel_eintrag['nummer'][$x]."";
					}
				  elseif (in_array($call_sor[$close_call], $unbekannt))
					{
					$call_sor[$close_call]			= "Unbekannter Anrufer ->";
					}
				  elseif ($call_sor[$close_call] <= 100)
					{					
					$call_int[$close_call] = $call_sor[$close_call];
					$call_sor[$close_call] = "";
					}
				  else
				  	{
					$call_sor[$close_call]			= "<a href=\"telebuch.php?nr=".$call_sor[$close_call]."\" target=\"_blank\">".$call_sor[$close_call]." -></a>";
					}
				}
			
			# Anrufername ermittel (Externes Ziel)
			# Telefongespräche:
			# - von intern nach extern
			if ($tmp_call_art == "0" && !empty($call_dest[$close_call]))
				{
				# Namensauflösung der gewählten Nummer ermitteln
				if (!$check_amt)
					{
					$tmp_dest_suche						= substr($call_dest[$close_call],1,strlen($call_dest[$close_call]));
					if (in_array($tmp_dest_suche, $tel_eintrag['nummer']))
						{
						$x = array_search($tmp_dest_suche, $tel_eintrag['nummer']);
						$call_dest[$close_call]			= "<a href=\"telebuch.php?nr=".$tel_eintrag['nummer'][$x]."\" target=\"_blank\">".$tel_eintrag['name'][$x]."</a><br />".$tel_eintrag['nummer'][$x]."";
						}
					  else
						{
						$call_dest[$close_call]			= "<a href=\"telebuch.php?nr=".$tmp_dest_suche."\" target=\"_blank\">".$tmp_dest_suche."</a>";
						}
					}
				}
			  elseif (!empty($call_dest[$close_call]))
			  	{
				# Namensauflösung interner Nebenstellen
				if (in_array($call_dest[$close_call], $tel_eintrag['nummer']))
					{
					$x = array_search($call_dest[$close_call], $tel_eintrag['nummer']);
					$call_dest[$close_call]			= "<a href=\"telebuch.php?nr=".$tel_eintrag['nummer'][$x]."\" target=\"_blank\">".$tel_eintrag['name'][$x]."</a><br />".$tel_eintrag['nummer'][$x]."";
					}
				  else
					{
					$call_dest[$close_call]			= "<a href=\"telebuch.php?nr=".$call_dest[$close_call]."\" target=\"_blank\">".$call_dest[$close_call]."</a>";
					}
				}
				
			# Überprüfen des Eigentümers für interne Nummern
			#
			if (!empty($call_int[$close_call]))
				{
				# Eigentümer ermitteln 				
				$found_owner					= FALSE;
				
				# Alle Nebenstellenarray überprüfen
				for ($i = 1; $i < count($nebenstellen); $i++)
					{
					if (in_array($call_int[$close_call], $nebenstellen[$i]))
						{
						# Gefunden!!!
						$call_owner[$close_call]= $i;
						$found_owner			= TRUE;
						# Schleife verlassen
						break;
						}
					}
				
				# Für den Fall, dass kein Eigentümer gefunden wurde
				if (!$found_owner)
					{
					$call_owner[$close_call]	= 1;
					}
				
				# Einen Pfeil am ende der Ausgabe anhängen	
				$call_int[$close_call] .= " ->";
				}
					
			if ((!empty($call_int[$close_call]) && !empty($call_dest[$close_call])) || (!empty($call_sor[$close_call]) && !empty($call_amt[$close_call])))
				{
				# Überprüfen ob ein Anruf zur gleichen Zeit eingetragen wurde
				# -> Doppelter anruf wird entfernt
				#
				# Variable setzten
				$double_call		= FALSE;
				# Funktion eingeschaltet
				if ($rem_double)
					{					
					# Variable setzten
					$double_call		= FALSE;
					$counter_prev		= $counter -1;
					$tmp_uhrzeit_begin	= date("H:i:s", $uhrzeit_beginn[$close_call]);
					$tmp_uhrzeit_begin1	= date("H:i:s", $uhrzeit_beginn[$close_call] - 1);
					# Überprüfen ob die Startuhrzeit und Telefonnummern gleich ist
					if ((($tabelle[$counter_prev]['uhrzeit'] == $tmp_uhrzeit_begin) || ($tabelle[$counter_prev]['uhrzeit'] == $tmp_uhrzeit_begin1)) && (($tabelle_a[$counter_prev]['sor_telenr'] == $call_sor[$close_call]) || ($tabelle_a[$counter_prev]['dest_telenr'] == $call_dest[$close_call])))
						{
						# -> Anruf doppelt -> Nicht ausgeben!
						$double_call	= TRUE;		// Doppelter Anruf -> nicht ausgaben
						$count_double++;			// Anzahl doppelter Anrufe um 1 erhöhen
						}
					}
					
				# Türsprechstelle umwandeln
				#
				#
				if ($edit_door)
					{
					# Überprüfen ob die Nummer der Schnittstelle vorhanden ist (Interner Anrufer)
					if (ereg($door['nr'], $call_int[$close_call]))
						{
						# Türsprechstelle als Anrufer umwandeln
						$call_int[$close_call]		= "";
						$call_sor[$close_call]		= $door['text']." -><br />".$door['nr'];
						}
					}
										
				# Anruf ausgeben (Webseite und Archiv) außer es ist ein doppelter Anruf
				if(!$double_call)			
					{
					# Übergabe an die Varibalen
					$tabelle[$counter]['typ']			= $call_art[$close_call];
					$tabelle[$counter]['dauer']			= date("H:i:s", $dauer[$close_call] - 3600);
					$tabelle[$counter]['uhrzeit']		= date("H:i:s", $uhrzeit_beginn[$close_call]);
					$tabelle[$counter]['int']			= $call_int[$close_call];
					$tabelle[$counter]['sor_telenr']	= $call_sor[$close_call];
					$tabelle[$counter]['dest_telenr']	= $call_dest[$close_call];
					$tabelle[$counter]['amt']			= $call_amt[$close_call];
					$tabelle[$counter]['none']			= $call_owner[$close_call];
						
					# Archiv schreiben
					$write_line_archiv					= TRUE;
					
					# Tabelle für die Ausgabe vorbereiten
					if ($call_owner[$close_call] == $_owner || $_owner == 0)
						{
						$tabelle_a[$counter]['typ']			= $call_art[$close_call];
						$tabelle_a[$counter]['dauer']		= date("H:i:s", $dauer[$close_call] - 3600);
						$tabelle_a[$counter]['uhrzeit']		= date("H:i:s", $uhrzeit_beginn[$close_call]);
						$tabelle_a[$counter]['int']			= $call_int[$close_call];
						$tabelle_a[$counter]['sor_telenr']	= $call_sor[$close_call];
						$tabelle_a[$counter]['dest_telenr']	= $call_dest[$close_call];
						$tabelle_a[$counter]['amt']			= $call_amt[$close_call];
						$tabelle_a[$counter]['none']		= $call_owner[$close_call];
						}
					}
				}
			  else
			  	{
				# Archiv schreiben
				$write_line_archiv					= FALSE;
				}
				
			# Variablen für nächsten Durchlauf setzen 
			$count_in_new_call[$close_call]	= 0;
			$call_dest[$close_call]			= "";
			$call_sor[$close_call]			= "";
			$call_art[$close_call]			= "";
			$sor_number[$close_call]		= FALSE;
			$dest_number[$akt_call_number]	= FALSE;
			$write_call						= FALSE;
			
			# Archiv falls aktiviert schreiben
			#
			if ($go_archiv && $write_line_archiv)
				{
				# Inhalt zusammensetzen
				$text = $tabelle[$counter]['typ'].";".$tabelle[$counter]['dauer'].";".$tabelle[$counter]['uhrzeit'].";".$tabelle[$counter]['int'].";".$tabelle[$counter]['sor_telenr'].";".$tabelle[$counter]['dest_telenr'].";".$tabelle[$counter]['amt'].";".$tabelle[$counter]['none'].";\r\n";
				# Inhalt in die Datei schreiben
				fwrite($fp_archiv,$text);
				}
			}
		}
	fclose($fp);
	
	if ($go_archiv)
		{
		# Archivdatei schließen
		fclose($fp_archiv);
		if (file_exists($archiv_pfad."\\".$logfile))
			{
			$archiv_text		= "Archiv: <font color=\"#FF9900\">geschrieben</font>";
			}
		  else
			{
			$archiv_text		= "Archiv: <font color=\"#FF0000\">Fehler beim schreiben</font>";
			}
		}
	
	# Counter übergeben
	$ausgabe['anrufe']	= "Anzahl der der Anrufe: ".count($tabelle_a);
	
	# Sortieren nach der Uhrzeit
	#
	if (count($tabelle_a) > 0)
		{
		$sortArray = array(); 
		foreach($tabelle_a as $key => $array) 
			{ 
			$sortArray[$key] = $array['uhrzeit']; 
			} 
		array_multisort($sortArray, SORT_DESC, SORT_STRING, $tabelle_a);
		}
	}
  else
  	{
	# Archivdatei wird benutzt
	#
	
	# Counter festlegen
	$counter_archiv		= 0;
	
	# Text für Ausgabe festlegen
	$archiv_text		= "Archiv: <font color=\"#00CC33\">benutzt</font>";
	
	# Archivdatei wird geöffnet
	$fp = fopen($archiv_pfad."\\".$logfile,"r");
	while($eintrag = fgetcsv($fp,500,";"))
		{
		if ($eintrag[7] == $_owner || $_owner == 0)
			{
			# Variablen übergeben
			$tabelle_a[$counter_archiv]['typ']			= $eintrag[0];
			$tabelle_a[$counter_archiv]['dauer']			= $eintrag[1];
			$tabelle_a[$counter_archiv]['uhrzeit']		= $eintrag[2];
			$tabelle_a[$counter_archiv]['int']			= $eintrag[3];
			$tabelle_a[$counter_archiv]['sor_telenr']		= $eintrag[4];
			$tabelle_a[$counter_archiv]['dest_telenr']	= $eintrag[5];
			$tabelle_a[$counter_archiv]['amt']			= $eintrag[6];
			$tabelle_a[$counter_archiv]['none']			= $eintrag[7];
			
			# Counter um 1 erhöhen
			$counter_archiv++;
			}
		}
	# Datei schließen
	fclose($fp);
	
	# Sortieren nach der Uhrzeit
	#
	if (count($tabelle_a) > 0)
		{
		$sortArray = array(); 
		foreach($tabelle_a as $key => $array) 
			{ 
			$sortArray[$key] = $array['uhrzeit']; 
			} 
		array_multisort($sortArray, SORT_DESC, SORT_STRING, $tabelle_a);
		}
	
	# Counter übergeben
	$ausgabe['anrufe']	= "Anzahl der der Anrufe: ".count($tabelle_a);
	}
	
	# Ausgabe für doppelt eingetragene Anrufe erstellen
	if ($rem_double)
		{
		if ($count_double > 0)
			{
			# Anzahl größer als 0 -> Text rot
			$double_text	= "Anzahl doppelter Anrufe: <font color=\"#FF0000\">".$count_double."</font>";
			}
		  else
		  	{
			# Sonst Text grün
			$double_text	= "Anzahl doppelter Anrufe: <font color=\"#00CC33\">".$count_double."</font>";
			}
		}
	  else
	  	{
		# Funktion deaktiviert
		$double_text	= "Anzahl doppelter Anrufe: <font color=\"#FF0000\">deaktiviert</font>";
		}

# Zeitmessung
$zeit	=  round(microtime(1) - $start, 3);

# Ausgabe
#

# Werte übergeben
$smarty = new Smarty;   
$smarty->assign('listbox', $ausgabe['listbox']); 						// Auswahlbox übergeben
$smarty->assign('listbox_owner', $ausgabe['listbox_e']);				// Auswahlbox Eigentümer
$smarty->assign('inhalte', $tabelle_a);    								// Tabelleninhalt (Telefongespräche etc.) übergeben
//$smarty->assign('inhalt_file', $ausgabe['inhalt_file']); 				// Logfile übergeben
$smarty->assign('logfile', $ausgabe['lst'][$logfile]);					// Aktuelle Logfilename übergeben
$smarty->assign('anrufe', $ausgabe['anrufe']);							// Anzahl der Anrufe übergeben
$smarty->assign('zeit', $zeit);											// Zeit übergeben
$smarty->assign('jahr', date("Y"));										// Aktuelles Jahr übergeben
$smarty->assign('archiv', $archiv_text);								// Archivtext übergeben
$smarty->assign('error', "<br />".$error."<br /><br />");				// Fehlermeldung übergeben
$smarty->assign('refresh_link', $refresh_link);							// Refresh-Link
$smarty->assign('owner', $owner[$_owner]);								// Eigentümer ausgeben
$smarty->assign('index_link', "index.php?_owner=".$_owner);				// Link auf dem Bild
$smarty->assign('double', $double_text);								// Link auf dem Bild

# Template übergeben
$smarty->display('anruferliste.tpl');									// Template angeben
?>