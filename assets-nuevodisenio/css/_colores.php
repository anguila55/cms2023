
<?php

include $_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'; //PRD
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
header("Content-type: text/css");

$conn = sql_conectar(); //Apertura de Conexion
$colorevento='#FFC210';
$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'ColorEvento'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$colorevento = trim($row['ZDESCRI']);
	
}
sql_close($conn);

$colorevento = $colorevento;

?>

:root {
  --verde: #6bbc64;
  --gris: #3c3c3b;
  --main: <?=$colorevento?>;
  --secondary: #FFC210;
  --addevent: #286efa;
  --verde-check: #00CE96;
  --celeste-clock: #49CAE8;
}

.bg-main-event{
  background-color: var(--main) !important;
}

.bg-verde{
  background-color: var(--verde) !important;
}

.color-main-event {
  color: var(--main);
}

.color-verde-check{
  color: var(--verde-check);
}

.color-celeste-clock{
 color: var(--celeste-clock);
}

.bg-secondary-event{
  background-color: var(--gris);
}

.color-secondary-event {
  color: var(--gris);
}

.bg-main-opacity {
  background-color: var(--main);
  opacity: 0.7;
}

.bg-banners {
  background-color: var(--secondary);
}

.bg-color-gris{
  background-color: rgb(161, 161, 161);
}

.bg-color-gris2{
  background-color: rgb(71, 71, 71);
}

.border-main-event{
  border-color: var(--main) !important;
}