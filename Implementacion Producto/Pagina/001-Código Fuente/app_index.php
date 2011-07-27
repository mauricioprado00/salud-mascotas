<?
//printf("cookie: %s, gc: %s", ini_get('session.cookie_lifetime'), ini_get('session.gc_maxlifetime'));die(__FILE__.__LINE__);
//sleep(3);
//time_nanosleep(0, 500000000);
//ini_set('display_errors','on');
//error_reporting(E_ALL);
//var_dump(strtotime('12:30'), time());
//die();
ini_set('log_errors', 'On');
ini_set('memory_limit', '100M');
include_once(dirname(__FILE__).'/basic.php');

//Core_Http_Header::ContentType('text/plain');
//var_duMP($GLOBALS);
//die();
//die();
//header("content-type: text/plain;");
//Core_Http_Header::ContentType('text/plain');
//Core_Http_Header::ContentType('text/plain');
//$db_connection = new COM("ADODB.Connection", NULL, 1251);
//if(Core_Http_Header::isAjaxRequest()){
//	echo "hola";
//	die();
//}
//var_dump(Core_Http_Header::getRequest('X_REQUESTED_WITH'));
//var_dump($_SERVER);
////var_dump($GLOBALS);
//die();
$app = Core_App::getInstance();
$app
	->setDbHost(DB_HOST)
	->setDbUser(DB_USER)
	->setDbPassword(DB_PASS)
	->setDbModel(DB_DATABASE);
//$x = Inta_Model_Traduccion::Traducir('alguna cosa', 'x: kradkk', 'Core');
//var_dump($x);
//$x = Inta_Model_Traduccion::Traducir('alguna cosa', 'x: kradkk', 'Core_kradkk');
////echo Inta_Db::getInstance()->getLastQuery();
//var_dump($x);
//die();
//$doc = new DOMDocument();
//$doc->load(realpath('/var/www/document.xml'));
//$doc->formatOutput = true;	
//echo $doc->saveXML();
//die();
//Test_DataModel::getInstance()->pruebaXmlOutputEntity();
//Test_DataModel::getInstance()->testXmlOutputCollectionHomogenea();
//Test_DataModel::getInstance()->testXmlOutputCollectionHeterogenea();
//Test_DataModel::getInstance()->textXmlOutputGroupedCollectionAndMethodSingle();
//Test_DataModel::getInstance()->testXmlOutputGroupeCollectionAndMethodMultiple();
//Test_Inta_Reporte::getInstance()->testXmlOutputAgrupado();
//Test_Inta_Reporte::getInstance()->testDocxOutputAgrupado();
//$i = new Inta_Model_View_ReporteActividad();
//echo $i->searchGetSql();
//Test_Inta_Reporte::getInstance()->testXmlOutputAgrupado2();
//Test_Inta_Reporte::getInstance()->testXmlOutputOrdenado1();
//die();

$app->initialize();
if(isset($_GET['dotest'])){
	//Test_XsltTemplates::getInstance()->test1();
}
//Test_XsltServer::getInstance()->testNoFormating();
//Test_XsltServer::getInstance()->testSingleFormating();
//Test_XsltServer::getInstance()->testMultipleFormating();
//Test_XsltServer::getInstance()->testAll();
//SELECT `id`, `id_agencia`, `estado`, `ano`, `objetivo`, `nombre_actividad`, `nombre_responsable`, `nombre_agencia`, `resultado_esperado` FROM `inta_algo` WHERE `id_agencia` = '1' AND `estado` = 'planificado' AND `ano` = '2010' AND ( `objetivo` LIKE '%de prueba%' OR `nombre_actividad` LIKE '%de prueba%' OR `nombre_responsable` LIKE '%de prueba%' OR `nombre_agencia` LIKE '%de prueba%' OR `resultado_esperado` LIKE '%de prueba%' )
//Test_Wheres::ComplexConditions();
////echo $c->toXmlString();
//$file = $app->getLayout()->getDesignFilePath('resource/xsl/test/agencia.xsl');
//$file2 = $app->getLayout()->getDesignFilePath('resource/xsl/test/agencia2.xsl');
//$xs = new Core_Xslt_Server();
//$xs->setSource($c);
//$xs->appendStyle($file);
//$xs->appendStyle($file2);
//echo $xs->toXmlString();
//die();
if(1){
	$app->run();
	return;
}
//Core_Server::regenerarClase();
//die();
if(true){
	//$x = new Frontend_Model_Domicilio();
	//$x->setId(9);
	//var_dump($x->load());
	//die(__FILE__.__LINE__);
//	header('content-type:text/plain');
//	$mascota = new Saludmascotas_Model_Mascota();
//	$mascota->setId(17)->load();
//	$colores_agregados = $mascota->getColoresAgregadosAsCollection();
//	var_dump($colores_agregados);
	

//		$x = new Saludmascotas_Model_View_ProvinciaPais();
//		var_dump($x->searchGetSql());
	//Test_Saludmascotas_Ubicaciones::testListadoProvincias();
	//Test_Saludmascotas_Usuario::enviarEmailRegistro();
	//Test_Saludmascotas_Usuario::add_user();
	die(__FILE__.__LINE__);
}
//include_once(dirname(__FILE__).'/test/test_de_herencia_de_modelo.php');
//var_dump($_SERVER);
////Library::_include('includes/otro.php');
//Library::_class('DBEntity');
//Library::_class('RegExp');
////Library::import("sdlfkjsd.* sdf sdf sdf sdf klljkl, paquete.x.%");
//Library::import("DBx.%, DBx.xx.%");

