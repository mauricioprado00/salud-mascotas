<?php /*es útf8*/
class Frontend_Mascota_Castracion_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			'set_para_castracion'
			,'pendientes'
			,'asignadas'
			,'realizadas'
			,'asignar'
			,'finalizar'
			,'confirmar'
		);

	}
	protected function set_para_castracion($id_mascota, $cancelar=false, $notificar_usuario=false){
		$helper = $this->getHelper();
		if(!$cancelar)
			$helper->setParaCastracion($id_mascota);
		else $helper->cancelarCastracion($id_mascota, $notificar_usuario);
		Core_Http_Header::Redirect(Core_Http_Header::getRequest('referer'));
		die();
	}
	protected function confirmar($id_mascota){
		$helper = $this->getHelper();
		$helper->actionConfirmarCastracion($id_mascota);
		Core_Http_Header::Redirect(Core_Http_Header::getRequest('referer'));
		die();
	}
	protected function pendientes($numero_pag=0){
		if($this->RedirectIfNotLoged())
			return true;
		if(Core_Http_Post::hasParameters()){
			$r = $this->asignar_handle_post($numero_pag);
			if($r)
				return;
		}
		Core_App::getInstance()->setPagina($numero_pag);
		$this->setPageReference('Castraciones', 'pendientes');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('mascota_castracion_pendientes')
		;
		$this->showLeftMenu('usuario');
		
		//loaded layout
//		Core_App::getLoadedLayout()
//			->getBlock('form_edit')//$x = $this->getObjectToEdit();
//			->setObjectToEdit($object_to_edit)
//		;
		$this->setActiveLeftMenu('mascotas_castraciones_pendientes');
	}
	protected function asignar_handle_post($numero_pag=0){
		$arr_post = Core_Http_Post::getParameters('Core_Object')->getData();
		if(!isset($arr_post['aceptar'])&&!isset($arr_post['finalizar'])){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Hubo un error en la asignación/finalización'), true);
			return;
		}
		if(isset($arr_post['aceptar'])){
			$idx = array_keys($arr_post['aceptar']);
			$idx = $idx[0];
			if(!isset($arr_post['id_mascota'][$idx], $arr_post['fecha_asignacion'][$idx], $arr_post['veterinaria_nombre'][$idx], $arr_post['veterinaria_id'][$idx])){
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Hubo un error en la asignación'), true);
				return;
			}
			$id_mascota = $arr_post['id_mascota'][$idx];
			$fecha_asignacion = $arr_post['fecha_asignacion'][$idx];
			$veterinaria_nombre = $arr_post['veterinaria_nombre'][$idx];
			$veterinaria_id = $arr_post['veterinaria_id'][$idx];
			$descripcion = $arr_post['descripcion'][$idx];
			$this->getHelper()->asignarCastracion($id_mascota, $fecha_asignacion, $veterinaria_nombre, $veterinaria_id, $descripcion);
		}
		elseif(isset($arr_post['finalizar'])){
			$idx = array_keys($arr_post['finalizar']);
			$idx = $idx[0];
//			var_dump($arr_post['id_mascota'][$idx], $arr_post['finalizar_resultado'][$idx]);
//			die(__FILE__.__LINE__);
			if(!isset($arr_post['id_mascota'][$idx], $arr_post['finalizar_resultado'][$idx])){
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Hubo un error en la finalización'), true);
				return;
			}
			$id_mascota = $arr_post['id_mascota'][$idx];
			$finalizar_resultado = $arr_post['finalizar_resultado'][$idx];
			$this->getHelper()->finalizarCastracion($id_mascota, $finalizar_resultado);
		}
		return;
	}
	protected function asignadas($numero_pag=0){
		if($this->RedirectIfNotLoged())
			return true;
		if(Core_Http_Post::hasParameters()){
			$r = $this->asignar_handle_post($numero_pag);
			if($r)
				return;
		}
		Core_App::getInstance()->setPagina($numero_pag);
		$this->setPageReference('Castraciones', 'asignadas');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('mascota_castracion_asignadas')
		;
		$this->showLeftMenu('usuario');
		
		//loaded layout
//		Core_App::getLoadedLayout()
//			->getBlock('form_edit')//$x = $this->getObjectToEdit();
//			->setObjectToEdit($object_to_edit)
//		;
		$this->setActiveLeftMenu('mascotas_castraciones_asignadas');
	}
	protected function realizadas($numero_pag=0){
		if($this->RedirectIfNotLoged())
			return true;
		if(Core_Http_Post::hasParameters()){
			$r = $this->asignar_handle_post($numero_pag);
			if($r)
				return;
		}
		Core_App::getInstance()->setPagina($numero_pag);
		$this->setPageReference('Castraciones', 'realizadas');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('mascota_castracion_realizadas')
		;
		$this->showLeftMenu('usuario');
		
		//loaded layout
//		Core_App::getLoadedLayout()
//			->getBlock('form_edit')//$x = $this->getObjectToEdit();
//			->setObjectToEdit($object_to_edit)
//		;
		$this->setActiveLeftMenu('mascotas_castraciones_realizadas');
	}
	public function asignar(){
		die(__FILE__.__LINE__);
	}
}
?>