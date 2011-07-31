<?
/**
 *@referencia Domicilio(id_domicilio) Saludmascotas_Model_Domicilio(id)
 *@referencia Mascota(id_mascota) Saludmascotas_Model_Mascota(id)
 *@referencia Usuario(id_usuario) Saludmascotas_Model_User(id)
*/
// *@listar Foto Saludmascotas_Model_FotoMascota
// *@listar Color Saludmascotas_Model_ColorMascota
// *@listar Localidad Saludmascotas_Model_Localidad
class Saludmascotas_Model_Perdida extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'activo'
			,'destacado'
			,'hora_extravio'
			,'descripcion'
			,'notificacion_email'
			,'republicar_automaticamente'
			,'quiere_destacar'
			,'mostrar_telefono'
			
			,'fecha_publicacion'
			,'fecha_expiracion'

			,'id_domicilio'
			,'id_mascota'
			,'id_usuario'
		);
		$this->addAutofilterFieldInput('hora_extravio', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('hora_extravio', array('Mysql_Helper','filterTimestampOutput'));
		$this->addAutofilterFieldInput('fecha_publicacion', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_publicacion', array('Mysql_Helper','filterTimestampOutput'));
		$this->addAutofilterFieldInput('fecha_expiracion', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_expiracion', array('Mysql_Helper','filterTimestampOutput'));
	}
	public function getCoincidencias($ids=null, $as_collection=true, $limit=null, $start=0, $as_objects=true, $columns=null){
		$mascota = $this->getMascota();
		$encuentro = new Saludmascotas_Model_View_MascotaEncuentro();
		$wheres = array();
		
		if(isset($ids)&&is_array($ids)&&count($ids)){
			$wheres[] = Db_Helper::in('en_id', true, $ids);
		}
		else{
			//agrego filtro de estado
			$wheres[] = Db_Helper::equal('en_activo','si');
			
			//agrego filtro de tiempo
			$hora_perdida = $this->getHoraExtravio(null, array());
			$wheres[] = Db_Helper::between('en_hora_encuentro', $hora_perdida, null, true);
			
			//agrego filtro de distancia
			//todo: agregar filtro de distancia, hacer un (barrio== or radio km<X or ciudad==)
			
			//agrego restricción de sexo
			$wheres[] = Db_Helper::in('ma_sexo', true, array('no se', $mascota->getSexo()));
	
			//agrego restricción de castrado
			$wheres[] = Db_Helper::in('ma_castrado', true, array('no se', $mascota->getCastrado()));
	
	//todo:descomentar esto, esta comentado solo para testeo
	//		//agrego restricción que no sea una mascota reportada por el mismo usuario
	//		$wheres[] = Db_Helper::equal('ma_id_dueno', $mascota->getIdDueno(), false);
		}

		
		//seteo las restricciones
		$encuentro->setWhereByArray($wheres);
//		echo $encuentro->searchGetSql();
//		die(__FILE__.__LINE__);
		if($as_objects==true)
			$as_objects = 'Saludmascotas_Model_View_MascotaEncuentro';
		if($as_collection)
			return new Core_Collection($encuentro->search(null, null, $limit, $start, $as_objects, $columns));
		return ($encuentro->search(null, null, $limit, $start, $as_objects, $columns));
	}
	public function getCoincidenciasSeleccionadas($as_objects=true){
		$reencuentro = new Saludmascotas_Model_Reencuentro();
		$where = array();
		$where[] = Db_Helper::equal('id_perdida', $this->getId());
		$where[] = Db_Helper::equal('iniciado_por','perdida');
		$reencuentro->setWhereByArray($where);
		$reencuentros = $reencuentro->search();
		if(!$reencuentros)
			return null;
		$ids_encuentros = array();
		foreach($reencuentros as $reencuentro)
			$ids_encuentros[] = $reencuentro->getIdEncuentro();
		if($as_objects=='ids')
			return $ids_encuentros;
		return $this->getCoincidencias($ids_encuentros);
	}
	public function getIdsCoincidenciasSeleccionadas(){
		return $this->getCoincidenciasSeleccionadas('ids');
	}
	public function getDbTableName() 
	{
		return 'sm_perdida';
	}
}
?>