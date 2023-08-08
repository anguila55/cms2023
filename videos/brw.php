<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC.'/sigma.php';
require_once GLBRutaFUNC.'/zdatabase.php';
require_once GLBRutaFUNC.'/zfvarias.php';
require_once GLBRutaFUNC.'/idioma.php';//Idioma


$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');

DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------


$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';

//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
//Guardo la session
$conn = sql_conectar(); //Apertura de Conexion
sql_close($conn);
//--------------------------------------------------------------------------------------------------------------

$filtronombre 	= (isset($_POST['filtronombre']))? $_POST['filtronombre'] : '';
//--------------------------------------------------------------------------------------------------------------
$imgAvatarNull = '../app-assets/img/avatar.png';
$pathimagenes = '../vidimg/';
$conn = sql_conectar(); //Apertura de Conexion
$liqueo=0;



$where = '';

if (is_numeric($filtronombre))
	{
		if($filtronombre=='0'){
			//$where .= " AND PRECATEGO = 'SPONSOR' ";
		}else{

			$numero = intval($filtronombre);
		
			$query = "	SELECT CATDESCRI
				FROM VID_CAT WHERE CATREG = $numero
			 	";
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$catdescri 	= trim($row['CATDESCRI']);
		$where .= " AND VIDCATEGO = '$catdescri' ";
		}


		}
	}else{

		$where .= "";

	}





$query="SELECT VIDREG,VIDTITULO,VIDURL,VIDURLPDF,VIDID,US_NOMBRE,US_MAIL,US_TEL,US_EMP,US_PAI,US_LIN,US_FAC,US_TWI,US_WEB,VIDIMG,VIDCATEGO
		FROM VID_MAEST
		WHERE ESTCODIGO=1 $where
		ORDER BY VIDREG DESC"; 
$Table = sql_query($query,$conn);

$colvid=0;
for($i=0; $i<$Table->Rows_Count; $i++){
 	$row = $Table->Rows[$i];
 	$vidreg 	= trim($row['VIDREG']);
	$vidtitulo 	= trim($row['VIDTITULO']);
	$vidurl 	= trim($row['VIDURL']);
	$vidurlpdf 	= trim($row['VIDURLPDF']);
	$vidid	 	= trim($row['VIDID']);
	$usnombre 	= trim($row['US_NOMBRE']);
	$usmail 	= trim($row['US_MAIL']);
	$ustelefono = trim($row['US_TEL']);
	$usempresa 	= trim($row['US_EMP']);
	$uspais 	= trim($row['US_PAI']);
	$uslinkedin = trim($row['US_LIN']);
	$usfacebook	= trim($row['US_FAC']);
	$ustwitter 	= trim($row['US_TWI']);
	$usweb 	= trim($row['US_WEB']);
	$vidimg 	= trim($row['VIDIMG']);
	$vidcatego 	= trim($row['VIDCATEGO']);
	

	
	$tmpl->setCurrentBlock('tabs');
	 $tmpl->setCurrentBlock('videos');
	 
	 $tmpl->setVariable('botonclick'		, 'fa-youtube-play');
	 $tmpl->setVariable('botonclicklink'		, "addPoints(".$vidreg.",'',9)");
	 	
	if ($vidurl==''){

		$tmpl->setVariable('displayvideo'		, 'display:none;');

		if ($vidurlpdf != ''){
			$tmpl->setVariable('botonclicklink'		, "addPoints(".$vidreg.",'".$vidurlpdf."',10)");
			$tmpl->setVariable('botonclick'		, 'fa-files-o');	
		}


	}
	if ($vidurlpdf==''){

		$tmpl->setVariable('displaypdf'		, 'display:none;');

	}
	if ($usnombre==''){

		$tmpl->setVariable('displayusuario'		, 'display:none;');

	}
	$tmpl->setVariable('hashtag'		, '');
	

	$queryliqueo = "SELECT VIDREG
					FROM VID_LIKE 
					WHERE VIDREG=$vidreg AND PERCODIGO=$percodigo ";				
		$Tableliqueo = sql_query($queryliqueo,$conn);
		if($Tableliqueo->Rows_Count>0){
			$liqueo = 1;
		}	
		

		if($liqueo==1){

			$tmpl->setVariable('colorlike'	, 'color: #00005f!important;');
			$liqueo=0;
		}else{

			$tmpl->setVariable('colorlike'	, 'color: #FFFFFF!important;');
		}
 	$tmpl->setVariable('vidreg', $vidreg);
	$tmpl->setVariable('vidtitulo'	, $vidtitulo);
	$tmpl->setVariable('vidurl'		, $vidurl);
	$tmpl->setVariable('vidurlpdf'		, $vidurlpdf);
	$tmpl->setVariable('vidid'	, $vidid);
	$tmpl->setVariable('usnombre'		, $usnombre);
	$tmpl->setVariable('usmail'		, $usmail);
	$tmpl->setVariable('ustelefono'	, $ustelefono);
	$tmpl->setVariable('usempresa'		, $usempresa);
	$tmpl->setVariable('uspais'		, $uspais);
	$tmpl->setVariable('uslinkedin'	, $uslinkedin);
	$tmpl->setVariable('usfacebook'		, $usfacebook);
	$tmpl->setVariable('ustwitter'	, $ustwitter);
	$tmpl->setVariable('usweb'		, $usweb);
	$tmpl->setVariable('vidcatego'		, $vidcatego);

	if ($vidimg == '') {
		$vidimg = $imgAvatarNull;
	} else {
		$vidimg = $pathimagenes . $vidreg . '/' . $vidimg;
	}
	$tmpl->setVariable('vidimg'		, $vidimg);
	
$tmpl->parse('videos');
$tmpl->parse('tabs');
}


//--------------------------------------------------------------------------------------------------------------

sql_close($conn);
$tmpl->show();

?>	