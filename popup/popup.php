<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';
	require_once GLBRutaFUNC.'/constants.php';		

	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('popup.html');
	DDIdioma($tmpl);

	 //////////// IMAGENES /////////////////////
    
	 $pathimagenes = '../popimg/';
	 $imgBannerHomeNull	= '../assets-nuevodisenio/img/bannerhome.jpg';
	 $tmpl->setVariable('imgProductoNull'	, $imgBannerHomeNull 	);
	
	 $conn = sql_conectar(); //Apertura de Conexion
	
	 $query = "	SELECT *
	 FROM ADM_POP WHERE POP_REG=1";

	
	
	$Table = sql_query($query,$conn);

	for($i=0; $i<$Table->Rows_Count; $i++){
	 $row = $Table->Rows[$i];
	
	 $popupimg 	= trim($row['POP_IMG']);
	 $popupurl 	= trim($row['POP_URL']);
	 $estcodigo 	= trim($row['ESTCODIGO']);
	 $popupreg 	= trim($row['POP_REG']);
	 $popupdescri 	= trim($row['POP_DESCRI']);
	 $popuptipo 	= trim($row['POP_TIPO']);
	
	 
	 if($popupimg==''){ 
		$tmpl->setvariable('displayimage'		, 'd-none');
	 }else{
		$tmpl->setvariable('displayimage'		, '');
		 $popupimg = $pathimagenes.$popupimg;
		 $tmpl->setVariable('popupimg'	, $popupimg	);
	 }
	 if($popupdescri==''){ 
		$tmpl->setvariable('displaytext'		, 'd-none');
	 }else{
		$tmpl->setvariable('displaytext'		, '');
		$tmpl->setVariable('popupdescri'	, $popupdescri	);
	 }
	 if ($popuptipo==2){

		$tmpl->setvariable('displaytext'		, 'd-none');
		$tmpl->setvariable('displayimage'		, '');

	}else if ($popuptipo==3){

		$tmpl->setvariable('displaytext'		, '');
		$tmpl->setvariable('displayimage'		, 'd-none');
	}else{

		$tmpl->setvariable('displaytext'		, '');
		$tmpl->setvariable('displayimage'		, '');
	}

	 
	 $tmpl->setVariable('popupurl'	, $popupurl	);


	}

	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
		
	$tmpl->show();
	
?>	
