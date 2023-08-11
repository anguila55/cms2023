<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('vsl.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';

	
	$percodigo = (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$perestcod = 1; //Activo por defecto
	$pernombre = '';
	$perapelli = '';
	$percorreo = '';
	$pertelefo = '';
	$perdirecc = '';
	$perciudad = '';
	$perestado = '';
	$paicodigo = '';
	$pathimagenes = '../perimg/'.$percodigo.'/';
	$imgAvatarNull = '../app-assets/img/avatar.png';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$whereVen = "PERVENCOM IN ('V','A') ";
	$whereCom= "PERVENCOM IN ('C','A') ";
	if($percodigo!=0){
		
		//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
		$perfilLog = array();
		$perfilLog = array();
		//Cargo la Clasificacion de productos del Perfil logueado
		$query = "	SELECT S.SECCODIGO, S.SECDESCRI
					FROM PER_SECT PS
					LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
					WHERE PS.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 AND PS.$whereVen";
		$TableSect = sql_query($query,$conn);
		for($i=0; $i<$TableSect->Rows_Count; $i++){
			$rowSect= $TableSect->Rows[$i];
			$seccodigo = trim($rowSect['SECCODIGO']);
			$secdescri = trim($rowSect['SECDESCRI']);
		

				$perfilLog[$seccodigo]['EXISTS'] = 1;

			
			//Busco si tiene un siguiente nivel / SubSector
			$querySectSub = "	SELECT SB.SECSUBCOD, SB.SECSUBDES
								FROM PER_SSEC PSB
								LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
								WHERE PSB.PERCODIGO=$percodlog AND SB.SECCODIGO=$seccodigo AND sb.ESTCODIGO<>3 AND PSB.$whereVen";
			$TableSSect = sql_query($querySectSub,$conn);
			for($j=0; $j<$TableSSect->Rows_Count; $j++){
				$rowSSect= $TableSSect->Rows[$j];
				$secsubcod = trim($rowSSect['SECSUBCOD']);
				$secsubdes = trim($rowSSect['SECSUBDES']);
				
					$perfilLog[$seccodigo][$secsubcod]['EXISTS'] = 1;
	
				
				
				//Busco si tiene un siguiente nivel / Categorias
				$queryCat = "	SELECT C.CATCODIGO, C.CATDESCRI
								FROM PER_CATE PC
								LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
								WHERE PC.PERCODIGO=$percodlog AND C.SECSUBCOD=$secsubcod AND C.ESTCODIGO<>3 AND PC.$whereVen ";

				$TableCat = sql_query($queryCat,$conn);
				for($k=0; $k<$TableCat->Rows_Count; $k++){
					$rowCat= $TableCat->Rows[$k];
					$catcodigo = trim($rowCat['CATCODIGO']);
					$catdescri = trim($rowCat['CATDESCRI']);
					
						$perfilLog[$seccodigo][$secsubcod][$catcodigo]['EXISTS'] = 1;
		
					
					//Busco si tiene un siguiente nivel / SubCategorias
					$queryCatSub = "	SELECT CS.CATSUBCOD,CS.CATSUBDES
										FROM PER_SCAT PSC
										LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
										WHERE PSC.PERCODIGO=$percodlog AND CS.CATCODIGO=$catcodigo AND CS.ESTCODIGO<>3 AND PSC.$whereVen";

					$TableCatSub = sql_query($queryCatSub,$conn);
					for($m=0; $m<$TableCatSub->Rows_Count; $m++){
						$rowCatSub= $TableCatSub->Rows[$m];
						$catsubcod = trim($rowCatSub['CATSUBCOD']);
						$catsubdes = trim($rowCatSub['CATSUBDES']);
						

							$perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod]['EXISTS'] = 1;
			
					}
				}
			}
		}
		$query = "	SELECT S.SECCODIGO, S.SECDESCRI
					FROM PER_SECT PS
					LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
					WHERE PS.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 AND PS.$whereCom";
		$TableSect = sql_query($query,$conn);
		for($i=0; $i<$TableSect->Rows_Count; $i++){
			$rowSect= $TableSect->Rows[$i];
			$seccodigo1 = trim($rowSect['SECCODIGO']);
			$secdescri1 = trim($rowSect['SECDESCRI']);
		

				$perfilLog1[$seccodigo1]['EXISTS'] = 1;

			
			//Busco si tiene un siguiente nivel / SubSector
			$querySectSub = "	SELECT SB.SECSUBCOD, SB.SECSUBDES
								FROM PER_SSEC PSB
								LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
								WHERE PSB.PERCODIGO=$percodlog AND SB.SECCODIGO=$seccodigo1 AND sb.ESTCODIGO<>3 AND PSB.$whereCom";
			$TableSSect = sql_query($querySectSub,$conn);
			for($j=0; $j<$TableSSect->Rows_Count; $j++){
				$rowSSect= $TableSSect->Rows[$j];
				$secsubcod1 = trim($rowSSect['SECSUBCOD']);
				$secsubdes1 = trim($rowSSect['SECSUBDES']);
				
					$perfilLog1[$seccodigo1][$secsubcod1]['EXISTS'] = 1;
	
				
				
				//Busco si tiene un siguiente nivel / Categorias
				$queryCat = "	SELECT C.CATCODIGO, C.CATDESCRI
								FROM PER_CATE PC
								LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
								WHERE PC.PERCODIGO=$percodlog AND C.SECSUBCOD=$secsubcod1 AND C.ESTCODIGO<>3 AND PC.$whereCom ";

				$TableCat = sql_query($queryCat,$conn);
				for($k=0; $k<$TableCat->Rows_Count; $k++){
					$rowCat= $TableCat->Rows[$k];
					$catcodigo1 = trim($rowCat['CATCODIGO']);
					$catdescri1 = trim($rowCat['CATDESCRI']);
					
						$perfilLog1[$seccodigo1][$secsubcod1][$catcodigo1]['EXISTS'] = 1;
		
					
					//Busco si tiene un siguiente nivel / SubCategorias
					$queryCatSub = "	SELECT CS.CATSUBCOD,CS.CATSUBDES
										FROM PER_SCAT PSC
										LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
										WHERE PSC.PERCODIGO=$percodlog AND CS.CATCODIGO=$catcodigo1 AND CS.ESTCODIGO<>3 AND PSC.$whereCom";

					$TableCatSub = sql_query($queryCatSub,$conn);
					for($m=0; $m<$TableCatSub->Rows_Count; $m++){
						$rowCatSub= $TableCatSub->Rows[$m];
						$catsubcod1 = trim($rowCatSub['CATSUBCOD']);
						$catsubdes1 = trim($rowCatSub['CATSUBDES']);
						

							$perfilLog1[$seccodigo1][$secsubcod1][$catcodigo1][$catsubcod1]['EXISTS'] = 1;
			
					}
				}
			}
		}
		//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
		//// ME FIJO QUE TIPO DE REUNION ES
		$queryreunion = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoReunion'";
		$Tablereunion = sql_query($queryreunion, $conn);
			$rowreunion = $Tablereunion->Rows[0];
			$tiporeunion = trim($rowreunion['ZVALUE']);

			
		
		$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.ESTCODIGO,P.PERCOMPAN,P.PERCORREO,P.PERCIUDAD,P.PERESTADO,
							P.PERCODPOS,P.PERTELEFO,P.PERURLWEB,P.PERUSUACC,P.PERPASACC,P.PERDIRECC,P.PERCARGO,
							P.PAICODIGO,I.PAIDESCRI,P.PEREMPDES,P.PERAVATAR
					FROM PER_MAEST P
					LEFT OUTER JOIN TBL_PAIS I ON I.PAICODIGO=P.PAICODIGO
					WHERE P.PERCODIGO=$percodigo ";
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo 	= trim($row['PERCODIGO']);
			$pernombre 	= trim($row['PERNOMBRE']);
			$perapelli 	= trim($row['PERAPELLI']);
			$estcodigo 	= trim($row['ESTCODIGO']);
			$percompan 	= trim($row['PERCOMPAN']);
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
			$paicodigo 	= trim($row['PAICODIGO']);
			$paidescri 	= trim($row['PAIDESCRI']);
			$perempdes 	= trim($row['PEREMPDES']);
			$peravatar 	= trim($row['PERAVATAR']);
			
			$tmpl->setVariable('percodigo'	, $percodigo	);
			$tmpl->setVariable('pernombre'	, $pernombre	);
			$tmpl->setVariable('perapelli'	, $perapelli	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			$tmpl->setVariable('percompan'	, $percompan	);
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
			$tmpl->setVariable('paicodigo'	, $paicodigo	);
			$tmpl->setVariable('paidescri'	, $paidescri	);
			$tmpl->setVariable('perempdes'	, $perempdes	);
			
			if($peravatar!=''){
				if(strpos($peravatar, "https://") !== false){
	
					$tmpl->setVariable('peravatar'	, $peravatar);
				
				}else{
					$tmpl->setVariable('peravatar'	, $pathimagenes.$peravatar);
				}
				
			}else{
				$tmpl->setVariable('peravatar'	, $imgAvatarNull);
			}

			
			
			//Busco todos los sectores que tiene el perfil
			$campo = ($IdiomView=='ING')? 'S.SECDESING' : 'S.SECDESCRI';
			$query = "	SELECT S.SECCODIGO, $campo AS SECDESCRI
						FROM PER_SECT PS
						LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
						WHERE PS.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND PS.$whereVen";
			$TableSect = sql_query($query,$conn);
			for($i=0; $i<$TableSect->Rows_Count; $i++){
				$rowSect= $TableSect->Rows[$i];
				$seccodigo = trim($rowSect['SECCODIGO']);
				$secdescri = trim($rowSect['SECDESCRI']);
				
				$tmpl->setCurrentBlock('sectores');
				$tmpl->setVariable('seccodigo'	, $seccodigo	);
				$tmpl->setVariable('secdescri'	, $secdescri	);
				
				//Busco si tiene un siguiente nivel / SubSector
				$campo = ($IdiomView=='ING')? 'SB.SECSUBDESING' : 'SB.SECSUBDES';
				$querySectSub = "	SELECT SB.SECSUBCOD, $campo AS SECSUBDES
									FROM PER_SSEC PSB
									LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
									WHERE PSB.PERCODIGO=$percodigo AND SB.SECCODIGO=$seccodigo AND sb.ESTCODIGO<>3 AND PSB.$whereVen ";
				$TableSSect = sql_query($querySectSub,$conn);
				for($j=0; $j<$TableSSect->Rows_Count; $j++){
					$rowSSect= $TableSSect->Rows[$j];
					$secsubcod = trim($rowSSect['SECSUBCOD']);
					$secsubdes = trim($rowSSect['SECSUBDES']);
					
					$tmpl->setCurrentBlock('subsectores');
					$tmpl->setVariable('secsubcod'	, $secsubcod	);
					$tmpl->setVariable('secsubdes'	, $secsubdes	);
					
					//Busco si tiene un siguiente nivel / Categorias
					$campo = ($IdiomView=='ING')? 'C.CATDESING' : 'C.CATDESCRI';
					$queryCat = "	SELECT C.CATCODIGO, $campo AS CATDESCRI
									FROM PER_CATE PC
									LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
									WHERE PC.PERCODIGO=$percodigo AND C.SECSUBCOD=$secsubcod AND C.ESTCODIGO<>3 AND PC.$whereVen";

					$TableCat = sql_query($queryCat,$conn);
					for($k=0; $k<$TableCat->Rows_Count; $k++){
						$rowCat= $TableCat->Rows[$k];
						$catcodigo = trim($rowCat['CATCODIGO']);
						$catdescri = trim($rowCat['CATDESCRI']);
						
						$tmpl->setCurrentBlock('categorias');
						$tmpl->setVariable('catcodigo'	, $catcodigo	);
						$tmpl->setVariable('catdescri'	, $catdescri	);
						
						//Busco si tiene un siguiente nivel / SubCategorias
						$campo = ($IdiomView=='ING')? 'CS.CATSUBDESING' : 'CS.CATSUBDES';
						$queryCatSub = "	SELECT CS.CATSUBCOD, $campo AS CATSUBDES
											FROM PER_SCAT PSC
											LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
											WHERE PSC.PERCODIGO=$percodigo AND CS.CATCODIGO=$catcodigo AND CS.ESTCODIGO<>3 AND PSC.$whereVen ";

						$TableCatSub = sql_query($queryCatSub,$conn);
						for($m=0; $m<$TableCatSub->Rows_Count; $m++){
							$rowCatSub= $TableCatSub->Rows[$m];
							$catsubcod = trim($rowCatSub['CATSUBCOD']);
							$catsubdes = trim($rowCatSub['CATSUBDES']);
							
							$tmpl->setCurrentBlock('subcategorias');
							$tmpl->setVariable('catsubcod'	, $catsubcod	);
							$tmpl->setVariable('catsubdes'	, $catsubdes	);
							
							if(isset($perfilLog1[$seccodigo][$secsubcod][$catcodigo][$catsubcod])){
								$tmpl->setVariable('colorsubcategoria'	, 'bg-main-event');
							}else{
								$tmpl->setVariable('colorsubcategoria'	, 'bg-color-gris');
								
							}
							
							$tmpl->parse('subcategorias');
						}
						if($TableCatSub->Rows_Count==-1){
							if(isset($perfilLog1[$seccodigo][$secsubcod][$catcodigo])){
								$tmpl->setVariable('colorcategoria'	, 'bg-main-event');
							}else{

								$tmpl->setVariable('colorcategoria'	, 'bg-color-gris');
							}
						}
						
						$tmpl->parse('categorias');
					}
					//logerror($queryCat);
					//logerror($seccodigo.'-'.$secsubcod.'-'.$TableCat->Rows_Count);
					if($TableCat->Rows_Count==-1){
						if(isset($perfilLog1[$seccodigo][$secsubcod])){
							$tmpl->setVariable('colorsubsector'	, 'bg-main-event');
						}else{
							$tmpl->setVariable('colorsubsector'	, 'bg-color-gris');

						}
					
					}
					
					$tmpl->parse('subsectores');
				}
				$tmpl->setVariable('colorsector'	, 'bg-color-gris');
				if($TableSSect->Rows_Count==-1){
					if(isset($perfilLog1[$seccodigo])){
						$tmpl->setVariable('colorsector'	, 'bg-main-event');
					}else{
						$tmpl->setVariable('colorsector'	, 'bg-color-gris');
					}
				}
				////Verificio si es tipo Oferta/Demanda o es Networking
				if ($tiporeunion == 'true') {
					$tmpl->setVariable('visiblecompraventa'	, ''	);
					$tmpl->setVariable('visibleintereses'	, 'd-none'	);
				}else{
					$tmpl->setVariable('visiblecompraventa'	, 'd-none'	);
					$tmpl->setVariable('visiblecompraventa1'	, 'd-none'	);
					$tmpl->setVariable('visiblecompraventa2'	, 'd-none'	);
					$tmpl->setVariable('visibleintereses'	, ''	);
				}	
				$tmpl->parse('sectores');
				
			}
			$campo = ($IdiomView=='ING')? 'S.SECDESING' : 'S.SECDESCRI';
			$queryCom = "	SELECT S.SECCODIGO, $campo AS SECDESCRI
						FROM PER_SECT PS
						LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
						WHERE PS.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND PS.$whereCom";
			$TableSect = sql_query($queryCom,$conn);
		
			for($i=0; $i<$TableSect->Rows_Count; $i++){
				$rowSect= $TableSect->Rows[$i];
				$seccodigo1 = trim($rowSect['SECCODIGO']);
				$secdescri1 = trim($rowSect['SECDESCRI']);
				
				$tmpl->setCurrentBlock('sectores1');
				$tmpl->setVariable('seccodigo1'	, $seccodigo1	);
				$tmpl->setVariable('secdescri1'	, $secdescri1	);
				
				//Busco si tiene un siguiente nivel / SubSector
				$campo = ($IdiomView=='ING')? 'SB.SECSUBDESING' : 'SB.SECSUBDES';
				$querySectSub = "	SELECT SB.SECSUBCOD, $campo AS SECSUBDES
									FROM PER_SSEC PSB
									LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
									WHERE PSB.PERCODIGO=$percodigo AND SB.SECCODIGO=$seccodigo1 AND sb.ESTCODIGO<>3 AND PSB.$whereCom ";
				$TableSSect = sql_query($querySectSub,$conn);
				for($j=0; $j<$TableSSect->Rows_Count; $j++){
					$rowSSect= $TableSSect->Rows[$j];
					$secsubcod1 = trim($rowSSect['SECSUBCOD']);
					$secsubdes1= trim($rowSSect['SECSUBDES']);
					
					$tmpl->setCurrentBlock('subsectores1');
					$tmpl->setVariable('secsubcod1'	, $secsubcod1	);
					$tmpl->setVariable('secsubdes1'	, $secsubdes1	);
					
					//Busco si tiene un siguiente nivel / Categorias
					$campo = ($IdiomView=='ING')? 'C.CATDESING' : 'C.CATDESCRI';
					$queryCat = "	SELECT C.CATCODIGO, $campo AS CATDESCRI
									FROM PER_CATE PC
									LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
									WHERE PC.PERCODIGO=$percodigo AND C.SECSUBCOD=$secsubcod1 AND C.ESTCODIGO<>3 AND PC.$whereCom";

					$TableCat = sql_query($queryCat,$conn);
					for($k=0; $k<$TableCat->Rows_Count; $k++){
						$rowCat= $TableCat->Rows[$k];
						$catcodigo1 = trim($rowCat['CATCODIGO']);
						$catdescri1 = trim($rowCat['CATDESCRI']);
						
						$tmpl->setCurrentBlock('categorias1');
						$tmpl->setVariable('catcodigo1'	, $catcodigo1	);
						$tmpl->setVariable('catdescri1'	, $catdescri1	);
						
						//Busco si tiene un siguiente nivel / SubCategorias
						$campo = ($IdiomView=='ING')? 'CS.CATSUBDESING' : 'CS.CATSUBDES';
						$queryCatSub = "	SELECT CS.CATSUBCOD, $campo AS CATSUBDES
											FROM PER_SCAT PSC
											LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
											WHERE PSC.PERCODIGO=$percodigo AND CS.CATCODIGO=$catcodigo1 AND CS.ESTCODIGO<>3 AND PSC.$whereCom ";

						$TableCatSub = sql_query($queryCatSub,$conn);
						for($m=0; $m<$TableCatSub->Rows_Count; $m++){
							$rowCatSub= $TableCatSub->Rows[$m];
							$catsubcod1 = trim($rowCatSub['CATSUBCOD']);
							$catsubdes1 = trim($rowCatSub['CATSUBDES']);
							
							$tmpl->setCurrentBlock('subcategorias1');
							$tmpl->setVariable('catsubcod1'	, $catsubcod1	);
							$tmpl->setVariable('catsubdes1'	, $catsubdes1	);
							
							if(isset($perfilLog[$seccodigo1][$secsubcod1][$catcodigo1][$catsubcod1])){
								$tmpl->setVariable('colorsubcategoria1'	, 'bg-main-event');
							}else{
								$tmpl->setVariable('colorsubcategoria1'	, 'bg-color-gris');
								
							}
							
							$tmpl->parse('subcategorias1');
						}
						if($TableCatSub->Rows_Count==-1){
							if(isset($perfilLog[$seccodigo1][$secsubcod1][$catcodigo1])){
								$tmpl->setVariable('colorcategoria1'	, 'bg-main-event');
							}else{

								$tmpl->setVariable('colorcategoria1'	, 'bg-color-gris');
							}
						}
						
						$tmpl->parse('categorias1');
					}
					//logerror($queryCat);
					//logerror($seccodigo.'-'.$secsubcod.'-'.$TableCat->Rows_Count);
					if($TableCat->Rows_Count==-1){
						if(isset($perfilLog[$seccodigo1][$secsubcod1])){
							$tmpl->setVariable('colorsubsector1'	, 'bg-main-event');
						}else{
							$tmpl->setVariable('colorsubsector1'	, 'bg-color-gris');

						}
					
					}
					
					$tmpl->parse('subsectores1');
				}
				$tmpl->setVariable('colorsector1'	, 'bg-color-gris');
				if($TableSSect->Rows_Count==-1){
					if(isset($perfilLog[$seccodigo1])){
						$tmpl->setVariable('colorsector1'	, 'bg-main-event');
					}else{
						$tmpl->setVariable('colorsector1'	, 'bg-color-gris');
					}
				}
				////Verificio si es tipo Oferta/Demanda o es Networking
				if ($tiporeunion == 'true') {
					$tmpl->setVariable('visiblecompraventa'	, ''	);
					$tmpl->setVariable('visibleintereses'	, 'd-none'	);
				}else{
					$tmpl->setVariable('visiblecompraventa'	, 'd-none'	);
					$tmpl->setVariable('visiblecompraventa1'	, 'd-none'	);
					$tmpl->setVariable('visiblecompraventa2'	, 'd-none'	);
					$tmpl->setVariable('visibleintereses'	, ''	);
				}	
				$tmpl->parse('sectores1');
				
			}
			
			$query = "	SELECT PERPROITM,PERPRONOM,PERPROFIL
						FROM PER_PROD
						WHERE PERCODIGO=$percodigo
						ORDER BY PERPROITM ";
			$Table = sql_query($query,$conn);
			for($i=0; $i<$Table->Rows_Count;$i++){
				$row= $Table->Rows[$i];
				$perproitm = trim($row['PERPROITM']);
				$perpronom = trim($row['PERPRONOM']);
				$perprofil = trim($row['PERPROFIL']);
				
				$tmpl->setCurrentBlock('productos');
				$tmpl->setVariable('perpronom'	, $perpronom	);
				$tmpl->setVariable('perprofil'	, $pathimagenes.$perprofil	);
				
				$tmpl->parse('productos');
			}
			
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
