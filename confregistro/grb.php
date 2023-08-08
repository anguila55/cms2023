<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	

			
	//--------------------------------------------------------------------------------------------------------------

	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';

	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	$textliberar 		= (isset($_POST['textliberar']))? trim($_POST['textliberar']) : '';
	$textconfirmar 		= (isset($_POST['textconfirmar']))? trim($_POST['textconfirmar']) : '';
	$evefchfin 		= (isset($_POST['evefchfin']))? trim($_POST['evefchfin']) : '';
	$evefch 		= (isset($_POST['evefch']))? trim($_POST['evefch']) : '';
	$nombreevento 		= (isset($_POST['nombreevento']))? trim($_POST['nombreevento']) : '';
	$mailcontacto 		= (isset($_POST['mailcontacto']))? trim($_POST['mailcontacto']) : '';
	$ocregistro 		= (isset($_POST['ocregistro']))? trim($_POST['ocregistro']) : '';
	$ingresoevento 		= (isset($_POST['ingresoevento']))? trim($_POST['ingresoevento']) : '';
	$tiporegistro 		= (isset($_POST['tiporegistro']))? trim($_POST['tiporegistro']) : '';
	$colorevento 		= (isset($_POST['colorevento']))? trim($_POST['colorevento']) : '';
	

	//--------------------------------------------------------------------------------------------------------------
	
	$query = "	UPDATE ZZZ_CONF SET
				ZDESCRI='$textconfirmar'
				WHERE ZPARAM='SisCorreoNombre' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZDESCRI='$textliberar'
				WHERE ZPARAM='SisCorreoPassword' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZDESCRI='$evefchfin'
				WHERE ZPARAM='SisCorreoLinkPlayStore' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZDESCRI='$evefch'
				WHERE ZPARAM='SisCorreoLinkAppStore' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZDESCRI='$nombreevento'
				WHERE ZPARAM='SisCorreoLinkExternalReuniones' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZDESCRI='$mailcontacto'
				WHERE ZPARAM='MenuNoticias' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZDESCRI='$ocregistro'
				WHERE ZPARAM='OcRegistro' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZDESCRI='$colorevento'
				WHERE ZPARAM='ColorEvento' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZDESCRI='$ingresoevento'
				WHERE ZPARAM='IngresoEvento' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZDESCRI='$tiporegistro'
				WHERE ZPARAM='TipoRegistro' ";
	$err = sql_execute($query,$conn,$trans);

	
		
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Guardado correctamente!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
