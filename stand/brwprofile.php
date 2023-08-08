<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------

//Configuracion del sitio
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC.'/idioma.php';//Idioma	


//Creo Sigma y le cargo una vista
$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');
DDIdioma($tmpl);

//Variables utiles
$estcodigo = 1; 
$pathimagenes = '../expimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';
$tmpl->setVariable('expavatar', $imgAvatarNull);
$tmpl->setVariable('imgAvatarNull', $imgAvatarNull);


//Datos entrante y validacion
$percodlog 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
$expreg 	= (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;

//$expslug 	= (isset($_POST['expslug'])) ? trim($_POST['expslug']) : '';


//Apertura de Conexion
$conn = sql_conectar(); 


//Busco el sponsor y sus tablas relacionales.
$query="SELECT 	EM.EXPREG,EM.EXPNOMBRE,EM.EXPDIRECCION,EM.EXPTELEFO,EM.EXPMAIL,EM.EXPWEB,EM.EXPLINKED,
				EM.EXPAVATAR,EM.EXPCATEGO,EM.EXPRUBROS,EM.EXPSTAND,EM.EXPPOSX,EM.EXPPOSY,EM.ESTCODIGO,
				EM.PERCODIGO,EM.EXPPOS,EM.EXPBANIMG1,
				PM.PERCODIGO AS PERFIL_RELACIONADO,PM.PERNOMBRE,PM.PERAPELLI, PM.PERTIPO
		FROM EXP_MAEST EM 
		LEFT OUTER JOIN PER_MAEST PM ON EM.PERCODIGO = PM.PERCODIGO
		WHERE EM.EXPREG=$expreg ";






$Table = sql_query($query, $conn); 

for ($i = 0; $i < $Table->Rows_Count; $i++) {

	
	$row = $Table->Rows[$i];
	//Datos base del sponsor
	$expreg 		= trim($row['EXPREG']);
	$expnombre 		= trim($row['EXPNOMBRE']);
	$expdireccion 	= trim($row['EXPDIRECCION']);
	$exptelefo 		= trim($row['EXPTELEFO']);
	$expmail 		= trim($row['EXPMAIL']);
	$expweb 		= trim($row['EXPWEB']);
	$explinked 		= trim($row['EXPLINKED']);
	$expcatego		= trim($row['EXPCATEGO']);

	//Avatar
	$expavatar 	= trim($row['EXPAVATAR']);
	$expbanimg1 	= trim($row['EXPBANIMG1']);

	//sobre la empresa
	$exprubros 	= trim($row['EXPRUBROS']);

	//datos del evento
	$expstand 	= trim($row['EXPSTAND']);
	$expposx 	= trim($row['EXPPOSX']);
	$expposy 	= trim($row['EXPPOSY']);
	$estcodigo 	= trim($row['ESTCODIGO']);
	$percodexp 	= trim($row['PERCODIGO']);
	$exppos 	= trim($row['EXPPOS']);


	//Perfil Relacionado
	$perfil_Relacionado 	= trim($row['PERFIL_RELACIONADO']);
	$pertipo 				= trim($row['PERTIPO']);


	$tmpl->setCurrentBlock('browser');

	//Datos de la empresa
	if ($expcatego==1){
		$tmpl->setVariable('Paramsiniframe'	, 'display:none;'	);
		$tmpl->setVariable('Paramconiframe'	, 'display:;'	);

	}else{

		$tmpl->setVariable('Paramsiniframe'	, 'display:;'	);
		$tmpl->setVariable('Paramconiframe'	, 'display:none;'	);


	}

	$tmpl->setVariable('expnombre'	, $expnombre);
	$tmpl->setVariable('exprubros'	, $exprubros);
	$tmpl->setVariable('expdireccion'	, $expdireccion);
	$tmpl->setVariable('exptelefo'	, $exptelefo);
	$tmpl->setVariable('expmail'	, $expmail);
	$tmpl->setVariable('expweb'		, $expweb);
	$tmpl->setVariable('explinked'	, $explinked);
	$tmpl->setVariable('expavatar'	, $pathimagenes.$expreg.'/'.$expavatar);
	$tmpl->setVariable('expbanimg1'	, $pathimagenes.$expreg.'/'.$expbanimg1);


	//Perfil relacionado
	$tmpl->setVariable('perfil_relacionado'	, $perfil_Relacionado);
	$tmpl->setVariable('pertipo'			, $pertipo);

	


	//Productos
	$productos ="SELECT  PRODREG,PRODNOMBRE, PRODIMG, PRODFOLLETO
						FROM EXP_PROD
						WHERE EXPREG = $expreg";

	$Table_Productos = sql_query($productos, $conn); 

	$prodActive = 'active';

	for ($prod_index = 0; $prod_index < $Table_Productos->Rows_Count; $prod_index++) {
		
		$row_prod = $Table_Productos->Rows[$prod_index];
		$prodnombre 	= trim($row_prod['PRODNOMBRE']);
		$prodimg 		= trim($row_prod['PRODIMG']);
		$prodfolleto 	= trim($row_prod['PRODFOLLETO']);


		$folderProd =  '../expimg/'.$expreg.'/';
		
		$tmpl->setCurrentBlock('productos');
		
		$tmpl->setVariable('prod-active'	, $prodActive);
		$tmpl->setVariable('prodnombre'		, $prodnombre);
		$tmpl->setVariable('prodimg'		, $folderProd.$prodimg);
		$tmpl->setVariable('prodfolleto'	, $folderProd.$prodfolleto);
		$tmpl->parse('productos');
		
		$prodActive = '';
		
	}


	//Texto
	$textos ="SELECT  TXTNOMBRE ,TXTDESCRI
						FROM EXP_TXT
						WHERE EXPREG = $expreg";

	$Table_textos = sql_query($textos, $conn); 

	for ($index_txt = 0; $index_txt < $Table_textos->Rows_Count; $index_txt++) {
		
		$row_txt = $Table_textos->Rows[$index_txt];
		$txtnombre 		= trim($row_txt['TXTNOMBRE']);
		$txtdescri 		= trim($row_txt['TXTDESCRI']);
		$tmpl->setCurrentBlock('textos');
		$tmpl->setVariable('txtnombre'		, $txtnombre);
		$tmpl->setVariable('txtdescri'		, $txtdescri);
		$tmpl->parse('textos');
		
		
	}

	//imagenes
	$imagenes ="SELECT  EXPIMG 
						FROM EXP_IMG
						WHERE EXPREG = $expreg";

	$Table_imagenes = sql_query($imagenes, $conn); 

	for ($index_img = 0; $index_img < $Table_imagenes->Rows_Count; $index_img++) {
		
		$row_img = $Table_imagenes->Rows[$index_img];

		$expimg 		= trim($row_img['EXPIMG']);
		$folderProd =  '../expimg/'.$expreg.'/';
		$tmpl->setCurrentBlock('imagenes');
		$tmpl->setVariable('expimg'		, $folderProd.$expimg);
		$tmpl->parse('imagenes');
		
		
	}

	
	//videos
	$videos ="SELECT  VIDURLID, VIDNOMBRE
						FROM EXP_VID
						WHERE EXPREG = $expreg";

	$Table_videos = sql_query($videos, $conn); 

	for ($index_videos = 0; $index_videos < $Table_videos->Rows_Count; $index_videos++) {
		
		$row_video = $Table_videos->Rows[$index_videos];

		$vidurlid 		= trim($row_video['VIDURLID']);
		$vidnombre 		= trim($row_video['VIDNOMBRE']);

		$tmpl->setCurrentBlock('videos');
		$tmpl->setVariable('vidurlid'		, $vidurlid);
		$tmpl->setVariable('vidnombre'		, $vidnombre);
		$tmpl->parse('videos');
		
		
	}
	

	$tmpl->parse('browser');
}




//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>