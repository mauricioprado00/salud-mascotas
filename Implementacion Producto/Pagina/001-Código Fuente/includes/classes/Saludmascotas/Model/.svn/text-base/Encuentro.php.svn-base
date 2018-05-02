<?
/**
 *@referencia Domicilio(id_domicilio) Saludmascotas_Model_Domicilio(id)
 *@referencia Mascota(id_mascota) Saludmascotas_Model_Mascota(id)
 *@referencia Usuario(id_usuario) Saludmascotas_Model_User(id)
 *@listar Reencuentro Saludmascotas_Model_Reencuentro
*/
// *@listar Foto Saludmascotas_Model_FotoMascota
// *@listar Color Saludmascotas_Model_ColorMascota
// *@listar Localidad Saludmascotas_Model_Localidad
class Saludmascotas_Model_Encuentro extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'activo'
//			,'destacado'
			,'hora_encuentro'
			,'descripcion'
			,'notificacion_email'
			,'republicar_automaticamente'
//			,'quiere_destacar'
			,'mostrar_telefono'
			,'tiene_mascota'
			,'estado_mascota'
			
			,'fecha_publicacion'
			,'fecha_expiracion'

			,'id_domicilio'
			,'id_mascota'
			,'id_usuario'
		);
		$this->addAutofilterFieldInput('hora_encuentro', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('hora_encuentro', array('Mysql_Helper','filterTimestampOutput'));
		$this->addAutofilterFieldInput('fecha_publicacion', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_publicacion', array('Mysql_Helper','filterTimestampOutput'));
		$this->addAutofilterFieldInput('fecha_expiracion', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_expiracion', array('Mysql_Helper','filterTimestampOutput'));
	}
	public function setActivo($set=true){
		$this->setData('activo', $set?'si':'no');
		return $this;
	}
	public function esActivo(){
		return $this->getActivo()=='si';
	}
	public function esEstadoVista(){
		return $this->getData('tiene_mascota')=='no';
	}
	public function esEstadoEnGuarda(){
		return $this->getData('tiene_mascota')=='si';
	}
	public function getCoincidencias($ids=null, $as_collection=true, $limit=null, $start=0, $as_objects=true, $columns=null){
		$perdida = new Saludmascotas_Model_View_MascotaPerdida();
		$wheres = array();
		
		if(isset($ids)&&is_array($ids)&&count($ids)){
			$wheres[] = Db_Helper::in('pe_id', true, $ids);
		}
		else{
			//agrego filtro de estado
			$wheres[] = Db_Helper::equal('pe_activo','si');
			
			//agrego filtro de tiempo
			$hora_encuentro = $this->getHoraEncuentro(null, array());
			$wheres[] = Db_Helper::between('pe_hora_extravio', null, $hora_encuentro, true);
			
			//agrego filtro de distancia
			//todo: agregar filtro de distancia, hacer un (barrio== or radio km<X or ciudad==)

			$mascota = $this->getMascota();
			if(!$mascota){
				$mascota = Core_App::getInstance()->getMascotaParam($mascota);
			}
			if($mascota){
				//agrego restricción de sexo
				if($mascota->getSexo()!='no se')
					$wheres[] = Db_Helper::in('ma_sexo', true, array('no se', $mascota->getSexo()));
		
				//agrego restricción de castrado
				if($mascota->getCastrado()!='no se')
					$wheres[] = Db_Helper::in('ma_castrado', true, array('no se', $mascota->getCastrado()));
			}
	
	//todo:descomentar esto, esta comentado solo para testeo
	//		//agrego restricción que no sea una mascota reportada por el mismo usuario
	//		$wheres[] = Db_Helper::equal('ma_id_dueno', $mascota->getIdDueno(), false);
		}

		
		//seteo las restricciones
		$perdida->setWhereByArray($wheres);
//		echo $perdida->searchGetSql();
//		die(__FILE__.__LINE__);
		if($as_objects==true)
			$as_objects = 'Saludmascotas_Model_View_MascotaPerdida';
		if($as_collection)
			return new Core_Collection($perdida->search(null, null, $limit, $start, $as_objects, $columns));
		return ($perdida->search(null, null, $limit, $start, $as_objects, $columns));
	}
	public function getCoincidenciasSeleccionadas($as_objects=true){
		$reencuentro = new Saludmascotas_Model_Reencuentro();
		$where = array();
		$where[] = Db_Helper::equal('id_encuentro', $this->getId());
		$where[] = Db_Helper::equal('iniciado_por','encuentro');
		$where[] = Db_Helper::equal('activo','si');
		$reencuentro->setWhereByArray($where);
		$reencuentros = $reencuentro->search();
		if(!$reencuentros)
			return null;
		$ids_perdidas = array();
		foreach($reencuentros as $reencuentro)
			$ids_perdidas[] = $reencuentro->getIdPerdida();
		if($as_objects=='ids')
			return $ids_perdidas;
		return $this->getCoincidencias($ids_perdidas);
	}
	public function getIdsCoincidenciasSeleccionadas(){
		return $this->getCoincidenciasSeleccionadas('ids');
	}
	public function getDbTableName() 
	{
		return 'sm_encuentro';
	}
}
?>