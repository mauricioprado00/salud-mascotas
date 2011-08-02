<?php
include_once(dirname(__FILE__).'/../includes/configuraciones.php');
$DB_HOST = DB_HOST;
$DB_USER = DB_USER;
$DB_PASS = DB_PASS;
$DB_DATABASE = DB_DATABASE;
$cargado = false;
if(isset($_GET['download'])){
	// Vamos a mostrar un PDF
	header('Content-type: application/x-sql');
	
	$filename = 'backup_db_'.date('Ymd_His').'.sql';
	header("Content-Disposition: attachment; filename=\"$filename\"");
	echo `mysqldump --skip-opt --quick --add-drop-table --add-locks --create-options --disable-keys --extended-insert --lock-tables --set-charset -h$DB_HOST -u$DB_USER -p$DB_PASS $DB_DATABASE`;
}
elseif(count($_FILES)&&isset($_FILES['archivo'])){
	extract($_FILES['archivo']);
	//$name, $type, $tmp_name, $size
	echo '<pre>';
	echo `mysql -h$DB_HOST -u$DB_USER -p$DB_PASS $DB_DATABASE < {$tmp_name}`;
	echo '</pre>';
	$cargado = true;
}
?>
<html>
<head>
	<style>
		li{margin:10px 0;text-align:left;}
		ul{display:inline-block;}
	</style>
</head>
<body>
<center>
<?php if($cargado): ?>
Se ha cargado la base de datos con el archivo <b><?php print $name; ?></b> (<?php print $size; ?> bytes)
<?php endif; ?>
<h1>Herramienta de restauracion de base de datos</h1>
<ul>
	<li><a href="restore_db.php?download">Descargar base de datos actual</a></li>
	<li>
		Cargar un script a la base de datos
		<form method="post" enctype="multipart/form-data">
			<input type="file" name="archivo" />
			<input type="submit" value="Enviar" />
		</form>
	</li>
</ul>
</center>
</body>
</html>