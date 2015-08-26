<?php if ( !defined("PROTECTED") ) { echo "Hacking attention"; die(); }
  
  $Month["������"]   = "JAN";
  $Month["�������"]  = "FEB";
  $Month["����"]     = "MAR";
  $Month["������"]   = "APR";
  $Month["���"]      = "MAY";
  $Month["����"]     = "JUN";
  $Month["����"]     = "JUL";
  $Month["������"]   = "AUG";
  $Month["��������"] = "SEP";
  $Month["�������"]  = "OCT";
  $Month["������"]   = "NOV";
  $Month["�������"]  = "DEC";

  $rMonth["JAN"]     = "������";
  $rMonth["FEB"]     = "�������";
  $rMonth["MAR"]     = "����";
  $rMonth["APR"]     = "������";
  $rMonth["MAY"]     = "���";
  $rMonth["JUN"]     = "����";
  $rMonth["JUL"]     = "����";
  $rMonth["AUG"]     = "������";
  $rMonth["SEP"]     = "��������";
  $rMonth["OCT"]     = "�������";
  $rMonth["NOV"]     = "������";
  $rMonth["DEC"]     = "�������";

  $MonthN["01"]      = "JAN";
  $MonthN["02"]      = "FEB";
  $MonthN["03"]      = "MAR";
  $MonthN["04"]      = "APR";
  $MonthN["05"]      = "MAY";
  $MonthN["06"]      = "JUN";
  $MonthN["07"]      = "JUL";
  $MonthN["08"]      = "AUG";
  $MonthN["09"]      = "SEP";
  $MonthN["10"]      = "OCT";
  $MonthN["11"]      = "NOV";
  $MonthN["12"]      = "DEC";

  $rMonthN["JAN"]     = "01";
  $rMonthN["FEB"]     = "02";
  $rMonthN["MAR"]     = "03";
  $rMonthN["APR"]     = "04";
  $rMonthN["MAY"]     = "05";
  $rMonthN["JUN"]     = "06";
  $rMonthN["JUL"]     = "07";
  $rMonthN["AUG"]     = "08";
  $rMonthN["SEP"]     = "09";
  $rMonthN["OCT"]     = "10";
  $rMonthN["NOV"]     = "11";
  $rMonthN["DEC"]     = "12";



  function ConvToSQL( $Date )
  {
    GLOBAL $MonthN;

    $DD = "DD";
    $MM = "MM";
    $GG = "GG";

    for ( $i = 0; $i < 2; $i++ ) $DD[$i]   = $Date[$i];
    for ( $i = 3; $i < 5; $i++ ) $MM[$i-3] = $Date[$i];

    for ( $i = strlen($Date)-2; $i < strlen($Date); $i++ ) $GG[$i-(strlen($Date)-2)] = $Date[$i];

    $SQLDate = $DD."-".$MonthN[$MM]."-".$GG;

    return $SQLDate;
  }


  function ConvFromSQL( $Date )
  {
    if ( $Date == "" ) return $Date;

    GLOBAL $rMonth;

    $DD = "DD";
    $MM = "MM";
    $GG = "GG";

    for ( $i = 0; $i < 2; $i++ ) $DD[$i]   = $Date[$i];
    for ( $i = 3; $i < 6; $i++ ) $MM[$i-3] = $Date[$i];
    for ( $i = 7; $i < 9; $i++ ) $GG[$i-7] = $Date[$i];

    return $DD." ".$rMonth[$MM]." ".$GG;
  }

  function ConvFromSQL2( $Date )
  {
    GLOBAL $rMonthN;

    $DD = "DD";
    $MM = "MM";
    $GG = "GG";

    for ( $i = 0; $i < 2; $i++ ) $DD[$i]   = $Date[$i];
    for ( $i = 3; $i < 6; $i++ ) $MM[$i-3] = $Date[$i];
    for ( $i = 7; $i < 9; $i++ ) $GG[$i-7] = $Date[$i];

    return $DD.".".$rMonthN[$MM].".".$GG;
  }

  function Show( $Msg )
  {
    if ( $Msg != "" ) print $Msg;
    else              print '<em>��� ������</em>';
  }

  function ConvertFormat( $Format )
  {
    $FormatList["A4"] = 1;
    $FormatList["A3"] = 2;
    $FormatList["A2"] = 4;
    $FormatList["A1"] = 8;
    $FormatList["A0"] = 16;

    $FormatList["�4"] = 1;
    $FormatList["�3"] = 2;
    $FormatList["�2"] = 4;
    $FormatList["�1"] = 8;
    $FormatList["�0"] = 16;

	$Index = 0;
	$Sheets = 0;


	while( $Index < strlen( $Format ) )
	{
	  $Reposit = "q";

	  for ( $i = 0; ($Index < strlen( $Format )) && ($Format[$Index] != ','); $i++,$Index++ )
	    $Reposit[$i] = $Format[$Index];

	  $Index++;


	  $len = strlen( $Reposit );
	  $Num = "q";
	  $Frm = "q";

	  for ( $i = 0; $i < ( $len - 2 ); $i++ ) $Num[$i] = $Reposit[$i];
	  for ( $i = 0; $i < 2; $i++ ) $Frm[$i] = $Reposit[$i+$len-2];


	  $Sheets += $Num*$FormatList[$Frm];
	}

	return $Sheets;
  }
?>