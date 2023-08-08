<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	

	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 			= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$dataClasificarVen	= (isset($_POST['dataClasificarVen']))? trim($_POST['dataClasificarVen']) : '';
	$dataClasificarCom	= (isset($_POST['dataClasificarCom']))? trim($_POST['dataClasificarCom']) : '';



	
	//Controlo si el usuario logueado es administrador
	$peradminlog = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	if($peradminlog!=1) $peradmin=0;

	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	
	$percodigo		= VarNullBD($percodigo		, 'N');	
	$queryreunion = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoReunion'";
	$Tablereunion = sql_query($queryreunion, $conn);
		$rowreunion = $Tablereunion->Rows[0];
		$tiporeunion = trim($rowreunion['ZVALUE']);
		
	
	
	//Almaceno la Clasificacion
	if($err == 'SQLACCEPT' && $errcod==0){
		$query=" DELETE FROM PER_SECT WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
		$query=" DELETE FROM PER_SSEC WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
		$query=" DELETE FROM PER_CATE WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
		$query=" DELETE FROM PER_SCAT WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
		

		////Verificio si es tipo Oferta/Demanda o es Networking
		if ($tiporeunion == 'true') {
			/// ES OFERTA / DEMANDA
			//Recorro la Clasificacion de Ventas
			if($dataClasificarVen!=''){
				$pervencom = 'V'; //Ventas
				$dataClasificarVen = json_decode($dataClasificarVen);
				foreach($dataClasificarVen->sectores as $ind => $data){
					$codigo		= $data->seccodigo;				
					$query = "	INSERT INTO PER_SECT(PERCODIGO,SECCODIGO,PERVENCOM)
								VALUES($percodigo,$codigo,'$pervencom')";
					$err = sql_execute($query,$conn,$trans);
				}
				
				//Recorro los subsectores
				if(isset($dataClasificarVen->subsectores)){
					foreach($dataClasificarVen->subsectores as $ind => $data){
						$codigo		= $data->secsubcod;
						$query = "	INSERT INTO PER_SSEC(PERCODIGO,SECSUBCOD,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn,$trans);
					}
				}
				
				//Recorro las categorias
				if(isset($dataClasificarVen->categorias)){
					foreach($dataClasificarVen->categorias as $ind => $data){
						$codigo		= $data->catcodigo;					
						$query = "	INSERT INTO PER_CATE(PERCODIGO,CATCODIGO,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn,$trans);
					}
				}
				//Recorro las subcategorias
				if(isset($dataClasificarVen->subcategorias)){
					foreach($dataClasificarVen->subcategorias as $ind => $data){
						$codigo		= $data->catsubcod;
						$query = "	INSERT INTO PER_SCAT(PERCODIGO,CATSUBCOD,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn,$trans);
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
					$TableCtrl = sql_query($queryCtrl,$conn,$trans);
					if($TableCtrl->Rows_Count>0){ //Existe
						$query = "	UPDATE PER_SECT SET PERVENCOM='A'
									WHERE PERCODIGO=$percodigo AND SECCODIGO=$codigo";
						$err = sql_execute($query,$conn,$trans);
					}else{
						$query = "	INSERT INTO PER_SECT(PERCODIGO,SECCODIGO,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn,$trans);
					}
				}
				
				//Recorro los subsectores
				if(isset($dataClasificarCom->subsectores)){
					foreach($dataClasificarCom->subsectores as $ind => $data){
						$codigo		= $data->secsubcod;
						
						//Verifico si ya existe en Ventas, si es asi, lo marco como Ambos 
						$queryCtrl = "SELECT 1 FROM PER_SSEC WHERE PERCODIGO=$percodigo AND SECSUBCOD=$codigo AND PERVENCOM='V' ";
						$TableCtrl = sql_query($queryCtrl,$conn,$trans);
						if($TableCtrl->Rows_Count>0){ //Existe
							$query = "	UPDATE PER_SSEC SET PERVENCOM='A'
										WHERE PERCODIGO=$percodigo AND SECSUBCOD=$codigo";
							$err = sql_execute($query,$conn,$trans);
						}else{
							$query = "	INSERT INTO PER_SSEC(PERCODIGO,SECSUBCOD,PERVENCOM)
										VALUES($percodigo,$codigo,'$pervencom')";
							$err = sql_execute($query,$conn,$trans);
						}
					}
				}
				
				//Recorro las categorias
				if(isset($dataClasificarCom->categorias)){
					foreach($dataClasificarCom->categorias as $ind => $data){
						$codigo		= $data->catcodigo;		

						//Verifico si ya existe en Ventas, si es asi, lo marco como Ambos 
						$queryCtrl = "SELECT 1 FROM PER_CATE WHERE PERCODIGO=$percodigo AND CATCODIGO=$codigo AND PERVENCOM='V' ";
						$TableCtrl = sql_query($queryCtrl,$conn,$trans);
						if($TableCtrl->Rows_Count>0){ //Existe
							$query = "	UPDATE PER_CATE SET PERVENCOM='A'
										WHERE PERCODIGO=$percodigo AND CATCODIGO=$codigo";
							$err = sql_execute($query,$conn,$trans);
						}else{
							$query = "	INSERT INTO PER_CATE(PERCODIGO,CATCODIGO,PERVENCOM)
										VALUES($percodigo,$codigo,'$pervencom')";
							$err = sql_execute($query,$conn,$trans);
						}
					}
				}
				//Recorro las subcategorias
				if(isset($dataClasificarCom->subcategorias)){
					foreach($dataClasificarCom->subcategorias as $ind => $data){
						$codigo		= $data->catsubcod;
						
						//Verifico si ya existe en Ventas, si es asi, lo marco como Ambos 
						$queryCtrl = "SELECT 1 FROM PER_SCAT WHERE PERCODIGO=$percodigo AND CATSUBCOD=$codigo AND PERVENCOM='V' ";
						$TableCtrl = sql_query($queryCtrl,$conn,$trans);
						if($TableCtrl->Rows_Count>0){ //Existe
							$query = "	UPDATE PER_SCAT SET PERVENCOM='A'
										WHERE PERCODIGO=$percodigo AND CATSUBCOD=$codigo";
							$err = sql_execute($query,$conn,$trans);
						}else{
							$query = "	INSERT INTO PER_SCAT(PERCODIGO,CATSUBCOD,PERVENCOM)
										VALUES($percodigo,$codigo,'$pervencom')";
							$err = sql_execute($query,$conn,$trans);
						}
					}
				}
			}
			
		} else {
			/// ES NETWORKING
			if($dataClasificarVen!=''){
				$pervencom = 'A'; //Ambas
				$dataClasificarVen = json_decode($dataClasificarVen);
				foreach($dataClasificarVen->sectores as $ind => $data){
					$codigo		= $data->seccodigo;				
					$query = "	INSERT INTO PER_SECT(PERCODIGO,SECCODIGO,PERVENCOM)
								VALUES($percodigo,$codigo,'$pervencom')";
					$err = sql_execute($query,$conn,$trans);
				}
				
				//Recorro los subsectores
				if(isset($dataClasificarVen->subsectores)){
					foreach($dataClasificarVen->subsectores as $ind => $data){
						$codigo		= $data->secsubcod;
						$query = "	INSERT INTO PER_SSEC(PERCODIGO,SECSUBCOD,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn,$trans);
					}
				}
				
				//Recorro las categorias
				if(isset($dataClasificarVen->categorias)){
					foreach($dataClasificarVen->categorias as $ind => $data){
						$codigo		= $data->catcodigo;					
						$query = "	INSERT INTO PER_CATE(PERCODIGO,CATCODIGO,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn,$trans);
					}
				}
				//Recorro las subcategorias
				if(isset($dataClasificarVen->subcategorias)){
					foreach($dataClasificarVen->subcategorias as $ind => $data){
						$codigo		= $data->catsubcod;
						$query = "	INSERT INTO PER_SCAT(PERCODIGO,CATSUBCOD,PERVENCOM)
									VALUES($percodigo,$codigo,'$pervencom')";
						$err = sql_execute($query,$conn,$trans);
					}
				}
			}

		}
		
		
		
		
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Guardado correctamente!') : $errmsg;
	}	
		
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	

