<?
# Hauptscript f�r die Verwaltung des Telefonbuches

# Zeitmessung
$start	= microtime(1);

# Einf�gen der anderen Seiten
include('config/config.inc.php'); 			// Konfigurationsdatei
include('config/var_get.inc.php');  		// Site zum holen der Varibalen aus der Adresse
require(SMARTY_DIR.'Smarty.class.php');   	// Smarty Class


# Zeitmessung
$zeit	=  round(microtime(1) - $start, 3);


if ($submit == "none")
	{
	# Ausgabe
	#
	
	$smarty = new Smarty;   
	$smarty->assign('nr', $nr); 				// Nummer �bergeben
	$smarty->assign('jahr', date("Y"));			// Aktuelles Jahr �bergeben
	$smarty->assign('zeit', $zeit);				// Zeit �bergeben
	$smarty->display('telebuch.tpl');			// Template angeben
	}
  elseif ($submit == "Abspeichern")
  	{
	# Eintrag schreiben
	#
	if ($nr != "none" && $_name != "none")
		{
		# Eintrag in das Telebuch
		#
		
		# Zeile zusammenbauen
		$input		 = $nr.";".$_name.";0\r\n";
		
		# Datei �ffnen
		$fp_telebuch = fopen ($telbuch,"a");
		
		# Zeile schreiben
		fwrite($fp_telebuch,$input);
		
		# Datei schlie�en
		fclose($fp_telebuch);
		
		# Ausgabe zusammenbauen
		$text = "Telefonnummer \"".$nr."\" mit folgendem Namen \"".$_name."\" erfolgreich eingetragen!!";
		}
	  else
	  	{
		# Ausgabe zusammenbauen
		$text = "Angaben unvollst&aulm;ndig";
		}
	
	# Ausgabe
	#
	
	$smarty = new Smarty;   
	$smarty->assign('text', $text); 			// Text �bergeben
	$smarty->assign('jahr', date("Y"));			// Aktuelles Jahr �bergeben
	$smarty->assign('zeit', $zeit);				// Zeit �bergeben
	$smarty->display('telebuch_send.tpl');		// Template angeben
	}
?>