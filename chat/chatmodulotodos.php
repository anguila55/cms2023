<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/agora/RtcTokenBuilder.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC.'/constants.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('chatmodulotodos.html');
	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar = (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	$pertipo 	= (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) : '';
	$perclase 	= (isset($_SESSION[GLBAPPPORT.'PERCLASE']))? trim($_SESSION[GLBAPPPORT.'PERCLASE']) : '';
//--------------------------------------------------------------------------------------------------------------

$pathimagenes = '../perimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';

$conn = sql_conectar(); //Apertura de Conexion

//--------------------------------------------------------------------------------------------------------------
//Registracion del usuario
//2:##:Usuario2:##:NULL:##:NULL:##:REGISTRACIONUSUARIOCHAT
$chatregister = "$percodigo:##:$pernombre:##:NULL:##:NULL:##:REGISTRACIONUSUARIOCHAT";
$tmpl->setVariable('percodigo', $percodigo);
$tmpl->setVariable('pernombre', $pernombre);
$tmpl->setVariable('chatregister', $chatregister);

$percoddst 	= (isset($_POST['percoddst'])) ? trim($_POST['percoddst']) : 0;
$fltbuscar 	= (isset($_POST['fltbuscar'])) ? trim($_POST['fltbuscar']) : '';
$tmpl->setVariable('fltbuscar'	, $fltbuscar	);

if ($percoddst != 0) {
	$tmpl->setVariable('scriptloadchat', "loadChatData($percoddst);");
}

$where='';
if($fltbuscar!=''){
	$where .= " AND (PERNOMBRE CONTAINING '$fltbuscar' OR PERAPELLI CONTAINING '$fltbuscar' OR PERCOMPAN CONTAINING '$fltbuscar') ";
}

$new_time = date("Y-m-d H:i:s", strtotime('-3 hours'));
//--------------------------------------------------------------------------------------------------------------
//Busco los perfiles del chat
$query  = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN,PERCODIGO,PERAVATAR
				FROM PER_MAEST 
				WHERE ESTCODIGO=1 AND PERCODIGO<>$percodigo $where
				ORDER BY PERNOMBRE ";
//logerror($query);
$Table 	= sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$pernom 	= trim($row['PERNOMBRE']);
	$perape 	= trim($row['PERAPELLI']);
	$compan 	= trim($row['PERCOMPAN']);
	$codigo 	= trim($row['PERCODIGO']);
	$peravatar =  trim($row['PERAVATAR']);

	
	$tmpl->setCurrentBlock('charlastodos');
	///Las personas que se hayan logueado al menos 3 horas antes. Porque no estamos guardando la duracion del logueo. Las 3 horas son son a modo de ejemplo

		if($peravatar!=''){
			if(strpos($peravatar, "https://") !== false){

				$tmpl->setVariable('charlaavatar'	, $peravatar);
			
			}else{
				$tmpl->setVariable('charlaavatar'	, $pathimagenes.$codigo.'/'.$peravatar);
			}
			
		}else{
			$tmpl->setVariable('charlaavatar'	, $imgAvatarNull);
		}
	$tmpl->setVariable('charlapercodigo', $codigo);
	$tmpl->setVariable('mostrarcartel', 'display: none;');
	$tmpl->setVariable('charlapernombre', $pernom);
	$tmpl->setVariable('charlaperapelli', $perape);
	$tmpl->setVariable('charlapercompan', $compan);
	

	///////////// Ultimos conectados ///////////////////
	$queryonline = "	SELECT PERCODIGO
					FROM PER_MAEST 
					WHERE PERCODIGO=$codigo AND (PERINGLOG IS NOT NULL) AND (PERULTLOG>='$new_time') AND ESTCODIGO=1";	
	$Tablelinea = sql_query($queryonline, $conn);
	if ($Tablelinea->Rows_Count>0){	
	
		$tmpl->setVariable('perfilesenlinea'	, 'background:green;');
	}else{
		$tmpl->setVariable('perfilesenlinea'	, "background:red;");
	}
	///////////////////////////////////////////////////
	
	$tmpl->parse('charlastodos');
}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();
//--------------------------------------------------------------------------------------------------------------

?>	
