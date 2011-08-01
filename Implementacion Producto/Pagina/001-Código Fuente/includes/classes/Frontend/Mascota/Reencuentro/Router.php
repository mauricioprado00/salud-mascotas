<?php /*es útf8*/
class Frontend_Mascota_Reencuentro_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			'confirmaciones_pendientes'
			,'confirmar'
		);
	}
	protected function confirmaciones_pendientes($id_mascota){
		$this->RedirectIfNotLoged();
		$mascota = $this->getHelper()->getMascota($id_mascota);
		if(!isset($mascota)){
			return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
		}
		$mascota->loadNonTableColumn();
		$fotos = $mascota->getListFoto();
		
		$handle = '';
		if($mascota->esEstadoPerdida()){
			$perdida = $mascota->getPerdidaActual();
			$handle = 'perdida';
			$reencuentros = new Core_Collection($perdida->getListReencuentro());
			$reencuentros = $reencuentros->addFilterEq('activo','si')->groupBy('iniciado_por');
			$reencuentros_seleccionados = $reencuentros['perdida'];
			$reencuentros_reportados = $reencuentros['encuentro'];
		}
		else{//$mascota->esEstadoEncuentro();
			$encuentro = $mascota->getEncuentroActual();
			$handle = 'encuentro';
			$reencuentros = new Core_Collection($encuentro->getListReencuentro());
			$reencuentros = $reencuentros->addFilterEq('activo','si')->groupBy('iniciado_por');
			$reencuentros_seleccionados = $reencuentros['encuentro'];
			$reencuentros_reportados = $reencuentros['perdida'];
		}
		
		$reencuentros = null;
		
		Core_App::getInstance()->setPagina($numero_pag);
		$this->setPageReference('Reencuentro', 'resolución');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('reencuentro_confirmaciones_pendientes')
			->addAction('reencuentro_confirmaciones_pendientes_'.$handle);
		;
		$this->showLeftMenu('usuario');
		
		
		//loaded layout
		$loaded_layout = Core_App::getLoadedLayout();
		$confirmaciones_pendientes = c($loaded_layout->getBlock('confirmaciones_pendientes'))
			->setMascota($mascota)
//			->setReencuentros($reencuentros)
		;
		$view_datos_mascota = c($loaded_layout->getBlock('view_datos_mascota'))
			->setMascota($mascota)
			->setPhotoList($fotos)
		;
		$reencuentros_seleccionados = c($loaded_layout->getBlock('reencuentros_seleccionados'))
			->setData('mascota_'.$handle, $mascota)
			->setReencuentros($reencuentros_seleccionados)
		;
		$reencuentros_reportados = c($loaded_layout->getBlock('reencuentros_reportados'))
			->setData('mascota_'.$handle, $mascota)
			->setReencuentros($reencuentros_reportados)
		;
		$this->setActiveLeftMenu('mascotas_usuario_mis_mascotas');
	}
	public function confirmar($id_mascota, $id_reencuentro){
		$this->RedirectIfNotLoged();
		$mascota = $this->getHelper()->getMascota($id_mascota);
		if(!isset($mascota)){
			return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
		}
		$mascota->loadNonTableColumn();
		$fotos = $mascota->getListFoto();
		
		$reencuentro = $this->getHelper()->getReencuentro($id_reencuentro);
		if(!isset($reencuentro)){
			return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
		}

		$handle = '';
		if($mascota->esEstadoPerdida()){
			$perdida = $this->getHelper()->getPerdida($mascota, $reencuentro);
			if(!isset($perdida)){
				return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
			}
			$handle = 'perdida';
		}
		else{//$mascota->esEstadoEncuentro();
			$encuentro = $this->getHelper()->getEncuentro($mascota, $reencuentro);
			if(!isset($encuentro)){
				return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
			}
			$handle = 'encuentro';
		}
		
		if(Core_Http_Post::hasParameters()){
			$return = $this->confirmar_handle_post(Core_Http_Post::getParameters('Core_Object'), $mascota, $reencuentro, $perdida, $encuentro);
			if(isset($return)){
				return $return;
			}
		}

		
		Core_App::getInstance()->setPagina($numero_pag);
		$this->setPageReference('Reencuentro', 'confirmar');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('reencuentro_confirmar')
			->addAction('reencuentro_confirmar_'.$handle);
		;
		$this->showLeftMenu('usuario');
		
		//loaded layout
		$loaded_layout = Core_App::getLoadedLayout();
		$view_datos_mascota = $loaded_layout->getBlock('view_datos_mascota')
			->setMascota($mascota)
		;
		if($handle=='perdida'){
			$view_datos_mascota_perdida = $loaded_layout->getBlock('view_datos_mascota')
				->setMascota($mascota)
				//->setPhotoList($fotos)
			;
			$encuentro = $reencuentro->getEncuentro();
			$encuentro_view = $loaded_layout->getBlock('encuentro_view')
				->setEncuentro($encuentro)
				->setReencuentro($reencuentro)
			;
		}
		elseif($handle=='encuentro'){
			$view_datos_mascota_encuentro = $loaded_layout->getBlock('view_datos_mascota')
				->setMascota($mascota)
				//->setPhotoList($fotos)
			;
			$perdida = $reencuentro->getPerdida();
			$perdida_view = $loaded_layout->getBlock('perdida_view')
				->setPerdida($perdida)
				->setReencuentro($reencuentro)
			;
		}
		$this->setActiveLeftMenu('mascotas_usuario_mis_mascotas');
	}
	private function confirmar_handle_post($post, $mascota, $reencuentro, $perdida=null, $encuentro=null){
		$baja_anuncio = $post->getBaja()?true:false;
		if(isset($perdida)){
			$result = $this->getHelper()->confirmarReencuentroPerdida($baja_anuncio, $reencuentro, $perdida, $mascota);
			if($result){
				$this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
				return true;
			}
		}
		elseif(isset($encuentro)){
			$result = $this->getHelper()->confirmarReencuentroEncuentro($baja_anuncio, $reencuentro, $encuentro, $mascota);
			if($result){
				$this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
				return true;
			}
		}
//		
//		var_dump($post->getBaja(), get_class($perdida), get_class($encuentro));
//		die(__FILE__.__LINE__);
	}
}
?>