<?
if (!isset($_SESSION))  session_start();
//include($_SERVER["DOCUMENT_ROOT"] . '/webcoordinador/func/zglobals.php'); //DEV
include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('videoconf.html');

$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$parametro = (isset($_GET['P']))? trim($_GET['P']) : '';

if ($percodigo != '' && $parametro!='') {
		
		$tmpl->setVariable('param'	, $parametro );
		
		$parametro 	= str_replace(md5('OnLiFeeOuNnCiRTada'),'',$parametro);
		$parametro 	= base64_decode($parametro);
		$parametro 	= str_replace(md5('NnCiRoEnTadaOnLf'),'',$parametro);
		$parametro 	= base64_decode($parametro);
		$parametro 	= str_replace(md5('NnCiRoEnTadaOnLf'),'',$parametro);
		$reureg 	= str_replace(md5('OnLiFeRCeOuNnCiRoEnTada'),'',$parametro);
		
		$conn = sql_conectar(); //Apertura de Conexion

		$query = "	SELECT 	PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST, 
						PD.PERCOMPAN AS PERCOMDST, PD.PERCORREO AS PERCORDST,
						PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL, 
						PS.PERCOMPAN AS PERCOMSOL, PS.PERCORREO AS PERCORSOL,
						R.REUFECHA,R.REUHORA,R.REULINK
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL 
				WHERE R.REUREG=$reureg ";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$reufecha	= BDConvFch($row['REUFECHA']);
		$reuhora	= substr(trim($row['REUHORA']),0,5);
		
		$percoddst 	= trim($row['PERCODDST']);
		$pernomdst 	= trim($row['PERNOMDST']);
		$perapedst 	= trim($row['PERAPEDST']);
		$percomdst 	= trim($row['PERCOMDST']);
		$percordst 	= trim($row['PERCORDST']);
			
		$percodsol 	= trim($row['PERCODSOL']);
		$pernomsol 	= trim($row['PERNOMSOL']);
		$perapesol 	= trim($row['PERAPESOL']);
		$percomsol 	= trim($row['PERCOMSOL']);
		$percorsol 	= trim($row['PERCORSOL']);
		$reulink 	= trim($row['REULINK']);

		$tmpl->setVariable('reufecha'	, $reufecha		);
		$tmpl->setVariable('reuhora'	, $reuhora		);
		$tmpl->setVariable('percoddst'	, $percoddst 	);
		$tmpl->setVariable('pernomdst'	, $pernomdst 	);
		$tmpl->setVariable('perapedst'	, $perapedst 	);
		$tmpl->setVariable('percomdst'	, $percomdst 	);
		$tmpl->setVariable('percordst'	, $percordst 	);
		$tmpl->setVariable('percodsol'	, $percodsol 	);
		$tmpl->setVariable('pernomsol'	, $pernomsol 	);
		$tmpl->setVariable('perapesol'	, $perapesol 	);
		$tmpl->setVariable('percomsol'	, $percomsol 	);
		$tmpl->setVariable('percorsol'	, $percorsol 	);
		$tmpl->setVariable('reulink'	, $reulink 	);
		
		sql_close($conn);
	
}else{
	header('Location: login');
	exit;
}



$tmpl->show();
