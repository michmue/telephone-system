<?php /* Smarty version 2.6.14, created on 2009-11-23 23:06:03
         compiled from telebuch.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Telefonliste IP400</title>
<link href="config/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="config/tablesort.js"></script>
</head>

<body>
<table width="700" border="0" cellpadding="0" cellspacing="0" align="center" class="tab">
  <tr>
    <td width="5">&nbsp;</td>
    <td width="690"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="72%" class="topic">Telefonbuch</td>
        <td width="28%" align="right"><a href="index.php"><img src="img/Innovaphone_title.png" alt="innovaphone" width="253" height="55" border="0" /></a></td>
      </tr>
    </table></td>
    <td width="5">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="text">
	<form id="form1" name="form1" method="get">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="467" align="right" class="name">Telefonnummer: </td>
        <td width="5">&nbsp;</td>
        <td width="467" align="left" class="name"><?php echo $this->_tpl_vars['nr']; ?>
<input name="nr" type="hidden" value="<?php echo $this->_tpl_vars['nr']; ?>
" /></td>
      </tr>
      <tr>
        <td align="right" class="name">Name:</td>
        <td>&nbsp;</td>
        <td align="left" class="name"><input type="text" name="_name" /></td>
      </tr>
      <tr>
        <td colspan="3" align="center" class="name"><input type="submit" name="Submit" value="Abspeichern" /></td>
        </tr>
    </table>
	</form>
	</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="text">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<div align="center" class="footer">
  <span class="zeit">Diese Seite wurde in <?php echo $this->_tpl_vars['zeit']; ?>
 Sekunden erstellt</span><br />
	Script by Daniel Nätscher &copy; <?php echo $this->_tpl_vars['jahr']; ?>

</div>
</body>
</html>