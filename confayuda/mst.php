<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	


$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('mst.html');
DDIdioma($tmpl);


$conn = sql_conectar(); //Apertura de Conexion

$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$perusuacc = (isset($_POST['perusuacc'])) ? trim($_POST['perusuacc']) : '';
$perpasacc = (isset($_POST['perpasacc'])) ? trim($_POST['perpasacc']) : '';
$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';

$tmpl->setVariable('percodnotif', $percodigo	);

if ($peradmin!=1){
		header('Location: ../login');	
}

$conn = sql_conectar(); //Apertura de Conexion


$errmsg 	= '';

$ayuperfil=0;
$query = "	SELECT *
			FROM ADM_AYUDA
			WHERE AYU_ID=0";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];

	$ayunumero 	= trim($row['AYU_NUMERO']);
	$ayucorreo 	= trim($row['AYU_CORREO']);
	$ayufaq 	= trim($row['AYU_FAQ']);
	$ayuperfil 	= trim($row['PERCODIGO']);
	

	$tmpl->setVariable('ayunumero'	, $ayunumero	);
	$tmpl->setVariable('ayucorreo'	, $ayucorreo	);
	$tmpl->setVariable('ayuperfil'	, $ayuperfil	);
	if ($ayufaq==1){

		$tmpl->setVariable('activo'	, 'selected'	);

	}else{

		$tmpl->setVariable('inactivo'	, 'selected'	);
	}
	

}

$arrayperfileslista=null;
//Seleccionamos los perfiles
$query = "	SELECT PERCOMPAN,PERNOMBRE,PERAPELLI,PERCODIGO
				FROM PER_MAEST 
				WHERE ESTCODIGO=1
				ORDER BY PERCOMPAN ASC,UPPER(PERNOMBRE) ";
$Table = sql_query($query, $conn);
$tmpl->setVariable('unoseleccionado', 'false');
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$percod 	= trim($row['PERCODIGO']);
	$pernombre	= trim($row['PERNOMBRE']);
	$perapelli	= trim($row['PERAPELLI']);
	$percompan	= trim($row['PERCOMPAN']);
	$tmpl->setCurrentBlock('perfiles');
	

	$perrelacionado='';



	 if($percod==$ayuperfil){
	 	$perrelacionado='selected';
	 }
	$tmpl->setVariable('percodigo', $percod);
	$tmpl->setVariable('pernombre', $pernombre);
	$tmpl->setVariable('perapelli', $perapelli);
	$tmpl->setVariable('percompan', $percompan);
	$tmpl->parse('perfiles');
	$arrayperfileslista[]=['percodigo'=>$percod,'perrelacionado'=>$perrelacionado,'pernombre'=>$pernombre,'perapelli'=>$perapelli,'percompan'=>$percompan];

	
}
$myJSON = json_encode($arrayperfileslista);
$tmpl->setvariable('arrayperfileslista'		, $myJSON);








//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
