<?php /*es útf8*/
class Frontend_Mascota_Perdida_Listado_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			'pagina'
		);
	}
	protected function localDispatch($numero_pag=0){
		return $this->pagina($numero_pag);
	}
	protected function pagina($numero_pag=0){
		$ids_mascotas_perdidas = $this->getHelper()->getIdsMascotasPerdidas();
		$ids_domicilios_perdidas = $this->getHelper()->getIdsDomiciliosPerdidas();
		$id_mascota = $ids_mascotas_perdidas[$numero_pag];
		$id_domicilio = $ids_domicilios_perdidas[$numero_pag];
		if(Core_Http_Post::hasParameters()){
			$ret = $this->pagina_post(Core_Http_Post::getParameters('Core_Object'), $id_mascota);
			if(isset($ret))
				return $ret;
		}
		$this->getHelper()->addPaginaVista($numero_pag);
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		$mascota->load();
		$mascota->loadNonTableColumn();
		$fotos = $mascota->getListFoto();
		$domicilio = new Frontend_Model_Domicilio();
		$domicilio->setId($id_domicilio);
		$domicilio->load();
//		var_dump($id_domicilio,$ids_domicilios_perdidas,$domicilio->getData());
//		die(__FILE__.__LINE__);
		//var_dump($ids_domicilios_perdidas);
		Core_App::getInstance()->setPagina($numero_pag);
		$this->setPageReference('Mascotas', 'Perdidas');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('mascotas_perdidas')
		;
		$this->showLeftMenu('dashboard');
		
		//loaded layout
		$loaded_layout = Core_App::getLoadedLayout();
		$view_datos_mascota =$loaded_layout->getBlock('view_datos_mascota')
			->setMascota($mascota)
			->setPhotoList($fotos)
		;
//		var_dump($domicilio->getData());
//		die(__FILE__.__LINE__);
		$map_swicher = $loaded_layout->getBlock('map_swicher')
			->setBaseUrl(Core_App::getUrlModel()->getUrl($this->getHelper()->getUrl()))
			->setDomicilios($this->getHelper()->prepareDomiciliosPerdidas(intval($numero_pag)))
			->setDomiciliosVistos($this->getHelper()->getPaginasVistas())
		;
		$view_ubicacion = $loaded_layout->getBlock('view_ubicacion')
			->setDomicilio($domicilio)
		;
//			->getBlock('form_edit')//$x = $this->getObjectToEdit();
//			->setObjectToEdit($object_to_edit)
//		;
		$this->setActiveLeftMenu('dashboard_mascotas_perdidas');
	}
	protected function pagina_post($post, $id_mascota=0){
		if($post->hasData('no_la_vi')){
			$this->redirect($this->getHelper()->getUrl($numero_pag+1));
			return true;
		}
		if($post->hasData('notificar')){
			$reencuentro = new Frontend_Model_Reencuentro();
			$post = Core_Http_Post::getParameters('Core_Object', $this->getHelper()->getUpdatableFields());
			$reencuentro->loadFromArray($post->getData(), false);
//			header('content-type:text/plain');
//			var_dump($domicilio_mascota->getData());
//			die(__FILE__.__LINE__);
			$guardado = $this->getHelper()->actionCrearReencuentro($reencuentro, $id_mascota)?true:false;
			if($guardado){//pasa validaciones
				$this->redirect($this->getHelper()->getUrl($numero_pag+1));
				return true;
//				$return = $this->_editar_step_ok($paso, $id_mascota, $preserve_mascota_edicion);
//				if(isset($return))
//					return $return;
			}
		}
	}
}
?>