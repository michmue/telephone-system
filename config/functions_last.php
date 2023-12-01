<?php
# Funktion um die Nummer des Anrufes in der Logdatei finden
function func_call_id($sting)
    {
	# Nummer des Anrufes, wichtig bei gleichzeitigen Gespraechen
        preg_match('/PBX0 ([\d]{1,2})/', $sting, $tmp_preg_array);
        $call_number    = $tmp_preg_array[1];
        
        unset($tmp_preg_array);
        return $call_number;
    }

# Funktion um die IP der Telefonanlage in der Logdatei zu finden    
function func_call_ip($sting)
    {
	preg_match('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/', $sting, $tmp_preg_array);
        $call_ip        = $tmp_preg_array[0];
        
        unset($tmp_preg_array);
        return $call_ip;
    }

# Funktion um die Zeit des Logeintrages in der Logdatei zu finden    
function func_call_time($sting)
    {
	# Uhrzeit des Anrufes ermitteln
        preg_match('/([\d]{1,2}):([\d]{1,2}):([\d]{1,2})/', $sting, $tmp_preg_array);
        $call_time      = $tmp_preg_array[0];
        
        unset($tmp_preg_array);
        return $call_time;
    }
 
# Funktion um die Anrufdauer zu berechnen    
function func_calc_call_time($start, $end)
    {
        # Zeiten zerlegen
        $uhrzeit_beginn		= split('[:]', $start);
        $uhrzeit_ende		= split('[:]', $end);
        
	# Dauer des Anrufes ermitteln
	$uhrzeit_beginn 	= @mktime($uhrzeit_beginn[0],$uhrzeit_beginn[1],$uhrzeit_beginn[2],1,1,2006);
        $uhrzeit_ende 		= @mktime($uhrzeit_ende[0],$uhrzeit_ende[1],$uhrzeit_ende[2],1,1,2006);
        $dauer 			= date("H:i:s", $uhrzeit_ende - $uhrzeit_beginn - 3600);
        
        unset($uhrzeit_beginn, $uhrzeit_ende);
        return $dauer;
    }

# Funktion um den Typ des anrufes zu bestimmen    
function func_call_type($source, $destination)
    {
        # Anzahl der Nummern ermitteln
        $len_source         = strlen($source);
        $len_destination    = strlen($destination);
        
        if($len_source == 2 && $len_destination == 2)
            {
                # Intern -> Intern
                $type = "int";
            }
          elseif($len_source == 2 && $len_destination > 2)
            {
                # Intern -> Extern
                $type = "out";
            }
          elseif(($len_source > 2 || $len_source == 1) && ($len_destination == 2 || $destination == 89004921435907075678))
          #elseif(($len_source > 2 && $len_destination == 2) || ($len_source > 2 && $destination == 89004921435907075678))
            {
                # Extern -> Intern bzw. SIP -> Intern
                $type = "in";
            }
          else
            {
                # Typ unbekannt (wie kann dass passieren?!?!)
                $type = "unknown";
            }
            
        unset($len_destination, $len_source);
        return $type;
    }

# Funktion um die Telefonnummern des Anrufes in der Logdatei zu finden    
function func_call_numbers($sting)
    {
	# Telefonnummern des Anrufes ermitteln
        preg_match_all('/\(.*?\)/', $sting, $tmp_preg_array);
        
        # Nummer 1 Klammern etc entfernen
        preg_match_all('/([0-9]+)/', $tmp_preg_array[0][0], $tmp_num_sor);
        $call_number[0] = $tmp_num_sor[0][0];
        
        # Nummer 2 Klammern etc entfernen
        preg_match_all('/([0-9]+)/', $tmp_preg_array[0][1], $tmp_num_dest);
        $call_number[1] = $tmp_num_dest[0][0];
        
        # Nummer 1: 0 bzw. 6 am Anfang entfernen
        preg_match_all('/([0-9])/', $call_number[0], $tmp_array);
        if ($tmp_array[0][0] == "0" || $tmp_array[0][0] == "6")
            {
                $call_number[0] = substr($call_number[0], 1);
            }
        
        # Nummer 2: 0 bzw 6 am Anfang entfernen
        preg_match_all('/([0-9])/', $call_number[1], $tmp_array);
        if ($tmp_array[0][0] == "0" || $tmp_array[0][0] == "6")
            {
                $call_number[1] = substr($call_number[1], 1);
            }
        
        unset($tmp_preg_array, $tmp_num_sor, $tmp_num_dest, $tmp_array);
        return $call_number;
    }

