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
        <td height="10px">
		   <div class="title"><a href="../../Enterprise/ViewEnterprise/ViewEnterprise.php?id_enterprise=<? echo $id_enterprise ?>">
		   ���������� � �����������</a> 
		   � <a href="../ViewDepartment/ViewDepartment.php?id_enterprise=<? echo $id_enterprise ?>">�������������</a>
		   � �������������� ������������� </div>
		   <div style="font-size:12px; text-align:right;"><? echo $Enterprise_Arr['NAME_SMALL'][0] ?></div>
		   <div class="title2">
		   <a class='page_doc' href="../../Employee/ViewEmployee/ViewEmployee.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>">�����������</a>
           <a class="page_doc" href="../../Address/ViewAddress/ViewAddress.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>">���������� ����������</a>
           <a class="page_doc_current" href="../../Department/ViewDepartment/ViewDepartment.php?id_enterprise=<? echo $Enterprise_Arr['ID_ENTERPRISE'][0] ?>">�������������</a>
		   </div>
           <div>&nbsp;</div>
		</td>
      </tr>
      <tr class="tdInt" height="%">
	  <form name="form1" method="post" action="">
        <td valign="top">
		<table class="borderList" width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr class="headSubList">
            <td colspan="5" class="headBorder">����������</td>
          </tr>
            <tr class="rowList item">
              <td width="200">������������</td>
              <td  ><input name="name_department" type="text" class="textField" id="name_department" value="<? echoML( $Department_Arr["NAME_DEPARTMENT"][0] ) ?>" size="40"></td>
            </tr>
			<tr class="rowList item">
				<td >�����</td>
				<td ><input name="num_department" type="text" class="textField" id="num_department" value="<? echoML( $Department_Arr["NUM_DEPARTMENT"][0] ) ?>" size="40"></td>
			</tr>
			<tr class="rowList item">
				<td >����</td>
				<td ><input name="fax" type="text" class="textField" id="fax" value="<? echoML( $Department_Arr["FAX"][0] ) ?>" size="40"></td>
			</tr>
			<tr class="rowList item">
				<td width="200">�������</td>
				<td  ><input name="phone" type="text" class="textField" id="phone" value="<? echoML( $Department_Arr["PHONE"][0] ) ?>" size="40"></td>
				</tr>
			<tr class="rowList item">
				<td >E-mail</td>
				<td ><input name="email" type="text" class="textField" id="email" value="<? echoML( $Department_Arr["EMAIL"][0] ) ?>" size="40"></td>
				</tr>
			<tr class="rowList item">
				<td >������</td>
				<td ><select name="action_dep" id="action_dep">
					<option value="1" <? if( $Department_Arr["ACTION_DEP"][0]) echo 'selected'; ?> >���������</option>
					<option value="0" <? if(!$Department_Arr["ACTION_DEP"][0]) echo 'selected'; ?>>�� ���������</option>
				</select>
				</td>
			</tr>
			<tr class="rowList item">
              <td colspan="2" align="center" ><span style=" margin-top:10px;">
              <input name="act" type="hidden" id="act" value="upd">
              <input name="id_department" type="hidden" id="id_department" value="<? echo $id_department ?>">
			  <input name="Button2" type="button" class="blueButton" value="�������"
			  onClick="javascript:
      if(confirm('�� ������� ��� ������ ������� �������������!')) 
      window.location.href='UpdDepartment.php?id_department=<? echo $id_department ?>&act=del'">
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
