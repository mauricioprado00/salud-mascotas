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
class Saludmascotas_Model_AdopcionSolicitud extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'activo'
//			,'destacado'
//			,'hora_adopcion_solicitud'
			,'descripcion'
			,'notificacion_email'
			,'republicar_automaticamente'
//			,'quiere_destacar'
			,'mostrar_telefono'
//			,'tiene_mascota'
			,'estado_mascota'
			
			,'fecha_publicacion'
			,'fecha_expiracion'

			,'id_domicilio'
			,'id_mascota'
			,'id_usuario'
		);
//		$this->addAutofilterFieldInput('hora_adopcion_solicitud', array('Mysql_Helper','filterTimestampInput'));
//		$this->addAutofilterFieldOutput('hora_adopcion_solicitud', array('Mysql_Helper','filterTimestampOutput'));
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
		$adopcion_oferta = new Saludmascotas_Model_View_MascotaAdopcionOferta();
		$wheres = array();
		
		if(isset($ids)&&is_array($ids)&&count($ids)){
			$wheres[] = Db_Helper::in('pe_id', true, $ids);
		}
		else{
			//agrego filtro de estado
			$wheres[] = Db_Helper::equal('pe_activo','si');
			
			//agrego filtro de tiempo
//			$hora_adopcion_solicitud = $this->getHoraAdopcionSolicitud(null, array());
//			$wheres[] = Db_Helper::between('pe_hora_extravio', null, $hora_adopcion_solicitud, true);
			
			//agrego filtro de distancia
			//todo: agregar filtro de distancia, hacer un (barrio== or radio km<X or ciudad==)

			$mascota = Core_App::getInstance()->getMascotaParam($mascota);
			if(!$mascota){
				$mascota = $this->getMascota();
			}
			else Core_App::getInstance()->setMascotaParam($mascota);
			
			if($mascota){
				//agrego restricción de sexo
				if($mascota->getSexo()!='no importa')
					$wheres[] = Db_Helper::in('ma_sexo', true, array('no se', $mascota->getSexo()));
		
				//agrego restricción de castrado
				if($mascota->getCastrado()!='no importa')
					$wheres[] = Db_Helper::in('ma_castrado', true, array('no se', $mascota->getCastrado()));
				//agrego restricción de especie
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
				$fecha_nacimiento_hasta = $mascota->getFechaNacimientoHasta(null,array());
				$fecha_nacimiento = $mascota->getFechaNacimiento(null,array());
				if(!$fecha_nacimiento_hasta)
					$fecha_nacimiento_hasta = null;
				else $fecha_nacimiento_hasta = date('Ymd', strtotime($fecha_nacimiento_hasta));
				if(!$fecha_nacimiento)
					$fecha_nacimiento = null;
				else $fecha_nacimiento = date('Ymd', strtotime($fecha_nacimiento));
				if($fecha_nacimiento_hasta<$fecha_nacimiento){
					$x = $fecha_nacimiento;
					$fecha_nacimiento = $fecha_nacimiento_hasta;
					$fecha_nacimiento_hasta = $x;
				}
//				var_dump($fecha_nacimiento, $fecha_nacimiento_hasta);
				if($fecha_nacimiento||$fecha_nacimiento_hasta)
					$wheres[] = Db_Helper::between('ma_fecha_nacimiento', $fecha_nacimiento, $fecha_nacimiento_hasta);
//				var_dump($mascota->getData());
//				die(__FILE__.__LINE__);
			}
	
	//todo:descomentar esto, esta comentado solo para testeo
	//		//agrego restricción que no sea una mascota reportada por el mismo usuario
	//		$wheres[] = Db_Helper::equal('ma_id_dueno', $mascota->getIdDueno(), false);
		}

		
		//seteo las restricciones
		$adopcion_oferta->setWhereByArray($wheres);
		//echo $adopcion_oferta->searchGetSql();die(__FILE__.__LINE__);
		if($as_objects==true)
			$as_objects = 'Saludmascotas_Model_View_MascotaAdopcionOferta';
		if($as_collection)
			return new Core_Collection($adopcion_oferta->search(null, null, $limit, $start, $as_objects, $columns));
		return ($adopcion_oferta->search(null, null, $limit, $start, $as_objects, $columns));
	}
	public function getCoincidenciasSeleccionadas($as_objects=true){
		$adopcion_conciliacion = new Saludmascotas_Model_AdopcionConciliacion();
		$where = array();
		$where[] = Db_Helper::equal('id_adopcion_solicitud', $this->getId());
		$where[] = Db_Helper::equal('iniciado_por','adopcion_solicitud');
		$where[] = Db_Helper::equal('activo','si');
		$adopcion_conciliacion->setWhereByArray($where);
		$adopcion_conciliacions = $adopcion_conciliacion->search();
		if(!$adopcion_conciliacions)
			return null;
		$ids_adopcion_ofertas = array();
		foreach($adopcion_conciliacions as $adopcion_conciliacion)
			$ids_adopcion_ofertas[] = $adopcion_conciliacion->getIdAdopcionOferta();
		if($as_objects=='ids')
			return $ids_adopcion_ofertas;
		return $this->getCoincidencias($ids_adopcion_ofertas);
	}
	public function getIdsCoincidenciasSeleccionadas(){
		return $this->getCoincidenciasSeleccionadas('ids');
	}
	public function getDbTableName() 
	{
		return 'sm_adopcion_solicitud';
	}
}
?>