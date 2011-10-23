<?
/**
 *@referencia Domicilio(id_domicilio) Saludmascotas_Model_Domicilio(id)
 *@referencia Mascota(id_mascota) Saludmascotas_Model_Mascota(id)
 *@referencia Usuario(id_usuario) Saludmascotas_Model_User(id)
 *@listar AdopcionConciliacion Saludmascotas_Model_AdopcionConciliacion
*/
// *@listar Foto Saludmascotas_Model_FotoMascota
// *@listar Color Saludmascotas_Model_ColorMascota
// *@listar Localidad Saludmascotas_Model_Localidad
class Saludmascotas_Model_AdopcionOferta extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'activo'
			,'destacado'
			//,'hora_extravio'
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
//		$this->addAutofilterFieldInput('hora_extravio', array('Mysql_Helper','filterTimestampInput'));
//		$this->addAutofilterFieldOutput('hora_extravio', array('Mysql_Helper','filterTimestampOutput'));
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
	public function getCoincidencias($ids=null, $as_collection=true, $limit=null, $start=0, $as_objects=true, $columns=null){
		$solicitud = new Saludmascotas_Model_View_MascotaAdopcionSolicitud();
		$wheres = array();
		
		if(isset($ids)&&is_array($ids)&&count($ids)){
			$wheres[] = Db_Helper::in('en_id', true, $ids);
		}
		else{
			//agrego filtro de estado
			$wheres[] = Db_Helper::equal('en_activo','si');
			
			//agrego filtro de tiempo
//			$hora_adopcion_oferta = $this->getHoraExtravio(null, array());
//			$wheres[] = Db_Helper::between('en_hora_adopcion_solicitud', $hora_adopcion_oferta, null, true);
			
			//agrego filtro de distancia
			//todo: agregar filtro de distancia, hacer un (barrio== or radio km<X or ciudad==)
			
			
			$mascota = Core_App::getInstance()->getMascotaParam($mascota);
			if(!$mascota){
				$mascota = $this->getMascota();
			}
			else Core_App::getInstance()->setMascotaParam($mascota);

			if($mascota){
				//agrego restricción de sexo
				if($mascota->getSexo()!='no se')
					$wheres[] = Db_Helper::in('ma_sexo', true, array('no importa', $mascota->getSexo()));
		
				//agrego restricción de castrado
				if($mascota->getCastrado()!='no se')
					$wheres[] = Db_Helper::in('ma_castrado', true, array('no importa', $mascota->getCastrado()));
				
				//agrego restricción de especie
//				var_dump($mascota->getIdEspecie());
//				die(__FILE__.__LINE__);
				if(!$mascota->getIdEspecie()){
					$raza = $mascota->getRaza();
					$especie = $raza->getEspecie();
					$id_especie = $especie->getId();
				}
				else $id_especie = $mascota->getIdEspecie();
				$raza = new Saludmascotas_Model_Raza();
				$raza->setWhere(
					Db_Helper::equal('id_especie', $id_especie)
				);
				$in_sql = $raza->searchGetSql(null,'ASC', null, 0, true, array('id'));
				$wheres[] = Db_Helper::custom('ma_id_raza IN('.$in_sql.')');
				
				//agrego restricción de fechas
				$fecha_nacimiento = $mascota->getFechaNacimiento(null,array());
				if(!$fecha_nacimiento)
					$fecha_nacimiento = null;
				else $fecha_nacimiento = date('Ymd', strtotime($fecha_nacimiento));
//				var_dump($fecha_nacimiento);
				if($fecha_nacimiento){
					$wheres[] = Db_Helper::between('ma_fecha_nacimiento', $fecha_nacimiento);
					$wheres[] = Db_Helper::between('ma_fecha_nacimiento_hasta', null, $fecha_nacimiento);
				}
//				var_dump($mascota->getData());
//				die(__FILE__.__LINE__);
			}

	
	//todo:descomentar esto, esta comentado solo para testeo
	//		//agrego restricción que no sea una mascota reportada por el mismo usuario
	//		$wheres[] = Db_Helper::equal('ma_id_dueno', $mascota->getIdDueno(), false);
		}

		
		//seteo las restricciones
		$solicitud->setWhereByArray($wheres);
		//echo $solicitud->searchGetSql();die(__FILE__.__LINE__);
		if($as_objects==true)
			$as_objects = 'Saludmascotas_Model_View_MascotaAdopcionSolicitud';
		if($as_collection)
			return new Core_Collection($solicitud->search(null, null, $limit, $start, $as_objects, $columns));
		return ($solicitud->search(null, null, $limit, $start, $as_objects, $columns));
	}
	public function getCoincidenciasSeleccionadas($as_objects=true){
		$adopcion_conciliacion = new Saludmascotas_Model_AdopcionConciliacion();
		$where = array();
		$where[] = Db_Helper::equal('id_adopcion_oferta', $this->getId());
		$where[] = Db_Helper::equal('iniciado_por','adopcion_oferta');
		$where[] = Db_Helper::equal('activo','si');
		$adopcion_conciliacion->setWhereByArray($where);
		$adopcion_conciliacions = $adopcion_conciliacion->search();
		//var_dump($adopcion_conciliacion->searchGetSql());die(__FILE__.__LINE__);		
		if(!$adopcion_conciliacions)
			return null;
		$ids_adopcion_solicituds = array();
		foreach($adopcion_conciliacions as $adopcion_conciliacion)
			$ids_adopcion_solicituds[] = $adopcion_conciliacion->getIdAdopcionSolicitud();
		if($as_objects=='ids')
			return $ids_adopcion_solicituds;
		return $this->getCoincidencias($ids_adopcion_solicituds);
	}
	public function getIdsCoincidenciasSeleccionadas(){
		return $this->getCoincidenciasSeleccionadas('ids');
	}
	public function getDbTableName() 
	{
		return 'sm_adopcion_oferta';
	}
}
?>