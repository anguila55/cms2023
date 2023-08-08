<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------

//Configuracion del sitio
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	
require_once GLBRutaFUNC . '/constants.php'; //constantes	


//Creo Sigma y le cargo una vista
$tmpl = new HTML_Template_Sigma();


//Variables utiles
$estcodigo = 1;
$pathimagenes = '../expimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';
$tmpl->setVariable('expavatar', $imgAvatarNull);
$tmpl->setVariable('imgAvatarNull', $imgAvatarNull);


//Datos entrante y validacion
$percodlog 	= (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$expreg 	= (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;
$prodreg = (isset($_POST['prodreg'])) ? trim($_POST['prodreg']) : 0;






//$expslug 	= (isset($_POST['expslug'])) ? trim($_POST['expslug']) : '';

//Apertura de Conexion
$conn = sql_conectar();

$arrayorden=null;
$queryorden = "SELECT 	EXPREG,EXPPOS
		FROM EXP_MAEST
		WHERE ESTCODIGO <>3
		ORDER BY EXPPOS,EXPNOMBRE";
$Tableorden = sql_query($queryorden,$conn);
if($Tableorden->Rows_Count>0){
	for ($z = 0; $z < $Tableorden->Rows_Count; $z++) {
		$roworden = $Tableorden->Rows[$z];
		$regorden = trim($roworden['EXPREG']);
		$arrayorden[]=$regorden;
	}
}
$valororden=array_search($expreg, $arrayorden);


$querycategoria = "	SELECT CATVIS
					FROM EXP_CAT EC
					LEFT OUTER JOIN EXP_MAEST EM ON EC.CATREG=EM.EXPCATEGO 
					WHERE EM.EXPREG=$expreg";

$Tablecategoria = sql_query($querycategoria,$conn);
if($Tablecategoria->Rows_Count>0){
	$rowcatego= $Tablecategoria->Rows[0];
	$catvis = trim($rowcatego['CATVIS']);

}

if ($catvis == 1) {
	$tmpl->loadTemplateFile('brw2.html');
} else if ($catvis == 2) {
	$tmpl->loadTemplateFile('brw.html');
}else{
	$tmpl->loadTemplateFile('brw2.html');
}



DDIdioma($tmpl);

//Busco el sponsor y sus tablas relacionales.
$query = "SELECT 	EM.EXPREG,EM.EXPNOMBRE,EM.EXPDIRECCION,EM.EXPTELEFO,EM.EXPMAIL,EM.EXPWEB,EM.EXPLINKED,
				EM.EXPAVATAR,EM.EXPCATEGO,EM.EXPRUBROS,EM.EXPPRODDES4,EM.EXPSTAND,EM.EXPPOSX,EM.EXPPOSY,EM.ESTCODIGO,
				EM.PERCODIGO,EM.EXPPOS,EM.EXPBANIMG1,EM.EXPBANIMG3,EM.EXPFAC,EM.EXPTWI,EM.EXPINSTA,EM.EXPYOUTUB, EM.QRCODE,
				PM.PERCODIGO AS PERFIL_RELACIONADO,PM.PERNOMBRE,PM.PERAPELLI, PM.PERTIPO,EM.EXPREULNK
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
	$expfac 		= trim($row['EXPFAC']);
	$exptwi		= trim($row['EXPTWI']);
	$expinsta 		= trim($row['EXPINSTA']);
	$expcatego		= trim($row['EXPCATEGO']);
	$percargo		= trim($row['EXPYOUTUB']);
	$expreulnk 		= trim($row['EXPREULNK']);
	$pernombrecontacto = trim($row['PERNOMBRE']);
	$perapellicontacto = trim($row['PERAPELLI']);
	$tmpl->setVariable('pernombrecontacto', $pernombrecontacto);
	$tmpl->setVariable('perapellicontacto', $perapellicontacto);
	//Avatar
	$expavatar 	= trim($row['EXPAVATAR']);
	$expbanimg1 	= trim($row['EXPBANIMG1']);
	$expbanimg3 	= trim($row['EXPBANIMG3']);

	//sobre la empresa
	$exprubros 	= trim($row['EXPRUBROS']);
	$expbrolnk 	= trim($row['EXPPRODDES4']);
	

	//datos del evento
	$expstand 	= trim($row['EXPSTAND']);
	$expposx 	= trim($row['EXPPOSX']);
	$expposy 	= trim($row['EXPPOSY']);
	$estcodigo 	= trim($row['ESTCODIGO']);
	$percodcontacto 	= trim($row['PERCODIGO']);
	$exppos 	= trim($row['EXPPOS']);
	$qrreg 	= trim($row['QRCODE']);
	///////////// VISIBILIDAD BOTONES //////////////
	$tmpl->setVariable('visibilidaddireccion', 'd-none');
	$tmpl->setVariable('visibilidadtelefono', 'd-none');
	$tmpl->setVariable('visibilidadmail', 'd-none');
	$tmpl->setVariable('visibilidadcargo', 'd-none');
	$tmpl->setVariable('visibilidadnetworking', 'd-none');
	$tmpl->setVariable('visibilidadchat', 'd-none');
	$tmpl->setVariable('visibilidadbrochure', 'd-none');

	/// BUSCO LOS STAND PREVIOS Y SIGUIENTES

	if ($Tableorden->Rows_Count>1){
		if ($valororden==0){
			
			$tmpl->setVariable('displayanterior', 'd-none');
			$tmpl->setVariable('standnro1', $arrayorden[($valororden+1)]);
		}
		if ($valororden==($Tableorden->Rows_Count-1)){
			
			$tmpl->setVariable('displaysiguiente', 'd-none');
			$tmpl->setVariable('standnro', $arrayorden[($valororden-1)]);
		}
		if ($valororden!=0 && $valororden!=($Tableorden->Rows_Count-1))
		{
			$tmpl->setVariable('standnro', $arrayorden[($valororden-1)]);
			$tmpl->setVariable('standnro1', $arrayorden[($valororden+1)]);
		}
	}else{
		$tmpl->setVariable('displayanterior', 'd-none');
		$tmpl->setVariable('displaysiguiente', 'd-none');
	}
	

	/// CIERRO LA BUSQUEDA DEL SIGUIENTE STAND

	//Perfil Relacionado
	//$perfil_Relacionado 	= trim($row['PERFIL_RELACIONADO']);
	//$pertipo 				= trim($row['PERTIPO']);


	$tmpl->setCurrentBlock('browser');

	$tmpl->setVariable('styleypf', '');
	//Datos de la empresa
	if ($expcatego == 1) {
		$tmpl->setVariable('Paramsiniframe', 'display:none;');
		$tmpl->setVariable('Paramconiframe', 'display:;');

		$tmpl->setVariable('displayimage', 'display:;');
		$tmpl->setVariable('displayiframe', 'display:none;');
		$tmpl->setVariable('iframeexpositor', '<div class=" d-flex justify-content-center"><img width="100%" height="100%" src="{expbanimg1}" alt=""></div>');
	} else {

		$tmpl->setVariable('Paramsiniframe', 'display:;');
		$tmpl->setVariable('Paramconiframe', 'display:none;');
	}

	$tmpl->setVariable('expreg', $expreg);
	$tmpl->setVariable('expnombre', $expnombre);
	$tmpl->setVariable('exprubros', nl2br($exprubros));
	if ($expdireccion != '') {
		$tmpl->setVariable('visibilidaddireccion', '');
	
		
	}
	$tmpl->setVariable('expdireccion', $expdireccion);
	if ($exptelefo != '') {
		$tmpl->setVariable('visibilidadtelefono', 'd-flex');
	
		
	}
	$tmpl->setVariable('exptelefo', $exptelefo);
	if ($expmail!= '') {
		$tmpl->setVariable('visibilidadmail', '');
		
	}
	$tmpl->setVariable('expmail', $expmail);
	if ($percodcontacto!=0){
		$tmpl->setVariable('visibilidadchat', '');
	}
	$tmpl->setVariable('percodcontacto', $percodcontacto);

	


	////////////////////////////////////////////////
	if($expreulnk==''){
		$tmpl->setVariable('visibilidadnetworking'	, 'd-none'	);
	}else{
		$tmpl->setVariable('visibilidadnetworking'	, ''	);
	}

	$tmpl->setVariable('expreulnk'		, $expreulnk 		);
	
	////////////////////////////////////////////////

	if ($expweb == '') {
		$tmpl->setVariable('verweb', 'display:none;');
		
	} else {
		$tmpl->setVariable('verweb', 'display:;');
	}
	if ($explinked == '') {
		$tmpl->setVariable('verlinkedin', 'display:none;');
		
	} else {
		$tmpl->setVariable('verlinkedin', 'display:;');
	}
	if ($expfac == '') {
		$tmpl->setVariable('verfacebook', 'display:none;');
		
	} else {
		$tmpl->setVariable('verfacebook', 'display:;');
	}
	if ($exptwi == '') {
		$tmpl->setVariable('vertwitter', 'display:none;');
		
	} else {
		$tmpl->setVariable('vertwitter', 'display:;');
	}
	if ($expinsta == '') {
		$tmpl->setVariable('verinstagram', 'display:none;');
		
	} else {
		$tmpl->setVariable('verinstagram', 'display:;');
	}
	$tmpl->setVariable('expweb', $expweb);
	if ($percargo!=''){
		$tmpl->setVariable('visibilidadcargo', '');
	}
	if ($expbrolnk!=''){

		$tmpl->setVariable('visibilidadbrochure', '');

	}
	$tmpl->setVariable('expbrolnk', $expbrolnk);
	$tmpl->setVariable('percargo', $percargo);
	$tmpl->setVariable('explinked', $explinked);
	$tmpl->setVariable('expfac', $expfac);
	$tmpl->setVariable('exptwi', $exptwi);
	$tmpl->setVariable('expinsta', $expinsta);
	$tmpl->setVariable('scriptbrwprofile', "showStandscount($expreg);");
	$tmpl->setVariable('expavatar', $pathimagenes . $expreg . '/' . $expavatar);
	$tmpl->setVariable('expbanimg1', $pathimagenes . $expreg . '/' . $expbanimg1);
	$tmpl->setVariable('expbanimg3', $pathimagenes . $expreg . '/' . $expbanimg3);
	$tmpl->setVariable('qrreg', $qrreg);


	//Perfiles
	$perfiles = "SELECT  PM.PERCODIGO, PM.PERCOMPAN, PM.PERTIPO, PM.PERCLASE
						FROM PER_MAEST PM
						LEFT OUTER JOIN EXP_PER P ON P.PERCODIGO=PM.PERCODIGO
						WHERE P.EXPREG = $expreg";

	$Table_Perfiles = sql_query($perfiles, $conn);

	for ($perfiles_index = 0; $perfiles_index < $Table_Perfiles->Rows_Count; $perfiles_index++) {

		$row_perfiles = $Table_Perfiles->Rows[$perfiles_index];
		$percodexp 	= trim($row_perfiles['PERCODIGO']);
		$pertipo 	= trim($row_perfiles['PERTIPO']);
		
		
		///////////// VISIBILIDAD BOTONES //////////////
	
	
	
		

		$tmpl->setVariable('percodexp', $percodexp);
		$tmpl->setVariable('pertipo', $pertipo);
	
	}

	//Productos
	$productos = "SELECT  PRODREG,PRODNOMBRE, PRODIMG, PRODFOLLETO
						FROM EXP_PROD
						WHERE EXPREG = $expreg";

	$Table_Productos = sql_query($productos, $conn);

	$prodActive = 'active';

	if ($Table_Productos->Rows_Count < 0){
		$tmpl->setVariable('muestroseccionproductos', 'd-none');
	}else{
		$tmpl->setVariable('muestroseccionproductos', '');
	}

	for ($prod_index = 0; $prod_index < $Table_Productos->Rows_Count; $prod_index++) {

		$row_prod = $Table_Productos->Rows[$prod_index];
		$prodreg 		= trim($row_prod['PRODREG']);
		$prodnombre 	= trim($row_prod['PRODNOMBRE']);
		$prodimg 		= trim($row_prod['PRODIMG']);
		$prodfolleto 	= trim($row_prod['PRODFOLLETO']);

		$tmpl->setVariable('visibilidadflechas', 'display:none;');
		if ($Table_Productos->Rows_Count>1){
			$tmpl->setVariable('visibilidadflechas', '');
		}
		$folderProd =  '../expimg/' . $expreg . '/';

		$tmpl->setCurrentBlock('productos');

		$tmpl->setVariable('prod-active', $prodActive);
		$tmpl->setVariable('prodreg', $prodreg);
		$tmpl->setVariable('prodnombre', $prodnombre);
		$tmpl->setVariable('prodimg', $folderProd . $prodimg);

		if (($IdiomView == 'ING')) {
			$tmpl->setVariable('des_informacion1', 'Download more information');
		} else {


			$tmpl->setVariable('des_informacion1', 'Descargar más información');
		}

		if ($prodfolleto == '') {
			$tmpl->setVariable('displayproductos', 'display:none;');
		} else {
			$tmpl->setVariable('displayproductos', 'display:;');
		}
		$tmpl->setVariable('prodfolleto', $folderProd . $prodfolleto);
		$tmpl->parse('productos');

		$prodActive = '';
	}


	//Texto
	$textos = "SELECT  TXTNOMBRE ,TXTDESCRI
						FROM EXP_TXT
						WHERE EXPREG = $expreg";

	$Table_textos = sql_query($textos, $conn);

	for ($index_txt = 0; $index_txt < $Table_textos->Rows_Count; $index_txt++) {

		$row_txt = $Table_textos->Rows[$index_txt];
		$txtnombre 		= trim($row_txt['TXTNOMBRE']);
		$txtdescri 		= trim($row_txt['TXTDESCRI']);
		$tmpl->setCurrentBlock('textos');
		$tmpl->setVariable('txtnombre', $txtnombre);
		$tmpl->setVariable('txtdescri', nl2br($txtdescri));
		$tmpl->parse('textos');
	}

	//imagenes
	$imagenes = "SELECT  EXPIMG 
						FROM EXP_IMG
						WHERE EXPREG = $expreg";

	$Table_imagenes = sql_query($imagenes, $conn);

	if ($Table_imagenes->Rows_Count < 0){
		$tmpl->setVariable('muestroseccionimagenes', 'd-none');
	}else{
		$tmpl->setVariable('muestroseccionimagenes', '');
	}

	for ($index_img = 0; $index_img < $Table_imagenes->Rows_Count; $index_img++) {

		$row_img = $Table_imagenes->Rows[$index_img];

		$expimg 		= trim($row_img['EXPIMG']);
		$folderProd =  '../expimg/' . $expreg . '/';
		$tmpl->setCurrentBlock('imagenes');
		if ($index_img == 0){
			$tmpl->setVariable('activeimg', 'active');
		}else{
			$tmpl->setVariable('activeimg', '');	
		}
		$tmpl->setVariable('expimg', $folderProd . $expimg);
		$tmpl->parse('imagenes');
	}

	//HIBRIDO-DIGITAL
	$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoEvento'";
	$Table2 = sql_query($query2, $conn);
	for ($i = 0; $i < $Table2->Rows_Count; $i++) {
		$row = $Table2->Rows[$i];
		$tipoevento = trim($row['ZVALUE']);
		if ($tipoevento != 'false') {

			$tmpl->setVariable('mostrarqr', '');
		} else {
			$tmpl->setVariable('mostrarqr', 'd-none');
		}
	}


	/// PARA STAND 2
	$variablevideo = 0;
	//videos
	$videos = "SELECT  VIDURLID, VIDNOMBRE
						FROM EXP_VID
						WHERE EXPREG = $expreg";

	$Table_videos = sql_query($videos, $conn);

	if ($Table_videos->Rows_Count < 0){
		$tmpl->setVariable('muestroseccionvideos', 'd-none');
	}else{
		$tmpl->setVariable('muestroseccionvideos', '');
	}

	for ($index_videos = 0; $index_videos < $Table_videos->Rows_Count; $index_videos++) {

		$row_video = $Table_videos->Rows[$index_videos];

		$vidurlid 		= trim($row_video['VIDURLID']);
		$vidnombre 		= trim($row_video['VIDNOMBRE']);

		$tmpl->setCurrentBlock('videos');
		$tmpl->setVariable('vidurlid', $vidurlid);
		$tmpl->setVariable('vidnombre', $vidnombre);
		$tmpl->parse('videos');

		/// PARA STAND 2
		if ($variablevideo == 0) {
			$tmpl->setVariable('vidurlid0', $vidurlid);
			$tmpl->setVariable('vidurlid0_0', $vidurlid);
			$variablevideo++;
		} else if ($variablevideo == 1) {
			$tmpl->setVariable('vidurlid1', $vidurlid);
			$variablevideo++;
		} else if ($variablevideo == 2) {
			$tmpl->setVariable('vidurlid2', $vidurlid);
			$variablevideo++;
		}
	}

	/// PARA STAND 2
	if ($variablevideo == 0) {
		$tmpl->setVariable('contadorvideos', '');
		$tmpl->setVariable('visflechai', 'visibility:hidden;');
		$tmpl->setVariable('visflechad', 'visibility:hidden;');
	} else if ($variablevideo == 1) {
		$tmpl->setVariable('contadorvideos', '1/1');
		$tmpl->setVariable('visflechai', 'visibility:hidden;');
		$tmpl->setVariable('visflechad', 'visibility:hidden;');
	} elseif ($variablevideo == 2) {
		$tmpl->setVariable('contadorvideos', '1/2');
	} elseif ($variablevideo == 3) {
		$tmpl->setVariable('contadorvideos', '1/3');
	}


	$tmpl->parse('browser');
}




//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>