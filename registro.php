<?
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/constants.php';
	require_once GLBRutaAPI  . '/timezone.php';
	//require_once GLBRutaFUNC.'/idioma.php';//Idioma



	$IdiomView = '';
	$peridioma = (isset($_GET['ID']))? trim($_GET['ID']):'ESP'; //Nota desde el home acceso directo	//--------------------------------------------------------------------------------------------------------------
	$usuario = (isset($_GET['USER']))? trim($_GET['USER']):0;

	$usuario		= VarNullBD($usuario		, 'N');

	if ($peridioma=='ESP'){

	  require_once GLBRutaFUNC.'/idiomaesp.php';

	  $IdiomView = strtoupper('esp');

	  }else{

		  require_once GLBRutaFUNC.'/idiomaing.php';
		  $IdiomView = strtoupper('ing');
	  }
	$tmpl= new HTML_Template_Sigma();
	$tmpl->loadTemplateFile('registro.html');
	DDIdioma($tmpl);
	$tmpl->setVariable('peridioma', $peridioma );


	// if(isset($_SESSION['PARAMETROS']['SisNombreEvento'])){
	// 	$tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);
	// }
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
	$paicodigo 			= '';
	if ($peridioma=='ESP')
	{
		$tmpl->setVariable('urlterminos', "https://drive.google.com/file/d/1tkKOZ-YI_xV9QidzkBMV5nZH90NqzxIT/view?usp=sharing" );
	}else{
		$tmpl->setVariable('urlterminos', "https://drive.google.com/file/d/1XJhysdW6OYs9DvhWtfgyRk7jnResq0Gb/view?usp=sharing" );
	}
	$conn= sql_conectar();//Apertura de Conexion
	//Listado de Paises
	$query = "SELECT PAICODIGO,PAIDESCRI,PAIDESCRIING
				FROM TBL_PAIS
				ORDER BY PAIDESCRI";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count;$i++){
		$row= $Table->Rows[$i];
		$paicod = trim($row['PAICODIGO']);
		if ($peridioma=='ESP')
		{
			$paides = trim($row['PAIDESCRI']);
		}else{
			$paides = trim($row['PAIDESCRIING']);
		}


		$tmpl->setCurrentBlock('paises');
		$tmpl->setVariable('paicodigo'	, $paicod 	);
		$tmpl->setVariable('paidescri'	, $paides 	);

		$tmpl->parseCurrentBlock('paises');
	}

		
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		
		$timezoneip=getTimeZoneIp($ipaddress);
		// Read the JSON file 
		$json = file_get_contents('api/timezone.json');
		// Decode the JSON file
		$json_data = json_decode($json,true);
		foreach ($json_data as &$value) {
			$tmpl->setCurrentBlock('zonahoraria');
			$tmpl->setVariable('timregcod'	, $value 		);
			$tmpl->setVariable('timdescri'	, $value	);
			if($value==$timezoneip){
				$tmpl->setVariable('timsel', 'selected' );
			}
			$tmpl->parse('zonahoraria');
		}
		
	
	


	//--------------------------------------------------------------------------------------------------------------

	$pertipcod='';

// BUSCO SI ES REGISTRO MULTIPLE
$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'TipoRegistro'";
			$Table2 = sql_query($query2, $conn);
			for ($i = 0; $i < $Table2->Rows_Count; $i++) {
				$row = $Table2->Rows[$i];
				$tiporegistro = trim($row['ZDESCRI']);
				if($tiporegistro=='false'){
					$tmpl->setVariable('registrounicovisible', '');
				}else{
					$tmpl->setVariable('registrounicovisible', 'd-none');
				}
			}


if($tiporegistro == 'false'){
	//Clase de Perfiles
$pertipcod =66;
$query = "	SELECT PERCLASE,PERCLADES
			FROM PER_CLASE
			WHERE PERTIPO=$pertipcod AND ESTCODIGO<>3
			ORDER BY PERCLASE ";
$Table = sql_query($query,$conn);
for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];
	$perclacod 	= trim($row['PERCLASE']);
	$perclades	= trim($row['PERCLADES']);

	$tmpl->setCurrentBlock('perclases2');

	if($perclacod == $usuario){
		$tmpl->setVariable('perclasel'	, 'selected'	);
	}

	$tmpl->setVariable('perclacod'	, $perclacod 	);
	$tmpl->setVariable('perclades'	, $perclades	);
	$tmpl->parse('perclases2');

}

}else{
	$tmpl->setVariable('sacarseleccionusuario'	, 'd-none' 	);
}


$query = "	SELECT BANNERS
			FROM ADM_IMG WHERE BANID='10'";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];
	$file 	= trim($row['BANNERS']);
	$tmpl->setVariable('file', $file);

}
//busqueda variable mostrar tipo de perfil 