# Funktion um das Telefonbuch ein zu lesen    
function func_telebuch($file)
    {
        # Telefonbuch auslesen
	#
	
	# Variablen definieren
	$tel_eintrag['nummer'] 	= array();
	$tel_eintrag['name']	= array();
	
	# Datei einlesen
	$fp = fopen($file,"r");
	while($eintrag = fgetcsv($fp,500,";"))
		{
		array_push($tel_eintrag['nummer'],$eintrag[0]);
		array_push($tel_eintrag['name'],$eintrag[1]);	
		}
                
        # Datei schlieﬂen
	fclose($fp);
        
        unset($fp, $eintrag);
        return $tel_eintrag;
    }

# Funktion um eine Telefonnummer in Telefonbuch zu suchen    
function func_telbuch_search($number,$phonebook)
    {
        global $unbekannt;
        
        # Telefonnummer suchen
        if (in_array($number, $phonebook['nummer']))
            { 
                $x = array_search($number, $phonebook['nummer']);
                #$name = $phonebook['name'][$x]."<br>".$number."<img src=\"img/edit.gif\" alt=\"edit\" border=\"0\"/><img src=\"img/delete.gif\" alt=\"x\" border=\"0\"/>
                $name = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                            <tr>
                              <td width=\"80%\" align=\"left\"><u>".$phonebook['name'][$x]."</u></td>
                              <td width=\"20%\" align=\"right\"><a href=\"telebuch_edit.php?nr=".$number."&_name=".$phonebook['name'][$x]."\"  title=\"Telefonbucheintrag &auml;ndern\"  rel=\"gb_page_center[800, 300]\"><img src=\"img/edit.gif\" alt=\"edit\" width=\"12\" height=\"12\" border=\"0\"/></a>&nbsp;</td>
                            </tr>
                            <tr>
                              <td width=\"80%\" align=\"left\">".$number."</td>
                              <td width=\"20%\" align=\"right\"><a href=\"telebuch_del.php?nr=".$number."&_name=".$phonebook['name'][$x]."\"  title=\"Telefonbucheintrag l&ouml;schen\"  rel=\"gb_page_center[800, 300]\"><img src=\"img/delete.gif\" alt=\"x\" width=\"12\" height=\"12\" border=\"0\"/></a>&nbsp;</td>
                            </tr>
                        </table>";
            }
          elseif(in_array($number, $unbekannt))
            {
                $name = "Unbekannter Anrufer";
            }
          else
            {
                #$name = "<a href=\"telebuch.php?nr=".$number."\"  title=\"Telefonbucheintrag hinzuf&uuml;gen\"  rel=\"gb_page_center[800, 300]\">".$number."</a>";
                $name = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                            <tr>
                              <td width=\"80%\" align=\"left\">".$number."</td>
                              <td width=\"20%\" align=\"right\"><a href=\"telebuch.php?nr=".$number."\"  title=\"Telefonbucheintrag hinzuf&uuml;gen\"  rel=\"gb_page_center[800, 300]\"><img src=\"img/add.gif\" alt=\"edit\" width=\"12\" height=\"12\" border=\"0\"/></a>&nbsp;</td>
                            </tr>
                            <tr>
                              <td width=\"80%\" align=\"left\">&nbsp;</td>
                              <td width=\"20%\" align=\"right\">&nbsp;</td>
                            </tr>
                        </table>";
            }
            
        unset($x);
        return $name;
    }

# Funktion um die Bezeichnung einer MSN zu finden    
function func_msn_search($number)
    {
        global $amt, $amt_name, $amt_owner;
        if (in_array($number, $amt))
            { 
                $x = array_search($number, $amt);
                $msn['name'] = $amt_name[$x];
                $msn['owner']= $amt_owner[$x];
            }
          else
            {
                $msn['name'] = $number;
                $msn['owner']= 1;
            }
            
        unset($x);
        return $msn;
    }

