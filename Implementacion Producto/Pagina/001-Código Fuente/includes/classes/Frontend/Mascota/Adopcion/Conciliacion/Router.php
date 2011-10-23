<?php /*es útf8*/
class Frontend_Mascota_Adopcion_Conciliacion_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			'confirmaciones_pendientes'
			,'confirmar'
			,'entrege_mi_mascota'
			,'recibi_mascota'
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
		if($mascota->esEstadoAdopcionOferta()){
			$adopcion_oferta = $mascota->getAdopcionOfertaActual();
			$handle = 'adopcion_oferta';
			$adopcion_conciliacions = new Core_Collection($adopcion_oferta->getListAdopcionConciliacion());
			$adopcion_conciliacions = $adopcion_conciliacions->addFilterEq('activo','si')->groupBy('iniciado_por');
			$adopcion_conciliacions_seleccionados = $adopcion_conciliacions['adopcion_oferta'];
			$adopcion_conciliacions_reportados = $adopcion_conciliacions['adopcion_solicitud'];
		}
		else{//$mascota->esEstadoAdopcionSolicitud();
			$adopcion_solicitud = $mascota->getAdopcionSolicitudActual();
			//var_dump($adopcion_solicitud);die(__FILE__.__LINE__);
			$handle = 'adopcion_solicitud';
			$adopcion_conciliacions = new Core_Collection($adopcion_solicitud->getListAdopcionConciliacion());
			$adopcion_conciliacions = $adopcion_conciliacions->addFilterEq('activo','si')->groupBy('iniciado_por');
			$adopcion_conciliacions_seleccionados = $adopcion_conciliacions['adopcion_solicitud'];
			$adopcion_conciliacions_reportados = $adopcion_conciliacions['adopcion_oferta'];
			//var_dump($adopcion_conciliacions_seleccionados, $adopcion_conciliacions_reportados);die(__FILE__.__LINE__);
		}
		
		$adopcion_conciliacions = null;
		
		Core_App::getInstance()->setPagina($numero_pag);
		$this->setPageReference('AdopcionConciliacion', 'resolución');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('adopcion_conciliacion_confirmaciones_pendientes')
			->addAction('adopcion_conciliacion_confirmaciones_pendientes_'.$handle);
		;
		$this->showLeftMenu('usuario');
		
		
		//loaded layout
		$loaded_layout = Core_App::getLoadedLayout();
		$confirmaciones_pendientes = c($loaded_layout->getBlock('confirmaciones_pendientes'))
			->setMascota($mascota)
