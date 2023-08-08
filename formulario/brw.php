<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';

	$tmpl= new HTML_Template_Sigma();
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);


	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';

	
	$usuario = (isset($_POST['usuario']))? trim($_POST['usuario']):'';
	
	
	$usuario		= VarNullBD($usuario		, 'N');
	



	$conn= sql_conectar();//Apertura de Conexion


	$query = "	SELECT *
	FROM VAR_MAEST WHERE USUARIO = $usuario";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
$row = $Table->Rows[$i];
$varreg 	= trim($row['VARREG']);
$vartitulo 	= trim($row['VARTITULO']);
$vardescri 	= trim($row['VARDESCRI']);
$vardescriing 	= trim($row['VARDESCRIING']);
$vardescripor 	= trim($row['VARDESCRIPOR']);
$mostrar	= trim($row['VARMOST']);
$requerida	= trim($row['VARREQ']);


$tmpl->setCurrentBlock('variables');

if($varreg <28){
	$tmpl->setVariable('displayacciones', 'd-none');	
}else{

	if($usuario!=0){

		$queryBusco = "	SELECT VARTITULO
		FROM VAR_MAEST WHERE USUARIO=0 AND VARREG = $varreg";
	
		$TableBusco = sql_query($queryBusco, $conn);
	
			if($TableBusco->Rows_Count<0){
				$tmpl->setVariable('displayacciones', '');	
			}else{
				$tmpl->setVariable('displayacciones', 'd-none');
			}



	}

}

$tmpl->setVariable('varreg', $varreg);
$tmpl->setVariable('vartitulo', $vartitulo);
$tmpl->setVariable('requerido', $vartitulo.'requerido');
$tmpl->setVariable('vardescri', $vardescri);
$tmpl->setVariable('vardescriing', $vardescriing);
$tmpl->setVariable('vardescripor', $vardescripor);
$tmpl->setVariable('mostrar', $mostrar);
$tmpl->setVariable('checkedmostrar', $mostrar== 'true'?'checked':'');
$tmpl->setVariable('requerida', $requerida);
$tmpl->setVariable('checkedrequerida', $requerida == 'true'? 'checked': '');
$tmpl->parse('variables');

$variables[]=['nombre'=>$vartitulo];
}
$tmpl->setVariable('variables', json_encode($variables));
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	$tmpl->show();

?>
