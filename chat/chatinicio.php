<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/agora/RtcTokenBuilder.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC.'/constants.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('chatinicio.html');
	DDIdioma($tmpl);

	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percorreo = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	
	$percoddst 	= (isset($_POST['percoddst']))? trim($_POST['percoddst']) : 0;
	$modayuda 	= (isset($_POST['modayuda']))? trim($_POST['modayuda']) : 0;
	
	
	$conn= sql_conectar();//Apertura de Conexion
	

	$tmpl->setVariable('displayfaq'	, 'display:none;'	);
	$tmpl->setVariable('displaywhatsapp'	, 'display:none;'	);
	$tmpl->setVariable('displaycorreo'	, 'display:none;'	);
	$tmpl->setVariable('viewmodal2'	, 'display:none;'	);


	$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'CompartirDatos'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$compartirdatos = trim($row['ZVALUE']);
	if ($compartirdatos == 'true') {
		$tmpl->setVariable('mostrarcompartir', '');
	}else if ($compartirdatos == 'false'){
		$tmpl->setVariable('mostrarcompartir', 'd-none');
	}
}

	
	if ($modayuda==1)
	{
		$query = "	SELECT *
			FROM ADM_AYUDA
			WHERE AYU_ID=0";

			$Table = sql_query($query,$conn);

			for($i=0; $i<$Table->Rows_Count; $i++){
				$row = $Table->Rows[$i];

				$ayunumero 	= trim($row['AYU_NUMERO']);
				$ayucorreo 	= trim($row['AYU_CORREO']);
				$ayufaq 	= trim($row['AYU_FAQ']);
				

				$tmpl->setVariable('ayunumero'	, $ayunumero	);
				$tmpl->setVariable('ayucorreo'	, $ayucorreo	);
				if ($ayufaq==1){

					$tmpl->setVariable('displayfaq'	, 'display:true;'	);

				}else{

					$tmpl->setVariable('displayfaq'	, 'display:none;'	);
				}
				if ($ayunumero!=''){

					$tmpl->setVariable('displaywhatsapp'	, 'display:true;'	);
				}else{
					$tmpl->setVariable('displaywhatsapp'	, 'display:none;'	);	
				}
				if ($ayucorreo!=''){

					$tmpl->setVariable('displaycorreo'	, 'display:true;'	);
				}else{
					$tmpl->setVariable('displaycorreo'	, 'display:none;'	);
				}
				

			}
	}else{
		$tmpl->setVariable('viewmodal2'	, 'display:true;'	);
	}



	//--------------------------------------------------------------------------------------------------------------
	//Registracion del usuario
	//2:##:Usuario2:##:NULL:##:NULL:##:REGISTRACIONUSUARIOCHAT
	$chatregister = "$percodigo:##:$pernombre:##:NULL:##:NULL:##:REGISTRACIONUSUARIOCHAT";
	$tmpl->setVariable('percodigo'	, $percodigo);
	$tmpl->setVariable('pernombre'	, $pernombre);
	$tmpl->setVariable('chatregister'	, $chatregister);

	if (($IdiomView=='ING')){

		$tmpl->setVariable('inicio_chat'		, 'Hello, I would like to start a conversation with you...'); 
	}else if  (($IdiomView=='ESP')) {

		$tmpl->setVariable('inicio_chat'		, 'Hola, me gustaría comenzar una conversación contigo...');
	}else{
		$tmpl->setVariable('inicio_chat'		, 'Olá, gostaria de começar uma conversa com você'); 
	}
	//--------------------------------------------------------------------------------------------------------------
	//Busco los perfiles del chat
	$query  = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN,PERCARGO
				FROM PER_MAEST
				WHERE PERCODIGO=$percoddst ";
	$Table 	= sql_query($query,$conn);
	
	for($i=0; $i < $Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];			
		$pernom 	= trim($row['PERNOMBRE']);
		$perape 	= trim($row['PERAPELLI']);
		$compan 	= trim($row['PERCOMPAN']);
		$cargo 	= trim($row['PERCARGO']);
		
		$tmpl->setVariable('percoddst'	, $percoddst	);
		$tmpl->setVariable('pernomdst'	, $pernom		);
		$tmpl->setVariable('perapedst'	, $perape		);
		$tmpl->setVariable('percomdst'	, $compan		);
		$tmpl->setVariable('percardst'	, $cargo		);
	}

	$pathimagenes = '../admimg/';
	$imgBannerHomeNull	= '../assets-nuevodisenio/img/bannerhome.jpg';
	$tmpl->setVariable('imgProductoNull'	, $imgBannerHomeNull 	);
	$query = "	SELECT *
				FROM ADM_IMG
				WHERE BANID=8";
	
	$Table = sql_query($query,$conn);
	
	if($Table->Rows_Count>0){
		$row = $Table->Rows[0];
		$bannermodal 	= trim($row['BANNERS']);
		$urlbannermodal 	= trim($row['URLBAN']);
		$estcodigobanner 	= trim($row['ESTCODIGO']);
		if($bannermodal==''){ 
			$bannermodal = $imgBannerHomeNull;
		}else{
			$bannermodal = $pathimagenes.$bannermodal;
		}
			if ($estcodigobanner==1){
				$tmpl->setVariable('displaybannermodal'	, '' 	);
			}else{
				$tmpl->setVariable('displaybannermodal'	, 'd-none' 	);
			}

			$tmpl->setVariable('bannermodal'	, $bannermodal	);
			$tmpl->setVariable('urlbannermodal'	, $urlbannermodal	);
	
	}
		
		




	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
	
?>	
