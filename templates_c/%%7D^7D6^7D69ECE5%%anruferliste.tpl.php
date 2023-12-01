<?php /* Smarty version 2.6.14, created on 2009-11-23 22:59:53
         compiled from anruferliste.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Telefonliste IP800</title>
<link href="config/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="config/tablesort.js"></script>
<?php echo $this->_tpl_vars['java']; ?>

</head>

<body>
<div align="center"><a href="../default.htm">Zur&uuml;ck zur Startseite</a><br /><br /></div>
<table width="950" border="0" cellpadding="0" cellspacing="0" align="center" class="tab">
  <tr>
    <td width="5">&nbsp;</td>
    <td width="940"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="72%" class="topic">Telefonliste vom <?php echo $this->_tpl_vars['logfile']; ?>
</td>
        <td width="28%" align="right"><a href="<?php echo $this->_tpl_vars['index_link']; ?>
"><img src="img/Innovaphone_title.png" alt="innovaphone" width="253" height="55" border="0" /></a></td>
      </tr>
    </table></td>
    <td width="5">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="72%" class="name"><?php echo $this->_tpl_vars['anrufe']; ?>
</td>
        <td width="14%" align="right"><?php echo $this->_tpl_vars['listbox_owner']; ?>
</td>
        <td width="14%" align="right"><?php echo $this->_tpl_vars['listbox']; ?>
</td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" class="name"><font color="#FF0000"><?php echo $this->_tpl_vars['error']; ?>
</font></td>
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
<?php $_from = $this->_tpl_vars['inhalte']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['inhalt']):
?>
      <tr>
        <td height="35" align="center" valign="top" class="text"><?php echo $this->_tpl_vars['inhalt']['typ']; ?>
&nbsp;</td>
        <td height="35" align="left" valign="top" class="text"><?php echo $this->_tpl_vars['inhalt']['sor_telenr']; ?>
&nbsp;</td>
        <td height="35" align="center" valign="top" class="text"><?php echo $this->_tpl_vars['inhalt']['amt']; ?>
&nbsp;</td>
        <td height="35" align="center" valign="top" class="text"><?php echo $this->_tpl_vars['inhalt']['int']; ?>
&nbsp;</td>
        <td height="35" align="left" valign="top" class="text"><?php echo $this->_tpl_vars['inhalt']['dest_telenr']; ?>
&nbsp;</td>
        <td height="35" align="center" valign="top" class="text"><?php echo $this->_tpl_vars['inhalt']['uhrzeit']; ?>
&nbsp;</td>
        <td height="35" align="center" valign="top" class="text"><?php echo $this->_tpl_vars['inhalt']['dauer']; ?>
&nbsp;</td>
      </tr>
<?php endforeach; endif; unset($_from); ?>
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
    <td class="name" align="center"><?php echo $this->_tpl_vars['refresh_link']; ?>
</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="text"><?php echo $this->_tpl_vars['inhalt_file']; ?>
</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<div align="center" class="footer">
   	<span class="zeit">Diese Seite wurde in <?php echo $this->_tpl_vars['zeit']; ?>
 Sekunden erstellt<br />
	<?php echo $this->_tpl_vars['archiv']; ?>
<br />
	<?php echo $this->_tpl_vars['double']; ?>
<br />
	Eigent&uuml;mer:</span> <?php echo $this->_tpl_vars['owner']; ?>
<br />
	Script by Daniel Nätscher &copy; <?php echo $this->_tpl_vars['jahr']; ?>

</div>
<div align="center"><br /><a href="../default.htm">Zur&uuml;ck zur Startseite</a></div>
</body>
</html>