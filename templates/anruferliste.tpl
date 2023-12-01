<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Telefonliste IP800</title>
<link href="config/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="config/tablesort.js"></script>
{$java}
</head>

<body>
<div align="center"><a href="../default.htm">Zur&uuml;ck zur Startseite</a><br /><br /></div>
<table width="950" border="0" cellpadding="0" cellspacing="0" align="center" class="tab">
  <tr>
    <td width="5">&nbsp;</td>
    <td width="940"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="72%" class="topic">Telefonliste vom {$logfile}</td>
        <td width="28%" align="right"><a href="{$index_link}"><img src="img/Innovaphone_title.png" alt="innovaphone" width="253" height="55" border="0" /></a></td>
      </tr>
    </table></td>
    <td width="5">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="72%" class="name">{$anrufe}</td>
        <td width="14%" align="right">{$listbox_owner}</td>
        <td width="14%" align="right">{$listbox}</td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" class="name"><font color="#FF0000">{$error}</font></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
	<table width="100%" border="1" cellspacing="0" cellpadding="0"  class="sortable" id="inhalt" rules="all">
      <tr>
        <th width="48" align="center">Status</th>
        <th width="243" align="left">Anrufer</th>
        <th width="140" align="center">eingehende MSN</th>
        <th width="88" align="center">Intern</th>
        <th width="243" align="left">gew&auml;hlte Nummer</th>
        <th width="80" align="center">Uhrzeit</th>
        <th width="80" align="center">Dauer</th>
      </tr>
{foreach from=$inhalte item=inhalt}
      <tr>
        <td height="35" align="center" valign="top" class="text">{$inhalt.typ}&nbsp;</td>
        <td height="35" align="left" valign="top" class="text">{$inhalt.sor_telenr}&nbsp;</td>
        <td height="35" align="center" valign="top" class="text">{$inhalt.amt}&nbsp;</td>
        <td height="35" align="center" valign="top" class="text">{$inhalt.int}&nbsp;</td>
        <td height="35" align="left" valign="top" class="text">{$inhalt.dest_telenr}&nbsp;</td>
        <td height="35" align="center" valign="top" class="text">{$inhalt.uhrzeit}&nbsp;</td>
        <td height="35" align="center" valign="top" class="text">{$inhalt.dauer}&nbsp;</td>
      </tr>
{/foreach}
    </table>    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="text">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="name" align="center">{$refresh_link}</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="text">{$inhalt_file}</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<div align="center" class="footer">
   	<span class="zeit">Diese Seite wurde in {$zeit} Sekunden erstellt<br />
	{$archiv}<br />
	{$double}<br />
	Eigent&uuml;mer:</span> {$owner}<br />
	Script by Daniel Nätscher &copy; {$jahr}
</div>
<div align="center"><br /><a href="../default.htm">Zur&uuml;ck zur Startseite</a></div>
</body>
</html>
