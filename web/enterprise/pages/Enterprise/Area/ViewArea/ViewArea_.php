<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>����� �����������</title>
<link rel="stylesheet" type="text/css" href="../../../../styles/styles.css">
<link rel="stylesheet" type="text/css" href="../../../../styles/nstyles.css">
<link rel="stylesheet" type="text/css" href="../../../../styles/style_menu.css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style type="text/css">
<!--
.style1 {font-size: 12px}
-->
</style>
</head>


<body">
<table width="100%" height="100%" border="0" cellpadding="10" cellspacing="0">
      <tr>
        <td style="padding:0px" height="10px">
           <? include "../../../../menu.php"; ?></td>
      </tr>
      <tr class="tdInt">
        <td height="10px">
           <div class="title">���������������� �������</div>
		   <div style="font-size:12px; text-align:right;">&nbsp;</div>
		   <div align="left"><a class="link_upd" href="../AddArea/AddArea.php">
		     [ �������� ���������������� ������� ]</a>
	      </div>
	      <div align="center">
	 <form name="form1" method="get" action="">
	        <span style="font-size:12px">����� �� ����� </span>
            <input name="name_area" type="text" class="textField" id="name_area" value="<? echo isset($name_area) ? $name_area : '' ?>" size="40" maxlength="30">
	        <input name="Submit" type="submit" class="blueButton" value="OK">
     </form>
          </div></td>
      </tr>
      <tr valign="top" class="tdInt" height="%">
         <td ><div id="pfc_online" style="border: #072a66 1px solid;">
	    <table class="borderList" width="100%" border="0" cellspacing="1" cellpadding="4" style="border-width:0px;">
          <tr class="headSubList" style="position: relative;top: expression(this.parentElement.parentElement.parentElement.scrollTop);" >
            <td width="26%" align="center" class="headBorder">��������</td>
            <td width="24%" align="center" class="headBorder">���</td>
            <td width="21%" align="center" class="headBorder">���</td>
            <td width="29%" align="center" class="headBorder">������</td>
          </tr>
          <? 
		  for ( $i = 0; $i < sizeof( $Area['ID_AREA']); $i++ ) 
		  { 
		  ?>
		  <a href="../UpdArea/UpdArea.php?id_area=<? echo $Area['ID_AREA'][$i] ?>">
           <tr class='rowList item' style="cursor:pointer" onMouseOver="this.style.background = '#f0f5fa';" onMouseOut="this.style.background = '#e4eaf2';">
              <td>&nbsp;<? echo $Area['NAME_AREA'][$i] ?></td>
              <td>&nbsp;<? echo $Area['TYPE_AREA'][$i] ?></td>
              <td>&nbsp;<? echo $Area['CODE'][$i] ?></td>
              <td>&nbsp;<? echo $Area['MAIL_INDEX'][$i] ?></td>
          </tr>
		  </a>
          <? } ?>
          <tr>
            <td colspan="17" class="endList"></td>
          </tr>
        </table>
	  </div></td>
      </tr>
    </table>
</body>
</html>
