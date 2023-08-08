<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	$arrayqr=null;
    $fileList = glob('../qrimage/*.png');
  
            foreach($fileList as $filename){
                /*$url = 
                'https://media.geeksforgeeks.org/wp-content/uploads/gfg-40.png';*/
				$file_name = basename($filename);
                
                $arrayqr[]=['url'=>$filename,'nombre'=>$file_name];
                }   
            
                $arrayqrJSON = json_encode($arrayqr);
                $tmpl->setvariable('arrayqrJSON'		, $arrayqrJSON);   
	$tmpl->show();
	
?>	
