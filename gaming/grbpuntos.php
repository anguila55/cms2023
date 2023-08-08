<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
		//--------------------------------------------------------------------------------------------------------------
		$errcod = 0;
		$errmsg = '';
		$err 	= 'SQLACCEPT';
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion

	$percodlog 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';

	//--------------------------------------------------------------------------------------------------------------
	$tipo = (isset($_POST['tipo']))? trim($_POST['tipo']):0;
	$valor = (isset($_POST['valor']))? trim($_POST['valor']):0;


	/////////////// Definicion de los tipos///////
	/*

	tipo=1 es visita al stand
	tipo=2 es click banner
	tipo=3 es click linkedin
	tipo=4 es click twitter
	tipo=5 es sala vip de reuniones
	tipo=6 es click a la web del expositor
	tipo=7 es brouchure principal
	tipo=8 es folletos
	tipo=9 videos en a la carta
	tipo=10 documentos a la carta
	tipo=11 videos en vivo sala
	tipo=12 videos diferidos en sala

	*/

	
	
	if($percodlog!=0 && $tipo!=0){
		
		//Verifico si existe la relacion 
		
		$query = "	SELECT PTS, MODELO
					FROM GAME_TABLE  
					WHERE TIPO=$tipo";		
		$TableCtrl = sql_query($query,$conn);
		if($TableCtrl->Rows_Count>0){
			$row= $TableCtrl->Rows[0];
			$modelo 	= trim($row['MODELO']);
			$puntos 	= trim($row['PTS']);
		}
		
		if ($modelo=='recurrente'){

			$query = "	INSERT INTO GAME_PTS (PERCODIGO, GAMEID, TIPO, VALOR, GAMEFCH, PUNTOS) 
					VALUES ($percodlog, GEN_ID(G_GAMEID,1), $tipo, $valor, CURRENT_TIMESTAMP, $puntos)";
			$err = sql_execute($query,$conn);

		}else{
			$query = "	SELECT PERCODIGO
					FROM GAME_PTS  
					WHERE PERCODIGO=$percodlog AND TIPO=$tipo AND VALOR=$valor";		
				$Table = sql_query($query,$conn);
				if($Table->Rows_Count>0){
					
				}else{

					$query = "	INSERT INTO GAME_PTS (PERCODIGO, GAMEID, TIPO, VALOR, GAMEFCH, PUNTOS) 
					VALUES ($percodlog, GEN_ID(G_GAMEID,1), $tipo, $valor, CURRENT_TIMESTAMP, $puntos)";
					$err = sql_execute($query,$conn);
				}

		}
			
		
	}
	//--------------------------------------------------------------------------------------------------------------
	

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