//			->setAdopcionConciliacions($adopcion_conciliacions)
		;
		$view_datos_mascota = c($loaded_layout->getBlock('view_datos_mascota'))
			->setMascota($mascota)
			->setPhotoList($fotos)
		;
		$adopcion_conciliacions_seleccionados = c($loaded_layout->getBlock('adopcion_conciliacions_seleccionados'))
			->setData('mascota_'.$handle, $mascota)
			->setAdopcionConciliacions($adopcion_conciliacions_seleccionados)
		;
		$adopcion_conciliacions_reportados = c($loaded_layout->getBlock('adopcion_conciliacions_reportados'))
			->setData('mascota_'.$handle, $mascota)
			->setAdopcionConciliacions($adopcion_conciliacions_reportados)
		;
		$this->setActiveLeftMenu('mascotas_usuario_mis_mascotas');
	}
	public function confirmar($id_mascota, $id_adopcion_conciliacion){
		$this->RedirectIfNotLoged();
		$mascota = $this->getHelper()->getMascota($id_mascota);
		if(!isset($mascota)){
			return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
		}
		$mascota->loadNonTableColumn();
		$fotos = $mascota->getListFoto();
		
		$adopcion_conciliacion = $this->getHelper()->getAdopcionConciliacion($id_adopcion_conciliacion);
		if(!isset($adopcion_conciliacion)){
			return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
		}

		$handle = '';
		if($mascota->esEstadoAdopcionOferta()){
			$adopcion_oferta = $this->getHelper()->getAdopcionOferta($mascota, $adopcion_conciliacion);
			if(!isset($adopcion_oferta)){
				return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
			}
			$handle = 'adopcion_oferta';
		}
		else{//$mascota->esEstadoAdopcionSolicitud();
			$adopcion_solicitud = $this->getHelper()->getAdopcionSolicitud($mascota, $adopcion_conciliacion);
			if(!isset($adopcion_solicitud)){
				return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
			}
			$handle = 'adopcion_solicitud';
		}
		
		if(Core_Http_Post::hasParameters()){
			$return = $this->confirmar_handle_post(Core_Http_Post::getParameters('Core_Object'), $mascota, $adopcion_conciliacion, $adopcion_oferta, $adopcion_solicitud);
			if(isset($return)){
				return $return;
			}
		}

		
		Core_App::getInstance()->setPagina($numero_pag);
		$this->setPageReference('AdopcionConciliacion', 'confirmar');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('adopcion_conciliacion_confirmar')
			->addAction('adopcion_conciliacion_confirmar_'.$handle);
		;
		$this->showLeftMenu('usuario');
		
		//loaded layout
		$loaded_layout = Core_App::getLoadedLayout();
		$view_datos_mascota = $loaded_layout->getBlock('view_datos_mascota')
			->setMascota($mascota)
		;
		if($handle=='adopcion_oferta'){
			$view_datos_mascota_adopcion_oferta = $loaded_layout->getBlock('view_datos_mascota')
				->setMascota($mascota)
				//->setPhotoList($fotos)
			;
			$adopcion_solicitud = $adopcion_conciliacion->getAdopcionSolicitud();
			$adopcion_solicitud_view = $loaded_layout->getBlock('adopcion_solicitud_view')
				->setAdopcionSolicitud($adopcion_solicitud)
				->setAdopcionConciliacion($adopcion_conciliacion)
			;
		}
		elseif($handle=='adopcion_solicitud'){
			$view_datos_mascota_adopcion_solicitud = $loaded_layout->getBlock('view_datos_mascota')
				->setMascota($mascota)
				//->setPhotoList($fotos)
			;
			$adopcion_oferta = $adopcion_conciliacion->getAdopcionOferta();
			$adopcion_oferta_view = $loaded_layout->getBlock('adopcion_oferta_view')
				->setAdopcionOferta($adopcion_oferta)
				->setAdopcionConciliacion($adopcion_conciliacion)
			;
		}
		$this->setActiveLeftMenu('mascotas_usuario_mis_mascotas');
	}
	private function confirmar_handle_post($post, $mascota, $adopcion_conciliacion, $adopcion_oferta=null, $adopcion_solicitud=null){
		//$baja_anuncio = $post->getBaja()?true:false;
		$baja_anuncio = true;
		$baja_mascota = true;
		if(isset($adopcion_oferta)){
			$result = $this->getHelper()->confirmarAdopcionConciliacionAdopcionOferta($baja_anuncio, $baja_mascota, $adopcion_conciliacion, $adopcion_oferta, $mascota);
			if($result){
				$this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
				return true;
			}
		}
		elseif(isset($adopcion_solicitud)){
			$result = $this->getHelper()->confirmarAdopcionConciliacionAdopcionSolicitud($baja_anuncio, $baja_mascota, $adopcion_conciliacion, $adopcion_solicitud, $mascota);
			if($result){
				$this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
				return true;
			}
		}
//		
//		var_dump($post->getBaja(), get_class($adopcion_oferta), get_class($adopcion_solicitud));
//		die(__FILE__.__LINE__);
	}
	protected function entrege_mi_mascota($id_mascota){
		return $this->finalizar_anuncio($id_mascota);
	}
	protected function recibi_mascota($id_mascota){
		return $this->finalizar_anuncio($id_mascota);
	}
	private function finalizar_anuncio($id_mascota){
		$this->RedirectIfNotLoged();
		$mascota = $this->getHelper()->getMascota($id_mascota);
		if(!isset($mascota)){
			return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
		}
		$mascota->loadNonTableColumn();
		$fotos = $mascota->getListFoto();
		
//		$adopcion_conciliacion = $this->getHelper()->getAdopcionConciliacion($id_adopcion_conciliacion);
//		if(!isset($adopcion_conciliacion)){
//			return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
//		}

		$handle = '';
		if($mascota->esEstadoAdopcionOferta()){
			$adopcion_oferta = $this->getHelper()->getAdopcionOferta($mascota/*, $adopcion_conciliacion*/);
			if(!isset($adopcion_oferta)){
				return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
			}
			$handle = 'adopcion_oferta';
		}
		else{//$mascota->esEstadoAdopcionSolicitud();
			$adopcion_solicitud = $this->getHelper()->getAdopcionSolicitud($mascota/*, $adopcion_conciliacion*/);
			if(!isset($adopcion_solicitud)){
				return $this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
			}
			$handle = 'adopcion_solicitud';
		}
		
		if(Core_Http_Post::hasParameters()){
			$return = $this->finalizar_anuncio_post(Core_Http_Post::getParameters('Core_Object'), $mascota/*, $adopcion_conciliacion*/, $adopcion_oferta, $adopcion_solicitud);
			if(isset($return)){
				return $return;
			}
		}

		
		Core_App::getInstance()->setPagina($numero_pag);
		$this->setPageReference('AdopcionConciliacion', 'confirmar');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('adopcion_conciliacion_finalizar')
			->addAction('adopcion_conciliacion_finalizar_'.$handle);
		;
		$this->showLeftMenu('usuario');
		
		//loaded layout
		$loaded_layout = Core_App::getLoadedLayout();
		$view_datos_mascota = $loaded_layout->getBlock('view_datos_mascota')
			->setMascota($mascota)
		;
		if($handle=='adopcion_oferta'){
			$view_datos_mascota_adopcion_oferta = $loaded_layout->getBlock('view_datos_mascota')
				->setMascota($mascota)
				//->setPhotoList($fotos)
			;
//			$adopcion_solicitud = $adopcion_conciliacion->getAdopcionSolicitud();
//			$adopcion_solicitud_view = $loaded_layout->getBlock('adopcion_solicitud_view')
//				->setAdopcionSolicitud($adopcion_solicitud)
//				->setAdopcionConciliacion($adopcion_conciliacion)
//			;
		}
		elseif($handle=='adopcion_solicitud'){
			$view_datos_mascota_adopcion_solicitud = $loaded_layout->getBlock('view_datos_mascota')
				->setMascota($mascota)
				//->setPhotoList($fotos)
			;
//			$adopcion_oferta = $adopcion_conciliacion->getAdopcionOferta();
//			$adopcion_oferta_view = $loaded_layout->getBlock('adopcion_oferta_view')
//				->setAdopcionOferta($adopcion_oferta)
//				->setAdopcionConciliacion($adopcion_conciliacion)
//			;
		}
		$this->setActiveLeftMenu('mascotas_usuario_mis_mascotas');
	}
	private function finalizar_anuncio_post($post, $mascota/*, $adopcion_conciliacion*/, $adopcion_oferta=null, $adopcion_solicitud=null){
		if(isset($adopcion_oferta)){
			//$baja_mascota = $post->getBaja()?true:false;
			$baja_mascota = true;
			$result = $this->getHelper()->finalizarAdopcionOferta($baja_mascota, $adopcion_oferta, $mascota);
			if($result){
				$this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
				return true;
			}
		}
		elseif(isset($adopcion_solicitud)){
			$result = $this->getHelper()->finalizarAdopcionSolicitud($adopcion_solicitud, $mascota);
			if($result){
				$this->Redirect(Frontend_Mascota_Helper::getUrlUsuario());
				return true;
			}
		}
//		die(__FILE__.__LINE__);
	} 
}
?>