<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC.'/constants.php'; //constantes

	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	$peradminlog = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$percodlog = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';


	if ($jsonperfiles['Industria'] == 0 )
	{
		$tmpl->setVariable('mostrarindustria'	, 'd-none'	);
	}
	
	if($peradminlog!=1){ 
		$tmpl->setVariable('peradminstyle'	, 'display:none;'	);
		$tmpl->setVariable('peradminstyle2'	, 'd-none'	);
		$tmpl->setVariable('viewpercoment'	, 'display:none;'	);
		$tmpl->setVariable('viewproddestacados'	, 'display:none;'	);
		$tmpl->setVariable('cmbpertipo'		, 'disabled'	);
		$tmpl->setVariable('cmbperclase'	, 'disabled'	);
		$tmpl->setVariable('cmbmescodigo'         ,'disabled'    );
		$tmpl->setVariable('cmbrucod'         ,'disabled'    );
		
		$tmpl->setVariable('rubro'      			, 'display:none;'	    );
		$tmpl->setVariable('tipoPerfil'      		, 'display:none;'	    );
		$tmpl->setVariable('clasePerfil'      		, 'display:none;'	    );
		$tmpl->setVariable('sala'     				, 'd-none'	    );
		$tmpl->setVariable('linkaurl'     , 'display:none;'	    );
		
	// RECORRO EL JSON Y PONGO DISPLAY NONE
	   

		if ($jsonperfiles['Nombre'] == 0 )
		{
			$tmpl->setVariable('mostrarnombre'	, 'd-none'	);
		}
		if ($jsonperfiles['Apellido'] == 0 )
		{
			$tmpl->setVariable('mostrarapellido'	, 'd-none'	);
		}
		if ($jsonperfiles['Id'] == 0 )
		{
			$tmpl->setVariable('mostrarid'	, 'd-none'	);
		}
		if ($jsonperfiles['Empresa'] == 0 )
		{
			$tmpl->setVariable('mostrarempresa'	, 'd-none'	);
		}
		if ($jsonperfiles['Cargo'] == 0 )
		{
			$tmpl->setVariable('mostrarcargo'	, 'd-none'	);
		}
		if ($jsonperfiles['Idioma'] == 0 )
		{
			$tmpl->setVariable('mostraridioma'	, 'd-none'	);
		}
		if ($jsonperfiles['Industria'] == 0 )
		{
			$tmpl->setVariable('mostrarindustria'	, 'd-none'	);
		}
		if ($jsonperfiles['ZonaHoraria'] == 0 )
		{
			$tmpl->setVariable('mostrarzonahoraria'	, 'd-none'	);
		}
		if ($jsonperfiles['DescripcionEmpresa'] == 0 )
		{
			$tmpl->setVariable('mostrardesemp'	, 'd-none'	);
		}
		if ($jsonperfiles['Correo'] == 0 )
		{
			$tmpl->setVariable('mostrarcorreo'	, 'd-none'	);
		}
		if ($jsonperfiles['Telefono'] == 0 )
		{
			$tmpl->setVariable('mostrartelefono'	, 'd-none'	);
		}
		if ($jsonperfiles['Web'] == 0 )
		{
			$tmpl->setVariable('mostrarweb'	, 'd-none'	);
		}
		if ($jsonperfiles['Linkedin'] == 0 )
		{
			$tmpl->setVariable('mostrarlinkedin'	, 'd-none'	);
		}
		if ($jsonperfiles['Facebook'] == 0 )
		{
			$tmpl->setVariable('mostrarfacebook'	, 'd-none'	);
		}
		
		if ($jsonperfiles['Twitter'] == 0 )
		{
			$tmpl->setVariable('mostrartwitter'	, 'd-none'	);
		}
		if ($jsonperfiles['Instagram'] == 0 )
		{
			$tmpl->setVariable('mostrarinstagram'	, 'd-none'	);
		}
		if ($jsonperfiles['Direccion'] == 0 )
		{
			$tmpl->setVariable('mostrardireccion'	, 'd-none'	);
		}
		if ($jsonperfiles['Ciudad'] == 0 )
		{
			$tmpl->setVariable('mostrarciudad'	, 'd-none'	);
		}
		if ($jsonperfiles['Estado'] == 0 )
		{
			$tmpl->setVariable('mostrarestado'	, 'd-none'	);
		}
		if ($jsonperfiles['CodigoPostal'] == 0 )
		{
			$tmpl->setVariable('mostrarcodigopostal'	, 'd-none'	);
		}
		if ($jsonperfiles['Pais'] == 0 )
		{
			$tmpl->setVariable('mostrarpais'	, 'd-none'	);
		}




		
	}
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 			= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;

	//// ME FIJO QUE TIPO DE REUNION ES
	$queryreunion = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoReunion'";
	$Tablereunion = sql_query($queryreunion, $conn);
		$rowreunion = $Tablereunion->Rows[0];
		$tiporeunion = trim($rowreunion['ZVALUE']);


	if ($percodigo==$percodlog){
		
		$tmpl->setVariable('linkintereses'	, ''	);
		$tmpl->setVariable('linkoferta'	, 'd-none'	);
		$tmpl->setVariable('linkdemanda'	, 'd-none'	);
		$tmpl->setVariable('ocultarcancelar'	, 'd-none'	);
		
		
	}else{
		if ($tiporeunion == 'true') {
			$tmpl->setVariable('linkintereses'	, 'd-none'	);
			$tmpl->setVariable('linkoferta'	, ''	);
			$tmpl->setVariable('linkdemanda'	, ''	);
			$tmpl->setVariable('ocultarcancelar'	, ''	);
		}else{
			$tmpl->setVariable('linkintereses'	, ''	);
			$tmpl->setVariable('linkoferta'	, 'd-none'	);
			$tmpl->setVariable('linkdemanda'	, 'd-none'	);
			$tmpl->setVariable('ocultarcancelar'	, ''	);
		}
	}
	$fltviewproductos 	= (isset($_POST['fltviewproductos']))? trim($_POST['fltviewproductos']) : '';
	$perestcod 			= 1; //Activo por defecto
	$pernombre 			= '';
	$perapelli 			= '';
	$percorreo 			= '';
	$pertelefo 			= '';
	$perdirecc 			= '';
	$perciudad 			= '';
	$perestado 			= '';
	$paicodigo 			= '';
	$dataSectoresVen		= '';
	$dataSubsectoresVen		= '';
	$dataCategoriasVen		= '';
	$dataSubcategoriasVen	= '';
	$dataSectoresCom		= '';
	$dataSubsectoresCom 	= '';
	$dataCategoriasCom		= '';
	$dataSubcategoriasCom	= '';
	$perempdes			= '';
	$pertipo			= '';
	$perclase			= '';
	$mescodigo        	= '';
	$tipo        	= 0;
	$perrubcod          = '';
	$mesnumero          = '';
	$peridioma			= '';
	$perreuurl			= '';
	$percpf				= '';
	$perindcod          = 0;
	$perarecod          =0;
	//--------------------------------------------------------------------------------------------------------------
	$pathimagenes = '../perimg/'.$percodigo.'/';
	$imgProductoNull	= '../app-assets/img/pages/sativa.png';
	$tmpl->setVariable('imgProductoNull'	, $imgProductoNull 	);
	
	$imgAvatarNull = '../app-assets/img/avatar.png';
	$tmpl->setVariable('imgAvatarNull'	, $imgAvatarNull 	);
	//--------------------------------------------------------------------------------------------------------------
	//Acceso directo a clasificar productos
	$tmpl->setVariable('fltviewproductos'	, $fltviewproductos 	);
	//--------------------------------------------------------------------------------------------------------------
	//Avatar Inicial
	$tmpl->setVariable('peravatar'	, $imgAvatarNull 	);
	//--------------------------------------------------------------------------------------------------------------
	
	$conn= sql_conectar();//Apertura de Conexion
	

	// BUSCO EL TIPO DE EVENTO
	$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoEvento'";
	$Table2 = sql_query($query2, $conn);
	for ($i = 0; $i < $Table2->Rows_Count; $i++) {
		$row = $Table2->Rows[$i];
		$tipoevento = trim($row['ZVALUE']);
		if ($tipoevento == 'true') {
			$tmpl->setVariable('tipo2', 'selected');
			$tmpl->setVariable('mostrarqr', '');
		} else {

			if ($tipoevento == 'fix'){
				$tmpl->setVariable('tipo1', 'selected');
			}else{
				$tmpl->setVariable('tipo0', 'selected');
			}

			$tmpl->setVariable('mostrarqr', 'd-none');
		}
	}


	if($percodigo!=0){


		$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.ESTCODIGO,P.PERCOMPAN,P.PERRUBCOD,P.PERCORREO,P.PERCIUDAD,P.PERESTADO,
							P.PERCODPOS,P.PERTELEFO,P.PERURLWEB,P.PERUSUACC,P.PERPASACC,P.PERDIRECC,P.PERCARGO,
							P.PAICODIGO,P.PERTIPO,P.PERCLASE,P.PEREMPDES,P.PERAVATAR,P.PERADMIN,PC.PERUSAREU,
							P.PERCOMENT,P.MESCODIGO,P.PERIDIOMA,
							P.TIMREG2,P.TIMOFFSET,P.PERREUURL,P.PERINDCOD,P.PERARECOD,P.PERCPF,P.PERFAC,P.PERTWI,P.PERINS,P.PERCODEVE,P.PERLINKED,P.PAICODIGO2,P.TIPO,P.PREG_ADC
					FROM PER_MAEST P
					LEFT OUTER JOIN PER_CLASE PC ON PC.PERCLASE=P.PERCLASE 
					WHERE P.PERCODIGO=$percodigo ";
				
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo 	= trim($row['PERCODIGO']);
			$pernombre 	= trim($row['PERNOMBRE']);
			$perapelli 	= trim($row['PERAPELLI']);
			$estcodigo 	= trim($row['ESTCODIGO']);
			$percompan 	= trim($row['PERCOMPAN']);
			$perrubcod 	= trim($row['PERRUBCOD']);
			$percorreo 	= trim($row['PERCORREO']);
			$perciudad 	= trim($row['PERCIUDAD']);
			$perestado 	= trim($row['PERESTADO']);
			$percodpos 	= trim($row['PERCODPOS']);
			$pertelefo 	= trim($row['PERTELEFO']);
			$perurlweb 	= trim($row['PERURLWEB']);
			$perusuacc 	= trim($row['PERUSUACC']);
			$perpasacc 	= trim($row['PERPASACC']);
			$perdirecc 	= trim($row['PERDIRECC']);
			$percargo  	= trim($row['PERCARGO']);
			$percpf  	= trim($row['PERCPF']);  
			$paicodigo 	= trim($row['PAICODIGO']);
			$paicodigo2 	= trim($row['PAICODIGO2']);
			$pertipo 	= trim($row['PERTIPO']);
			$perclase 	= trim($row['PERCLASE']);
			$mescodigo  = trim($row['MESCODIGO']);
			$perempdes 	= trim($row['PEREMPDES']);
			$peravatar 	= trim($row['PERAVATAR']);
			$peradmin 	= trim($row['PERADMIN']);
			$perusadis 	= trim($row['PERUSAREU']);
		
			$percoment 	= trim($row['PERCOMENT']);
			$peridioma  = trim($row['PERIDIOMA']);
			$timreg 	= trim($row['TIMREG2']);
			$timoffset 	= trim($row['TIMOFFSET']);
			$perreuurl 	= trim($row['PERREUURL']);
			$perindcod 	= trim($row['PERINDCOD']);
			$perarecod 	= trim($row['PERARECOD']);
			$perfac 	= trim($row['PERFAC']);
			$pertwi 	= trim($row['PERTWI']);
			$perins 	= trim($row['PERINS']);
			$perlinked 	= trim($row['PERLINKED']);
			$percodeve 	= trim($row['PERCODEVE']);
			$tipo 	= trim($row['TIPO']);
		
		$pregadc = htmlspecialchars_decode(trim($row['PREG_ADC']));
		 
		$array2 = json_decode($pregadc, true);

		 for ($n=0; $n<count($array2) ; $n++) { 

		 		$tmpl->setCurrentBlock('preguntasadicionales');
		 	 	$tmpl->setVariable('preguntaadicional'	,$array2[$n]['pregunta']);
		  		$tmpl->setVariable('respuestaadicional'	, $array2[$n]['respuesta']);
		 	 	$tmpl->parse('preguntasadicionales');
		 	}
			
			
			if($peradmin=='') $peradmin=0;
			
			if($peravatar==''){ 
				$peravatar = $imgAvatarNull;
			}else{
				$peravatar = $pathimagenes.$peravatar;
			}
			
			
			$tmpl->setVariable('pernombre'	, $pernombre	);
			$tmpl->setVariable('perapelli'	, $perapelli	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			$tmpl->setVariable('percompan'	, $percompan	);
			$tmpl->setVariable('perrubcod'	, $perrubcod	);

			$tmpl->setVariable('percorreo'	, $percorreo	);
			$tmpl->setVariable('perciudad'	, $perciudad	);
			$tmpl->setVariable('perestado'	, $perestado	);
			$tmpl->setVariable('percodpos'	, $percodpos	);
			$tmpl->setVariable('pertelefo'	, $pertelefo	);
			$tmpl->setVariable('perurlweb'	, $perurlweb	);
			$tmpl->setVariable('perusuacc'	, $perusuacc	);
			$tmpl->setVariable('perpasacc'	, $perpasacc	);
			$tmpl->setVariable('perdirecc'	, $perdirecc	);
			$tmpl->setVariable('percargo'	, $percargo 	);
			$tmpl->setVariable('perempdes'	, $perempdes 	);
			$tmpl->setVariable('peravatar'	, $peravatar 	);
			$tmpl->setVariable('percoment'	, $percoment 	);
			$tmpl->setVariable('peridioma'	, $peridioma 	);
			$tmpl->setVariable('perreuurl'	, $perreuurl 	);
			$tmpl->setVariable('perindcod'	, $perindcod 	);
			$tmpl->setVariable('perarecod'	, $perarecod 	);
			$tmpl->setVariable('percpf'		, $percpf 		);
			$tmpl->setVariable('perfac'		, $perfac 		);
			$tmpl->setVariable('pertwi'		, $pertwi 		);
			$tmpl->setVariable('perins'		, $perins 		);
			$tmpl->setVariable('perlinked'		, $perlinked 		);
			$tmpl->setVariable('percodeve'	, $percodeve 	);

			if ($tipo == 0){ // virtuales
				$tmpl->setVariable('tipo0'	, 'selected' );
				$tmpl->setVariable('tipo1'	, '' );
				$tmpl->setVariable('tipo2'	, '' );
			}else if ($tipo == 1){  // presenciales
				$tmpl->setVariable('tipo1'	, 'selected' );
				$tmpl->setVariable('tipo0'	, '' );
				$tmpl->setVariable('tipo2'	, '' );
			}else if ($tipo == 2){ // ambas
				$tmpl->setVariable('tipo2'	, 'selected' );
				$tmpl->setVariable('tipo0'	, '' );
				$tmpl->setVariable('tipo1'	, '' );
			}
			
			$tmpl->setVariable('peradminsel_'.$peradmin	, 'selected' 	);
			
			if($perusadis!=1 && $peradminlog!=1){
				$tmpl->setVariable('btnviewdisp' , 'display:none;' 	);
			}
			
			//busco sponsor
			$tmpl->setVariable('display-sponsor'		, 'd-none');
			$querySponsor =  "SELECT R.EXPREG 
								FROM EXP_PER R
								LEFT OUTER JOIN EXP_MAEST S ON R.EXPREG=S.EXPREG
								WHERE R.PERCODIGO = $percodigo AND S.ESTCODIGO=1";
			$TablSponsor = sql_query($querySponsor,$conn);

			

			if ($TablSponsor->Rows_Count != -1) {
				
				$rowsponsor= $TablSponsor->Rows[0];
				$expreg 	= trim($rowsponsor['EXPREG']);
				$tmpl->setVariable('display-sponsor'		, '');
				$tmpl->setVariable('sponsorcod'		,$expreg );

			}

			//busco sponsor

			$querySponsorcod =  "SELECT EXPREG FROM EXP_MAEST WHERE PERCODIGO = $percodigo AND ESTCODIGO=1";
			$TablSponsorcod = sql_query($querySponsorcod,$conn);

			

			if ($TablSponsorcod->Rows_Count != -1) {
				
				$rowsponsorcod= $TablSponsorcod->Rows[0];
				$expreg 	= trim($rowsponsorcod['EXPREG']);
				$tmpl->setVariable('display-sponsor'		, '');
				$tmpl->setVariable('sponsorcod'		,$expreg );

			}
			
			//Busco la disponibilidad
			$queryDisp="SELECT PERDISFCH,PERDISHOR,DISP_BOOL 
						FROM PER_DISP
						WHERE PERCODIGO=$percodigo
						ORDER BY PERDISFCH,PERDISHOR ";
			$TableDet = sql_query($queryDisp,$conn);
			$dataDisp = '';
			for($j=0; $j<$TableDet->Rows_Count; $j++){
				$rowDet= $TableDet->Rows[$j];
				$perdisfch 	= BDConvFch($rowDet['PERDISFCH']);
				$dispbool 	= trim($rowDet['DISP_BOOL']);
				$perdishor 	= substr(trim($rowDet['PERDISHOR']),0,5);
				$dataDisp .= '{"fecha":"'.$perdisfch.'","hora":"'.$perdishor.'","dispbool":'.$dispbool.'},';
								
			}
			$dataDisp = substr($dataDisp,0,strlen($dataDisp)-1);
			$tmpl->setVariable('dataDisp'	, $dataDisp	);
			
			//Cargo los Sectores - Ventas
			$queryClas = "	SELECT S.SECCODIGO
							FROM PER_SECT C
							LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=C.SECCODIGO
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";
			
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$seccodigo 	= trim($rowClas['SECCODIGO']);
				$dataSectoresVen .= '{"seccodigo":"'.$seccodigo.'"},';
			}
			$dataSectoresVen = substr($dataSectoresVen,0,strlen($dataSectoresVen)-1);
			
			//Cargo los SubSectores - Ventas
			$queryClas = "	SELECT S.SECSUBCOD
							FROM PER_SSEC C
							LEFT OUTER JOIN SEC_SUB S ON S.SECSUBCOD=C.SECSUBCOD
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$secsubcod 	= trim($rowClas['SECSUBCOD']);				
				$dataSubsectoresVen .= '{"secsubcod":"'.$secsubcod.'"},';
			}
			$dataSubsectoresVen = substr($dataSubsectoresVen,0,strlen($dataSubsectoresVen)-1);
			
			//Cargo las Categorias - Ventas
			$queryClas = "	SELECT S.CATCODIGO
							FROM PER_CATE C
							LEFT OUTER JOIN CAT_MAEST S ON S.CATCODIGO=C.CATCODIGO
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";
			
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$catcodigo 	= trim($rowClas['CATCODIGO']);
				$dataCategoriasVen .= '{"catcodigo":"'.$catcodigo.'"},';
			}
			$dataCategoriasVen = substr($dataCategoriasVen,0,strlen($dataCategoriasVen)-1);
			
			//Cargo las SubCategorias - Ventas
			$queryClas = "	SELECT S.CATSUBCOD
							FROM PER_SCAT C
							LEFT OUTER JOIN CAT_SUB S ON S.CATSUBCOD=C.CATSUBCOD
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";
			
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$catsubcod 	= trim($rowClas['CATSUBCOD']);
				$dataSubcategoriasVen .= '{"catsubcod":"'.$catsubcod.'"},';
			}
			$dataSubcategoriasVen = substr($dataSubcategoriasVen,0,strlen($dataSubcategoriasVen)-1);
			//- - - - - - - - - - - - - - - - - - - - - - - - - - - - 
			
			//- - - - - - - - - - - - - - - - - - - - - - - - - - - - 
			//Cargo los Sectores - Compras
			$queryClas = "	SELECT S.SECCODIGO
							FROM PER_SECT C
							LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=C.SECCODIGO
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";
			
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$seccodigo 	= trim($rowClas['SECCODIGO']);
				$dataSectoresCom .= '{"seccodigo":"'.$seccodigo.'"},';
			}
			$dataSectoresCom = substr($dataSectoresCom,0,strlen($dataSectoresCom)-1);
			
			//Cargo los SubSectores - Compras
			$queryClas = "	SELECT S.SECSUBCOD
							FROM PER_SSEC C
							LEFT OUTER JOIN SEC_SUB S ON S.SECSUBCOD=C.SECSUBCOD
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$secsubcod 	= trim($rowClas['SECSUBCOD']);				
				$dataSubsectoresCom .= '{"secsubcod":"'.$secsubcod.'"},';
			}
			$dataSubsectoresCom = substr($dataSubsectoresCom,0,strlen($dataSubsectoresCom)-1);
			
			//Cargo las Categorias - Compras
			$queryClas = "	SELECT S.CATCODIGO
							FROM PER_CATE C
							LEFT OUTER JOIN CAT_MAEST S ON S.CATCODIGO=C.CATCODIGO
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";
			
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$catcodigo 	= trim($rowClas['CATCODIGO']);
				$dataCategoriasCom .= '{"catcodigo":"'.$catcodigo.'"},';
			}
			$dataCategoriasCom = substr($dataCategoriasCom,0,strlen($dataCategoriasCom)-1);
			
			//Cargo las SubCategorias - Compras
			$queryClas = "	SELECT S.CATSUBCOD
							FROM PER_SCAT C
							LEFT OUTER JOIN CAT_SUB S ON S.CATSUBCOD=C.CATSUBCOD
							WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";
			
			$TableClas = sql_query($queryClas,$conn);
			for($j=0; $j<$TableClas->Rows_Count; $j++){
				$rowClas= $TableClas->Rows[$j];
				$catsubcod 	= trim($rowClas['CATSUBCOD']);
				$dataSubcategoriasCom .= '{"catsubcod":"'.$catsubcod.'"},';
			}
			$dataSubcategoriasCom = substr($dataSubcategoriasCom,0,strlen($dataSubcategoriasCom)-1);
		}
		
		//Solo los Expositores pueden subir productos
		//if($pertipo!=1){
			$tmpl->setVariable('viewproddestacados'		, 'display:true;' 		);
		//}
	}
	$tmpl->setVariable('percodigo'	, $percodigo	);
	//Clasificacion de Ventas
	$tmpl->setVariable('dataSectoresVen'		, $dataSectoresVen 			);
	$tmpl->setVariable('dataSubsectoresVen'		, $dataSubsectoresVen 		);
	$tmpl->setVariable('dataCategoriasVen'		, $dataCategoriasVen 		);
	$tmpl->setVariable('dataSubcategoriasVen'	, $dataSubcategoriasVen 	);
	
	//Clasificacion de Compras
	$tmpl->setVariable('dataSectoresCom'		, $dataSectoresCom 			);
	$tmpl->setVariable('dataSubsectoresCom'		, $dataSubsectoresCom 		);
	$tmpl->setVariable('dataCategoriasCom'		, $dataCategoriasCom 		);
	$tmpl->setVariable('dataSubcategoriasCom'	, $dataSubcategoriasCom 	);
	
	//--------------------------------------------------------------------------------------------------------------
	$query = "	SELECT PERPROITM,PERPRONOM,PERPROFIL
				FROM PER_PROD
				WHERE PERCODIGO=$percodigo
				ORDER BY PERPROITM ";
	$productos = array();
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count;$i++){
		$row= $Table->Rows[$i];
		$perproitm = trim($row['PERPROITM']);
		$perpronom = trim($row['PERPRONOM']);
		$perprofil = trim($row['PERPROFIL']);
		
		$productos[$perproitm]['perpronom'] = $perpronom;
		$productos[$perproitm]['perprofil'] = $pathimagenes.$perprofil;
	}
	
	//Productos
	for($i=1; $i<=5; $i++){
		$perpronom 	= (isset($productos[$i]))? $productos[$i]['perpronom'] : '';
		$perprofil 	= (isset($productos[$i]))? $productos[$i]['perprofil'] : $imgProductoNull;
		$readonly 	= (isset($productos[$i]))? '' : 'readonly';
		
		
		$tmpl->setCurrentBlock('productos');
		if ($IdiomView=='ESP') {
			$tmpl->setVariable('proddestacados','Producto o servicio');
			$tmpl->setVariable('placeholderproductos','Ingrese Link o URL (https://...)');
		}else if ($IdiomView=='ING'){
			$tmpl->setVariable('proddestacados','Products or services');
			$tmpl->setVariable('placeholderproductos','Enter Link or URL (https://...)');
		}else{

			$tmpl->setVariable('proddestacados','Produtos ou serviços');
			$tmpl->setVariable('placeholderproductos','Ingrese Link o URL (https://...)');
		}
		$tmpl->setVariable('perproitm'	, $i	);
		$tmpl->setVariable('perpronom'	, $perpronom 	);
		$tmpl->setVariable('perprofil'	, $perprofil 	);
		$tmpl->setVariable('perpronomreadonly'	, $readonly	);
		$tmpl->parseCurrentBlock();
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	//Tipo de Perfiles
	$query = "SELECT PERTIPO,PERTIPDES$IdiomView AS PERTIPDES
				FROM PER_TIPO
				WHERE ESTCODIGO=1			
				ORDER BY PERTIPO";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$pertipcod 	= trim($row['PERTIPO']);
		$pertipdes	= trim($row['PERTIPDES']);
		
		//SI el usuario no es admin, cargo solo los registros asignados
		if($peradminlog!=1){ 
			if($pertipo==$pertipcod){
				$tmpl->setCurrentBlock('pertipos');
				$tmpl->setVariable('pertipcod'	, $pertipcod 		);
				$tmpl->setVariable('pertipdes'	, $pertipdes	);
				$tmpl->setVariable('pertipsel', 'selected' );
				$tmpl->parse('pertipos');
			}
		}else{
			$tmpl->setCurrentBlock('pertipos');
			$tmpl->setVariable('pertipcod'	, $pertipcod 		);
			$tmpl->setVariable('pertipdes'	, $pertipdes	);
			
			if($pertipo==$pertipcod){
				$tmpl->setVariable('pertipsel', 'selected' );
			}
			
			$tmpl->parse('pertipos');
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	//Clase de Perfiles
	if($pertipo!=''){
		$query = "	SELECT PERCLASE,PERCLADES
					FROM PER_CLASE	
					WHERE PERTIPO=$pertipo
					ORDER BY PERCLASE ";
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$perclacod 	= trim($row['PERCLASE']);
			$perclades	= trim($row['PERCLADES']);
			
			//SI el usuario no es admin, cargo solo los registros asignados
			if($peradminlog!=1){ 
				if($perclase==$perclacod){
					$tmpl->setCurrentBlock('perclases');
					$tmpl->setVariable('perclacod'	, $perclacod 	);
					$tmpl->setVariable('perclades'	, $perclades	);	
					$tmpl->setVariable('perclasel', 'selected' );
					$tmpl->parse('perclases');
				}
			}else{
				$tmpl->setCurrentBlock('perclases');
				$tmpl->setVariable('perclacod'	, $perclacod 	);
				$tmpl->setVariable('perclades'	, $perclades	);	

				if($perclase==$perclacod){
					$tmpl->setVariable('perclasel', 'selected' );
				}
				
				$tmpl->parse('perclases');
			}
		}
	}



	//--------------------------------------------------------------------------------------------------------------
	//Seleccionamos las mesas
	$query = "	SELECT MESCODIGO,MESNUMERO,ESTCODIGO,PERCODIGO
				FROM MES_MAEST
				WHERE ESTCODIGO=1	
				ORDER BY MESCODIGO";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$mesclacod 	= trim($row['MESCODIGO']);
		$mesnumero	= trim($row['MESNUMERO']);
		$usuario	= trim($row['PERCODIGO']);
		//$estcodigo = trim($row['ESTCODIGO']);
		
			$tmpl->setCurrentBlock('mesclases');
			$tmpl->setVariable('mesclacod'	, $mesclacod 	);
			$tmpl->setVariable('mesnumero'	, $mesnumero	);	

			if($percodigo == $usuario){
				$tmpl->setVariable('mesclasel', 'selected' );
			}
			
			$tmpl->parse('mesclases');
		
	}
	//--------------------------------------------------------------------------------------------------------------

