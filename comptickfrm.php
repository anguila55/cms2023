<?php
	if(!isset($_SESSION))  session_start();
	 //include($_SERVER["DOCUMENT_ROOT"].'/congresoaapresid/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/idioma.php';
	require_once GLBRutaFUNC . '/constants.php';
	
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('comptickfrm.html');
	//--------------------------------------------------------------------------------------------------------------
	
	
	if (isset($_SESSION[GLBAPPPORT.'PERCODIGO'])){

		if (trim($_SESSION[GLBAPPPORT.'PERCODIGO']) !=""){
			$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : 0;
			$perclase = (isset($_SESSION[GLBAPPPORT.'PERCLASE']))? trim($_SESSION[GLBAPPPORT.'PERCLASE']) : 0;

		}else{
			$percodigo = (isset($_GET['P']))? trim($_GET['P']):0; 
			$perclase = (isset($_GET['C']))? trim($_GET['C']):0; 

		}
		

	}else{
		$percodigo = (isset($_GET['P']))? trim($_GET['P']):0; 
		$perclase = (isset($_GET['C']))? trim($_GET['C']):0; 
	}
	

		$tikcod=1;


	$tmpl->setVariable('percodigo', $percodigo);
	$tmpl->setVariable('perclase', $perclase);
	
	$publicKeyMP = 'APP_USR-06ad317e-2698-4490-b190-1e683615e654';
	$conn= sql_conectar();//Apertura de Conexion
	if($tikcod!=0 && $percodigo!=0){
		
		
		//Cargo los tickets a comprar
		$query="SELECT TIKCODIGO,TIKDESCRI,TIKVALOR
				FROM TIK_MAEST
				WHERE ESTCODIGO=1 AND TIKCODIGO=1 
				ORDER BY TIKORDEN ";
		
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row = $Table->Rows[0];
			$tikcodigo 	= trim($row['TIKCODIGO']);
			$tikdescri 	= trim($row['TIKDESCRI']);
			$tikvalor 	= trim($row['TIKVALOR']);

			
				if (($IdiomView=='ESP')){
					$tmpl->setVariable('tikdesfor', 'Te invitamos a hacer el pago para participar en ARQUISUR');
				}else{
					$tmpl->setVariable('tikdesfor', 'Te invitamos a hacer el pago para participar en ARQUISUR');
				}
				
						
			$tmpl->setVariable('publicKeyMP', $publicKeyMP);
			$tmpl->setVariable('tikcodfor', $tikcodigo);
			
			$tmpl->setVariable('tikvalfor', $tikvalor);
		}

		if (($IdiomView=='ESP')){
			$tmpl->setVariable('compra'		, 'Iniciar Compra');
			$tmpl->setVariable('volver'		, 'Volver');

		}else{
			$tmpl->setVariable('compra'		, 'Buy');
			$tmpl->setVariable('volver'		, 'Back');
		}
		
		
	}
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
$query = "	SELECT BANNERS
			FROM ADM_IMG WHERE BANID='10'";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];
	$file 	= trim($row['BANNERS']);
	$tmpl->setVariable('file', $file);

}

sql_close($conn);
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
$tmpl->setVariable('NAME_TITLE', NAME_TITLE );
$tmpl->setVariable('LOGIN_PERIOD', LOGIN_PERIOD );
$tmpl->setVariable('LOGIN_EMAIL', SEND_MAIL_LOGIN );
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	

