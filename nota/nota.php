<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma
require_once GLBRutaFUNC . '/constants.php'; //Idioma

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('nota.html');
DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';





$prereg1 = (isset($_POST['prereg']))? trim($_POST['prereg']) : 0;





$conn = sql_conectar(); //Apertura de Conexion
$precategoria='';

$pathimg = '../prensaimg';
$conn = sql_conectar(); //Apertura de Conexion

$query = "	SELECT  PR.PREREG,  PR.PRETITULO, PR.PREDESCRI,  PR.PREIMG,  PR.PREURL,  PR.PERCODIGO,  PR.PRECATEGO,  PR.EXPREG, PR.PREESTADO, PR.PREFUENTE, PR.PREBAJADA, PR.PREFECHA
				FROM PREN_MAEST PR
                WHERE PR.PREREG = $prereg1
				ORDER BY PREREG ";
		
	$Table = sql_query($query,$conn);
	if($Table->Rows_Count>0){
        $row= $Table->Rows[0];
		$prereg 		= trim($row['PREREG']);
		$pretitulo 		= trim($row['PRETITULO']);
		$predescri 		= trim($row['PREDESCRI']);
		$preimg		    = trim($row['PREIMG']);
		$percodigo     	= trim($row['PERCODIGO']);
		$preurl    		= trim($row['PREURL']);
		$precatego    	= trim($row['PRECATEGO']);
		$expreg     	= trim($row['EXPREG']);
		$preestado    	= trim($row['PREESTADO']);
		$prefuente     	= trim($row['PREFUENTE']);
		$prebajada    	= trim($row['PREBAJADA']);
		$prefecha    	= BDConvFch($row['PREFECHA']);
		$precategoria = $precatego;

		$prefecha	= substr($prefecha,0,2).'-'.substr($prefecha,3,2).'-'.substr($prefecha,6,4);
	
		
		$tmpl->setVariable('prereg'			, $prereg);
		$tmpl->setVariable('pretitulo'		, $pretitulo);
		htmlspecialchars_decode($predescri);
		$tmpl->setVariable('predescri'	, htmlspecialchars_decode($predescri));
		$tmpl->setVariable('preimg', $pathimg . '/' . $prereg . '/' . $preimg);
		$tmpl->setvariable('percodigopre'	, $percodigo);
		$tmpl->setvariable('preurl'			, $preurl);
		$tmpl->setvariable('precatego'		, $precatego);
		$tmpl->setvariable('expreg'			, $expreg);
		$tmpl->setvariable('preestado'		, $preestado);
		$tmpl->setvariable('prefuente'			, $prefuente);
		$tmpl->setvariable('prebajada'	, $prebajada);
		$tmpl->setvariable('prefecha'			, $prefecha);
		$tmpl->setvariable('precompartir'			, URL_WEB.'/nota/bsq?ID='.$prereg);
		
	
	}
	
	$query = "	SELECT FIRST 3 PR.PREREG,  PR.PRETITULO, PR.PREIMG, PR.PRECATEGO,  PR.EXPREG
				FROM PREN_MAEST PR
                WHERE PR.PRECATEGO = '$precategoria' AND PR.PREESTADO<>3 AND PR.PREREG<> $prereg1
				ORDER BY PR.PREREG ";
		
	$Table = sql_query($query,$conn);

	if ($Table->Rows_Count<0){
		$tmpl->setvariable('tepuedeinteresar'		, 'd-none');
	}


	for($i=0; $i<$Table->Rows_Count; $i++){
        $row= $Table->Rows[$i];
		$prereginteres 		= trim($row['PREREG']);
		$pretitulointeres 		= trim($row['PRETITULO']);
		$preimginteres		    = trim($row['PREIMG']);
		$precategointeres    	= trim($row['PRECATEGO']);
		$expreginteres     	= trim($row['EXPREG']);

	
		$tmpl->setCurrentBlock('interes');

		$pretitulolink=convBBBdatos($pretitulointeres);

		$tmpl->setVariable('prereginteres'			, $prereginteres.'||'.$pretitulolink);

		$tmpl->setVariable('pretitulointeres'		, $pretitulointeres);
		$tmpl->setVariable('preimginteres', $pathimg . '/' . $prereginteres . '/' . $preimginteres);

		$tmpl->setvariable('precategointeres'		, $precategointeres);
		$tmpl->setvariable('expreginteres'			, $expreginteres);
		$tmpl->parse('interes');
		
	
	}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();


function convBBBdatos($texto){
	$textofin = $texto;
	$textofin = str_replace(' ','-',$textofin);
	
	$textofin = str_replace('á','a',$textofin);
	$textofin = str_replace('é','e',$textofin);
	$textofin = str_replace('í','i',$textofin);
	$textofin = str_replace('ó','o',$textofin);
	$textofin = str_replace('ú','u',$textofin);
	$textofin = str_replace('ñ','n',$textofin);
	
	$textofin = str_replace('Á','A',$textofin);
	$textofin = str_replace('É','E',$textofin);
	$textofin = str_replace('Í','I',$textofin);
	$textofin = str_replace('Ó','O',$textofin);
	$textofin = str_replace('Ú','U',$textofin);
	$textofin = str_replace('Ñ','N',$textofin);
	
	$textofin = str_replace('Ç','C',$textofin);
	$textofin = str_replace('ç','c',$textofin);
	
	$textofin = str_replace('ä','a',$textofin);
	$textofin = str_replace('ë','e',$textofin);
	$textofin = str_replace('ï','i',$textofin);
	$textofin = str_replace('ö','o',$textofin);
	$textofin = str_replace('ü','u',$textofin);
	
	$textofin = str_replace('Ä','A',$textofin);
	$textofin = str_replace('Ë','E',$textofin);
	$textofin = str_replace('Ï','I',$textofin);
	$textofin = str_replace('Ö','O',$textofin);
	$textofin = str_replace('Ü','U',$textofin);
	
	return $textofin;
}

?>	
