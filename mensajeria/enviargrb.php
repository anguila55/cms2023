<?php include('../val/valuser.php'); ?>
<?


	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaAPI  . '/mailchimp.php';
	require_once GLBRutaFUNC . '/constants.php';

			
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
	$percargo 			= (isset($_SESSION[GLBAPPPORT.'PERCARGO']))? trim($_SESSION[GLBAPPPORT.'PERCARGO']) : '';
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$msgreg 	= (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$perclase 	= (isset($_POST['perclase']))? trim($_POST['perclase']) : 0;
	$pertipo 	= (isset($_POST['pertipo']))? trim($_POST['pertipo']) : 0;
	$pertipousuario 	= (isset($_POST['pertipousuario']))? trim($_POST['pertipousuario']) : 1;
	

	//--------------------------------------------------------------------------------------------------------------
	$msgreg = VarNullBD($msgreg , 'N');
	$pertipo = VarNullBD($pertipo , 'N');
	$perclase = VarNullBD($perclase , 'N');
	$pertipousuario = VarNullBD($pertipousuario , 'N');
	$array=null;
	if ($msgreg!=0) {
		$usuario='';
		$mail='';
		$query="SELECT MSGREP, MSGCC,MSGCCO FROM MSG_CABE WHERE MSGREG=10";
		$Table = sql_query($query,$conn,$trans);
		$row = $Table->Rows[0];
		$msgrep 	= trim($row['MSGREP']);
		$msgcc	= trim($row['MSGCC']);
		$msgcco	= trim($row['MSGCCO']);
		$query="SELECT MSGTITULO,MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG FROM MSG_CABE WHERE MSGREG=$msgreg";
		$Table = sql_query($query,$conn,$trans);
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
		

		
		
		$where = '';
		if($pertipousuario==1){
			$where .= " ESTCODIGO=1 ";
		}else if($pertipousuario==2){
			$where .= " ESTCODIGO<>3 ";
		}else if($pertipousuario==3){
			$where .= " ESTCODIGO=3 ";
		}else if($pertipousuario==8){
			$where .= " ESTCODIGO=8 ";
		}else if($pertipousuario==9){
			$where .= " ESTCODIGO=9 ";
		}
		if($perclase!=0){
			$where .= " AND P.PERCLASE=$perclase ";
		}
		if($pertipo!=0){
			$where .= " AND P.PERTIPO=$pertipo ";
		}
		
		
		$query = " 	SELECT PERNOMBRE, PERAPELLI, PERCORREO, PERCOMPAN 
					FROM PER_MAEST P
					WHERE $where ";		
		if($perclase==9999){ //Perfiles sin Reuniones Aceptadas
			$query = "	SELECT PERNOMBRE, PERAPELLI, PERCORREO 
						FROM REU_CABE R
						LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODDST
						WHERE R.REUESTADO=1 AND P.ESTCODIGO<>3  ";
		}
		
		$Table = sql_query($query,$conn,$trans);
		
		
		
		
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$pernombre	= trim($row['PERNOMBRE']);
			$perapelli = trim($row['PERAPELLI']);
			$percorreo 	= trim($row['PERCORREO']);
			$percompan 	= trim($row['PERCOMPAN']);
			$msgdescriaux = str_replace("*|Nombre Usuario|*",$pernombre.' '.$perapelli,$msgdescri);
			$msgdescriaux = str_replace("*|Empresa Usuario|*",$percompan,$msgdescriaux);
			$msgdescriaux = str_replace("*|Correo Usuario|*",$percorreo,$msgdescriaux);


			$body = '<!DOCTYPE html>
	<html lang="en" class="loading">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		</head>
	
	<body>
	<div style="text-align:center">
	<img src=' . getUrl("/mailsimg/$msgreg/$msgimg") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
		<!--app-assets/img/logo-light.png
		-->
		<br>
	</div>
	<div dir="ltr" style="max-width: 800px;">
		
		<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
				<div>'.$msgdescriaux.'</div>
		</font>
		<div style="text-align:center; margin-top: 30px;">
					<b><a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="'.$msglnk.'">'.$msgbot.'</a></b>
		</div>
	</div>
</body>
</html>';
			$tagnombreevento = preg_replace('/\s+/', '-', NAME_TITLE);
			$mail = [
				"from_email" => SEND_MAIL_USUARIO,
				"from_name" => $msgcco,
				"subject" => $msgsub,
				"preserve_recipients" => FALSE,
				"html" => $body,
				"tags"=> [
					$tagnombreevento."_".$msgreg
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


		
	
		
		sendMail($mail);
		
			//$array[]=["email"=>$percorreo,"type"=>"to"];
		}
	
	
		
		
		
	}
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Su correo fue enviado!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al enviar!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