if ( ($tiporegistro=='false') && ($usuario==0) ){

	$tmpl->setVariable('faltaseleccionusuario', 'd-none');	
	$arraypreguntasdinamicas=null;
	$myJSON = json_encode($arraypreguntasdinamicas);
$tmpl->setvariable('arraypreguntasdinamicas'		, $myJSON);
$validacion[] = '';
$tmpl->setVariable('validacion', json_encode($validacion));



}else{



$query = "SELECT VARMOST FROM VAR_MAEST WHERE VARTITULO='pertipo' AND USUARIO=$usuario";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$mostrartipoperfil	= trim($row['VARMOST']);
	}

	//--------------------------------------------------------------------------------------------------------------
	//Tipo de Perfiles
	$query = "SELECT PERTIPO,PERTIPDES$IdiomView AS PERTIPDES
				FROM PER_TIPO
				WHERE ESTCODIGO=1
				ORDER BY PERTIPO";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		if($mostrartipoperfil == "true"){
			$pertipcod 	= trim($row['PERTIPO']);
		}else{
			$pertipcod 	= 66;
		}
		$pertipdes	= trim($row['PERTIPDES']);

		//SI el usuario no es admin, cargo solo los registros asignados

		$tmpl->setCurrentBlock('pertipos');
		if($usuario != 0){
			
			if($pertipcod == 66){
				$tmpl->setVariable('pertipsel'	, 'selected' 		);
			}
		}
		$tmpl->setVariable('pertipcod'	, $pertipcod 		);
		$tmpl->setVariable('pertipdes'	, $pertipdes	);
		$tmpl->parse('pertipos');


	}

	if($usuario != 0){
		$pertipcod 	= 66;
	}

	//variables del formulario
	$arraypreguntasdinamicas=null;
	$query = "SELECT * FROM VAR_MAEST WHERE USUARIO=$usuario";

$Table = sql_query($query,$conn);
for($i=0; $i<$Table->Rows_Count; $i++){
$row = $Table->Rows[$i];
$nombre	= trim($row['VARTITULO']);
$mostrar	= trim($row['VARMOST']);
$validar	= trim($row['VARREQ']);
$textoesp	= trim($row['VARDESCRI']);
$textoing	= trim($row['VARDESCRIING']);
$textopor	= trim($row['VARDESCRIPOR']);
$vartipo	= trim($row['VARTIPO']);
$varopc	= trim($row['VAROPC']);

$validacion[]=$nombre.$validar;

if($mostrar==="true"){
$tmpl->setVariable('mostrar'.$nombre, '');
}else{
	$tmpl->setVariable('mostrar'.$nombre, 'd-none');
}

if($usuario !=0){
	$tmpl->setVariable('mostrarpertipo', 'd-none');
	$tmpl->setVariable('mostrarperclase', 'd-none');
}

if ($peridioma=='ESP')
		{
			$textopregunta = $textoesp;
			$tmpl->setVariable('texto'.$nombre, $textoesp);
		}else if ($peridioma=='POR'){
			$textopregunta = $textopor;
			$tmpl->setVariable('texto'.$nombre, $textopor);
		}else{
			$textopregunta = $textoing;
			$tmpl->setVariable('texto'.$nombre, $textoing);
		}

		if( ($mostrar==="true") && ($i>25) ){

			$arraypreguntasdinamicas[]=['nombre'=>$nombre,'textopregunta'=>$textopregunta,'tipopregunta'=>$vartipo,'opcionespregunta'=>$varopc];
		}

		

}

$myJSON = json_encode($arraypreguntasdinamicas);
$tmpl->setvariable('arraypreguntasdinamicas'		, $myJSON);




$tmpl->setVariable('validacion', json_encode($validacion));


	//--------------------------------------------------------------------------------------------------------------
	//Clase de Perfiles
	if($pertipcod!=''){
		$query = "	SELECT PERCLASE,PERCLADES
					FROM PER_CLASE
					WHERE PERTIPO=$pertipcod AND ESTCODIGO<>3
					ORDER BY PERCLASE ";
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$perclacod 	= trim($row['PERCLASE']);
			$perclades	= trim($row['PERCLADES']);

			$tmpl->setCurrentBlock('perclases');
			$tmpl->setVariable('perclacod'	, $perclacod 	);
			if($perclacod == $usuario){
				$tmpl->setVariable('perclasel'	, 'selected'	);
			}
			$tmpl->setVariable('perclades'	, $perclades	);
			$tmpl->parse('perclases');

		}
	}


}

	$pathimagenes = '../admimg/';
$imgBannerHomeNull	= '../assets-nuevodisenio/img/bannerhome.jpg';
$tmpl->setVariable('imgProductoNull'	, $imgBannerHomeNull 	);
$tmpl->setVariable('displaybannervideo'	, 'd-none' 	);
$query = "	SELECT BANNERS
			FROM ADM_IMG
            WHERE BANID<2";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];
    $bannerhomeimgchico 	= trim($row['BANNERS']);


	if($bannerhomeimgchico==''){
		$bannerhomeimgchico = $imgBannerHomeNull;
	}else{
		$bannerhomeimgchico = $pathimagenes.$bannerhomeimgchico;
	}

	if($i==0){
		$tmpl->setVariable('bannerhomeimginferior'	, $bannerhomeimgchico	);
	}else{
		$tmpl->setVariable('bannerhomeimginferiorcel'	, $bannerhomeimgchico	);
	}
}

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
		
		
	$tmpl->parse('area');
}
	//--------------------------------------------------------------------------------------------------------------
//// ME FIJO QUE TIPO DE REUNION ES
$queryreunion = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoReunion'";
$Tablereunion = sql_query($queryreunion, $conn);
	$rowreunion = $Tablereunion->Rows[0];
	$tiporeunion = trim($rowreunion['ZVALUE']);

	if ($tiporeunion == 'true') {
		$tmpl->setVariable('linkintereses'	, 'd-none'	);
		$tmpl->setVariable('linkoferta'	, ''	);
		$tmpl->setVariable('linkdemanda'	, ''	);
	}else{
		$tmpl->setVariable('linkintereses'	, ''	);
		$tmpl->setVariable('linkoferta'	, 'd-none'	);
		$tmpl->setVariable('linkdemanda'	, 'd-none'	);
	}


	sql_close($conn);
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();

?>
