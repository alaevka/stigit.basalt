<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>����� �����������</title>
<link rel="stylesheet" type="text/css" href="../../../../styles/styles.css">
<link rel="stylesheet" type="text/css" href="../../../../styles/nstyles.css">
<link rel="stylesheet" type="text/css" href="../../../../styles/style_menu.css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body>
<table width="100%" height="100%" border="0" cellpadding="10" cellspacing="0">
      <tr>
        <td style="padding:0px" height="10px">
           <? include "../../../../menu.php"; ?>		</td>
      </tr>
      <tr class="tdInt">
        <td height="10px">
           <div class="title">���������� � �����������</div>
		   <div style="font-size:12px; text-align:right;"><? echo $Enterprise_Arr['NAME_SMALL'][0] ?></div>
		   <div class="title2">
		   <a class='page_doc' href="../../Employee/ViewEmployee/ViewEmployee.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>">�����������</a>
           <a class="page_doc" href="../../Address/ViewAddress/ViewAddress.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>">���������� ����������</a>
           <a class="page_doc" href="../../Department/ViewDepartment/ViewDepartment.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>">�������������</a>
		   </div>
           <div>&nbsp;</div>
	    </td>
      </tr>
      <tr class="tdInt" height="%">
         <td valign="top">
		 <table class="borderList" width="100%" border="0" cellspacing="1" cellpadding="2">
		           <tr class="headSubList">
            <td colspan="5" class="headBorder">����� ����������</td>
          </tr>
              <tr class="rowList item">
                <td width="200">���������</td>
                <td ><? echo $Enterprise_Arr['NAME_CALL'][0]?></td>
              </tr>
              <tr class="rowList item">
                  <td>������� ������������</td>
                  <td  ><? echo $Enterprise_Arr['NAME_SMALL'][0]?></td>
              </tr>
		 <tr class="rowList item">
                  <td >������ ������������</td>
                  <td ><? echo $Enterprise_Arr['NAME_ENTERPRISE'][0]?></td>
		 </tr>
			  
		      <tr>
		        <td class="rowList item" >�������</td>
		        <td class="rowList item"><? echo $Enterprise_Arr['PHONE'][0]?></td>
	          </tr>
		      <tr>
		        <td class="rowList item" >����</td>
		        <td class="rowList item"><? echo $Enterprise_Arr['FAX'][0]?></td>
	          </tr>
		      <tr>
		        <td class="rowList item" >E-mail</td>
		        <td class="rowList item"><? echo $Enterprise_Arr['EMAIL'][0]?></td>
	          </tr>
		      <tr>
		      	<td class="rowList item" >������</td>
		      	<td class="rowList item"><? if(!empty($Enterprise_Arr["ACTION"][0])) echo "�����������"; else echo "<b>�� �����������</b>";?></td>
		    </tr>
		      <tr>
		      	<td class="rowList item" >����</td>
		      	<td class="rowList item"><? echo $Enterprise_Arr['URL'][0]?></td>
	      	</tr>
		      <tr>
		      	<td class="rowList item">������������� �� </td>
		      	<td nowrap class="rowList item">
					<a href="?id_enterprise=<?= $Enterprise_Arr["ID_ENT_LAST_INCARNATION"][0] ?>"><?= $Enterprise_Arr["NAME_ENT_LAST_INCARNATION"][0] ?></a></td>
		      	</tr>
		      <tr>
		      	<td align="left" class="rowList item" >������������� � </td>
		      	<td align="left" class="rowList item" ><?
				for($i=0;$i<sizeof($Enterprise_incarnation_Arr['ID_ENTERPRISE']);$i++){
						if($i>0){ echo '<br>'; }
						echo '<a href="?id_enterprise='.$Enterprise_incarnation_Arr['ID_ENTERPRISE'][$i].'">'.$Enterprise_incarnation_Arr['NAME_CALL'][$i].'</a>';
				 
				}
				?></td>
		      	</tr>
		      <tr>
		      	<td class="rowList item" >����������</td>
		      	<td class="rowList item"><? echo $Enterprise_Arr['NOTE'][0] ?></td>
	      	</tr>
		      <tr>
		      	<td class="rowList item" >���� ��������� </td>
		      	<td class="rowList item"><? echo $Enterprise_Arr['A'][0] ?></td>
		      	</tr>
		      <tr>
		    <td colspan="2" align="center" class="rowList item" >
			 <input name="Button" type="button" onClick="document.location='../UpdEnterprise/UpdEnterprise.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>'" class="blueButton" value="�������������"></td>
		    </tr>
        </table>        </td>
      </tr>
    </table>
</body>
</html>
