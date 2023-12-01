<?
# Hauptscript für die Verwaltung des Telefonbuches

# Zeitmessung
$start	= microtime(1);

# Einfügen der anderen Seiten
include('config/config.inc.php'); 			// Konfigurationsdatei
include('config/var_get.inc.php');  		        // Site zum holen der Varibalen aus der Adresse
include('config/functions.php');  		        // Funktionen 
require(SMARTY_DIR.'Smarty.class.php');   	        // Smarty Class


# Zeitmessung
$zeit	=  round(microtime(1) - $start, 3);


if ($submit == "none")
	{
	# Ausgabe
	#
	
	$smarty = new Smarty;   
	$smarty->assign('nr', $nr); 				// Nummer übergeben
	$smarty->assign('name', $_name); 			// Nummer übergeben
	$smarty->assign('jahr', date("Y"));			// Aktuelles Jahr übergeben
	$smarty->assign('zeit', $zeit);				// Zeit übergeben
	$smarty->display('telebuch_edit.tpl');			// Template angeben
	}
  elseif ($submit == "Aendern")
  	{
	# Eintrag schreiben
	#
	if ($nr != "none" && $_name != "none")
		{
		# Eintrag im Telebuch loeschen
		
                # Telefonbuch einlesen
		$phonebook = func_telebuch($telbuch);
                
                # ID finden
                unset($x);
                if (in_array($nr, $phonebook['nummer']))
                    { 
                        $x = array_search($nr, $phonebook['nummer']);
                    }
                    
                
                # Telefonbuch Zeilenweise in einen Array einlesen
                $lines = file($telbuch);
        
                # Zeile aendern
                $lines[$x] = $nr.";".$_name.";0\n";
                
                # Datei zum ueberschreiben oeffnen
                $datei=fopen($telbuch,"w+");
                
                # Zeilensprung einfuegen
                $newfile=implode("", $lines);
                
                # Datei schreiben
                fwrite($datei, $newfile);
                
                # Datei schliessen 
                fclose($datei);
		
		# Ausgabe zusammenbauen
		$text = "Telefonnummer \"".$nr."\" mit folgendem Namen \"".$_name."\" erfolgreich geaendert!!";
		}
	  else
	  	{
		# Ausgabe zusammenbauen
		$text = "Angaben unvollst&aulm;ndig";
		}
	
	# Ausgabe
	#
	
	$smarty = new Smarty;   
	$smarty->assign('text', $text); 			// Text übergeben
	$smarty->assign('jahr', date("Y"));			// Aktuelles Jahr übergeben
	$smarty->assign('zeit', $zeit);				// Zeit übergeben
	$smarty->display('telebuch_send.tpl');		// Template angeben
	}
?>