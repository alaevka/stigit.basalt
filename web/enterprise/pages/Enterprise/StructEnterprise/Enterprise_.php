<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>����� �����������</title>
<link rel="stylesheet" type="text/css" href="../../../styles/styles.css">
<link rel="stylesheet" type="text/css" href="../../../styles/nstyles.css">
<link rel="stylesheet" type="text/css" href="../../../styles/style_menu.css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body>
<table width="100%" height="100%" border="0" cellpadding="10" cellspacing="0">
      <tr height="10px">
        <td style="padding:0px">
           <? include "../../../menu.php"; ?>		</td>
      </tr>
      <tr class="tdInt" height="10px">
        <td>
           <p><span class="title">���������� � �����������</span></p>	 </td>
      </tr>
      <tr class="tdInt" height="%">
         <td valign="top"><b><a class="link_upd" style=" background:#FFFFFF; border:#CCCCCC solid 1px; padding:2px" 
		          href="../UpdEnterprise/UpdEnterprise.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>">
				  [����� ����������]</a>&nbsp;&nbsp;
				  <a class="link_upd" href="../UpdEnterprise/UpdEnterprise.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>">
				  [�����������]</a>&nbsp;&nbsp;
				  <a class="link_upd" href="../UpdEnterprise/UpdEnterprise.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>">
				  [���������� ����������]</a>&nbsp;&nbsp;
				  <a class="link_upd" href="../UpdEnterprise/UpdEnterprise.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>">
				  [������� � �����������������]</a>
            </b>   
			<br>
			<br> 
            <table class="borderList" width="100%" border="0" cellspacing="1" cellpadding="2">
              <tr class="rowList item">
                <td width="200">���������</td>
                <td >&nbsp;<? echo $Enterprise_Arr['NAME_CALL'][0]?></td>
              </tr>
              <tr class="rowList item">
                  <td>������� ������������</td>
                  <td  >&nbsp;<? echo $Enterprise_Arr['NAME_SMALL'][0]?></td>
              </tr>
		 <tr class="rowList item">
                  <td >������ ������������</td>
                  <td >&nbsp;<? echo $Enterprise_Arr['NAME_ENTERPRISE'][0]?></td>
		 </tr>
			  
		      <tr>
		        <td class="rowList item" >�������</td>
		        <td class="rowList item">&nbsp;<? echo $Enterprise_Arr['PHONE'][0]?></td>
	          </tr>
		      <tr>
		        <td class="rowList item" >����</td>
		        <td class="rowList item">&nbsp;<? echo $Enterprise_Arr['FAX'][0]?></td>
	          </tr>
		      <tr>
		        <td class="rowList item" >E-mail</td>
		        <td class="rowList item">&nbsp;<? echo $Enterprise_Arr['EMAIL'][0]?></td>
	          </tr>
		      <tr>
		    <td colspan="2" align="center" class="rowList item" >
			 <input name="Button" type="button" onClick="document.location.href='../UpdEnterprise/UpdEnterprise.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>'" class="blueButton" value="�������������"></td>
		    </tr>
        </table>        </td>
      </tr>
    </table>
</body>
</html>
