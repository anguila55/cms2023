<?php
	if(!isset($_SESSION))  session_start();
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/idioma.php';
	
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('comptick.html');
	
	//require_once 'func/mercado/autoload.php';
	//MercadoPago\SDK::setAccessToken("TEST-5301884912047897-070619-7b88ec27814ca6414a45220ac0e22940-603996575");
	
	$publicKeyMP = 'APP_USR-06ad317e-2698-4490-b190-1e683615e654';
	
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : 0;
	$percodigo=18175;
	if($percodigo==0){
		 header('Location: login');
	}else{
		
		$conn= sql_conectar();//Apertura de Conexion
		
		//Cargo los tickets a comprar
		$query="SELECT TIKCODIGO,TIKDESCRI,TIKVALOR
				FROM TIK_MAEST
				WHERE ESTCODIGO=1 
				ORDER BY TIKORDEN ";
		
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$tikcodigo 	= trim($row['TIKCODIGO']);
			$tikdescri 	= trim($row['TIKDESCRI']);
			$tikvalor 	= trim($row['TIKVALOR']);
			
			$tikvalortxt = '$'.number_format(floatval($tikvalor),2 , ',', '.');
			if($tikvalor==0) $tikvalortxt = 'Free';
			
			if($tikvalor!=0){
				$tmpl->setCurrentBlock('formstick');
				$tmpl->setVariable('publicKeyMP', $publicKeyMP);
				$tmpl->setVariable('tikcodfor', $tikcodigo);
				$tmpl->setVariable('tikdesfor', $tikdescri);
				$tmpl->setVariable('tikvalfor', $tikvalor);
				$tmpl->parse('formstick');
			}else{
				$tmpl->setCurrentBlock('formstickfree');
				$tmpl->setVariable('tikcodffor', $tikcodigo);
				$tmpl->setVariable('tikdesffor', $tikdescri);
				$tmpl->parse('formstickfree');
			}


			
			
			// Crea un objeto de preferencia
			//$preference = new MercadoPago\Preference();

			// Crea un ítem en la preferencia
			//$item = new MercadoPago\Item();
			//$item->title = $tikdescri;
			//$item->category_id = $tikcodigo;
			//$item->quantity = 1;
			//$item->unit_price = $tikvalor;
			//$preference->items = array($item);
			//$preference->save();
		}

		if (($IdiomView=='ESP')){

			$tmpl->setVariable('incripciondos'		, '"Con la inscripción en la plataforma del Congreso Aapresid 2020 tendrás accesos a 1 charla gratuita a elección. El acceso a la plataforma es irrestricto para cualquier día del congreso."');
			$tmpl->setVariable('tickets'		, 'Tickets para acceso a charlas del 24 al 28 de Agosto:');
			$tmpl->setVariable('codigopromocional'		, 'Código promocional:');
			$tmpl->setVariable('validar'		, 'VALIDAR');
			$tmpl->setVariable('contratar'		, 'CONTRATAR');
			$tmpl->setVariable('cancelar'		, 'CANCELAR');
			$tmpl->setVariable('textocartel'		, '¿Desea contratar el ticket de acceso gratuito?');
			$tmpl->setVariable('titulocartel'		, 'TICKET GRATUITO');

		}else{
	
			$tmpl->setVariable('incripciondos'		, '"By registring in the 2020 AAPRESID CONGRESS PLATFORM, you will gain access to 1 free keynote or workshop of your choice. All the other sections of the platform are completely free and open for the duration of the Congress."');
			$tmpl->setVariable('tickets'		, 'Tickets for access to talks from August 24 to 28:');
			$tmpl->setVariable('codigopromocional'		, 'Promotional code:');
			$tmpl->setVariable('validar'		, 'VALIDATE');
			$tmpl->setVariable('contratar'		, 'GET');
			$tmpl->setVariable('cancelar'		, 'CANCEL');
			$tmpl->setVariable('textocartel'		, 'Do you want to contract the free access ticket?');
			$tmpl->setVariable('titulocartel'		, 'FREE TICKET');
		}
		
		sql_close($conn);
	}
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	

