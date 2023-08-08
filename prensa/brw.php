<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');

//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
// $fltbuscar 		= (isset($_POST['fltbuscar']))? trim($_POST['fltbuscar']) : '';
$fltfavoritos 	= (isset($_POST['fltfavoritos']))? $_POST['fltfavoritos'] : '0';
$preregnota 	= (isset($_POST['preregnota']))? $_POST['preregnota'] : '0';
$filtronombre 	= (isset($_POST['filtronombre']))? $_POST['filtronombre'] : '';

//var_dump($filtronombre);die;
$pathimg = '../prensaimg';
$conn = sql_conectar(); //Apertura de Conexion

if ($preregnota>1){

	$prereg=$preregnota;
	$tmpl->setVariable('scriptbrwprofile'	, "abrirNota($prereg);"	);
}
$tmpl->setVariable('displaymercados', 'display:none');
$where = '';
	

	if (is_numeric($filtronombre))
	{
		if($filtronombre=='0'){
			//$where .= " AND PRECATEGO = 'SPONSOR' ";
		}else{

			$numero = intval($filtronombre);
		
			$query = "	SELECT CATDESCRI
				FROM PRE_CAT WHERE CATREG = $numero
			 	";
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$catdescri 	= trim($row['CATDESCRI']);
		$where .= " AND PRECATEGO = '$catdescri' ";
		}


		}
	}else{

		$where .= " AND (PR.PRETITULO CONTAINING '$filtronombre' OR PR.PREDESCRI CONTAINING '$filtronombre' OR PR.PREBAJADA CONTAINING '$filtronombre') ";

	}

	
$array=null;

$query = "	SELECT  PR.PREREG,  PR.PRETITULO, SUBSTRING(PR.PREDESCRI FROM 1 FOR 190) AS  PREDESCRI,  PR.PREIMG,  PR.PREURL,  PR.PERCODIGO,  PR.PRECATEGO,  PR.EXPREG, PR.PREESTADO, PR.PREFUENTE, PR.PREBAJADA, PR.PREFECHA, PR.PRETIPO, PR.PRETAMANO, PR.PREN_ORD
				FROM PREN_MAEST PR
				WHERE PR.PREESTADO<>3 $where
				ORDER BY PR.PREN_ORD ASC, PR.PREFECHA DESC ";
				
	//logerror($query);			
	$Table = sql_query($query,$conn);

	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
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
		$pretipo     	= trim($row['PRETIPO']);
		$pretamano    	= trim($row['PRETAMANO']);

		$predescri1 = Strip_tags(htmlspecialchars_decode($predescri));
		$preimg1 = $pathimg . '/' . $prereg . '/' . $preimg;
		
		$prefecha	= substr($prefecha,6,4).'-'.substr($prefecha,3,2).'-'.substr($prefecha,0,2);
		$array[]=['preurl'=>$preurl,'prereg'=>$prereg,'preimg'=>$preimg1,'pretitulo'=>$pretitulo,'predescri'=>$predescri1,'precatego'=>$precatego,'prefuente'=>$prefuente,'prebajada'=>$prebajada,'prefecha'=>$prefecha,'pretipo'=>$pretipo,'pretamano'=>$pretamano];

		
		if ($preurl != '') {
			$tmpl->setVariable('cssactive', 'color:#292ECE');
		}else{
			
			$tmpl->setVariable('cssactive', 'color:silver');
			$tmpl->setVariable('inactivo', 'inactivo');
		}
		
		$tmpl->setCurrentBlock('comunicados');
		
		$tmpl->setVariable('prereg'			, $prereg);
		$tmpl->setVariable('pretitulo'		, $pretitulo);
		//htmlspecialchars_decode($predescri);
		$tmpl->setVariable('predescri'	, Strip_tags(htmlspecialchars_decode($predescri)));
		$tmpl->setVariable('preimg', $pathimg . '/' . $prereg . '/' . $preimg);
		// $tmpl->setvariable('preimg'			, $preimg);
		$tmpl->setvariable('percodigopre'	, $percodigo);
		$tmpl->setvariable('preurl'			, $preurl);
		$tmpl->setvariable('precatego'		, $precatego);
		$tmpl->setvariable('expreg'			, $expreg);
		$tmpl->setvariable('preestado'		, $preestado);
		$tmpl->setvariable('prefuente'			, $prefuente);
		$tmpl->setvariable('prebajada'	, $prebajada);
		$tmpl->setvariable('prefecha'			, $prefecha);
		$tmpl->setvariable('pretipo'		, $pretipo);
		$tmpl->setvariable('pretamano'			, $pretamano);
		
		$tmpl->parse('comunicados');
	}
	
	$myJSON = json_encode($array);
	$tmpl->setvariable('arrayprueba'		, $myJSON);
	
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
