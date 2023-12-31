<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('enviar.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	//$msgreg = (isset($_SESSION[GLBAPPPORT.'MSGREG']))? trim($_SESSION[GLBAPPPORT.'MSGREG']) : '';
	$msgreg = (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$tipoenvio = (isset($_POST['tipoenvio']))? trim($_POST['tipoenvio']) : 0;
	
	
	
	
	$conn= sql_conectar();//Apertura de ConexionMSG

	$query="SELECT MSGREG, MSGTITULO, MSGDESCRI FROM MSG_CABE WHERE MSGREG=$msgreg";
	//logerror($query);
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$msgreg 	= trim($row['MSGREG']);
	$msgtitulo= trim($row['MSGTITULO']);
	//$msgdescri = trim($row['MSGDESCRI']);

	$tmpl->setVariable('msgreg'	, $msgreg);
	$tmpl->setVariable('msgtitulo'	, $msgtitulo);
	
	//TIpo de envio (1:Correo, 2:Notificacion)
	if($tipoenvio==1){
		$tmpl->setVariable('tipoenvio'	, 'Envio por Correo');
		$tmpl->setVariable('functionguardar'	, 'guardarMaestroCorreo');
	}else if($tipoenvio==2){
		$tmpl->setVariable('tipoenvio'	, 'Envio por Notificaciones');
		$tmpl->setVariable('functionguardar'	, 'guardarMaestroNotif');
	}

	
	$pertipcod='';

	//--------------------------------------------------------------------------------------------------------------
	//Tipo de Perfiles
	$query = "SELECT PERTIPO,PERTIPDES$IdiomView AS PERTIPDES
				FROM PER_TIPO
				WHERE ESTCODIGO=1			
				ORDER BY PERTIPO";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$pertipcod 	= trim($row['PERTIPO']);
		$pertipdes	= trim($row['PERTIPDES']);
		
		//SI el usuario no es admin, cargo solo los registros asignados
	
		$tmpl->setCurrentBlock('pertipos');
		$tmpl->setVariable('pertipcod'	, $pertipcod 		);
		$tmpl->setVariable('pertipdes'	, $pertipdes	);
		$tmpl->parse('pertipos');
			
		
	}
	//--------------------------------------------------------------------------------------------------------------
	//Clase de Perfiles
	if($pertipcod!=''){
		$query = "	SELECT PERCLASE,PERCLADES
					FROM PER_CLASE	
					WHERE PERTIPO=$pertipcod
					ORDER BY PERCLASE ";
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$perclacod 	= trim($row['PERCLASE']);
			$perclades	= trim($row['PERCLADES']);
			
			$tmpl->setCurrentBlock('perclases');
			$tmpl->setVariable('perclacod'	, $perclacod 	);
			$tmpl->setVariable('perclades'	, $perclades	);	
			$tmpl->parse('perclases');
			
		}
	}
	
	sql_close($conn);	
	$tmpl->show();
	
	
	
	
?>	
