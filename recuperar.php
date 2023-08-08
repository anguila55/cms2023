<?
	if(!isset($_SESSION))  session_start();
    include($_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
    require_once GLBRutaFUNC.'/constants.php';
	//require_once GLBRutaFUNC.'/idioma.php';//Idioma	

	

	$IdiomView = '';
	$peridioma = (isset($_GET['ID']))? trim($_GET['ID']):'ESP'; //Nota desde el home acceso directo	//--------------------------------------------------------------------------------------------------------------

	if ($peridioma=='ESP'){

	  require_once GLBRutaFUNC.'/idiomaesp.php';
	  
	  $IdiomView = strtoupper('esp');
	  
	  }else{
		  
		  require_once GLBRutaFUNC.'/idiomaing.php';
		  $IdiomView = strtoupper('ing');
	  }

	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('recuperar.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------

    //nombre evento
    $tmpl->setVariable('SisNombreEvento', NAME_TITLE );

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

sql_close($conn);
	
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	

