<?
/**
 *@referencia Mascota(id_mascota) Saludmascotas_Model_Mascota(id)
 *@referencia Usuario(id_usuario_pa) Saludmascotas_Model_User(id)
 *@referencia Veterinario(id_veterinario) Saludmascotas_Model_User(id)
*/
class Saludmascotas_Model_Castracion extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'activo'
			,'fecha_solicitud'
			,'fecha_asignacion'
			,'id_veterinario'
			,'veterinario'
			,'id_mascota'
			,'id_usuario_pa'
			,'fecha_confirmacion'
			,'resultado_confirmacion'
			,'descripcion'
		);
		$this->addAutofilterFieldInput('fecha_solicitud', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_solicitud', array('Mysql_Helper','filterTimestampOutput'));
		$this->addAutofilterFieldInput('fecha_asignacion', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_asignacion', array('Mysql_Helper','filterTimestampOutput'));
		$this->addAutofilterFieldInput('fecha_confirmacion', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_confirmacion', array('Mysql_Helper','filterTimestampOutput'));
		
	}
	public function setActivo($set=true){
		$this->setData('activo', $set?'si':'no');
		return $this;
	}
	public function esActivo(){
		return $this->getActivo()=='si';
	}
	public function esResultadoConfirmacionRealizada(){
		return $this->getResultadoConfirmacion()=='realizada';
	}
	public function esResultadoConfirmacionNoRealizada(){
		return $this->getResultadoConfirmacion()=='no realizada';
	}
	public function setResultadoConfirmacionRealizada(){
		return $this->setResultadoConfirmacion('realizada');
	}
	public function setResultadoConfirmacionNoRealizada(){
		return $this->setResultadoConfirmacion('no realizada');
	}
	public function asignarSpa(){
		
		$usuario = new Saludmascotas_Model_User();
		$usuario->setWhere(
			Db_Helper::equal('tipo','spa'),
			Db_Helper::null('id_domicilio',false)
		);
		$usuarios = new Core_Collection($usuario->search(null, 'ASC', null, 0, get_class($usuario)));
//		echo $usuario->searchGetSql();
//		var_dump($usuarios);
//		die(__FILE__.__LINE__);

		$mascota = $this->getMascota();
		$domicilio_mascota = $mascota->getDomicilio();
		if(!$domicilio_mascota){
			return false;
		}
		if($usuarios->count()){
			foreach($usuarios as $usuario){
				$domicilio_usuario = $usuario->getDomicilio();
				//var_dump($usuario->getData(), $domicilio_usuario);
				if(!$domicilio_usuario){
					die('no hay domicilio_usuario');
					$usuario->setDistancia(0);
					continue;
				}
				$lat1 = $domicilio_mascota->getLat();
				$lng1 = $domicilio_mascota->getLng();
				$lat2 = $domicilio_usuario->getLat();
				$lng2 = $domicilio_usuario->getLng();
				$distancia = Saludmascotas_Helper::getInstance()->min_distance_km($lat1, $lng1, $lat2, $lng2);
				$usuario->setDistancia($distancia);
				//var_dump($distancia);
			}
			$usuarios = $usuarios->OrderBy('distancia');
			$usuario_seleccionado = null;
			foreach($usuarios as $usuario){
				if($usuario->getDistancia()>0){
					$usuario_seleccionado = $usuario;
					break;
				}
			}
			//var_dump($usuario_seleccionado);
			if($usuario_seleccionado){
				$this->setIdUsuarioPa($usuario_seleccionado->getId());
				return $this->update();
			}
		}
		else{
			//die('no hay users');
		}
		//die(__FILE__.__LINE__);
		return false;
	}
	public function getDbTableName() 
	{
		return 'sm_castracion';
	}
}
?>