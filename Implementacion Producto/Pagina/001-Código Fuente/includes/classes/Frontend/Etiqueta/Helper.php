<?php //es útf8
class Frontend_Etiqueta_Helper extends Frontend_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public function getUrlChangeEtiquetaMascota(){
		return 'etiqueta/change_etiqueta_mascota';
	}
	public function getUrlAgregarEtiquetaMascota($id_mascota){
		return 'etiqueta/agregar_etiqueta_mascota/'.$id_mascota;
	}
	public function getUrlEliminarEtiqueta($id_etiqueta){
		return 'etiqueta/eliminar_etiqueta/'.$id_etiqueta;
	}
	public function actionAgregarEtiquetaMascota($mascota, $nombre_etiqueta, $id_parent=null){
		$etiqueta = new Frontend_Model_Etiqueta();
		$etiqueta
			->setNombre($nombre_etiqueta)
		;
		$id_parent = $id_parent?$id_parent:null;
		if(!$etiqueta->validateFields()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la etiqueta"));
			Core_Helper::LoadValidationTranslation();
			//var_dump($mascota->getValidationMessages());
			if($etiqueta->getValidationMessages())
				foreach($etiqueta->getValidationMessages() as $key=>$messages){
					foreach($messages as $message){
						Core_App::getInstance()->addErrorMessage($message);
					}
				}
			return false;
		}
		$usuario = $this->getLogedUser();
		$etiqueta
			->setIdUsuario($usuario->getId())
			->setIdParent($id_parent)
		;
		
		if($etiqueta->insert()){
			$result = self::actionChangeEtiquetaMascota($etiqueta->getId(), $mascota->getId());
			if(!$result)
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la etiqueta"));
			return $result;
		}
		Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la etiqueta"));
		return false;
	}
	public function actionChangeEtiquetaMascota($id_etiqueta, $id_mascota){
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		if(!$mascota->load()){
//			die(__FILE__.__LINE__);
			return false;
		}
		$usuario = $this->getLogedUser();
		if($mascota->getIdDueno()!=$usuario->getId()){
//			die(__FILE__.__LINE__);
			return false;
		}
		$etiqueta = new Frontend_Model_Etiqueta();
		$etiqueta->setId($id_etiqueta);
		if(!$etiqueta->load()){
//			die(__FILE__.__LINE__);
			return false;
		}
		if($etiqueta->getIdUsuario()!=$usuario->getId()){
//			die(__FILE__.__LINE__);
			return false;
		}
		$etiqueta_mascota = new Frontend_Model_EtiquetaMascota();
		$etiqueta_mascota
			->setIdEtiqueta($id_etiqueta)
			->setIdMascota($id_mascota)
		;
		if(!$etiqueta_mascota->load(null, true, false)){
			if(!$etiqueta_mascota->insert()){
//				die(__FILE__.__LINE__);
				return false;
			}
			return true;
		}
		return $etiqueta_mascota->delete();
	}
}
?>