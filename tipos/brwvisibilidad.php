<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
    $tmpl->loadTemplateFile('brwvisibilidad.html');
    	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
    $conn= sql_conectar();//Apertura de Conexion
    //Diccionario de idiomas
    //logerror($_POST['pertipo']);
    
    $pertipo =(isset($_POST['pertipo'])) ? trim($_POST['pertipo']) : '';
    $perclase =(isset($_POST['perclase'])) ? trim($_POST['perclase']) : '';
    $tmpl->setVariable('pertipooriginal',$pertipo);
    $tmpl->setVariable('perclaseoriginal',$perclase);

  

	$query="SELECT R.PERTIPO, R.PERTIPDESESP, PS.PERCLASE,PS.PERCLADES
            FROM PER_TIPO R
            LEFT OUTER JOIN PER_CLASE PS ON R.PERTIPO=PS.PERTIPO
            WHERE  R.ESTCODIGO=1  AND PS.ESTCODIGO=1 
            ORDER BY R.PERTIPDESESP  ";
            $Table = sql_query($query,$conn);

            for($i=0; $i<$Table->Rows_Count; $i++){
            $row = $Table->Rows[$i];



                $pertipodes   = $row['PERTIPDESESP'];
                $pertipcod  = $row['PERTIPO'];
                $perclades   = $row['PERCLADES'];
                $perclacod   = $row['PERCLASE'];

                if ($pertipcod==$pertipo && $perclacod==$perclase){
                    $pertiporides=$pertipodes;
                    $perclaorides=$perclades;
                    $tmpl->setVariable('pertiporides',$pertiporides);
                    $tmpl->setVariable('perclaseorides',$perclaorides);
                }

                    $tmpl->setCurrentBlock('visibilidad');
                    $queryperm = "SELECT PERTIPOPERM,PERTIPO,PERTIPCLA,PERTIPDST, PERCLADST  
                                FROM PER_TIPO_PERM 
                                WHERE PERTIPO = $pertipo AND PERTIPCLA= $perclase AND PERTIPDST=$pertipcod AND PERCLADST=$perclacod
                                ORDER BY PERTIPOPERM";
                    $Tableperm = sql_query($queryperm, $conn);
                    if($Tableperm->Rows_Count>0){

                        $rowperm = $Tableperm->Rows[0];
                        $pertipoperm= trim($rowperm['PERTIPOPERM']);
                        $tmpl->setVariable('pertipoperm',$pertipoperm);
                        $tmpl->setVariable('pertiporicod',$pertipo);
                        $tmpl->setVariable('pertipdstcod',$pertipcod);
                        $tmpl->setVariable('pertipdstdes',$pertipodes);
                        $tmpl->setVariable('perclaoricod',$perclase);
                        $tmpl->setVariable('percladstcod',$perclacod);
                        $tmpl->setVariable('percladstdes',$perclades);
                        $tmpl->setVariable('vischecked','checked');

                    }else{
                        $tmpl->setVariable('pertipoperm',0);
                        $tmpl->setVariable('pertiporicod',$pertipo);
                        $tmpl->setVariable('pertipdstcod',$pertipcod);
                        $tmpl->setVariable('pertipdstdes',$pertipodes);
                        $tmpl->setVariable('perclaoricod',$perclase);
                        $tmpl->setVariable('percladstcod',$perclacod);
                        $tmpl->setVariable('percladstdes',$perclades);
                        $tmpl->setVariable('vischecked','');


                    }
                    $tmpl->parse('visibilidad');


                




            }


	//--------------------------------------------------------------------------------------------------------------
    sql_close($conn);	
    // $tmpl->setVariable('codclasee', $codclasee);

	$tmpl->show();
   
?>	
