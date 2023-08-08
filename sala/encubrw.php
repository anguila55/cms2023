<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';		
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('encubrw.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar = (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	
	$tmpl->setVariable('pernombre'	, $pernombre	);
	$tmpl->setVariable('perapelli'	, $perapelli	);
	$tmpl->setVariable('perusuacc'	, $perusuacc	);
	$tmpl->setVariable('perpasacc'	, $perpasacc	);
	$tmpl->setVariable('peravatar'	, $peravatar	);
		
	//Nombre del Evento
	$tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);	
	
	if($peradmin!=1) $tmpl->setVariable('viewadmin'	, 'none'	);
	if($btnsectores!=1) $tmpl->setVariable('btnsectores'	, 'display:;'	);
	if($btnsubsectores!=1) $tmpl->setVariable('btnsubsectores'	, 'display:;'	);
	if($btncategorias!=1) $tmpl->setVariable('btncategorias'	, 'display:none;'	);
	if($btnsubcategorias!=1) $tmpl->setVariable('btnsubcategorias'	, 'display:none;'	);
	
	//Habilito las opciones del Menu
	if(json_decode($_SESSION['PARAMETROS']['MenuActividades']) == false){
		$tmpl->setVariable('ParamMenuActividades'	, 'display:;'	);
	}
	if(json_decode($_SESSION['PARAMETROS']['MenuAgenda']) == false){
		$tmpl->setVariable('ParamMenuAgenda'	, 'display:;'	);
	}
	if(json_decode($_SESSION['PARAMETROS']['MenuMensajes']) == false){
		$tmpl->setVariable('ParamMenuMensajes'	, 'display:;'	);
	}
	if(json_decode($_SESSION['PARAMETROS']['MenuNoticias']) == false){
		$tmpl->setVariable('ParamMenuNoticias'	, 'display:;'	);
	}
	if(json_decode($_SESSION['PARAMETROS']['MenuExportar']) == false){
		$tmpl->setVariable('ParamMenuExportar'	, 'display:;'	);
	}
	if(json_decode($_SESSION['PARAMETROS']['MenuEncuesta']) == false){
		$tmpl->setVariable('ParamMenuEncuesta'	, 'display:none;'	);
	}
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$agereg = (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	
	////--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($agereg!=0){
		$tmpl->setVariable('ageregnew'	, $agereg		);
			
		//Recorro las encuestas
		$query = "	SELECT EC.ENCREG,EC.ENCDESCRI
					FROM AGE_ENCU AE
					LEFT OUTER JOIN ENC_CABE EC ON EC.ENCREG=AE.ENCREG
					WHERE AE.AGEREG=$agereg AND EC.ESTCODIGO=1
					ORDER BY EC.ENCDESCRI ";
		logerror($query);
		$TableCabe = sql_query($query,$conn);
		for($j=0; $j<$TableCabe->Rows_Count; $j++){
			$rowCabe = $TableCabe->Rows[$j];		
			$encreg 	= trim($rowCabe['ENCREG']);
			$encdescri 	= trim($rowCabe['ENCDESCRI']);
			
			$tmpl->setCurrentBlock('encuestas');
			$tmpl->setVariable('encdescri'	, $encdescri);
			
			//Busco si el perfil ya completo la encuesta
			$queryResp = "SELECT ENCPREITM FROM ENC_RESP WHERE ENCREG=$encreg AND PERCODIGO=$percodigo AND AGEREG=$agereg ";
			$TableResp = sql_query($queryResp,$conn);
			if($TableResp->Rows_Count>0){
				//Ya fue contestada la encuesta
				
			}else{	
				$tmpl->setVariable('enccheck'		, 'display:none;'	);
				//Recorro las preguntas de la encuesta
				$query = "	SELECT EP.ENCREG, EP.ENCPREITM, EP.ENCPREGUN, EP.ENCPRETIP, EP.ENCPREVAL,PM.ENCRES
							FROM ENC_PREG EP
							LEFT OUTER JOIN PER_MAEST PM ON PM.PERCODIGO=$percodigo
							WHERE EP.ENCREG=$encreg AND EP.ESTCODIGO<>3 
							ORDER BY EP.ENCPREORD ";
				$Table = sql_query($query,$conn);
				for($i=0; $i<$Table->Rows_Count; $i++){
					$row = $Table->Rows[$i];
					$encpreitm 	= trim($row['ENCPREITM']);
					$encpretip 	= trim($row['ENCPRETIP']);			
					$encpregun 	= trim($row['ENCPREGUN']);
					$encpreval 	= trim($row['ENCPREVAL']);
					$encres 	= trim($row['ENCRES']);
										
					$tmpl->setCurrentBlock('preguntas');
					$tmpl->setVariable('encreg'		, $encreg	);

					if ($encpretip==1) { //LIBRE
						$tmpl->setVariable('pregtip1'	, 'display:visible');
						$tmpl->setVariable('pregtip2'	, 'display:none');
						$tmpl->setVariable('pregtip3'	, 'display:none');
						$tmpl->setVariable('pregtip4'	, 'display:none');
					}
					if($encpretip==2){	//TABULADO
						$tmpl->setVariable('pregtip1'	, 'display:none');
						$tmpl->setVariable('pregtip2'	, 'display:visible');
						$tmpl->setVariable('pregtip3'	, 'display:none');
						$tmpl->setVariable('pregtip4'	, 'display:none');
						
						$vopciones= explode(",",$encpreval);

						foreach ($vopciones as $key => $value) {
							$tmpl->setCurrentBlock('preval');
							$tmpl->setVariable('encpreval'	, $value);
							$tmpl->parse('preval');
						}
					}
					if ($encpretip==3) {	//CALIFICADO
						$tmpl->setVariable('pregtip1'	, 'display:none');
						$tmpl->setVariable('pregtip2'	, 'display:none');
						$tmpl->setVariable('pregtip3'	, 'display:visible');
						$tmpl->setVariable('pregtip4'	, 'display:none');

						$tmpl->setCurrentBlock('jsclasificar');
						$tmpl->setVariable('encpreitmcla', $encpreitm);
						$tmpl->setVariable('encpreval'	, $encpreval);
						$tmpl->parse('jsclasificar');
					}
					if ($encpretip==4) {	//SELECCION MULTIPLE
						$tmpl->setVariable('pregtip1'	, 'display:none');
						$tmpl->setVariable('pregtip2'	, 'display:none');
						$tmpl->setVariable('pregtip3'	, 'display:none');
						$tmpl->setVariable('pregtip4'	, 'display:visible');

						
						$vopciones= explode(",",$encpreval);
						
						foreach ($vopciones as $key => $value) {
							$aux = explode('=',$value);
							
							$tmpl->setCurrentBlock('premultiple');
							$tmpl->setVariable('encpreval'	, trim($aux[0]));
							$tmpl->setVariable('encpredes'	, trim($aux[1]));
							$tmpl->parse('premultiple');
						}
					}
					$tmpl->setVariable('encpreitm'	, $encpreitm);
					$tmpl->setVariable('encpregun'	, $encpregun);

					$tmpl->parse('preguntas');
				}
			}
			
			$tmpl->parse('encuestas');
		}
		
	}
	
	//--------------------------------------------------------------------------------------------------------------
	//sql_close($conn);	
	$tmpl->show();
	
?>	