//variables del formulario
$query = "SELECT VARTITULO,VARDESCRI,VARDESCRIING,VARDESCRIPOR FROM VAR_MAEST";

$Table = sql_query($query,$conn);
for($i=0; $i<$Table->Rows_Count; $i++){
$row = $Table->Rows[$i];
$nombre	= trim($row['VARTITULO']);
$textoesp	= trim($row['VARDESCRI']);
$textoing	= trim($row['VARDESCRIING']);
$textopor	= trim($row['VARDESCRIPOR']);

if ($IdiomView=='ESP')
		{
			$tmpl->setVariable('texto'.$nombre, $textoesp);
		}else if ($IdiomView=='POR'){
			$tmpl->setVariable('texto'.$nombre, $textopor);
		}else{
			$tmpl->setVariable('texto'.$nombre, $textoing);
		}

}



	//Seleccionamos los rubros
	 $query = "	SELECT PERRUBCOD,PERRUBDES$IdiomView AS PERRUBDES
	 			FROM PER_RUBR 
	 			ORDER BY PERRUBDES$IdiomView ";			
	 $Table = sql_query($query,$conn);
	 for($i=0; $i<$Table->Rows_Count; $i++){
	 	$row = $Table->Rows[$i];
	 	$rubcod 	= trim($row['PERRUBCOD']);
	 	$perrubdes	= trim($row['PERRUBDES']);
		
	 	if($peradminlog!=1){ 
	 		if($perrubcod==$rubcod){
	 			$tmpl->setCurrentBlock('rubros');
	 			$tmpl->setVariable('rubcod'		, $rubcod 		);
	 			$tmpl->setVariable('perrubdes'	, $perrubdes	);
	 			$tmpl->setVariable('rubsel'		, 'selected' 	);
	 			$tmpl->parse('rubros');
	 		}
	 	}else{
	 		$tmpl->setCurrentBlock('rubros');
	 		$tmpl->setVariable('rubcod'	, $rubcod 		);
	 			$tmpl->setVariable('perrubdes'	, $perrubdes	);
			
	 		if($perrubcod==$rubcod){
	 			$tmpl->setVariable('rubsel', 'selected' );
	 		}
			
	 		$tmpl->parse('rubros');
	 	}
		
	 }

	//--------------------------------------------------------------------------------------
	//Seleccionamos los rubros

	$queryA="SELECT SECCODIGO,SECDESCRI,SECDESING FROM SEC_MAEST WHERE ESTCODIGO<>3";
	$TableA=sql_query($queryA,$conn);
	for($i=0; $i<$TableA->Rows_Count; $i++){
		$row = $TableA->Rows[$i];
		$perareacod 	= trim($row['SECCODIGO']);
		$peraredes		= trim($row['SECDESCRI']);
		$peraredesing		= trim($row['SECDESING']);
		

		$tmpl->setCurrentBlock('area');
			$tmpl->setVariable('perareacod'	, $perareacod );
			if($IdiomView=='ING'){
				$tmpl->setVariable('peraredes'	, $peraredesing );
			}else{
				$tmpl->setVariable('peraredes'	, $peraredes );
			}
			 if ($perarecod==$perareacod) {
				$tmpl->setVariable('areasel', 'selected' );
			 }
		$tmpl->parse('area');
	}


	//--------------------------------------------------------------------------------------
	//Seleccion de Industrias
	$query1="SELECT PERINDCOD, PERINDDESESP FROM PER_IND ";

	$Table1=sql_query($query1,$conn);
	for($i=0; $i<$Table1->Rows_Count; $i++){
		$row = $Table1->Rows[$i];
		$perinducod 	= trim($row['PERINDCOD']);
	
		$perinddes	= trim($row['PERINDDESESP']);
		
		$tmpl->setCurrentBlock('industria');
			$tmpl->setVariable('perinducod'	, $perinducod 		);
			$tmpl->setVariable('perinddes'	, $perinddes 		);
			if ($perindcod==$perinducod) {
				$tmpl->setVariable('indusel', 'selected' );
			 }
		$tmpl->parse('industria');

	}

	//Listado de Paises
	$query = "	SELECT PAICODIGO,PAIDESCRI,PAIDESCRIING
				FROM TBL_PAIS
				ORDER BY PAIDESCRI ";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count;$i++){
		$row= $Table->Rows[$i];
		$paicod = trim($row['PAICODIGO']);

		if ($IdiomView == 'ESP'){
			$paides = trim($row['PAIDESCRI']);
		}else{
			$paides = trim($row['PAIDESCRIING']);	
		}

		
		
		$tmpl->setCurrentBlock('paises');
		$tmpl->setVariable('paicodigo'	, $paicod 	);
		$tmpl->setVariable('paidescri'	, $paides 	);
		
		if($paicodigo==$paicod){
			$tmpl->setVariable('paiselected', 'selected' );
		}
		
		$tmpl->parseCurrentBlock();
	}

	//--------------------------------------------------------------------------------------------------------------
	
		// Read the JSON file 
		$json = file_get_contents('../api/timezone.json');
		// Decode the JSON file
		$json_data = json_decode($json,true);
		foreach ($json_data as &$value) {
			$tmpl->setCurrentBlock('zonahoraria');
			$tmpl->setVariable('timregcod'	, $value 		);
			$tmpl->setVariable('timdescri'	, $value	);
			if($value==$timreg){
				$tmpl->setVariable('timsel', 'selected' );
			}
			$tmpl->parse('zonahoraria');
		}
	
	
	//--------------------------------------------------------------------------------------------------------------

	$query1="SELECT IDICODIGO, IDIDESCRI FROM IDI_MAEST ";

	$Table1=sql_query($query1,$conn);
	for($i=0; $i<$Table1->Rows_Count; $i++){
		$row = $Table1->Rows[$i];
		$idicodigo 	= trim($row['IDICODIGO']);
		$ididescri 	= trim($row['IDIDESCRI']);
	
		$tmpl->setCurrentBlock('idiomas');
		$tmpl->setVariable('idicodigo'	, $idicodigo	);
		$tmpl->setVariable('ididescri'	, $ididescri	);
		if ($idicodigo==$peridioma) {
			$tmpl->setVariable('idiselected', 'selected' );
		 }
		$tmpl->parse('idiomas');

	}


	




	

	sql_close($conn);
	
	$tmpl->show();
	
?>	
