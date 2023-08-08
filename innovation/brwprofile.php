<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brwprofile1.html');

//Diccionario de idiomas
DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$expreg = (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;
$prodreg = (isset($_POST['prodreg'])) ? trim($_POST['prodreg']) : 0;
$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
$peradmin 			= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
$estcodigo = 1; //Activo por defecto el estado
$pathPlano = '../app/';
$variablevideo = 0;

$pathimagenes = '../expimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';
$tmpl->setVariable('expavatar', $imgAvatarNull);
$tmpl->setVariable('imgAvatarNull', $imgAvatarNull);
//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion

$liqueo=0;
$query = "	SELECT EM.EXPREG,EM.EXPNOMBRE,EM.EXPDIRECCION,EM.EXPTELEFO,EM.EXPMAIL,EM.EXPWEB,EM.EXPLINKED,EM.EXPFAC,EM.EXPRUBROS,
					EM.EXPTWI,EM.EXPYOUTUB,EM.EXPAVATAR,EM.EXPCATEGO, EM.EXPDESAFIO1, EM.EXPDESAFIO2, EM.EXPDESAFIO3
			FROM EXP_MAEST EM 
			WHERE EM.EXPREG=$expreg   ";
$Table = sql_query($query, $conn);			
if($Table->Rows_Count>0){
	$row = $Table->Rows[0];
		
	//DATOS DE CONTACTO
	$expreg 		= trim($row['EXPREG']);
	$expnombre 		= trim($row['EXPNOMBRE']);
	$expdireccion 	= trim($row['EXPDIRECCION']);
	$exptelefo 		= trim($row['EXPTELEFO']);
	$expmail 		= trim($row['EXPMAIL']);
	$expweb 		= trim($row['EXPWEB']);
	$explinked 		= trim($row['EXPLINKED']);
	$expfac 		= trim($row['EXPFAC']);
	$exptwi 		= trim($row['EXPTWI']);
	$expyoutub 		= trim($row['EXPYOUTUB']);
	$expavatar 		= trim($row['EXPAVATAR']);
	$exprubros 		= trim($row['EXPRUBROS']);
	//$expreutit 		= trim($row['EXPREUTIT']);
	//$expreudes 		= trim($row['EXPREUDES']);
	//$expreulnk 		= trim($row['EXPREULNK']);
	//$expbrolnk 		= trim($row['EXPBROLNK']);
	$expcatego 		= trim($row['EXPCATEGO']);
	$areadesafio 	= trim($row['EXPDESAFIO1']);
	$desafiodesafio 	= trim($row['EXPDESAFIO2']);
	$buscamosdesafio 	= trim($row['EXPDESAFIO3']);
	
	$percodexp 	= '';
	$pernombre 	= '';
	$perapelli 	= '';
	$pertipo 	= '';
	

	if ($expavatar == '') {
		$expavatar = $imgAvatarNull;
	} else {
		$expavatar = $pathimagenes . $expreg . '/' . $expavatar;
	}
	
	//Busco el primer perfil asignado al expositor
	$queryPer = "	SELECT FIRST 1 E.PERCODIGO,P.PERTIPO,P.PERNOMBRE,P.PERAPELLI
					FROM EXP_PER E
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=E.PERCODIGO
					WHERE E.EXPREG=$expreg ";
	$TablePer = sql_query($queryPer, $conn);
	for ($j = 0; $j < $TablePer->Rows_Count; $j++) {
		$rowPer = $TablePer->Rows[$j];
		
		$percodexp 	= trim($rowPer['PERCODIGO']);
		$pernombre 	= trim($rowPer['PERNOMBRE']);
		$perapelli 	= trim($rowPer['PERAPELLI']);
		$pertipo 	= trim($rowPer['PERTIPO']);
	}
	
	//Busco el primer perfil asignado al expositor
	$queryDes = "	SELECT E.PERCODIGO,E.EXPREG,P.PERCORREO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,E.DESDESC,E.DESLINK
					FROM DES_PER E
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=E.PERCODIGO
					WHERE E.EXPREG=$expreg AND P.PERCODIGO=E.PERCODIGO AND E.ESTCODIGO=1";
	$TableDes = sql_query($queryDes, $conn);
	for ($j = 0; $j < $TableDes->Rows_Count; $j++) {
		$rowDes = $TableDes->Rows[$j];
		
		$percodexp1 	= trim($rowDes['PERCODIGO']);
		$expregexp 	= trim($rowDes['EXPREG']);
		$desnombre 	= trim($rowDes['PERNOMBRE']);
		$desapelli 	= trim($rowDes['PERAPELLI']);
		$descorreo 	= trim($rowDes['PERCORREO']);
		$descompan 	= trim($rowDes['PERCOMPAN']);
		$resumo 	= trim($rowDes['DESDESC']);
		$linkvideo 	= trim($rowDes['DESLINK']);

		$tmpl->setCurrentBlock('desafios');

		$tmpl->setVariable('percodexp1'		, $percodexp1 		);
		$tmpl->setVariable('expregexp'		, $expregexp 		);
		$tmpl->setVariable('desnombre'		, $desnombre 		);
		$tmpl->setVariable('desapelli'		, $desapelli 	);
		$tmpl->setVariable('descorreo'		, $descorreo 		);
		$tmpl->setVariable('descompan'	,  $descompan 	);
		$tmpl->setVariable('resumo'	,  $resumo  	);
		$tmpl->setVariable('linkvideo'	,  $linkvideo  	);
		$tmpl->setVariable('display-options'	,  'd-none' 	);
		if ($percodexp1==$percodigo || $peradmin==1 ){

			$tmpl->setVariable('display-options'	,  '' 	);

		}
		
		
		$tmpl->parse('desafios');
	}
	//Si no hay perfil relacionado, oculto el boton de solicitar reunion
	if($percodexp==''){
		$tmpl->setVariable('btnsolreu'	, 'display:none;'	);
		$tmpl->setVariable('btnchat'	, 'display:none;'	);
	}
	

	$tmpl->setVariable('expreg'			, $expreg 			);
	$tmpl->setVariable('expnombre'		, $expnombre 		);
	$tmpl->setVariable('expdireccion'	, $expdireccion 	);
	$tmpl->setVariable('exptelefo'		, $exptelefo 		);
	$tmpl->setVariable('expmail'		, $expmail 			);
	$tmpl->setVariable('expweb'			, $expweb 			);
	$tmpl->setVariable('explinked'		, $explinked 		);
	$tmpl->setVariable('expfac'			, $expfac 			);
	$tmpl->setVariable('exptwi'			, $exptwi 			);
	$tmpl->setVariable('expyoutub'		, $expyoutub 		);
	$tmpl->setVariable('perapelli'		, $perapelli		);
	$tmpl->setVariable('pernombre'		, $pernombre		);
	$tmpl->setVariable('percodexp'		, $percodexp 		);
	$tmpl->setVariable('pertipo'		, $pertipo 			);
	$tmpl->setVariable('expavatar'		, $expavatar 		);
	$tmpl->setVariable('exprubros'		, $exprubros 		);
	$tmpl->setVariable('areadesafio', $areadesafio);
	$tmpl->setVariable('desafiodesafio', $desafiodesafio);
	$tmpl->setVariable('buscamosdesafio', $buscamosdesafio);

}





//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>