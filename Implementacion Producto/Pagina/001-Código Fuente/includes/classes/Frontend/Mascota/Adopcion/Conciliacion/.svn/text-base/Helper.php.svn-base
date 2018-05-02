<?php //es útf8
class Frontend_Mascota_Adopcion_Conciliacion_Helper extends Frontend_Mascota_Adopcion_Conciliacion_HelperAdopcionOferta{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public static function getUrlFinalizarAnuncioAdopcionOferta($id_mascota){
		return 'mascotas/adopcion/conciliacion/entrege_mi_mascota/'.$id_mascota;
	}
	public static function getUrlFinalizarAnuncioAdopcionSolicitud($id_mascota){
		return 'mascotas/adopcion/conciliacion/recibi_mascota/'.$id_mascota;
	}
	public static function getUrlConfirmacionesPendientes($id_mascota){
		return 'mascotas/adopcion/conciliacion/confirmaciones_pendientes/'.$id_mascota;
	}
	public static function getUrlConfirmar($id_mascota, $id_adopcion_conciliacion){
		return 'mascotas/adopcion/conciliacion/confirmar/' . $id_mascota . '/' . $id_adopcion_conciliacion;
	}
	public static function getMascota($id_mascota){
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		if(!$mascota->load()){
			Core_App::getInstance()->addErrorMessage("La mascota no existe", true);
			return null;
		}
		$usuario = self::getLogedUser();
		if($mascota->getIdDueno()!=$usuario->getId()){
			Core_App::getInstance()->addErrorMessage("La mascota que intentas editar no es tuya", true);
			return null;
		}
		if(!$mascota->esEstadoAdopcionSolicitud() && !$mascota->esEstadoAdopcionOferta()){
			Core_App::getInstance()->addErrorMessage("No hay adopcion_conciliacions por resolver, la mascota ya se encuentra en posesión de su dueño", true);
			return null;
		}
		return $mascota;
	}
	public static function getAdopcionConciliacion($id_adopcion_conciliacion){
		$adopcion_conciliacion = new Saludmascotas_Model_AdopcionConciliacion();
		$adopcion_conciliacion->setId($id_adopcion_conciliacion);
		if(!$adopcion_conciliacion->load()){
			Core_App::getInstance()->addErrorMessage("No existe el adopcion_conciliacion", true);
			return null;
		}
		return $adopcion_conciliacion;
	}
	public static function getAdopcionOferta($mascota, $adopcion_conciliacion=null){
		$adopcion_oferta = $mascota->getAdopcionOfertaActual();
		if(isset($adopcion_conciliacion)){
			if($adopcion_conciliacion->getIdAdopcionOferta()!=$adopcion_oferta->getId()){
				Core_App::getInstance()->addErrorMessage("Lo sentimos ha ocurrido un error el adopcion_conciliacion no coincide con tu mascota", true);
				return null;
			}
		}
		return $adopcion_oferta;
	}
	public static function getAdopcionSolicitud($mascota, $adopcion_conciliacion=null){
		$adopcion_solicitud = $mascota->getAdopcionSolicitudActual();
		if(isset($adopcion_conciliacion)){
			if($adopcion_conciliacion->getIdAdopcionSolicitud()!=$adopcion_solicitud->getId()){
				Core_App::getInstance()->addErrorMessage("Lo sentimos ha ocurrido un error el adopcion_conciliacion no coincide con tu mascota", true);
				return null;
			}
		}
		return $adopcion_solicitud;
	}
}
?>