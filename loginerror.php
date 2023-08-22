<?
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/constants.php';
	//require_once GLBRutaFUNC.'/idioma.php';//Idioma	

	

	$IdiomView = '';
	$peridioma = (isset($_GET['ID']))? trim($_GET['ID']):'ESP'; //Nota desde el home acceso directo	//--------------------------------------------------------------------------------------------------------------
    
	if ($peridioma=='ESP'){

		require_once GLBRutaFUNC.'/idiomaesp.php';
		
		$IdiomView = strtoupper('esp');
		
		}else if ($peridioma=='ING'){
			
			require_once GLBRutaFUNC.'/idiomaing.php';
			$IdiomView = strtoupper('ing');
		}else if ($peridioma=='POR'){
			
		  require_once GLBRutaFUNC.'/idiomapor.php';
		  $IdiomView = strtoupper('por');
	  }

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('loginerror.html');
DDIdioma($tmpl);
$tmpl->setVariable('peridioma', $peridioma );

if (isset($_GET['QRCode'])) {
    $codigoqr = trim($_GET['QRCode']);
}
$tmpl->setVariable('codigoqr', $codigoqr );
	
	$_SESSION[GLBAPPPORT.'PERCODIGO'] = '';
	
	//Nombre del Evento
	// if(isset($_SESSION['PARAMETROS']['SisNombreEvento'])){
	// 	$tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);	
	// }
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
	$tmpl->setVariable('NAME_TITLE', NAME_TITLE );
	$tmpl->setVariable('LOGIN_PERIOD', LOGIN_PERIOD );
	$tmpl->setVariable('LOGIN_EMAIL', SEND_MAIL_LOGIN );
	$tmpl->setVariable('REGISTRO_URL', SEND_MAIL_REGISTRO );
$tmpl->setVariable('RECUPERAR_URL', SEND_MAIL_RECUPERAR );

	$conn= sql_conectar();//Apertura de Conexion

$pathimagenes = '../admimg/';
$imgBannerHomeNull	= '../assets-nuevodisenio/img/bannerhome.jpg';
$tmpl->setVariable('imgProductoNull'	, $imgBannerHomeNull 	);
$tmpl->setVariable('displaybannervideo'	, 'd-none' 	);
$query = "	SELECT BANNERS
			FROM ADM_IMG
            WHERE BANID<2";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];
    $bannerhomeimgchico 	= trim($row['BANNERS']);


	if($bannerhomeimgchico==''){ 
		$bannerhomeimgchico = $imgBannerHomeNull;
	}else{
		$bannerhomeimgchico = $pathimagenes.$bannerhomeimgchico;
	}

	if($i==0){
		$tmpl->setVariable('bannerhomeimginferior'	, $bannerhomeimgchico	);
	}else{
		$tmpl->setVariable('bannerhomeimginferiorcel'	, $bannerhomeimgchico	);
	}
}
// traigo valor de registro
$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'OcRegistro'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$ocregistro = trim($row['ZDESCRI']);
	if($ocregistro == "si"){
		$tmpl->setVariable('ocultar', 'd-none');
	}else{
		$tmpl->setVariable('ocultar', '');

	}
}

$query = "	SELECT BANNERS
			FROM ADM_IMG WHERE BANID='10'";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];
	$file 	= trim($row['BANNERS']);
	$tmpl->setVariable('file', $file);

}


sql_close($conn);
	
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
?>	
