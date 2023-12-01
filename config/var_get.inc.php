<?
# Anruferliste
#

# Varibale für die Logfile
if (!isset($logfile)) 
	{
	$logfile = $_GET['logfile'];
	}
	
if (empty($logfile))
	{
	$logfile = "standard";
	}
	
# Varibale für den Refresh
if (!isset($refresh)) 
	{
	$refresh = $_GET['refresh'];
	}
	
if (empty($refresh))
	{
	$refresh = FALSE;
	}
	
# Varibale für den Eigentümer
if (!isset($_owner)) 
	{
	$_owner = $_GET['_owner'];
	}
	
if (empty($_owner))
	{
	$_owner = 0;
	}

# Telebuch eintragen
#

# Varibale für die Nummer
if (!isset($nr)) 
	{
	$nr = $_GET['nr'];
	}
	
if (empty($nr))
	{
	$nr = "none";
	}

# Varibale für submit
if (!isset($submit)) 
	{
	$submit = $_GET['Submit'];
	}
	
if (empty($submit))
	{
	$submit = "none";
	}

# Varibale für den Namen
if (!isset($_name)) 
	{
	$_name = $_GET['_name'];
	}
	
if (empty($_name))
	{
	$_name = "none";
	}
?>