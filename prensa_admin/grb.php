<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	$pathimagenes = '../prensaimg/';
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);	   				
	}
	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';

	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$prereg 		= (isset($_POST['prereg']))? trim($_POST['prereg']) : 0;
	$pretitulo 		= (isset($_POST['pretitulo']))? trim($_POST['pretitulo']) : '';
	$predescri 	= (isset($_POST['predescri']))? trim($_POST['predescri']) : '';
	$preurl 		= (isset($_POST['preurl']))? trim($_POST['preurl']) : '';
	$precatego 		= (isset($_POST['precatego']))? trim($_POST['precatego']) : '';
	$percodigo 		= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : '';
	$prefuente 	= (isset($_POST['prefuente']))? trim($_POST['prefuente']) : '';
	$prebajada 	= (isset($_POST['prebajada']))? trim($_POST['prebajada']) : '';
	$prefecha 	= (isset($_POST['prefecha']))? trim($_POST['prefecha']) : '';
	$pretipo 		= (isset($_POST['pretipo']))? trim($_POST['pretipo']) : 0;
	$pretamano 		= (isset($_POST['pretamano']))? trim($_POST['pretamano']) : 0;
	$prepos 		= (isset($_POST['prepos']))? trim($_POST['prepos']) : 0;
	
	$preestado 		= 1;
	
	if($prereg==''){
		$prereg=0;
	}
	if($prepos==''){
		$prepos=999999;
	}
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$prereg			= VarNullBD($prereg			, 'N');
	$pretitulo		= VarNullBD($pretitulo		, 'S');
	$predescri     =VarNullBD($predescri, 			'S');
	$preurl         =VarNullBD($preurl, 		'S');
	$precatego		= VarNullBD($precatego		, 'S');
	$percodigo		= VarNullBD($percodigo		, 'N');
	$preestado		= VarNullBD($preestado		, 'N');
	$prefuente     =VarNullBD($prefuente, 			'S');
	$prebajada         =VarNullBD($prebajada, 		'S');
	$prefecha = substr($prefecha,5,2).'/'.substr($prefecha,8,2).'/'.substr($prefecha,0,4);
	$pretipo		= VarNullBD($pretipo		, 'N');
	$pretamano		= VarNullBD($pretamano		, 'N');
	$prepos		= VarNullBD($prepos		, 'N');

	if($prereg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_PRENSA,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$prereg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO PREN_MAEST (PREREG,PRETITULO,PREDESCRI,PREURL,PRECATEGO,PERCODIGO,PREESTADO,PREFUENTE,PREBAJADA,PREFECHA,PRETIPO,PRETAMANO,PREN_ORD)
					VALUES ($prereg,$pretitulo,$predescri,$preurl,$precatego,$percodigo,$preestado,$prefuente,$prebajada,'$prefecha',$pretipo,$pretamano,$prepos) ";
	}else{
		$query = " 	UPDATE PREN_MAEST SET 
					PRETITULO=$pretitulo,PREDESCRI=$predescri, PREURL=$preurl, PRECATEGO=$precatego,
					PERCODIGO=$percodigo,PREESTADO=$preestado, PREFUENTE=$prefuente, PREBAJADA=$prebajada, PREFECHA='$prefecha',
					PRETIPO=$pretipo, PRETAMANO=$pretamano, PREN_ORD=$prepos
					WHERE PREREG=$prereg ";
	}
	//logerror($query);
	//die();
	$err = sql_execute($query,$conn,$trans);	
	date_default_timezone_set('UTC');
	//--------------------------------------------------------------------------------------------------------------
	if(isset($_FILES['preimg'])){
		
		$ext 	= pathinfo($_FILES['preimg']['name'], PATHINFO_EXTENSION);
		$name 	= 'PRENIMG'.date(mktime(0, 0, 0, 7, 1, 2000)).'.'.$ext;
		
		if (!file_exists($pathimagenes.$prereg)) {
			mkdir($pathimagenes.$prereg);	   				
		}
		if(file_exists($pathimagenes.$prereg.'/'.$name)){
			unlink($pathimagenes.$prereg.'/'.$name);
		}


		/* ------------------------- RESIZE A LA IMAGEN 720 ------------------------- */
		function resize_image($file, $w, $h, $crop=FALSE) {
			list($width, $height) = getimagesize($file);
			$r = $width / $height;
			if ($crop) {
				if ($width > $height) {
					$width = ceil($width-($width*abs($r-$w/$h)));
				} else {
					$height = ceil($height-($height*abs($r-$w/$h)));
				}
				$newwidth = $w;
				$newheight = $h;
			} else {
				if ($w/$h > $r) {
					$newwidth = $h*$r;
					$newheight = $h;
				} else {
					$newheight = $w/$r;
					$newwidth = $w;
				}
			}
			$src = imagecreatefromjpeg($file);
			$dst = imagecreatetruecolor($newwidth, $newheight);
			imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		
			return $dst;
		}


		
		/* ------------------------------------ - ----------------------------------- */
		
		move_uploaded_file( $_FILES['preimg']['tmp_name'], $pathimagenes.$prereg.'/'.$name);
		
		
		$img = resize_image($pathimagenes.$prereg.'/'.$name, 720, 720);
		imagepng($img, $pathimagenes.$prereg.'/'.$name);
		// logerror($img);
		$_SESSION[GLBAPPPORT.'PREIMG'] =  $pathimagenes.$prereg.'/'.$name; //Actualizo la variable de Session del AVATAR
		
		$query = "	UPDATE PREN_MAEST SET PREIMG='$name' WHERE PREREG=$prereg ";
		$err = sql_execute($query,$conn,$trans);
	}
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Guardado correctamente!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al guardar la nota! Revisar cantidad de caracteres' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
