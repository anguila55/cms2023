<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC.'/agora/RtcTokenBuilder.php';
	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('chat.html');

	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
		
	$percodigo 			= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre 			= (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli 			=(isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$percompan 			= (isset($_SESSION[GLBAPPPORT.'PERCOMPAN']))? trim($_SESSION[GLBAPPPORT.'PERCOMPAN']) : '';
	$perusuacc 			= (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percorreo 			= (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin 			= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar 			= (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	$pertipo 			= (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) : '';
	$perclase 			= (isset($_SESSION[GLBAPPPORT.'PERCLASE']))? trim($_SESSION[GLBAPPPORT.'PERCLASE']) : '';
	$timoffset 			=(isset($_SESSION[GLBAPPPORT.'TIMOFFSET']))? trim($_SESSION[GLBAPPPORT.'TIMOFFSET']) : '';

	//--------------------------------------------------------------------------------------------------------------
	
    $pathimagenes = '../perimg/';
    $imgAvatarNull = '../app-assets/img/avatar.png';
	
	$percoddst 	= (isset($_POST['percoddst']))? trim($_POST['percoddst']) : 0;
	
	
	$conn= sql_conectar();//Apertura de Conexion
	
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->setVariable('percodigo'	, $percodigo);
	$tmpl->setVariable('pernombre'	, $pernombre);
	//--------------------------------------------------------------------------------------------------------------
///
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


	//Busco datos del perfil destino
	$query  = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN,PERAVATAR
				FROM PER_MAEST
				WHERE PERCODIGO=$percoddst ";
	$Table 	= sql_query($query,$conn);

	for($i=0; $i < $Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];			
		$pernom 	= trim($row['PERNOMBRE']);
		$perape 	= trim($row['PERAPELLI']);
		$compan 	= trim($row['PERCOMPAN']);
		$avatar 	= trim($row['PERAVATAR']);
		
		if($avatar!=''){
			if(strpos($avatar, "https://") !== false){

				$tmpl->setVariable('avatar'	, $avatar);
			
			}else{
				$tmpl->setVariable('avatar'	, $pathimagenes.$percoddst.'/'.$avatar);
			}
			
		}else{
			$tmpl->setVariable('avatar'	, $imgAvatarNull);
		}
		$tmpl->setVariable('percoddst'	, $percoddst	);
		$tmpl->setVariable('pernomdst'	, $pernom		);
		$tmpl->setVariable('perapedst'	, $perape		);
		$tmpl->setVariable('percomdst'	, $compan		);
	}
	
	//Busco la conversacion con el primer perfil
	$query  = "	SELECT PS.PERAVATAR AS AVATAR ,PS.PERCODIGO AS SOLCOD,PS.PERNOMBRE AS SOLNOM,C.CHATEXTO,C.CHAFCHREG
				FROM TBL_CHAT C
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=C.PERCODIGO
				WHERE C.ESTCODIGO=1 AND ((C.PERCODIGO=$percodigo AND C.PERCODDST=$percoddst) OR (C.PERCODDST=$percodigo AND C.PERCODIGO=$percoddst)) 
				ORDER BY C.CHAREG ";
				
	$Table 	= sql_query($query,$conn);			
	for($i=0; $i < $Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];			
		$solcod 	= trim($row['SOLCOD']);
		$solnom 	= trim($row['SOLNOM']);
		$texto 		= trim($row['CHATEXTO']);
		$foto 		= trim($row['AVATAR']);
		$chafchreg	= BDConvFch($row['CHAFCHREG']);
		$fechareg	= substr($chafchreg,0,11);
		$horareg 	= substr($chafchreg,11,8);
				
		//Hora segun Zona Horaria
		$haux = date('H:i:s', strtotime('+10800 seconds', strtotime($horareg))); //Pongo la hora en Huso horario 0
		$haux = date('H:i:s', strtotime($timoffset.' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
		$horareg = $haux;	
			
		$tmpl->setCurrentBlock('conversacion');
		
		$reg_pattern = "/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/";
		$convertedStr = preg_replace($reg_pattern, '<a href="$0" target="_blank" rel="noopener noreferrer">$0</a>', $texto);
		$otrotexto = nl2br($convertedStr);
		$textofinal = str_replace('href="www','href="//www',$otrotexto);

		$tmpl->setVariable('mensajes'	, $textofinal);

		//$tmpl->setVariable('mensajes'	, $texto	);
		$tmpl->setVariable('chafchreg'	, $fechareg.' '.$horareg	);
			
		if ($foto != '') {
			$tmpl->setVariable('foto', $pathimagenes . $solcod . '/' . $foto);
		} else {
			$tmpl->setVariable('foto', $imgAvatarNull);
		}
	
		
		//Mensajes del perfil destino			
		if($solcod != $percodigo){
			$tmpl->setVariable('clasechat'	, 'chat-left'	);
			$tmpl->setVariable('placement'	, 'left'		);
			
		}else{ //Mensaje del perfil logueado			
			$tmpl->setVariable('clasechat'	, ''		);
			$tmpl->setVariable('placement'	, 'right'	);
			
		}
		$tmpl->parse('conversacion');		
	}
	
	sql_close($conn);	
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	//--------------------------------------------------------------------------------------------------------------
	
	
?>	
