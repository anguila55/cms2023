<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$msgreg = (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$msgestado = 1; //Activo por defecto
	$secdescri = '';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($msgreg!=0){
		$query = "SELECT MSGREG, MSGFCHREG, MSGTITULO, MSGDESCRI, MSGESTADO
					FROM MSG_CABE
					WHERE MSGREG=$msgreg";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$msgreg = trim($row['MSGREG']);
			$msgfchreg = trim($row['MSGFCHREG']);
			$msgtitulo = trim($row['MSGTITULO']);
			$msgdescri = trim($row['MSGDESCRI']);
			$msgestasdo = trim($row['MSGESTADO']);
			
			$tmpl->setVariable('msgreg'	, $msgreg	);
			$tmpl->setVariable('msgfchreg'	, $msgfchreg	);
			$tmpl->setVariable('msgtitulo'	, $msgtitulo	);
			$tmpl->setVariable('msgdescri'	, $msgdescri	);
			$tmpl->setVariable('msgestado'	, $msgestado	);

			
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