//Library::_class('FileFilterIncluder');

//Library::_class('HtmlDirectoryList');
/**
*@todo email dificil de configurar
*@todo validaciones frontend/backend sin integrar
*@todo jerarquia de clases de modelo restrictiva a solo una base de datos (Core_Model_Abstract)
**/
/** TO DO
*@todo los paths estan bien ahora
*@todo hacer los Library::_new('DB.funca',x,y,z)
*@todo estaria bueno hacer:   Library::import("DB.%txt, DB.xx.%html|php ); para indicar extensiones
/**/

/** TO DO
*@todo para poder independizar el nucleo:
*@todo 1) crear el htacces que redirecciona recursos accesibles publicamente en el directorio de proyecto especifico
*@todo 2) modificar el metodo de inclusion de clases escaneando no solo el direcotio includes/classes sino cualquiera que se agregen en el/los subproyectos
*@todo 3) modificar el solucionador de recursos (getSkinUrl) para que resuelva bustando en los paths tanto del proyecto como del nucleo (se deben poder agregar paths de skin)
*@todo 4) crear un archivo xml, y parseador generico de configuraciones, que lea toda estos sets del subproyecto
*@todo 5) indicar en el archivo xml la configuracion del path con el que se accede al nucleo (si esta desviado) por un htacces o si se accede directamente por otra url
*@todo 6) que se pueda indicar las demas configuraciones en el xml (conexion a la base, suburl, etc) 
*@todo 7) configuraciones de suburl dependientes del hostname. Ej:
*@todo <virtualhost>
*@todo 	<http_host>*</http_localhost>
*@todo 	<config>
*@todo 		<database>
*@todo 			<host>127.0.0.1</host>
*@todo 			<user>root</user>
*@todo 			<password>elNuev0</host>
*@todo 			<schema>app_bigmotorcicle</schema>
*@todo 		</database>
*@todo  	<paths>
*@todo  		<root_path>h:\AppServ\www\BigMotorcicle</root_path>
*@todo  		<subpath_layout>layout/</subpath_layout>
*@todo  		<subpath_design>design/</subpath_design>
*@todo  		<subpath_skin>skin/</subpath_skin>
*@todo  		<subpath_layout>layout/</subpath_layout>
*@todo  		<subpath_layout>layout/</subpath_layout>
*@todo  	</paths>
*@todo 	</config>
*@todo </virtualhost>
*@todo <virtualhost>
*@todo 	<http_host>156.%</http_localhost>
*@todo 	<config>
*@todo 		<database>
*@todo 			<host>xxxxxx</host>
*@todo 			<user>xxxxx</user>
*@todo 			<password>xxxxxx</host>
*@todo 			<schema>xxxxxxxx</schema>
*@todo 		</database>
*@todo  	<paths>
*@todo  		<root_path>h:\AppServ\www\xxxxxxxx</root_path>
*@todo  	</paths>
*@todo 	</config>
*@todo </virtualhost>
*@todo 8) crear un proyecto instalador, que configure el xml y los paths y demas y cree en el directorio de destino
*/
?>