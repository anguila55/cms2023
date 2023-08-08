<?php
	if(!isset($_SESSION))  session_start();
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	
	require_once 'func/mercado/autoload.php';
	MercadoPago\SDK::setAccessToken("APP_USR-7291647091659820-071219-ddf8db425232c2f32c4ed9687bdffd42-780102457");
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_GET['P']))? trim($_GET['P']):0; 
	$errcod = 0;
	$errnum	= '';
	
	$tikcod 			= (isset($_REQUEST["tikcodigo"]))? $_REQUEST["tikcodigo"] : 0;
	$token 				= (isset($_REQUEST["token"]))? $_REQUEST["token"] : '';
	$payment_method_id	= (isset($_REQUEST["payment_method_id"]))? $_REQUEST["payment_method_id"] : '';
	$installments		= (isset($_REQUEST["installments"]))? $_REQUEST["installments"] : 0;
	$issuer_id			= (isset($_REQUEST["issuer_id"]))? $_REQUEST["issuer_id"] : 0;
	$tikcodpro			= (isset($_REQUEST["tikcodpro"]))? $_REQUEST["tikcodpro"] : '';
	
	if($percodigo=='') $percodigo=0;
	if($tikcod=='') $tikcod=0;	
	if($installments=='') $installments=0;	
	if($issuer_id=='') $issuer_id=0;
	if($tikcodpro=='') $tikcodpro=0;
	
	$datos = 	'perfil: '.$percodigo."\n";
	$datos .= 	'tik: '.$tikcod."\n";
	$datos .= 	'token: '.$token."\n";
	$datos .= 	'payment_method_id: '.$payment_method_id."\n";
	$datos .= 	'installments: '.$installments."\n";
	$datos .= 	'issuer_id: '.$issuer_id."\n";
	$datos .= 	'tikcodpro: '.$tikcodpro."\n";
	$datos .= 	'---'."\n";
	foreach($_REQUEST as $clave=>$valor){
			$datos .= 	$clave.': '.$valor."\n";
	}
	
		
	if($percodigo!=0 && $tikcod!=0){ //Existe el ticket seleccionado
		$conn= sql_conectar();//Apertura de Conexion
		
		//Busco el correo del usuario que paga
		$query = " 	SELECT P.PERCORREO
					FROM PER_MAEST P
					WHERE P.PERCODIGO=$percodigo ";
		$Table = sql_query($query, $conn);
		$row = $Table->Rows[0];
		$percorreo = trim($row['PERCORREO']);
		
		//$percorreo = 'test_user_89178550@testuser.com';//correo de prueba para testeos
		
		//Busco datos del ticket contratado
		$query = " 	SELECT TIKVALOR,TIKDESCRI
					FROM TIK_MAEST
					WHERE TIKCODIGO=$tikcod ";
		$Table = sql_query($query, $conn);
		$row = $Table->Rows[0];
		$tikdescri = trim($row['TIKDESCRI']);
		$tikvalor = trim($row['TIKVALOR']);
		
		$datos .= 	'tikdescri: '.$tikdescri."\n";
		$datos .= 	'tikvalor: '.$tikvalor."\n";
		
		if($token!=''){
			$payment = new MercadoPago\Payment();
			$payment->transaction_amount = $tikvalor;
			$payment->token = $token;
			$payment->description = $tikdescri;
			$payment->installments = $installments;
			$payment->payment_method_id = $payment_method_id;
			$payment->issuer_id = $issuer_id;
			$payment->payer = array(
				"email" => $percorreo
			);
			
			$payment->save();
			$status = $payment->status;
			$status_detail = $payment->status_detail;
			$status_id = $payment->id;
			$datos .= "status: ".$status."\n";
			$datos .= "status_detail: ".$status_detail."\n";
			$datos .= "status_id: ".$status_id."\n";
			$datos .= 	"-----------------------------\n\n";
		}elseif($tikvalor==0){ //Ticket Gratuito
			//Si el ticket es gratuito, verifico si ya lo compro anteriormente
			$queryCtrl = " 	SELECT PERTIKREG
							FROM PER_TICK
							WHERE PERCODIGO=$percodigo AND TIKCODIGO=$tikcod ";
			$TableCtrl = sql_query($queryCtrl, $conn);
			if($TableCtrl->Rows_Count>0){
				$status ='rechazado';
				$errnum ='?E=5001';
				$errcod=2;
			}else{
				$status='approved';
			}
		}
		//$customer = new MercadoPago\Customer();
		//$customer->email = $percorreo;
		//$customer->save();
				
		if($errcod==0 && $status=='approved'){
			$query = " 	INSERT INTO PER_TICK (PERCODIGO,PERTIKREG,TIKCODIGO,TIKTOKEN ,TIKPAYMET,TIKCUOTAS,TIKUSERID,TIKESTCOD,TIKFCHREG,TIKTARJETA,TIKORDEN,TIKMONEDA,TIKDATE,TIKTIPO) 
						VALUES($percodigo,GEN_ID(TIK_REGISTRO,1),$tikvalor,'$token','$payment_method_id',$installments,$issuer_id,1,CURRENT_TIMESTAMP,'$payment_method_id','$token','Pesos Argentinos',CURRENT_TIMESTAMP,0)";
			$err = sql_execute($query,$conn);
		}else{
			$errcod=2;
			
			if($status=='in_process'){ //status == in_process		
				switch($status_detail){
					case 'pending_contingency':
						$errnum = '?E=1813';
						break;
					case 'pending_review_manual':
						$errnum = '?E=1814';
						break;
				}
			}else{//status == rejected		
				switch($status_detail){
					case 'cc_rejected_bad_filled_card_number':
						$errnum = '?E=1922';
						break;
					case 'cc_rejected_bad_filled_date':
						$errnum = '?E=1923';
						break;
					case 'cc_rejected_bad_filled_other':
						$errnum = '?E=1924';
						break;
					case 'cc_rejected_bad_filled_security_code':
						$errnum = '?E=1925';
						break;
					case 'cc_rejected_blacklist':
						$errnum = '?E=1926';
						break;
					case 'cc_rejected_call_for_authorize':
						$errnum = '?E=1927';
						break;
					case 'cc_rejected_card_disabled':
						$errnum = '?E=1928';
						break;
					case 'cc_rejected_card_error':
						$errnum = '?E=1929';
						break;
					case 'cc_rejected_duplicated_payment':
						$errnum = '?E=1930';
						break;
					case 'cc_rejected_high_risk':
						$errnum = '?E=1931';
						break;
					case 'cc_rejected_insufficient_amount':
						$errnum = '?E=1932';
						break;
					case 'cc_rejected_invalid_installments':
						$errnum = '?E=1933';
						break;
					case 'cc_rejected_max_attempts':
						$errnum = '?E=1934';
						break;
					case 'cc_rejected_other_reason':
						$errnum = '?E=1935';
						break;
				}
			}
			
		}
		sql_close($conn);
		
		if($errcod==0){
			//header('Location: comptickres');
			echo "<script>top.window.location = '../../enviomailtransaccion.php?ID=$percodigo'</script>";
		}else{
			//header('Location: comptickerr'.$errnum);
			echo "<script>top.window.location = 'comptickerr".$errnum."'</script>";
		}
	}else if($percodigo!=0 && $tikcod==0 && $tikcodpro!=''){ //Ingreso un codigo promocional
		$conn= sql_conectar();//Apertura de Conexion
		//Busco si el codigo promocional es valido
		$query = " 	SELECT TIKCODREG,TIKCODIGO
					FROM TIK_CODE
					WHERE TIKCODVAL='$tikcodpro' AND TIKCODEST=1 AND PERCODIGO IS NULL ";
		
		$Table = sql_query($query, $conn);
		if($Table->Rows_Count>0){
			$row = $Table->Rows[0];
			$tikcodreg = trim($row['TIKCODREG']);
			$tikcodigo = trim($row['TIKCODIGO']);
			
			$query = " 	INSERT INTO PER_TICK (PERCODIGO,PERTIKREG,TIKCODIGO,TIKTOKEN ,TIKPAYMET,TIKCUOTAS,TIKUSERID,TIKESTCOD,TIKFCHREG) 
						VALUES($percodigo,GEN_ID(TIK_REGISTRO,1),$tikcodigo,'','CODPROMOCIONAL',0,$tikcodreg,1,CURRENT_TIMESTAMP)";
			$err = sql_execute($query,$conn);
			
			$query = " 	UPDATE TIK_CODE SET 
						TIKCODEST=2, PERCODIGO=$percodigo
						WHERE TIKCODREG=$tikcodreg";
			$err = sql_execute($query,$conn);
			$errcod=0;
			
		}else{
			//Codigo Promocional Invalido
			$errnum = '?E=3876';
			$errcod=2;
		}
		sql_close($conn);
		
		if($errcod==0){
			//header('Location: comptickres');
			echo "<script>top.window.location = '../../enviomailtransaccion.php?ID=$percodigo'</script>";
		}else{
			//header('Location: comptickerr'.$errnum);
			echo "<script>top.window.location = 'comptickerr".$errnum."'</script>";
		}
	}else{
		//header('Location: comptickerr');
		echo "<script>top.window.location = 'comptickerr'</script>";
	}
	
	$datos .= 	"-----------------------------\n\n";
	
	file_put_contents("datos.txt",$datos, FILE_APPEND);
?>