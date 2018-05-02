<?//es útf8
/**
 *@referencia Domicilio(id_domicilio) Saludmascotas_Model_Domicilio(id)
 *@referencia Dueno(id_dueno) Saludmascotas_Model_User(id)
 *@referencia EstadoMascota(id_estadomascota) Saludmascotas_Model_EstadoMascota(id)
 *@referencia LongitudPelaje(id_longitud_pelaje) Saludmascotas_Model_LongitudPelaje(id)
 *@referencia Manto(id_manto) Saludmascotas_Model_Manto(id)
 *@referencia Raza(id_raza) Saludmascotas_Model_Raza(id)
 *@listar Foto Saludmascotas_Model_FotoMascota
 *@listar Color Saludmascotas_Model_ColorMascota
 *@listar Perdida Saludmascotas_Model_Perdida
 *@listar Encuentro Saludmascotas_Model_Encuentro
 *@listar AdopcionOferta Saludmascotas_Model_AdopcionOferta
 *@listar AdopcionSolicitud Saludmascotas_Model_AdopcionSolicitud
 *@listar Castracion Saludmascotas_Model_Castracion
 *@listar EtiquetaMascota Saludmascotas_Model_EtiquetaMascota
*/
//*@listar Localidad Saludmascotas_Model_Localidad
class Saludmascotas_Model_Mascota extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'activa'
			,'castrado'
			,'descripcion'
			,'destacada'
			,'entrenada'
			,'fecha_nacimiento'
			,'fecha_nacimiento_hasta'
			,'nombre'
			,'para_adoptar'
			,'para_cruza'
			,'para_venta'
			,'pedigree'
			,'quiere_destacar'
			,'sexo'
			,'tamano'
			,'id_domicilio'
			,'id_dueno'
			,'id_estadomascota'
			,'id_longitud_pelaje'
			,'id_manto'
			,'id_raza'
			,'estado_adopcion'
			,'estado_castracion'
		);
		$this->addAutofilterFieldInput('fecha_nacimiento', array('Mysql_Helper','filterDateInput'));
		$this->addAutofilterFieldOutput('fecha_nacimiento', array('Mysql_Helper','filterDateOutput'));
		$this->addAutofilterFieldInput('fecha_nacimiento_hasta', array('Mysql_Helper','filterDateInput'));
		$this->addAutofilterFieldOutput('fecha_nacimiento_hasta', array('Mysql_Helper','filterDateOutput'));
	}
	public function getEstadoFull(){
		$estado = array();
		if($this->esEstadoConDueno()){
			if($this->esParaVenta()){
				$estado[] = 'Para venta';
			}
			elseif($this->esParaAdoptar()){
				$estado[] = 'Para adopción';
			}
			if($this->esParaCruza()){
				$estado[] = 'Para cruza';
			}
			if(!count($estado)){
				$estado[] = $this->getEstadoMascota()->getNombre();
			}
		}
		elseif($this->esEstadoNoAplica()){
			if($this->esEstadoAdopcionSolicitud()){
				$estado[] = 'Quiero adoptar';
			}
		}
		else{
			$estado[] = $this->getEstadoMascota()->getNombre();
		}
		return implode(', ', $estado);
	}
	public function setEstadoAdopcionOferta(){
		return $this->setEstadoAdopcion('oferta');
	}
	public function setEstadoAdopcionSolicitud(){
		return $this->setEstadoAdopcion('solicitud');
	}
	public function setEstadoAdopcionNo(){
		return $this->setEstadoAdopcion('no');
	}
	public function setEstadoCastracionNo(){
		return $this->setEstadoCastracion('no');
	}
	public function setEstadoCastracionSolicitada(){
		return $this->setEstadoCastracion('solicitada');
	}
	public function setEstadoCastracionAsignada(){
		return $this->setEstadoCastracion('asignada');
	}
	public function setEstadoCastracionRealizada(){
		return $this->setEstadoCastracion('realizada');
	}
	public function setEstadoCastracionCancelada(){
		return $this->setEstadoCastracion('cancelada');
	}
	public function esEstadoAdopcionOferta(){
		return $this->getEstadoAdopcion()=='oferta';
	}
	public function esEstadoAdopcionSolicitud(){
		return $this->getEstadoAdopcion()=='solicitud';
	}
	public function noEsEstadoAdopcion(){
		return $this->getEstadoAdopcion()=='no';
	}
	public function puedeCrearCastracion(){
		return $this->getCastrado()!='si'
			&&!$this->esEstadoCastracionAsingada()
			&&!$this->esEstadoCastracionSolicitada()
			&&!$this->esEstadoCastracionRealizada()
		;
	}
	public function puedeConfirmarCastracion(){
		if($this->esEstadoCastracionAsingada()){
			//var_dump($this->getData());
			$castracion = $this->getCastracionActual();
			if(time()>=$castracion->getFechaAsignacion()){
				return true;
			}
		}
		return false;
	}
	public function puedeCancelarCastracion(){
		return $this->esEstadoCastracionAsingada()||$this->esEstadoCastracionSolicitada();
	}
	public function puedeAsignarCastracion(){
		return $this->esEstadoCastracionSolicitada();
	}
	public function puedeReasignarCastracion(){
		return $this->esEstadoCastracionAsingada()||$this->esEstadoCastracionSolicitada();
	}
	public function esEstadoCastracionSolicitada(){
		return $this->getEstadoCastracion()=='solicitada';
	}
	public function esEstadoCastracionAsingada(){
		return $this->getEstadoCastracion()=='asignada';
	}
	public function esEstadoCastracionRealizada(){
		return $this->getEstadoCastracion()=='realizada';
	}
	public function esEstadoCastracionCancelada(){
		return $this->getEstadoCastracion()=='cancelada';
	}
	public function noEsEstadoCastracion(){
		return $this->getEstadoCastracion()=='no';
	}
	public function setActiva($set=true){
		$this->setData('activa', $set?'si':'no');
		return $this;
	}
	public function esActiva(){
		return $this->getActiva()=='si';
	}
	public function setParaCruza($set=true){
		$this->setData('para_cruza', $set?'si':'no');
		return $this;
	}
	public function setParaAdoptar($set=true){
		$this->setData('para_adoptar', $set?'si':'no');
		return $this;
	}
	public function setParaVenta($set=true){
		$this->setData('para_venta', $set?'si':'no');
		return $this;
	}
	public function setPedigree($set=true){
		$this->setData('pedigree', $set?'si':'no');
		return $this;
	}
	public function setQuiereDestacar($set=true){
		$this->setData('quiere_destacar', $set?'si':'no');
		return $this;
	}
	public function setDestacado($set=true){
		$this->setData('destacado', $set?'si':'no');
		return $this;
	}
	public function esParaAdoptar(){
		return $this->getParaAdoptar()=='si';
	}
	public function esParaVenta(){
		return $this->getParaVenta()=='si';
	}
	public function esParaCruza(){
		return $this->getParaCruza()=='si';
	}
	public function esPedigree(){
		return $this->getPedigree()=='si';
	}
	public function esQuiereDestacar(){
		return $this->getQuiereDestacar()=='si';
	}
	public function esDestacado(){
		return $this->getDestacado()=='si';
	}
	public function getLinkUrlImage($max_width=null, $max_height=null){
		$fotos = $this->getListFoto();
		if(!$fotos){
			$foto = new Saludmascotas_Model_FotoMascota();
			$sp = Core_App::getLayout()->getSkinPath('img/nophoto.png');
			if(!$sp)
				return null;
			$foto->setRuta($sp);
			$image = new Core_Image_Cache($sp, $max_width, $max_height);
			return ($image->getLinkUrl());
		}
		else $foto = $fotos[0];
		if(!isset($max_width)&&!isset($max_height)){
			return $foto->getLinkUrl();
		}
		return $foto->getThumbLinkUrl($max_width, $max_height);
	}
	public function getFileImage($max_width=null, $max_height=null){
		return CFG_PATH_ROOT . '/' . $this->getLinkUrlImage($max_width, $max_height);
	}
	public function getSizeImage($max_width=null, $max_height=null){
		return getimagesize($this->getFileImage($max_width, $max_height));
	}
	public function getUrlImage($max_width=null, $max_height=null){
		return Core_App::getUrlModel()->getUrl($this->getLinkUrlImage($max_width, $max_height));
//		$fotos = $this->getListFoto();
//		if(!$fotos){
//			$foto = new Saludmascotas_Model_FotoMascota();
//			$sp = Core_App::getLayout()->getSkinPath('img/nophoto.png');
//			if(!$sp)
//				return null;
//			$foto->setRuta($sp);
//			$image = new Core_Image_Cache($sp, $max_width, $max_height);
//			return Core_App::getUrlModel()->getUrl($image->getLinkUrl());
//		}
//		else $foto = $fotos[0];
//		if(!isset($max_width)&&!isset($max_height)){
//			return $foto->getUrl();
//		}
//		return $foto->getThumbUrl($max_width, $max_height);
	}
	public function setEstadoByName($nombre_estado_mascota){
		if(!($estado_mascota = Saludmascotas_Model_EstadoMascota::getEstadoByName($nombre_estado_mascota)))
			return false;
		$this->setIdEstadomascota($estado_mascota->getId());
		return true;
	}
	protected function esEstadoByName($nombre_estado_mascota){
		if(!($estado_mascota = Saludmascotas_Model_EstadoMascota::getEstadoByName($nombre_estado_mascota)))
			return false;
		return $this->getIdEstadomascota()==$estado_mascota->getId();
	}
	public function setEstadoConDueno(){
		return $this->setEstadoByName('con dueno');
	}
	public function setEstadoPerdida(){
		return $this->setEstadoByName('perdida');
	}
	public function setEstadoEliminada(){
		return $this->setEstadoByName('eliminada');
	}
	public function setEstadoEnGuarda(){
		return $this->setEstadoByName('en guarda');
	}
	public function setEstadoVista(){
		return $this->setEstadoByName('vista');
	}
	public function setEstadoNoAplica(){
		return $this->setEstadoByName('na');
	}
	public function esEstadoConDueno(){
		return $this->esEstadoByName('con dueno');
	}
	public function esEstadoPerdida(){
		return $this->esEstadoByName('perdida');
	}
	public function esEstadoEliminada(){
		return $this->esEstadoByName('eliminada');
	}
	public function esEstadoEnGuarda(){
		return $this->esEstadoByName('en guarda');
	}
	public function esEstadoVista(){
		return $this->esEstadoByName('vista');
	}
	public function esEstadoNoAplica(){
		return $this->esEstadoByName('na');
	}
	public function esEstadoEncuentro(){
		return $this->esEstadoVista()||$this->esEstadoEnGuarda();
	}
	protected function setRazaByName($nombre_raza, $id_especie, $create_if_needed=false){
		$raza = new Saludmascotas_Model_Raza();
		$raza
			->setNombre($nombre_raza)
			->setIdEspecie($id_especie)
		;
		if(!$raza->load()){
			if($create_if_needed){
				$raza = new Saludmascotas_Model_Raza();
				$raza->setNombre($nombre_raza);
				$raza->setIdEspecie($id_especie);
				$raza->setDescripcion('');
				if(!$raza->insert()){
					return false;
				}
			}
			else
				return false;
		}
		$this->setIdRaza($raza->getId());
		return true;
	}
	public function getColoresAgregadosAsCollection(){
		$colores = $this->getListColor();
		if(!$colores)
			return null;
		$collection = new Core_Collection();
		foreach($colores as $color_mascota){
			$color = $color_mascota->getColor();
			$collection->addItem($color);
		}
		return $collection;
	}
	public function calcularEdad(){
		$fecha_nacimiento = $this->getFechaNacimiento();
		if(!$fecha_nacimiento)
			return null;
		list($dia_nacimiento, $mes_nacimiento, $anio_nacimiento) = explode('/', $fecha_nacimiento);
		$fecha_actual = date('d/m/Y');
		list($dia_actual, $mes_actual, $anio_actual) = explode('/', $fecha_actual);
		
		return Core_Helper::calculateAgeFormatted($dia_nacimiento, $mes_nacimiento, $anio_nacimiento, $dia_actual, $mes_actual, $anio_actual);
	}
	public function calcularEdadHasta(){
		$fecha_nacimiento = $this->getFechaNacimientoHasta();
		if(!$fecha_nacimiento)
			return null;
		list($dia_nacimiento, $mes_nacimiento, $anio_nacimiento) = explode('/', $fecha_nacimiento);
		$fecha_actual = date('d/m/Y');
		list($dia_actual, $mes_actual, $anio_actual) = explode('/', $fecha_actual);
		
		return Core_Helper::calculateAgeFormatted($dia_nacimiento, $mes_nacimiento, $anio_nacimiento, $dia_actual, $mes_actual, $anio_actual);
	}
	public function smartGetDomicilio(){
		if($this->esEstadoPerdida()){
			$perdida = $this->getPerdidaActual(false);
			if(!$perdida){
				return null;
			}
			return $perdida->getDomicilio();
		}
		if($this->esEstadoEncuentro()){
			$encuentro = $this->getEncuentroActual(false);
			if(!$encuentro){
				return null;
			}
			return $encuentro->getDomicilio();
		}
		return $this->getDomicilio();
	}
	public function getEncuentroActual($load_nontabledata=true){
		$encuentros = $this->getListEncuentro();
		if(!$encuentros)
			return null;
		$encuentros = new Core_Collection($encuentros);
		$encuentros = $encuentros->addFilterEq('activo', 'si');
		if(!$encuentros->count())
			return null;
		$encuentro = $encuentros->getFirst();
		if($load_nontabledata)
			$encuentro->loadNonTableColumn();
		return $encuentro;
	}
	public function getPerdidaActual($load_nontabledata=true){
		$perdidas = $this->getListPerdida();
		if(!$perdidas)
			return null;
		$perdidas = new Core_Collection($perdidas);
		$perdidas = $perdidas->addFilterEq('activo', 'si');
		if(!$perdidas->count())
			return null;
		$perdida = $perdidas->getFirst();
		if($load_nontabledata)
			$perdida->loadNonTableColumn();
		return $perdida;
	}
	public function getAdopcionSolicitudActual($load_nontabledata=true){
		$adopcion_solicituds = $this->getListAdopcionSolicitud();
		if(!$adopcion_solicituds)
			return null;
		$adopcion_solicituds = new Core_Collection($adopcion_solicituds);
		$adopcion_solicituds = $adopcion_solicituds->addFilterEq('activo', 'si');
		if(!$adopcion_solicituds->count())
			return null;
		$adopcion_solicitud = $adopcion_solicituds->getFirst();
		if($load_nontabledata)
			$adopcion_solicitud->loadNonTableColumn();
		return $adopcion_solicitud;
	}
	public function getAdopcionOfertaActual($load_nontabledata=true){
		$adopcion_ofertas = $this->getListAdopcionOferta();
		if(!$adopcion_ofertas)
			return null;
		$adopcion_ofertas = new Core_Collection($adopcion_ofertas);
		$adopcion_ofertas = $adopcion_ofertas->addFilterEq('activo', 'si');
		if(!$adopcion_ofertas->count())
			return null;
		$adopcion_oferta = $adopcion_ofertas->getFirst();
		if($load_nontabledata)
			$adopcion_oferta->loadNonTableColumn();
		return $adopcion_oferta;
	}
	public function getCastracionActual($load_nontabledata=true){
		$castraciones = $this->getListCastracion();
		if(!$castraciones)
			return null;
		$castraciones = new Core_Collection($castraciones);
		$castraciones = $castraciones->addFilterEq('activo', 'si');
		if(!$castraciones->count())
			return null;
		$castracion = $castraciones->getFirst();
//		if($load_nontabledata)
//			$castracion->loadNonTableColumn();
		return $castracion;
	}
	public function hasReencuentros($iniciado_por=null){
		
		$wheres = array(Db_Helper::equal('ma_id'));
		if(isset($iniciado_por)){
			$wheres[] = Db_Helper::equal('re_iniciado_por', $iniciado_por);
			$wheres[] = Db_Helper::equal('re_activo','si');
		}
		$reencuentro = c(new Saludmascotas_Model_View_MascotaReencuentro())
			->setMaId($this->getId())
			->setWhereByArray($wheres)
		;
		return $reencuentro->searchCount();
	}
	public function hasAdopcionConciliacion($iniciado_por=null){
		
		$wheres = array(Db_Helper::equal('ma_id'));
		if(isset($iniciado_por)){
			$wheres[] = Db_Helper::equal('re_iniciado_por', $iniciado_por);
			$wheres[] = Db_Helper::equal('re_activo','si');
		}
		$adopcion_conciliacion = c(new Saludmascotas_Model_View_MascotaAdopcionConciliacion())
			->setMaId($this->getId())
			->setWhereByArray($wheres)
		;
		return $adopcion_conciliacion->searchCount();
	}
	public function getReencuentros($iniciado_por=null){
		$wheres = array(Db_Helper::equal('ma_id'));
		if(isset($iniciado_por)){
			$wheres[] = Db_Helper::equal('re_iniciado_por', $iniciado_por);
			$wheres[] = Db_Helper::equal('re_activo','si');
		}
		$reencuentro = c(new Saludmascotas_Model_View_MascotaReencuentro())
			->setMaId($this->getId())
			->setWhereByArray($wheres)
		;
		return $reencuentro->searchCount();
	}
	public function esEtiquetaMascota($etiqueta, $match_childs=false){
		$etiquetas_mascotas = $this->getListEtiquetaMascota();
		$collection = new Core_Collection($etiquetas_mascotas);
		$collection = $collection->addFilterEq('id_etiqueta', $etiqueta->getId());
		if($collection && $collection->count()){
			return true;
		}
		if(!$match_childs)
			return false;
		foreach($etiquetas_mascotas as $item){
			$et = new Saludmascotas_Model_Etiqueta();
			$et->setId($item->getIdEtiqueta());
			$et->load();
			//$et->setData($item->getData());
			if($et->isInParents($etiqueta)){
				return true;
			}
		}
	}
	public function getTamanoLabel($tamano){
		switch($tamano){
			case '';
		}
	}
	public function getDbTableName() 
	{
		return 'sm_mascota';
	}
}
?>