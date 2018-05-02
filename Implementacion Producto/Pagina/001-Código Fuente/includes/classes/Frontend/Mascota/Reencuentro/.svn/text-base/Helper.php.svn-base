<?php //es útf8
class Frontend_Mascota_Reencuentro_Helper extends Frontend_Mascota_Reencuentro_HelperPerdida{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public static function getUrlFinalizarAnuncioPerdida($id_mascota){
		return 'mascotas/reencuentro/encontre_mi_mascota/'.$id_mascota;
	}
	public static function getUrlFinalizarAnuncioEncuentro($id_mascota){
		return 'mascotas/reencuentro/encontre_su_dueno/'.$id_mascota;
	}
	public static function getUrlConfirmacionesPendientes($id_mascota){
		return 'mascotas/reencuentro/confirmaciones_pendientes/'.$id_mascota;
	}
	public static function getUrlConfirmar($id_mascota, $id_reencuentro){
		return 'mascotas/reencuentro/confirmar/' . $id_mascota . '/' . $id_reencuentro;
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
		if(!$mascota->esEstadoEncuentro() && !$mascota->esEstadoPerdida()){
			Core_App::getInstance()->addErrorMessage("No hay reencuentros por resolver, la mascota ya se encuentra en posesión de su dueño", true);
			return null;
		}
		return $mascota;
	}
	public static function getReencuentro($id_reencuentro){
		$reencuentro = new Saludmascotas_Model_Reencuentro();
		$reencuentro->setId($id_reencuentro);
		if(!$reencuentro->load()){
			Core_App::getInstance()->addErrorMessage("No existe el reencuentro", true);
			return null;
		}
		return $reencuentro;
	}
	public static function getPerdida($mascota, $reencuentro=null){
		$perdida = $mascota->getPerdidaActual();
		if(isset($reencuentro)){
			if($reencuentro->getIdPerdida()!=$perdida->getId()){
				Core_App::getInstance()->addErrorMessage("Lo sentimos ha ocurrido un error el reencuentro no coincide con tu mascota", true);
				return null;
			}
		}
		return $perdida;
	}
	public static function getEncuentro($mascota, $reencuentro=null){
		$encuentro = $mascota->getEncuentroActual();
		if(isset($reencuentro)){
			if($reencuentro->getIdEncuentro()!=$encuentro->getId()){
				Core_App::getInstance()->addErrorMessage("Lo sentimos ha ocurrido un error el reencuentro no coincide con tu mascota", true);
				return null;
			}
		}
		return $encuentro;
	}
}
?>