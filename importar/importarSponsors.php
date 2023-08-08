<?php include('../val/valuser.php');
include('../phpqrcode/qrlib.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/constants.php';
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
$trans	= sql_begin_trans($conn);
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


for ($row = 2; $row <= $lastRow; $row++) {
  
  $expnombre      =   $worksheet->getCell('A' .  $row)->getValue();
  $expnombre1=$expnombre;
  $expcatego      =   $worksheet->getCell('B' .  $row)->getValue();
  $exppos      =   $worksheet->getCell('C' .  $row)->getValue();
  $expdireccion       =   $worksheet->getCell('D' .  $row)->getValue();
  $exptelefo      =   $worksheet->getCell('E' .  $row)->getValue();
  $expmail      =   $worksheet->getCell('F' .  $row)->getValue();
  $expfac      =   $worksheet->getCell('G' .  $row)->getValue();
  $explinked      =   $worksheet->getCell('H' .  $row)->getValue();
  $exptwi       =   $worksheet->getCell('I' .  $row)->getValue();
  $expinsta       =   $worksheet->getCell('J' .  $row)->getValue();
  $exprubros      =   $worksheet->getCell('K' . $row)->getValue();
  $percodigocontacto           =   $worksheet->getCell('L' . $row)->getValue();



  $expnombre		= VarNullBD($expnombre, 'S');
  $expmail		= VarNullBD($expmail, 'S');
  $exprubros		= VarNullBD($exprubros, 'S');
  $expcatego		= VarNullBD($expcatego, 'N');
  $percodigocontacto		= VarNullBD($percodigocontacto, 'N');
  $exppos			= VarNullBD($exppos, 'N');
  $explinked		= VarNullBD($explinked, 'S');
  $expfac			= VarNullBD($expfac, 'S');
  $exptwi			= VarNullBD($exptwi, 'S');
  $expdireccion	= VarNullBD($expdireccion, 'S');
  $exptelefo		= VarNullBD($exptelefo, 'S');
  $expinsta		= VarNullBD($expinsta, 'S');





  //Usuario en minuscula
  $expnombre 	= strtolower($expnombre);

  $query2 = "SELECT EXPREG FROM EXP_MAEST WHERE EXPNOMBRE = $expnombre";
  $Table = sql_query($query2, $conn,$trans);
  $rows = $Table->Rows_Count;


  if ($rows == -1 && $expnombre!= 'null' && $expnombre!= '') {
  
    
    $query 		= 'SELECT GEN_ID(G_EXPOSITORES,1) AS ID FROM RDB$DATABASE';
    $TblId		= sql_query($query, $conn, $trans);
    $RowId		= $TblId->Rows[0];
    $expreg 	= trim($RowId['ID']);
   

    $tempDir = $pathqrimagenes;

	
		$codeContents = URL_WEB . 'login.php?QRCode=E||'.$expreg;

		// we need to generate filename somehow, 
		// with md5 or with database ID used to obtains $codeContents...
    $userName 	= convBBBdatos($expnombre1);
    $fileName = $expreg.'_EXP_'.$userName.'_'.md5($codeContents).'.png';

		$pngAbsoluteFilePath = $tempDir.$fileName;
		$urlRelativeFilePath = '../qrimage/'.$fileName;

		// generating
		if (!file_exists($pngAbsoluteFilePath)) {
      define('IMAGE_WIDTH',1024);
			define('IMAGE_HEIGHT',1024);
			QRcode::png($codeContents, $pngAbsoluteFilePath,QR_ECLEVEL_H, 4);
			
		} else {
			
		}
    $query = " 	INSERT INTO EXP_MAEST(EXPREG,EXPNOMBRE,EXPMAIL,EXPRUBROS, ESTCODIGO,EXPCATEGO,EXPPOS,EXPLINKED,EXPFAC,EXPTWI,EXPDIRECCION,EXPTELEFO,EXPINSTA,PERCODIGO,QRCODE)
					VALUES($expreg,$expnombre,$expmail,$exprubros,1,$expcatego,$exppos,$explinked,$expfac,$exptwi,$expdireccion,$exptelefo,$expinsta,$percodigocontacto,'$urlRelativeFilePath') ";
	
  $err = sql_execute($query, $conn,$trans);
    
    
  } else {

    if ($expnombre != '' && $expnombre!= 'null') {
      $query = " 	UPDATE EXP_MAEST SET 
      EXPNOMBRE=$expnombre, EXPMAIL=$expmail, EXPRUBROS=$exprubros,EXPCATEGO=$expcatego, EXPPOS=$exppos, EXPLINKED = $explinked, EXPFAC = $expfac, EXPTWI  = $exptwi , EXPDIRECCION = $expdireccion , EXPTELEFO = $exptelefo, EXPINSTA = $expinsta,PERCODIGO=$percodigocontacto
      WHERE EXPNOMBRE=$expnombre ";

    $err = sql_execute($query, $conn,$trans);
    }
      
   
  }
    

}




if ($err == 'SQLACCEPT' && $errcod == 0) {
  
  sql_commit_trans($trans);
  $errcod = 0;
  $errmsg =  'Improtacion Exitosa'      /*TrMessage('Permiso actualizado!')*/;
} else {
  sql_rollback_trans($trans);
  $errcod = 2;
  $errmsg = ($errmsg == '') ? 'Error al importar' /*TrMessage('Error al actualizar el permiso!')*/ : $errmsg;
}

sql_close($conn);
function convBBBdatos($texto){
  $textofin = $texto;
  $textofin = str_replace(' ','+',$textofin);
  
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
  
  return $textofin;
}
echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
?>