<?
if(!isset($_SESSION))  session_start();
//include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC.'/sigma.php';	
require_once GLBRutaFUNC.'/zdatabase.php';
require_once GLBRutaFUNC.'/zfvarias.php';
require_once GLBRutaFUNC.'/class.phpmailer.php';
require_once GLBRutaFUNC.'/class.smtp.php';
require_once GLBRutaAPI.'/mailchimp.php';
require_once GLBRutaFUNC.'/constants.php';


$percorreo = (isset($_POST['percorreo'])) ? trim(SQL_replace($_POST['percorreo'])) : '';

//nombre evento
// $tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);
// $tmpl->setVariable('SisNombreEvento', NAME_TITLE );

#buscar la bd con la base de datos
#query
#usuario de PassAcceso
#cod =1
#clave new
#contraseña de nueva y el usuario lo pasas por el md5 la contraseña
#rowcount esta en 0 esta correao errado

$conn = sql_conectar();//Apertura de Conexion

$errcod = 0;
$errmsg = '';

$query="SELECT MSGTITULO,MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG FROM MSG_CABE WHERE MSGREG=2";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$msgtitulo 	= trim($row['MSGTITULO']);
		$msgdescri	= trim($row['MSGDESCRI']);
		$msgsub = trim($row['MSGSUB']);
		$msgbot = trim($row['MSGBOT']);
		$msglnk = trim($row['MSGLNK']);
		$msgimg = trim($row['MSGIMG']);
		$msgdescri = htmlspecialchars_decode($msgdescri);
        $msgdescri = str_replace('class="ql-align-center"','style="text-align:center"',$msgdescri);
		$msgdescri = str_replace('class="ql-align-justify"','style="text-align:justify"',$msgdescri);
		$msgdescri = str_replace('class="ql-align-right"','style="text-align:right"',$msgdescri);

		$query="SELECT MSGREP, MSGCC, MSGCCO FROM MSG_CABE WHERE MSGREG=10";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$msgrep 	= trim($row['MSGREP']);
		$msgcc	= trim($row['MSGCC']);
		$msgcco	= trim($row['MSGCCO']);

$query = "SELECT PERCODIGO,PERNOMBRE,PERAPELLI,PERCORREO,PERUSUACC,PERCOMPAN
			FROM PER_MAEST 
			WHERE PERCORREO='$percorreo' AND ESTCODIGO<>3 ";

$Table = sql_query($query, $conn);

for ($i = 0; $i < $Table->Rows_Count; $i++) {
    $row = $Table->Rows[$i];
    $percodigo = trim($row['PERCODIGO']);
    $pernombre = trim($row['PERNOMBRE']);
    $perapelli = trim($row['PERAPELLI']);
    $percorreo = trim($row['PERCORREO']);
    $perusuacc = trim($row['PERUSUACC']);
    $percompan = trim($row['PERCOMPAN']);
    $msgdescriaux = str_replace("*|Nombre Usuario|*",$pernombre.' '.$perapelli,$msgdescri);
	$msgdescriaux = str_replace("*|Empresa Usuario|*",$percompan,$msgdescriaux);
    $msgdescriaux = str_replace("*|Correo Usuario|*",$percorreo,$msgdescriaux);

    //Genero una clave aleatoria nueva
    $longitud = 6;
    $newpass = substr(MD5(rand(5, 100)), 0, $longitud);

    $perpasacc = md5('BenVido' . $newpass . 'PassAcceso' . $perusuacc);
    $perpasacc = 'B#SD' . md5(substr($perpasacc, 1, 10) . 'BenVidO' . substr($perpasacc, 5, 8)) . 'E##$F';

    //Actualizo la contraseña nueva
    $query = "UPDATE PER_MAEST SET PERPASACC='$perpasacc' WHERE PERCODIGO=$percodigo ";
    $err = sql_execute($query, $conn);

    $body = '<!DOCTYPE html>
    <html lang="en" class="loading">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        </head>

    <body>
    <div style="text-align:center">
        <img src=' . getUrl("/mailsimg/2/$msgimg") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
        <!--app-assets/img/logo-light.png
        -->
        <br>
    </div>
    <div dir="ltr">
        
        <br>
        <br>

        <font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
            <div>'.$msgdescriaux.'</div>
        </font>
        <font color="#212121" style="font-family:arial,sans-serif;font-size:22px;">
            <div style="text-align:center">' . $newpass . '</div>
        </font>
        <div style="text-align:center; margin-top: 30px;">
        <b>  <a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . '">'.$msgbot.'</a></b> 
        </div>
    </div>
</body>
</html>';
    //var_dump(SEND_MAIL_USUARIO,MAIL_NAME_APP,$percorreo,$body); die;
        $tagnombreevento = preg_replace('/\s+/', '-', NAME_TITLE);
        $mail = [
            "from_email" => SEND_MAIL_USUARIO,
        "from_name" => $msgcco,
        "subject" => $msgsub,
        "html" => $body,
        "tags"=> [
            $tagnombreevento."_2"
        ],
            "headers" =>[

                "Reply-To" => $msgrep
            ],
            "to" => [
                [
                    "email" => $percorreo,
                    "type" => "to"
                ]
            ]
        ];

       
    
   
    
    if (filter_var($percorreo, FILTER_VALIDATE_EMAIL)) {
        
        sendMail($mail);
       // var_dump(sendMail($mail)); die;
    }
}

if ($Table->Rows_Count > 0) {
    echo 'OK';
} else {
    echo 'ERROR';
}

//header('Location: recuperarwait.html');
?>	