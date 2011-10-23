<?
/**
 *@referencia Domicilio(id_domicilio) Saludmascotas_Model_Domicilio(id)
 *@listar Castracion Saludmascotas_Model_Castracion
*/
class Saludmascotas_Model_User extends Core_Model_User{
	public function init(){
		parent::init();
		$this->setId(null)
			->setActivo(null)
			->setNombre(null)
			->setApellido(null)
			->setTelefono(null)
			->setCelular(null)
			->setEmail(null)
			->setFechaAlta(null)
			
			->setUsername(null)
			->setPassword(null)
			->setIdDomicilio(null)
			->setTipo(null)
		;
		$this->addAutofilterFieldInput('fecha_alta', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_alta', array('Mysql_Helper','filterTimestampOutput'));
	}
	protected function onStartSession($username){
		$this->resetData();
		$this->setUsername($username);
		$this->load();
	}
	public static function getUsuarioByCredentials(&$username, $password){
		$x = new self();
		$x->setUsername($username);
		$x->setEmail($username);
		$x->setPassword($password);
		$x->setWhere('(', Db_Helper::equal('username'), ' or ', Db_Helper::equal('email'), ') and ', Db_Helper::equal('password'));
		$cant = $x->searchCount();
		$ret = $cant==1;
		if($ret){
			$usuario = $x->search();
			$usuario = array_pop($usuario);
			$username = $usuario->getUsername();
			return $usuario;
		}
		return false;
	}
	function validate(&$username, $password){
		$usuario = self::getUsuarioByCredentials($username, $password);
		if(!$usuario)
			return false;
		return $usuario->getActivo()=='si';
//		return $usuario?true:false;
//		/*consultar la base de datos*/
//		//echo "validando $username, $password\n";
//		$x = new self();
//		$x->setUsername($username);
//		$x->setEmail($username);
//		$x->setPassword($password);
//		$x->setWhere('(', Db_Helper::equal('username'), ' or ', Db_Helper::equal('email'), ') and ', Db_Helper::equal('password'));
//		$cant = $x->searchCount();
//		$ret = $cant==1;
//		echo $x->searchGetSql();
//		if($ret){
//			$usuario = $x->search();
//			$usuario = array_pop($usuario);
//			$username = $usuario->getUsername();
//		}
//		return $ret;
	}
//	public function login($username=null, $password=null){
//		if(!@$this&&$username===null){
//			return(false);
//		}
//		if($username!=null){
//			return(parent::login($username, $password));
//		}
//		return(parent::login($this->username, $this->password));
//	}
	public function esTipoSpa(){
		return $this->getTipo()=='spa';
	}
	public function esTipoNormal(){
		return $this->getTipo()=='normal';
	}
	public function esTipoVeterinaria(){
		return $this->getTipo()=='veterinaria';
	}
	public static function getLogedUser(){
		$_this = new self();
		
		if(!$_this->isLoged())
			return(null);
		return($_this);
	}
	public function getDbTableName() 
	{
		return 'sm_usuario';
	}
	protected function onLogedOk(){
		//Granguia_Cart_Helper::onLogin();
	}
}
?>