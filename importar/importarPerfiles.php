<?php include('../val/valuser.php');
include('../phpqrcode/qrlib.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC.'/constants.php';
require_once 'Classes/PHPExcel/IOFactory.php';
require_once "Classes/PHPExcel.php";
$PERADMIN = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';

//verificamos is es administrador

if ($PERADMIN != 1) {
  header('Location: ../index');
}


$errcod = 0;
$errmsg = '';
$err    = 'SQLACCEPT';
$conn = sql_conectar();
//$trans	= sql_begin_trans($conn);
// $trans  = sql_begin_trans($conn);
$pathqrimagenes = '../qrimage/';
	if (!file_exists($pathqrimagenes)) {
		mkdir($pathqrimagenes);
	}






$tmpfname = $_FILES["file"]["tmp_name"];
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();


//  echo $worksheet->getCell('B'. 2)->getValue();
$contadorusuarios=0;

for ($row = 2; $row <= $lastRow; $row++) {
  
  $PERNOMBRE      =   $worksheet->getCell('A' .  $row)->getValue();
  $PERNOMBRE1=$PERNOMBRE;
  $PERAPELLI      =   $worksheet->getCell('B' .  $row)->getValue();
  $PERAPELLI1=$PERAPELLI; 
  $PERCPF      =   $worksheet->getCell('C' .  $row)->getValue();
  $PERCOMPAN      =   $worksheet->getCell('D' .  $row)->getValue();
  $PERCARGO       =   $worksheet->getCell('E' .  $row)->getValue();
  $PERCORREO      =   $worksheet->getCell('F' .  $row)->getValue();
  $PERPASACC      =   $worksheet->getCell('G' .  $row)->getValue();
  $ESTCODIGO      =   $worksheet->getCell('H' .  $row)->getValue();
  $PAICODIGO      =   $worksheet->getCell('I' .  $row)->getValue();
  $PERTIPO       =   $worksheet->getCell('J' .  $row)->getValue();
  $PERCLASE       =   $worksheet->getCell('K' .  $row)->getValue();
  $PERIDIOMA      =   $worksheet->getCell('L' . $row)->getValue();
  $TIPO           =   $worksheet->getCell('M' . $row)->getValue();
  $PEREMPDES           =   $worksheet->getCell('N' . $row)->getValue();
  $PERTELEFO           =   $worksheet->getCell('O' . $row)->getValue();
  $PERURLWEB           =   $worksheet->getCell('P' . $row)->getValue();
  $PERLINKED           =   $worksheet->getCell('Q' . $row)->getValue();
  $PERFAC           =   $worksheet->getCell('R' . $row)->getValue();
  $PERTWI           =   $worksheet->getCell('S' . $row)->getValue();
  $PERINS           =   $worksheet->getCell('T' . $row)->getValue();
  $PERDIRECC           =   $worksheet->getCell('U' . $row)->getValue();
  $PERCIUDAD           =   $worksheet->getCell('V' . $row)->getValue();
  $PERESTADO           =   $worksheet->getCell('W' . $row)->getValue();
  $PERCODPOS           =   $worksheet->getCell('X' . $row)->getValue();
  $TIMREG2           =   $worksheet->getCell('Y' . $row)->getValue();

  if ($PERNOMBRE!=''){
    $PERNOMBRE           =   substr(convBBBdatosAcentos($PERNOMBRE), 0, 90);   
  }

  if ($PERAPELLI!=''){
    $PERAPELLI           =   substr(convBBBdatosAcentos($PERAPELLI), 0, 90);   
  }

  if ($PERCOMPAN!=''){
    $PERCOMPAN           =   substr(convBBBdatosAcentos($PERCOMPAN), 0, 480);  
  }

  if ($PERCARGO!=''){
    $PERCARGO            =   substr(convBBBdatosAcentos($PERCARGO), 0, 90);
  }

  if ($PERCORREO!=''){
    $PERCORREO           =   convBBBdatos($PERCORREO);   
  }

  if ($PEREMPDES!=''){
    $PEREMPDES           =   substr(convBBBdatosAcentos($PEREMPDES), 0, 4800);
  }

  if ($PERDIRECC!=''){
    $PERDIRECC           =   substr(convBBBdatosAcentos($PERDIRECC), 0, 480);   
  }

  if ($PERCIUDAD!=''){
    $PERCIUDAD           =   substr(convBBBdatosAcentos($PERCIUDAD), 0, 480);
  }

  if ($PERESTADO!=''){
    $PERESTADO           =   substr(convBBBdatosAcentos($PERESTADO), 0, 480);
  }


  
    //Usuario en minuscula
    $PERUSUACC=$PERCORREO;
    $PERUSUACC 	= strtolower($PERUSUACC);

 




	
//Si no hay correo, le pongo el usuario ingresado


  $PERPASACC = md5('BenVido'.$PERPASACC.'PassAcceso'.$PERUSUACC);
  $PERPASACC = 'B#SD'.md5(substr($PERPASACC,1,10).'BenVidO'.substr($PERPASACC,5,8)).'E##$F';



	if($ESTCODIGO==0) $ESTCODIGO=1;


  $query2 = "SELECT PERCODIGO FROM PER_MAEST WHERE PERUSUACC = '$PERUSUACC'";
  $Table = sql_query($query2, $conn);
  if ($Table == 'SQLERROR'){
    $errmsg="<br>Hubo un problema con el usuario: <br><strong style='font-weight:800'>".$PERNOMBRE.' '.$PERAPELLI.' <br> revise sus datos para ver si existe algun caracter extraño en el correo';
    $errcod = 2;
  }else{
    $rows = $Table->Rows_Count;
  }
  

  if ($rows == -1 && $PERUSUACC!= 'null' && $PERCORREO!='') {

    
    $query 		= 'SELECT GEN_ID(G_PERFILES,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn);
		$RowId		= $TblId->Rows[0];			
		$PERCODIGO 	= trim($RowId['ID']);
   

    $tempDir = $pathqrimagenes;

	
		$codeContents = URL_WEB . 'login.php?QRCode=P||'.$PERCODIGO;

		// we need to generate filename somehow, 
		// with md5 or with database ID used to obtains $codeContents...
    $userName 	= convBBBdatos($PERNOMBRE1).'-'.convBBBdatos($PERAPELLI1);
		$fileName = $PERCODIGO.'_PER_'.$userName.'_'.md5($codeContents).'.png';

		$pngAbsoluteFilePath = $tempDir.$fileName;
		$urlRelativeFilePath = '../qrimage/'.$fileName;

		// generating
		if (!file_exists($pngAbsoluteFilePath)) {
      define('IMAGE_WIDTH',1024);
			define('IMAGE_HEIGHT',1024);
			QRcode::png($codeContents, $pngAbsoluteFilePath,QR_ECLEVEL_H, 4);
			
		} else {
			
		}
    $query = " 	INSERT INTO PER_MAEST(PERCODIGO,	PERNOMBRE,	PERAPELLI,	ESTCODIGO,	PERCOMPAN,	PERCORREO,PAICODIGO,PERUSUACC,	PERPASACC,PERCARGO,	PERTIPO,	PERCLASE,	PERADMIN,	PERIDIOMA,TIMREG,PERPOP,QRCODE,TIPO,PERPOP2,TIMOFFSET,PERCPF,PERCIUDAD,PERESTADO,PERCODPOS,PERTELEFO,PERURLWEB,PERDIRECC,TIMREG2,PEREMPDES,PERFAC,PERTWI,PERINS,PERLINKED)

    VALUES( $PERCODIGO,	'$PERNOMBRE',	'$PERAPELLI',	$ESTCODIGO,	'$PERCOMPAN',	'$PERCORREO',$PAICODIGO,	'$PERUSUACC',	'$PERPASACC',	'$PERCARGO',	$PERTIPO,	$PERCLASE,0,'$PERIDIOMA',	0,0,'$urlRelativeFilePath',$TIPO,0,0,'$PERCPF','$PERCIUDAD','$PERESTADO','$PERCODPOS','$PERTELEFO','$PERURLWEB','$PERDIRECC','$TIMREG2','$PEREMPDES','$PERFAC','$PERTWI','$PERINS','$PERLINKED') ";
	
    $err = sql_execute($query, $conn);

    if ($err == 'SQLERROR'){
      $errmsg="<br>Hubo un problema con el usuario: <br><strong style='font-weight:800'>".$PERNOMBRE.' '.$PERAPELLI.' <br> revise sus datos para ver si existe algun caracter extraño';
    $errcod = 2;
    }else{
      $contadorusuarios++;
    }
    
    
  } else {

    if ($PERUSUACC!= 'null' && $PERUSUACC!='') {
      $query = " 	UPDATE PER_MAEST SET 
					PERNOMBRE='$PERNOMBRE', PERAPELLI='$PERAPELLI', ESTCODIGO=$ESTCODIGO,
					PERCOMPAN='$PERCOMPAN',PERCORREO='$PERCORREO',PAICODIGO=$PAICODIGO,PERUSUACC='$PERUSUACC',PERPASACC='$PERPASACC', PERCARGO='$PERCARGO', PERTIPO=$PERTIPO, PERCLASE=$PERCLASE,
					PERIDIOMA='$PERIDIOMA', TIMREG=0,TIPO=$TIPO,TIMOFFSET=0,PERCPF='$PERCPF',PERCIUDAD='$PERCIUDAD',PERESTADO='$PERESTADO',PERCODPOS='$PERCODPOS',PERTELEFO='$PERTELEFO',PERURLWEB='$PERURLWEB',PERDIRECC='$PERDIRECC',TIMREG2='$TIMREG2',PEREMPDES='$PEREMPDES',PERFAC='$PERFAC',PERTWI='$PERTWI',PERINS='$PERINS',PERLINKED='$PERLINKED'
					WHERE PERUSUACC='$PERUSUACC' ";

      $err = sql_execute($query, $conn);
      if ($err == 'SQLERROR'){
        $errmsg="<br>Hubo un problema con el usuario: <br><strong style='font-weight:800'>".$PERNOMBRE.' '.$PERAPELLI.' <br> revise sus datos para ver si existe algun caracter extraño';
      $errcod = 2;
      }else{
        $contadorusuarios++;
      }
    }
   
    }

}




if ($err == 'SQLACCEPT' && $errcod == 0) {

  //sql_commit_trans($trans);
  $errcod = 0;
  $errmsg =  "<br>Importacion Exitosa, se han actualizado/creado: <br><strong style='font-weight:800'>".$contadorusuarios.' de '.($lastRow-1).' usuarios </strong>';      /*TrMessage('Permiso actualizado!')*/;
} else {
  //sql_rollback_trans($trans);
  $errcod = 2;
  $errmsg = ($errmsg == '') ? 'Error al importar' /*TrMessage('Error al actualizar el permiso!')*/ : $errmsg;
}

sql_close($conn);
echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';

function convBBBdatos($texto){
  $textofin = $texto;
  $textofin = str_replace(' ','',$textofin);
  $textofin = str_replace("'",'',$textofin);
  $textofin = str_replace('"','',$textofin);
  
  $textofin = str_replace('á','a',$textofin);
  $textofin = str_replace('é','e',$textofin);
  $textofin = str_replace('í','i',$textofin);
  $textofin = str_replace('ó','o',$textofin);
  $textofin = str_replace('ú','u',$textofin);
  $textofin = str_replace('ñ','n',$textofin);
  
  $textofin = str_replace('Á','A',$textofin);
  $textofin = str_replace('É','E',$textofin);
  $textofin = str_replace('Í','I',$textofin);
  $textofin = str_replace('Ó','O',$textofin);
  $textofin = str_replace('Ú','U',$textofin);
  $textofin = str_replace('Ñ','N',$textofin);
  
  $textofin = str_replace('Ç','C',$textofin);
  $textofin = str_replace('ç','c',$textofin);

  $textofin = str_replace('ä','a',$textofin);
	$textofin = str_replace('ë','e',$textofin);
	$textofin = str_replace('ï','i',$textofin);
	$textofin = str_replace('ö','o',$textofin);
	$textofin = str_replace('ü','u',$textofin);
	
	$textofin = str_replace('Ä','A',$textofin);
	$textofin = str_replace('Ë','E',$textofin);
	$textofin = str_replace('Ï','I',$textofin);
	$textofin = str_replace('Ö','O',$textofin);
	$textofin = str_replace('Ü','U',$textofin);
  
  return $textofin;
}
function convBBBdatosAcentos($texto){
  $textofin = $texto;
  $textofin = str_replace("'",'',$textofin);
  $textofin = str_replace('"','',$textofin);

  $textofin = str_replace('ä','a',$textofin);
	$textofin = str_replace('ë','e',$textofin);
	$textofin = str_replace('ï','i',$textofin);
	$textofin = str_replace('ö','o',$textofin);
	$textofin = str_replace('ü','u',$textofin);
	
	$textofin = str_replace('Ä','A',$textofin);
	$textofin = str_replace('Ë','E',$textofin);
	$textofin = str_replace('Ï','I',$textofin);
	$textofin = str_replace('Ö','O',$textofin);
	$textofin = str_replace('Ü','U',$textofin);
  
  return $textofin;
}

?>