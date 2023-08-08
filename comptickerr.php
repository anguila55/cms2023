<?php
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/idioma.php';
	require_once GLBRutaFUNC . '/constants.php';
	
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('comptickerr.html');
	
	//https://www.mercadopago.com.ar/developers/es/guides/payments/api/handling-responses/
	//--------------------------------------------------------------------------------------------------------------
	$errcod = (isset($_GET['E']))? trim($_GET['E']) : 0;
	$mensaje = '';
	
	if (($IdiomView=='ESP')){
		$reintentar = 'REINTENTAR';
	}else{
		$reintentar = 'RETRY';
	}

	switch($errcod){
		case 1813: // in_process - pending_contingency
			if (($IdiomView=='ESP')){
				$mensaje = 'Estamos procesando tu pago. No te preocupes, menos de 2 días hábiles te avisaremos por e-mail si se acreditó.';
			}else{
				$mensaje = 'We are processing your payment. Do not worry, in less than 2 business days we will notify you by e-mail if it has been credited.';
			}
			break;
		case 1814: // in_process - pending_review_manual
			if (($IdiomView=='ESP')){
				$mensaje = 'Estamos procesando tu pago. No te preocupes, menos de 2 días hábiles te avisaremos por e-mail si se acreditó o si necesitamos más información.';
			}else{
				$mensaje = 'We are processing your payment. Do not worry, less than 2 business days we will notify you by email if it has been accredited or if we need more information.';
			}
			
			break;
			
		case 1922: // rejected - cc_rejected_bad_filled_card_number
			if (($IdiomView=='ESP')){
				$mensaje = 'Por favor, revisa el número de tarjeta.';
			}else{
				$mensaje = 'Please check the card number.';
			}
			break;
		case 1923: // rejected - cc_rejected_bad_filled_date
			if (($IdiomView=='ESP')){
				$mensaje = 'Por favor, revisa la fecha de vencimiento.';
			}else{
				$mensaje = 'Please check the expiration date.';
			}
			
			break;
		case 1924: // rejected - cc_rejected_bad_filled_other
			if (($IdiomView=='ESP')){
				$mensaje = 'Por favor, revisa los datos ingresados.';
			}else{
				$mensaje = 'Please, review the entered data.';
			}
			break;
		case 1925: // rejected - cc_rejected_bad_filled_security_code
			if (($IdiomView=='ESP')){
				$mensaje = 'Por favor, revisa el código de seguridad de la tarjeta.';
			}else{
				$mensaje = 'Please check the cards security code.';
			}
			
			break;
		case 1926: // rejected - cc_rejected_blacklist
			if (($IdiomView=='ESP')){
				$mensaje = 'No pudimos procesar tu pago.';
			}else{
				$mensaje = 'We were unable to process your payment.';
			}
			break;
		case 1927: // rejected - cc_rejected_call_for_authorize
			if (($IdiomView=='ESP')){
				$mensaje = 'Por favor, debes autorizar ante la empresa emisora de la tarjeta el importe del pago.';
			}else{
				$mensaje = 'Por favor, debes autorizar ante la empresa emisora de la tarjeta el importe del pago.';
			}
			break;
		case 1928: // rejected - cc_rejected_card_disabled
			if (($IdiomView=='ESP')){
				$mensaje = 'Por favor, llama a la empresa emisora para activar tu tarjeta o usa otro medio de pago. El teléfono está al dorso de tu tarjeta.';
			}else{
				$mensaje = 'Please call the issuing company to activate your card or use another payment method. The phone is on the back of your card.';
			}
			break;
		case 1929: // rejected - cc_rejected_card_error
			if (($IdiomView=='ESP')){
				$mensaje = 'No pudimos procesar tu pago.';
			}else{
				$mensaje = 'We were unable to process your payment.';
			}
			
			break;
		case 1930: // rejected - cc_rejected_duplicated_payment
			if (($IdiomView=='ESP')){
				$mensaje = 'Ya hiciste un pago por ese valor. Si necesitas volver a pagar usa otra tarjeta u otro medio de pago.';
			}else{
				$mensaje = 'You already made a payment for that value. If you need to pay again, use another card or other payment method.';
			}
			
			break;
		case 1931: // rejected - cc_rejected_high_risk
			if (($IdiomView=='ESP')){
				$mensaje = 'Tu pago fue rechazado. Elige otro de los medios de pago.';
			}else{
				$mensaje = 'Your payment was declined. Choose another payment method.';
			}
			
			break;
		case 1932: // rejected - cc_rejected_insufficient_amount
			if (($IdiomView=='ESP')){
				$mensaje = 'Tu tarjeta no tiene fondos suficientes.';
			}else{
				$mensaje = 'Your card does not have sufficient funds.';
			}
			
			break;
		case 1933: // rejected - cc_rejected_invalid_installments
			if (($IdiomView=='ESP')){
				$mensaje = 'Tu tarjeta no procesa pagos en las cuotas seleccionadas.';
			}else{
				$mensaje = 'Your card does not process payments in the selected installments.';
			}
			break;
		case 1934: // rejected - cc_rejected_max_attempts
			if (($IdiomView=='ESP')){
				$mensaje = 'Llegaste al límite de intentos permitidos.';
			}else{
				$mensaje = 'You have reached the limit of allowed attempts.';
			}
		
			break;
		case 1935: // rejected - cc_rejected_other_reason
			if (($IdiomView=='ESP')){
				$mensaje = 'La empresa emisora de la tarjeta no proceso el pago.';
			}else{
				$mensaje = 'The card issuing company did not process the payment';
			}
			
			break;
			
		case 3876: // ticket promocional invalido
			if (($IdiomView=='ESP')){
				$mensaje = 'El codigo ingresado no es valido.';
			}else{
				$mensaje = 'The entered code is not valid.';
			}
			break;
		
		case 5001: // ticket gratuito ya comprado
			if (($IdiomView=='ESP')){
				$mensaje = 'Ya contrato este ticket anteriormente, no puede volver a contratarlo.';
			}else{
				$mensaje = 'Since I previously contracted this ticket, you cannot re-contract it.';
			}
			
			break;
		case 6001: // ticket gratuito ya comprado
			if (($IdiomView=='ESP')){
				$mensaje = 'Tu pago fue rechazado. Elige otro de los medios de pago.';
			}else{
				$mensaje = 'Tu pago fue rechazado. Elige otro de los medios de pago.';
			}
			
			break;
		case 6002: // ticket gratuito ya comprado
				if (($IdiomView=='ESP')){
					$mensaje = 'Has cancelado la transacción en el formulario de pago. Intenta nuevamente.';
				}else{
					$mensaje = 'Has cancelado la transacción en el formulario de pago. Intenta nuevamente.';
				}
				
				break;
		case 6003: // ticket gratuito ya comprado
				if (($IdiomView=='ESP')){
					$mensaje = 'Al parecer ocurrió un error en el formulario de pago. Intenta nuevamente.';
				}else{
					$mensaje = 'Al parecer ocurrió un error en el formulario de pago. Intenta nuevamente.';
				}
				
				break;
		case 6004: // ticket gratuito ya comprado
				if (($IdiomView=='ESP')){
					$mensaje = 'Superaste el tiempo máximo que puedes estar en el formulario de pago (10 minutos). La transacción fue cancelada por Webpay.';
				}else{
					$mensaje = 'Superaste el tiempo máximo que puedes estar en el formulario de pago (10 minutos). La transacción fue cancelada por Webpay.';
				}
				
				break;
		case 6005: // ticket gratuito ya comprado
				if (($IdiomView=='ESP')){
					$mensaje = 'No pudimos procesar tu pago, intente nuevamente';
				}else{
					$mensaje = 'No pudimos procesar tu pago, intente nuevamente';
				}
				
				break;
					
				
		default:
			
		if (($IdiomView=='ESP')){
			$mensaje = 'Se presento un error al completar su compra, por favor reintente mas tarde';
		}else{
			$mensaje = 'An error occurred when completing your purchase, please retry later';
		}
		
			break;
		
	}
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
$tmpl->setVariable('NAME_TITLE', NAME_TITLE );
$tmpl->setVariable('LOGIN_PERIOD', LOGIN_PERIOD );
$tmpl->setVariable('LOGIN_EMAIL', SEND_MAIL_LOGIN );
	$tmpl->setVariable('mensaje', $mensaje);
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	

