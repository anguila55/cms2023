<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/idioma.php'; //Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$mescodigo = (isset($_POST['mescodigo']))? trim($_POST['mescodigo']) : 0;
	$estcodigo = 1; //Activo por defecto
	$secnumero = '';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($mescodigo!=0){
		$query = "	SELECT MESCODIGO, MESNUMERO, ESTCODIGO, PERCODIGO
					FROM MES_MAEST
					WHERE MESCODIGO=$mescodigo";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$mescodigo = trim($row['MESCODIGO']);
			$mesnumero = trim($row['MESNUMERO']);
			$estcodigo = trim($row['ESTCODIGO']);
			$usuario = trim($row['PERCODIGO']);
			
			$tmpl->setVariable('mescodigo'	, $mescodigo	);
			$tmpl->setVariable('mesnumero'	, $mesnumero	);

			if ($estcodigo == 1){
				$tmpl->setVariable('selectedusuario'	, 'selected'	);
			}else{
				$tmpl->setVariable('selectedflotante'	, 'selected'	);
			}
			
			$tmpl->setVariable('disablededit'	, 'disabled'	);
		}

	}


	$arrayperfileslista=null;
//Seleccionamos los perfiles
$query = "	SELECT PERCOMPAN,PERNOMBRE,PERAPELLI,PERCODIGO
				FROM PER_MAEST 
				WHERE ESTCODIGO=1 AND TIPO<>0
				ORDER BY PERCOMPAN ASC,UPPER(PERNOMBRE) ";
$Table = sql_query($query, $conn);
$tmpl->setVariable('unoseleccionado', 'false');
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$percod 	= trim($row['PERCODIGO']);
	$pernombre	= trim($row['PERNOMBRE']);
	$perapelli	= trim($row['PERAPELLI']);
	$percompan	= trim($row['PERCOMPAN']);
	
	$tmpl->setVariable('percodigo', $percod);
	$tmpl->setVariable('pernombre', $pernombre);
	$tmpl->setVariable('perapelli', $perapelli);
	$tmpl->setVariable('percompan', $percompan);
	
	$percontacto='';

	if ($usuario==$percod){
		$percontacto='selected';
	}

	$tmpl->setVariable('percodigo', $percod);
	$tmpl->setVariable('pernombre', $pernombre);
	$tmpl->setVariable('perapelli', $perapelli);
	$tmpl->setVariable('percompan', $percompan);
	$arrayperfileslista[]=['percodigo'=>$percod,'pernombre'=>$pernombre,'perapelli'=>$perapelli,'percompan'=>$percompan,'percontacto'=>$percontacto];

	
}
$myJSON = json_encode($arrayperfileslista);
$tmpl->setvariable('arrayperfileslista'		, $myJSON);
	
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
