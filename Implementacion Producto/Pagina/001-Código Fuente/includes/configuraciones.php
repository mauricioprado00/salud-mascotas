<?
if(strpos($_SERVER["REQUEST_URI"], "~bigmoto") !== false)
{/*configuracion para el sitio*/
	{
	/*
	Database:	bigmoto_app
	Host:	localhost
	Username:	bigmoto_app
	Password:	bigmoto_app
	*/
	}

	/* BASE DE DATOS */
	define("DB_HOST", "localhost");
	define("DB_USER", "bigmoto_app");
	define("DB_PASS", "bigmoto_app");
	define('DB_DATABASE', 'bigmoto_app');
	
	//define('CFG_PATH_ROOT', $_SERVER['DOCUMENT_ROOT']);
	define('CFG_PATH_ROOT', '/home/bigmoto/domains/bigmotorcycles.com.ar/public_html');
	//define('CFG_PATH_ROOT', dirname(dirname(__FILE__).'/../../'));
	define('CONF_PATH_APP', '/');
	define('CONF_PATH_DESIGN', CONF_PATH_APP.'design/');
	define('CONF_PATH_INCLUDES', CONF_PATH_APP.'includes/');
	define('CONF_PATH_CLASSES', CONF_PATH_INCLUDES.'classes/');
	define('CONF_PATH_CORE', CONF_PATH_CLASSES.'Core/');
	define('CONF_SUBPATH_LAYOUT', 'layout/');
	define('CONF_SUBPATH_DESIGN', 'design/');
	define('CONF_SUBPATH_SKIN', 'skin/');
	define('CONF_PATH_SKIN', CONF_PATH_APP.CONF_SUBPATH_SKIN);
	define('CONF_SUBPATH_TEMPLATE', 'template/');
	define('CONF_SUBPATH_RESOURCE', 'resource/');
	define('CONF_SUBURL_APP','/~bigmoto/');
	define("CONF_URL_APP", 'http://'.$_SERVER['HTTP_HOST'].CONF_SUBURL_APP);
	define("CONF_DEBUG_ENVIRONMENT", 0);
	if (!getenv('PHP_PEAR_INSTALL_DIR')) {
		putenv('PHP_PEAR_INSTALL_DIR=' . CFG_PATH_ROOT.CONF_PATH_INCLUDES.'pearlib');
	}

}
else
{
	/* BASE DE DATOS */
	define("DB_HOST", "192.168.1.215");
	define("DB_USER", "root");
	define("DB_PASS", "elNuev0");
//	define("DB_HOST", "192.168.1.100");
//	define("DB_USER", "root");
//	define("DB_PASS", "desbole");
	define('DB_DATABASE', 'app_saludmascotas');
	define('CONF_SECRETHASH', md5(DB_HOST.DB_USER.DB_PASS.DB_DATABASE));
	
	define('CFG_PATH_ROOT', '/var/www/saludmascotas');
	
	define('CONF_PATH_APP', '/');
	define('CONF_PATH_DESIGN', CONF_PATH_APP.'design/');
	define('CONF_PATH_INCLUDES', CONF_PATH_APP.'includes/');
	define('CONF_PATH_CLASSES', CONF_PATH_INCLUDES.'classes/');
	define('CONF_PATH_CORE', CONF_PATH_CLASSES.'Core/');
	define('CONF_SUBPATH_LAYOUT', 'layout/');
	define('CONF_SUBPATH_DESIGN', 'design/');
	define('CONF_SUBPATH_SKIN', 'skin/');
	define('CONF_SUBPATH_UPLOADS', CONF_SUBPATH_SKIN.'uploads/');
	define('CONF_PATH_SKIN', CONF_PATH_APP.CONF_SUBPATH_SKIN);
	define('CONF_PATH_UPLOADS', CONF_PATH_APP.CONF_SUBPATH_UPLOADS);
	define('CONF_SUBPATH_TEMPLATE', 'template/');
	define('CONF_SUBPATH_RESOURCE', 'resource/');
	define('CONF_SUBURL_APP','/saludmascotas/');
	define("CONF_URL_APP", 'http://'.$_SERVER['HTTP_HOST'].CONF_SUBURL_APP);
	define("CONF_DEBUG_ENVIRONMENT", 0);
	if (!getenv('PHP_PEAR_INSTALL_DIR')) {
		putenv('PHP_PEAR_INSTALL_DIR=' . CFG_PATH_ROOT.CONF_PATH_INCLUDES.'pearlib');
	}
	define('ZFLIBPATH',dirname(__FILE__).'/../../ZendFramework/library/');
	define('CONF_MAP24_APPKEY', 'FJX5715ae3ff53e4ea286f2a1aab1356X50');
	//marco//define('CONF_MAP24_APPKEY', 'FJXc8510e121bd6338c5d953e1dc5515X50');
	define('CONF_GMAPS_APPKEY', 'ABQIAAAAKENOc8YHJVLSb2T5siO_WRRI_dZmxT_lvf5qO6Udg29WQ8WPHxQkKuOmI4YdESx3ZhQqHpCdaTrKbA');
	define('SM_USER_ACTIVATION', 0);
	//http://maps.google.com/maps/geo?q=1600+Amphitheatre+Parkway,+Mountain+View,+CA&output=json&oe=utf8&sensor=true_or_false&key=ABQIAAAAKENOc8YHJVLSb2T5siO_WRRI_dZmxT_lvf5qO6Udg29WQ8WPHxQkKuOmI4YdESx3ZhQqHpCdaTrKbA
	//www.saludmascotas.com.ar define('CONF_GMAPS_APPKEY', 'ABQIAAAAKENOc8YHJVLSb2T5siO_WRQ8ne26DyBQuBQas01YiO0ymxME8xRKexO61GCe6T7YVrDyFBzM8fdI6A');
}
?>