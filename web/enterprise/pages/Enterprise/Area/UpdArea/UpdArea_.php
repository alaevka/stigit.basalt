<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>����� �����������</title>

<link rel="stylesheet" type="text/css" href="../../../../styles/styles.css">
<link rel="stylesheet" type="text/css" href="../../../../styles/nstyles.css">
<link rel="stylesheet" type="text/css" href="../../../../styles/style_menu.css">

<script src="../../../../scripts/CalendarJS.js" type="text/javascript"></script> 
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<body>
<table width="100%" height="100%" border="0" cellpadding="10" cellspacing="0" id="bgTable">
      <tr>
        <td style="padding:0px" height="10px">
           <? include "../../../../menu.php"; ?>		
		 </td>
      </tr>
      <tr class="tdInt">
        <td height="10px"><div class="title"><a href="../ViewArea/ViewArea.php">
		   ���������������� ������� </a> � �������������� ���������������� �������</div>
          <div style="font-size:12px; text-align:right;">&nbsp;</div>
		</td>
      </tr>
      <tr class="tdInt" height="%">
	  <form name="form1" method="post" action="">
        <td valign="top">
		<table class="borderList" width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr class="headSubList">
            <td colspan="5" class="headBorder">���������� </td>
          </tr>
            <tr class="rowList item">
              <td width="200" nowrap >��������</td>
              <td ><input name="name_area" type="text" class="textField" id="name_area" value="<? echoML( $Area_Arr["NAME_AREA"][0] ) ?>" size="40"></td>
            </tr>
            <tr class="rowList item">
              <td >���</td>
              <td><input name="type_area" type="text" class="textField" id="type_area" value="<? echoML( $Area_Arr["TYPE_AREA"][0] ) ?>" size="40"></td>
            </tr>
            <tr class="rowList item">
              <td >���</td>
              <td><input name="code" type="text" class="textField" id="code" value="<? echoML( $Area_Arr["CODE"][0] ) ?>" size="40"></td>
            </tr>
            <tr class="rowList item">
              <td >�������� ������</td>
              <td><input name="mail_index" type="text" class="textField" id="mail_index" value="<? echoML( $Area_Arr["MAIL_INDEX"][0] ) ?>" size="40"></td>
            </tr>
			<tr class="rowList item">
              <td colspan="2" align="center" ><span style=" margin-top:10px;">
              <input name="act" type="hidden" id="act" value="upd">
              <input name="id_area" type="hidden" id="id_area" value="<? echo $id_area ?>">
			  <input name="Button2" type="button" class="blueButton" value="�������"
			  onClick="javascript:
      if(confirm('�� ������� ��� ������ ������� �����!')) 
      window.location.href='UpdArea.php?id_area=<? echo $id_area ?>&act=del'">
              <input name="Button" type="button" class="blueButton" onClick="form1.submit();" value="���������">
			  <input name="Button" type="button" class="blueButton" value="������" onClick="history.go(-1)">
              </span></td>
            </tr>
          <tr>
            <td colspan="2" class="endList"></td>
          </tr>
        </table>
	</td>
        </form>
      </tr>
</table>
</body>
</html>
