<?
/**
 *@referencia Usuario(id_usuario) Saludmascotas_Model_User(id)
*/
//echo '<div class="contenedor_main">ok</div>';
//die("ok");
class Saludmascotas_Model_ConfiguracionUsuario extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"nombre",
			"valor",
			"id_usuario"
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public static function findConfig($nombre){
		$usuario = Frontend_Usuario_Model_User::getLogedUser();
		if(!$usuario)
			return false;
		$id_usuario = $usuario->getId();
		$config = new self();
		$config->setNombre($nombre);
		$config->setIdUsuario($id_usuario);
		$config->setWhere(
			Db_Helper::equal('nombre'),
			Db_Helper::equal('id_usuario')
		);
		$config = $config->search(null, null, null, null, __CLASS__);
		if(!$config || !is_array($config) || !count($config))
			return(null);
		$config = array_shift($config);
		return($config);
	}
	public static function findConfigValues($array){
		$return = array();
		foreach($array as $key=>$default){
			$return[$key] = self::findConfigValue($key, $default);
		}
		return $return;
	}
	public static function findConfigValue($nombre, $default=null){
		$config = self::findConfig($nombre);
		if(!$config){
			if(isset($default)){
				$usuario = Frontend_Usuario_Model_User::getLogedUser();
				if(!$usuario)
					return false;
				$id_usuario = $usuario->getId();
				$config = new self();
				$config->setNombre($nombre)
					->setValor($default)
					->setIdUsuario($id_usuario)
					->replace()
				;
				return $default;
			}
			return(null);
		}
		return($config->getValor());
	}
	public function filterValue(){
		$configured = $this->getValor();
		$valores = array();
		foreach(explode("\n", $configured) as $valor){
			$valor = trim($valor);
			if($valor==='')
				continue;
			if($valor[0]=='#')
				continue;
			$valores[] = $valor;
		}
		if(count($valores))
			return($valores);
		return null;
	}
	public static function findConfigTextFiltered($nombre, $default=null){
		$configured = self::findConfig($nombre, $default);
		if(!$configured)
			return null;
		$arr = $configured->getData();
		$config = new self();
		$config->loadFromArray($arr);
		return $config->filterValue();
	}
	public function getDbTableName() 
	{
		return 'sm_configuracion_usuario';
	}
}
?>