# Funktion um den Eigentuemer eines Anrufes zu finden    
function func_check_owner($_owner, $type, $source, $destination)
    {
        # Globale Variablen aufrufen
        global $amt, $amt_owner, $nebenstellen;
        
        # Variablen definieren
        $ergebnis = FALSE;
        
        if($_owner == 0)
            {
                # Kein Eigentuemer ausgewaelt
                # -> Alle Anrufe werden angezeigt
                $ergebnis = TRUE;
            }
          elseif($type == "int")
            {
                # interner Anruf
                if(in_array($source, $nebenstellen[$_owner]) || in_array($destination, $nebenstellen[$_owner]))
                    {
                        $ergebnis = True;
                    }
            }
          elseif($type == "in")
            {
                # eingehender Anruf
                if(in_array($destination, $amt))
                    {
                        # MSN gefunden
                        $x = array_search($destination, $amt);
                    }
                  else
                    {
                        # Falls die MSN nicht eingetragen ist 
                        $x = 0;
                    }
                
                if($amt_owner[$x] == $_owner)
                    {
                        $ergebnis = TRUE;
                    }
            }
          elseif($type == "out")
            {
                # ausgehender Anruf
                if(in_array($source, $nebenstellen[$_owner]))
                    {
                        $ergebnis = TRUE;
                    }
            }
            
        unset($x);
        return $ergebnis;
    }

# Funktion um die Logfiles in einem Ordner zu finden    
function func_find_logfiles($dir)
    {
        # Variablen definieren
        $files = array();
        
        # ÷ffnen des Verzeichnisses
        $dp 	= opendir ($dir);
        
        # Durchlaufen des Verzeichnisses
        while ($file = readdir ($dp)) 
                {
            if ($file != "." && $file != ".." && is_file($dir."/".$file)) 
                        {
                        # Alle Dateiname in einen Array schreiben
                array_push($files, $file);
                }
                }
            # Arry sortieren
            sort($files, SORT_STRING);
            $files = array_reverse($files);
        
        # Verzeichniss schlieﬂen
        closedir($dp);
        
        unset($dp, $file);
        return $files;
    }

# Funktion um eine Listbox fuer die Logfiles zu erstellen    
function func_listbox_logfiles($logfiles, $logfile, $_owner)
    {
        # Listbox ˆffnen
        $listbox	= "<form method=\"get\" action=\"index.php\">\n<input type=\"hidden\" name=\"_owner\" value=\"".$_owner."\">\n<select name=\"logfile\"  onChange=\"javascript:this.form.submit();\">\n";
        
        # Schleife
        for ($i = 0; $i<count($logfiles); $i++)
                {
                if ($logfiles[$i] == $logfile)
                        {
                        # Aktuelle Logfile als "ausgew‰hlt" kennzeichnen
                        $selected = " SELECTED ";
                        }
                  else
                        {
                        $selected = " ";
                        }
                        
                # Anzeigename
                $datum 		= split('[-]', substr($logfiles[$i],6,10));
                $file_name 	= date("d M Y", mktime(0,0,0,$datum[1],$datum[2],$datum[0]));
                $listbox       .= "<option".$selected."value=\"".$logfiles[$i]."\">".$file_name."</option>\n";
                }
        # Listbox (Logfiles) schlieﬂen
        $listbox .= "</select>\n</form>\n";
        
        unset($datum, $file_name, $selected);
        return $listbox;
    }

# Funktion um eine Listbox fuer die Eigentuemer zu erstellen    
function func_listbox_owner($owner, $_owner, $logfile)
    {
        # Listbox (Eigentuemer) erzeugen
        #
        $listbox = "<form method=\"get\" action=\"index.php\">\n<input type=\"hidden\" name=\"logfile\" value=\"".$logfile."\">\n<select name=\"_owner\"  onChange=\"javascript:this.form.submit();\">\n";
        
        # Schleife
        for ($i = 0; $i<count($owner); $i++)
                {
                if ($_owner == $i)
                        {
                        # Aktueller Eigent¸mer als "ausgew‰hlt" kennzeichnen
                        $selected = " SELECTED ";
                        }
                  else
                        {
                        $selected = " ";
                        }
                # Anzeige
                $listbox  .= "<option".$selected."value=\"".$i."\">".$owner[$i]."</option>\n";
                }
        
        # Listbox (Eigentuemer) schlieﬂen
        $listbox .= "</select>\n</form>\n";
        
        unset($selected);
        return $listbox;
    }
    
function func_logfile_name($logfile)
    {
        # Anzeigename der Logfile erstllen 
	$datum 						= split('[-]', substr($logfile,6,10));
	$file_name 					= date("d M Y", mktime(0,0,0,$datum[1],$datum[2],$datum[0]));
        
        unset($datum);
        return $file_name;
    }
?>