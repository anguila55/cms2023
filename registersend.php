<?
	
    if(!isset($_SESSION))  session_start();
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php');
	include($_SERVER["DOCUMENT_ROOT"].'/phpqrcode/qrlib.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	require_once GLBRutaAPI  . '/mailchimp.php';
	require_once GLBRutaFUNC . '/constants.php';
	require_once GLBRutaAPI  . '/timezone.php';
	
	$peridioma = (isset($_GET['ID']))? trim($_GET['ID']):'ESP'; //Nota desde el home acceso directo	//--------------------------------------------------------------------------------------------------------------
	

	
	$pernombre 		= (isset($_POST['pernombre']))? trim(SQL_replace($_POST['pernombre'])) : '';
	$perapelli 		= (isset($_POST['perapelli']))? trim(SQL_replace($_POST['perapelli'])) : '';
	$percpf 		= (isset($_POST['percpf']))? trim(SQL_replace($_POST['percpf'])) : '';
	$percompan 		= (isset($_POST['percompan']))? trim(SQL_replace($_POST['percompan'])) : '';
	$percargo 		= (isset($_POST['percargo']))? trim(SQL_replace($_POST['percargo'])) : '';
	$pertipo 		= (isset($_POST['pertipo']))?  trim(SQL_replace($_POST['pertipo'])) : '';
	$tipo 		= (isset($_POST['tipo']))?  trim(SQL_replace($_POST['tipo'])) : '';
	$perclase 		= (isset($_POST['perclase']))? trim(SQL_replace($_POST['perclase'])) : '';
	$perrubcod 		= (isset($_POST['perrubcod']))? trim(SQL_replace($_POST['perrubcod'])) : '0';
	$percorreo 		= (isset($_POST['percorreo']))? trim(SQL_replace($_POST['percorreo'])) : '';
	$pertelefo 		= (isset($_POST['pertelefo']))? trim(SQL_replace($_POST['pertelefo'])) : '';
	$peridioma 		= (isset($_POST['peridioma']))? trim(SQL_replace($_POST['peridioma'])) : '';
	$perurlweb 		= (isset($_POST['perurlweb']))? trim(SQL_replace($_POST['perurlweb'])) : '';
	$perdirecc 		= (isset($_POST['perdirecc']))? trim(SQL_replace($_POST['perdirecc'])) : '';
	$perciudad 		= (isset($_POST['perciudad']))? trim(SQL_replace($_POST['perciudad'])) : '';
	$perestado 		= (isset($_POST['perestado']))? trim(SQL_replace($_POST['perestado'])) : '';
	$percodpos 		= (isset($_POST['percodpos']))? trim(SQL_replace($_POST['percodpos'])) : '';
	$dataClasificarVen	= (isset($_POST['dataClasificarVen']))? trim(SQL_replace($_POST['dataClasificarVen'])) : '';
	$dataClasificarCom	= (isset($_POST['dataClasificarCom']))? trim(SQL_replace($_POST['dataClasificarCom'])) : '';
	$paicodigo 		= (isset($_POST['paicodigo']))? trim(SQL_replace($_POST['paicodigo'])) : '';
	$timezone 		= (isset($_POST['timezone']))? trim(SQL_replace($_POST['timezone'])) : '';
	$perparnom1 	= (isset($_POST['perparnom1']))? trim(SQL_replace($_POST['perparnom1'])) : '';
	$perparape1 	= (isset($_POST['perparape1']))? trim(SQL_replace($_POST['perparape1'])) : '';
	$perparcarg1 	= (isset($_POST['perparcarg1']))? trim(SQL_replace($_POST['perparcarg1'])) : '';
	$perparnom2 	= (isset($_POST['perparnom2']))? trim(SQL_replace($_POST['perparnom2'])) : '';
	$perparape2 	= (isset($_POST['perparape2']))? trim(SQL_replace($_POST['perparape2'])) : '';
	$perparcarg2 	= (isset($_POST['perparcarg2']))? trim(SQL_replace($_POST['perparcarg2'])) : '';
	$perparnom3 	= (isset($_POST['perparnom3']))? trim(SQL_replace($_POST['perparnom3'])) : '';
	$perparape3 	= (isset($_POST['perparape3']))? trim(SQL_replace($_POST['perparape3'])) : '';
	$perparcarg3 	= (isset($_POST['perparcarg3']))? trim(SQL_replace($_POST['perparcarg3'])) : '';
	$percoment 		= (isset($_POST['percoment']))? trim(SQL_replace($_POST['percoment'])) : '';
	$perempdes 		= (isset($_POST['perempdes']))? trim(SQL_replace($_POST['perempdes'])) : '';
	$perinfact 		= (isset($_POST['perinfact']))? trim(SQL_replace($_POST['perinfact'])) : ''; //Check de Informacion de Actividades
	$perinfpar 		= (isset($_POST['perinfpar']))? trim(SQL_replace($_POST['perinfpar'])) : ''; //Check de Informacion de Participantes
	$perarecod 		= (isset($_POST['perarecod']))? trim(SQL_replace($_POST['perarecod'])) : '';
	$perfac 		= (isset($_POST['perfac']))? trim(SQL_replace($_POST['perfac'])) : '';
	$pertwi 		= (isset($_POST['pertwi']))? trim(SQL_replace($_POST['pertwi'])) : '';
	$perins 		= (isset($_POST['perins']))? trim(SQL_replace($_POST['perins'])) : '';
	$perlinked 		= (isset($_POST['perlinked']))? trim(SQL_replace($_POST['perlinked'])) : '';
	$preguntasadicionales	= (isset($_POST['preguntasadicionales']))? trim(SQL_replace($_POST['preguntasadicionales'])) : '';

	$perusuacc 		= (isset($_POST['perusuacc']))? trim(SQL_replace($_POST['perusuacc'])) : ''; 
	$perpasacc 		= (isset($_POST['perpasacc']))? trim(SQL_replace($_POST['perpasacc'])) : ''; 

	$encres=1;

	if ($pertipo == ''){
		$pertipo = TIPO_DE_PERFIL_POR_DEFECTO;
	}
	if ($perclase == ''){
		$perclase = TIPO_DE_CLASE_POR_DEFECTO;
	}
	if ($paicodigo == ''){
		$paicodigo = PAIS_POR_DEFECTO;
	}
	if ($tipo == ''){
		$tipo = TIPO_POR_DEFECTO;
	}
	if ($perarecod == ''){
		$perarecod = 0;
	}

	///////////////// Obetengo Timeoffset de la api//////////
	$timoffset=strval(getTimeZone($timezone));
	/////////////////////////////////////////////////////////

	$perclase		= VarNullBD($perclase		, 'N');

	$pathqrimagenes = 'qrimage/';
	if (!file_exists($pathqrimagenes)) {
		mkdir($pathqrimagenes);
	}
	
	//Usuario en minuscula
	$perusuacc 	= strtolower($perusuacc);
	
	//Si no hay correo, le pongo el usuario ingresado
	if($percorreo=='') $percorreo=$perusuacc;
	
	$perpasacc = md5('BenVido'.$perpasacc.'PassAcceso'.$perusuacc);
	$perpasacc = 'B#SD'.md5(substr($perpasacc,1,10).'BenVidO'.substr($perpasacc,5,8)).'E##$F';
	
	$param 		= md5('MEETINGPOINTPARAMETROCAMBIOCLAVE').md5('PARAMETROCAMBIOMEETINGPOINTCLAVE');
	
	$conn= sql_conectar();//Apertura de Conexion

	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	//Verifiquemos que los datos del nuevo usuario no coincidan con uno ya existente
	$query= "SELECT PERCODIGO FROM PER_MAEST WHERE PERUSUACC='$perusuacc'";
	
	$Table = sql_query($query,$conn);
	if ($Table->Rows_Count>0) {
		if ($peridioma=='ESP'){

			$errmsg='El usuario ya existe';
			
			}else{
				
				$errmsg='This user already exists';
			}
		$errcod=2;
	}

	$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkAppStore'";
	$Table2 = sql_query($query2, $conn);
	for ($i = 0; $i < $Table2->Rows_Count; $i++) {
		$row = $Table2->Rows[$i];
		$evefch = trim($row['ZDESCRI']);	
	}

	$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkPlayStore'";
	$Table2 = sql_query($query2, $conn);
	for ($i = 0; $i < $Table2->Rows_Count; $i++) {
		$row = $Table2->Rows[$i];
		$evefchfin = trim($row['ZDESCRI']);
		
	}
	$evefch = substr($evefch, 8, 2).'-'.substr($evefch,5,2).'-'.substr($evefch,0,4);
	$evefchfin = substr($evefchfin, 8, 2).'-'.substr($evefchfin,5,2).'-'.substr($evefchfin,0,4);
	//Verificamos que el email no este registrando exceptuando si el usuario es admin o no
	$query = "SELECT PERCORREO, PERADMIN FROM PER_MAEST WHERE PERADMIN IS NULL AND PERCORREO='$percorreo'";
	$Table = sql_query($query,$conn);
	if ($Table->Rows_Count>0) {
		if ($peridioma=='ESP'){

			$errmsg='El usuario ya existe';
			
			}else{
				
				$errmsg='This user already exists';
			}
		$errcod=2;
	}

	$query="SELECT MSGTITULO,MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG FROM MSG_CABE WHERE MSGREG=1";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$msgtitulo 	= trim($row['MSGTITULO']);
		$msgdescri	= trim($row['MSGDESCRI']);
		$msgsub = trim($row['MSGSUB']);
		$msgbot = trim($row['MSGBOT']);
		$msglnk = trim($row['MSGLNK']);
		$msgimg = trim($row['MSGIMG']);
		$msgdescri = htmlspecialchars_decode($msgdescri);
		$msgdescri = str_replace('class="ql-align-center"','style="text-align:center"',$msgdescri);
		$msgdescri = str_replace('class="ql-align-justify"','style="text-align:justify"',$msgdescri);
		$msgdescri = str_replace('class="ql-align-right"','style="text-align:right"',$msgdescri);

		$query="SELECT MSGREP, MSGCC, MSGCCO FROM MSG_CABE WHERE MSGREG=10";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$msgrep 	= trim($row['MSGREP']);
		$msgcc	= trim($row['MSGCC']);
		$msgcco	= trim($row['MSGCCO']);
	//--------------------------------------------------------------------------------------------------------------
	
	if ($errcod == 0) {
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_PERFILES,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn);
		$RowId		= $TblId->Rows[0];			
		$percodigo 	= trim($RowId['ID']);

		$tempDir = $pathqrimagenes;

		$codeContents = URL_WEB . 'login.php?QRCode=P||'.$percodigo;

		$fileName = $percodigo.'_PER_'.md5($codeContents).'.png';

		$pngAbsoluteFilePath = $tempDir.$fileName;
		$urlRelativeFilePath = '../qrimage/'.$fileName;

		// generating
		if (!file_exists($pngAbsoluteFilePath)) {

			define('IMAGE_WIDTH',1024);
			define('IMAGE_HEIGHT',1024);
			QRcode::png($codeContents, $pngAbsoluteFilePath,QR_ECLEVEL_H, 4);
			
		} else {
			
		}
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO PER_MAEST(PERCODIGO,PERNOMBRE,PERAPELLI,PERCPF,ESTCODIGO,PERCOMPAN,PERRUBCOD,PERCORREO,PERCIUDAD,PERESTADO,
											PERCODPOS,PERTELEFO,PERURLWEB,PERUSUACC,PERPASACC,PERDIRECC,PERCARGO,
											PAICODIGO,PERTIPO,PERCLASE,PERPARNOM1,PERPARAPE1,PERPARCARG1,
											PERPARNOM2,PERPARAPE2,PERPARCARG2,PERPARNOM3,PERPARAPE3,PERPARCARG3,PERCOMENT,PERIDIOMA,TIMREG,TIMREG2,TIMOFFSET,ENCRES,
											PERINFACT,PERINFPAR,QRCODE,PERPOP,TIPO,PEREMPDES,PERFAC,PERTWI,PERINS,PERLINKED,PERPOP2,PREG_ADC,PERARECOD)
					VALUES($percodigo,'$pernombre','$perapelli','$percpf',9,'$percompan','$perrubcod','$percorreo','$perciudad','$perestado','$percodpos',
							'$pertelefo','$perurlweb','$perusuacc','$perpasacc','$perdirecc','$percargo',
							$paicodigo,$pertipo,$perclase,'$perparnom1','$perparape1','$perparcarg1',
							'$perparnom2','$perparape2','$perparcarg2','$perparnom3','$perparape3','$perparcarg3','$percoment','$peridioma',0,'$timezone',$timoffset,$encres,
							'$perinfact','$perinfpar','$urlRelativeFilePath',0,$tipo,'$perempdes','$perfac','$pertwi','$perins','$perlinked',0,'$preguntasadicionales',$perarecod) ";
							
		$err = sql_execute($query,$conn);

	
		$queryreunion = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoReunion'";
		$Tablereunion = sql_query($queryreunion, $conn);
			$rowreunion = $Tablereunion->Rows[0];
			$tiporeunion = trim($rowreunion['ZVALUE']);

		//Almaceno la Clasificacion
	if($err == 'SQLACCEPT' && $errcod==0){
		$query=" DELETE FROM PER_SECT WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn);
		$query=" DELETE FROM PER_SSEC WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn);
		$query=" DELETE FROM PER_CATE WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn);
		$query=" DELETE FROM PER_SCAT WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn);
		
		////Verificio si es tipo Oferta/Demanda o es Networking
		if ($tiporeunion == 'true') {
			//Recorro la Clasificacion de Ventas
			if($dataClasificarVen!=''){
				$pervencom = 'V'; //Ventas
				$dataClasificarVen = json_decode($dataClasificarVen);
				foreach($dataClasificarVen->sectores as $ind => $data){
					$codigo		= $data->seccodigo;				
					$query = "	INSERT INTO PER_SECT(PERCODIGO,SECCODIGO,PERVENCOM)
								VALUES($percodigo,$codigo,'$pervencom')";
					$err = sql_execute($query,$conn);
				}
				
				//Recorro los subsectores
				if(isset($dataClasificarVen->subsectores)){
					foreach($dataClasificarVen->subsectores as $ind => $data){
						$codigo		= $data->secsubcod;
						$query = "	INSERT INTO PER_SSEC(PERCODIGO,SECSUBCOD,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn);
					}
				}
				
				//Recorro las categorias
				if(isset($dataClasificarVen->categorias)){
					foreach($dataClasificarVen->categorias as $ind => $data){
						$codigo		= $data->catcodigo;					
						$query = "	INSERT INTO PER_CATE(PERCODIGO,CATCODIGO,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn);
					}
				}
				//Recorro las subcategorias
				if(isset($dataClasificarVen->subcategorias)){
					foreach($dataClasificarVen->subcategorias as $ind => $data){
						$codigo		= $data->catsubcod;
						$query = "	INSERT INTO PER_SCAT(PERCODIGO,CATSUBCOD,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn);
					}
				}
			}
			
			//Recorro la Clasificacion de Compras
			if($dataClasificarCom!=''){
				$pervencom = 'C'; //Compras
				$dataClasificarCom = json_decode($dataClasificarCom);
				foreach($dataClasificarCom->sectores as $ind => $data){
					$codigo		= $data->seccodigo;	
					
					//Verifico si ya existe en Ventas, si es asi, lo marco como Ambos
					$queryCtrl = "SELECT 1 FROM PER_SECT WHERE PERCODIGO=$percodigo AND SECCODIGO=$codigo AND PERVENCOM='V' ";
					$TableCtrl = sql_query($queryCtrl,$conn);
					if($TableCtrl->Rows_Count>0){ //Existe
						$query = "	UPDATE PER_SECT SET PERVENCOM='A'
									WHERE PERCODIGO=$percodigo AND SECCODIGO=$codigo";
						$err = sql_execute($query,$conn);
					}else{
						$query = "	INSERT INTO PER_SECT(PERCODIGO,SECCODIGO,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn);
					}
				}
				
				//Recorro los subsectores
				if(isset($dataClasificarCom->subsectores)){
					foreach($dataClasificarCom->subsectores as $ind => $data){
						$codigo		= $data->secsubcod;
						
						//Verifico si ya existe en Ventas, si es asi, lo marco como Ambos 
						$queryCtrl = "SELECT 1 FROM PER_SSEC WHERE PERCODIGO=$percodigo AND SECSUBCOD=$codigo AND PERVENCOM='V' ";
						$TableCtrl = sql_query($queryCtrl,$conn);
						if($TableCtrl->Rows_Count>0){ //Existe
							$query = "	UPDATE PER_SSEC SET PERVENCOM='A'
										WHERE PERCODIGO=$percodigo AND SECSUBCOD=$codigo";
							$err = sql_execute($query,$conn);
						}else{
							$query = "	INSERT INTO PER_SSEC(PERCODIGO,SECSUBCOD,PERVENCOM)
										VALUES($percodigo,$codigo,'$pervencom')";
							$err = sql_execute($query,$conn);
						}
					}
				}
				
				//Recorro las categorias
				if(isset($dataClasificarCom->categorias)){
					foreach($dataClasificarCom->categorias as $ind => $data){
						$codigo		= $data->catcodigo;		

						//Verifico si ya existe en Ventas, si es asi, lo marco como Ambos 
						$queryCtrl = "SELECT 1 FROM PER_CATE WHERE PERCODIGO=$percodigo AND CATCODIGO=$codigo AND PERVENCOM='V' ";
						$TableCtrl = sql_query($queryCtrl,$conn);
						if($TableCtrl->Rows_Count>0){ //Existe
							$query = "	UPDATE PER_CATE SET PERVENCOM='A'
										WHERE PERCODIGO=$percodigo AND CATCODIGO=$codigo";
							$err = sql_execute($query,$conn);
						}else{
							$query = "	INSERT INTO PER_CATE(PERCODIGO,CATCODIGO,PERVENCOM)
										VALUES($percodigo,$codigo,'$pervencom')";
							$err = sql_execute($query,$conn);
						}
					}
				}
				//Recorro las subcategorias
				if(isset($dataClasificarCom->subcategorias)){
					foreach($dataClasificarCom->subcategorias as $ind => $data){
						$codigo		= $data->catsubcod;
						
						//Verifico si ya existe en Ventas, si es asi, lo marco como Ambos 
						$queryCtrl = "SELECT 1 FROM PER_SCAT WHERE PERCODIGO=$percodigo AND CATSUBCOD=$codigo AND PERVENCOM='V' ";
						$TableCtrl = sql_query($queryCtrl,$conn);
						if($TableCtrl->Rows_Count>0){ //Existe
							$query = "	UPDATE PER_SCAT SET PERVENCOM='A'
										WHERE PERCODIGO=$percodigo AND CATSUBCOD=$codigo";
							$err = sql_execute($query,$conn);
						}else{
							$query = "	INSERT INTO PER_SCAT(PERCODIGO,CATSUBCOD,PERVENCOM)
										VALUES($percodigo,$codigo,'$pervencom')";
							$err = sql_execute($query,$conn);
						}
					}
				}
			}
		}else{
			if($dataClasificarVen!=''){
				$pervencom = 'A'; //AMBAS
				$dataClasificarVen = json_decode($dataClasificarVen);
				foreach($dataClasificarVen->sectores as $ind => $data){
					$codigo		= $data->seccodigo;				
					$query = "	INSERT INTO PER_SECT(PERCODIGO,SECCODIGO,PERVENCOM)
								VALUES($percodigo,$codigo,'$pervencom')";
					$err = sql_execute($query,$conn);
				}
				
				//Recorro los subsectores
				if(isset($dataClasificarVen->subsectores)){
					foreach($dataClasificarVen->subsectores as $ind => $data){
						$codigo		= $data->secsubcod;
						$query = "	INSERT INTO PER_SSEC(PERCODIGO,SECSUBCOD,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn);
					}
				}
				
				//Recorro las categorias
				if(isset($dataClasificarVen->categorias)){
					foreach($dataClasificarVen->categorias as $ind => $data){
						$codigo		= $data->catcodigo;					
						$query = "	INSERT INTO PER_CATE(PERCODIGO,CATCODIGO,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn);
					}
				}
				//Recorro las subcategorias
				if(isset($dataClasificarVen->subcategorias)){
					foreach($dataClasificarVen->subcategorias as $ind => $data){
						$codigo		= $data->catsubcod;
						$query = "	INSERT INTO PER_SCAT(PERCODIGO,CATSUBCOD,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn);
					}
				}
			}
		}

		
		
		
		
	}
	$msgdescriaux = str_replace("*|Nombre Usuario|*",$pernombre.' '.$perapelli,$msgdescri);
	$msgdescriaux = str_replace("*|Empresa Usuario|*",$percompan,$msgdescriaux);
	$msgdescriaux = str_replace("*|Correo Usuario|*",$percorreo,$msgdescriaux);
		$param .= "::$percodigo";
		
		$body ='<!DOCTYPE html>
						<html lang="en" class="loading">
							<head>
								<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
							</head>

						<body>
						<div style="text-align:center">
	<img src=' . getUrl("/mailsimg/1/$msgimg") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
		<!--app-assets/img/logo-light.png
		-->
		<br>
	</div>
	<div dir="ltr">
		
<br>
		<br>
		
		<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
				<div>'.$msgdescriaux.'</div>
		</font>
		<div style="text-align:center; margin-top: 30px;">
			<a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'registerconf?N='.$param.'">'.$msgbot.'</a>
		</div>
		<div style="text-align:center; margin-top:10px;">
			<a style="background-color: #286efa!important;border: none;color: white;padding: 15px 32px;text-align:center;text-decoration: none;display: inline-block;border-radius: 8px;font-size: 16px;" href="https://www.addevent.com/dir/?client=atcUIPxJKzRJHYfkDmWv110113&start='.$evefch.'&end='.$evefchfin.'&title='.NAME_TITLE.'&location='.URL_WEB.'">Add to Calendar</a>
		</div>
	</div>
</body>
</html>';
				$tagnombreevento = preg_replace('/\s+/', '-', NAME_TITLE);
				if ($msgcc!=''){
					$mail = [
						"from_email" => SEND_MAIL_USUARIO,
							"from_name" => $msgcco,
							"subject" => $msgsub,
							"html" => $body,
							"tags"=> [
								$tagnombreevento."_1"
							],
						"headers" =>[

							"Reply-To" => $msgrep
						],
						"to" => [
							[
								"email" => $percorreo,
								"type" => "to"
							],
							[
								"email" => $msgcc,
								"type" => "cc"
							]
						]
					];


				}else{
					$mail = [
						"from_email" => SEND_MAIL_USUARIO,
							"from_name" => $msgcco,
							"subject" => $msgsub,
							"html" => $body,
							"tags"=> [
								$tagnombreevento."_1"
							],
						"headers" =>[

							"Reply-To" => $msgrep
						],
						"to" => [
							[
								"email" => $percorreo,
								"type" => "to"
							]
						]
					];


				}
						
						if (filter_var($percorreo, FILTER_VALIDATE_EMAIL)) {
							
							if($err == 'SQLACCEPT'){
							
								$errcod = 0;
								sendMail($mail);
								$errmsg = 'Guardado correctamente!';
							}else{            
								$errcod = 2;
								$errmsg = 'Error al guardar';
							}
							
						   // var_dump(sendMail($mail)); die;
						}
	
					
	
			}


	sql_close($conn);
	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
