<?php /*es útf8*/
class Frontend_Mascota_Encuentro_Listado_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			'pagina'
		);
	}
	protected function localDispatch($numero_pag=0){
		return $this->pagina($numero_pag);
	}
	private function getIdsDomiciliosEncuentros($restart=false){
		if($restart||$this->getSession()->getVar('mascotas_encuentros_listado_ids_domicilios')==null){
			$this->getIdsMascotasEncuentros($restart);
		}
		return $this->getSession()->getVar('mascotas_encuentros_listado_ids_domicilios');
	}
	private function getDomiciliosEncuentros($restart=false){
		if($restart||$this->getSession()->getVar('mascotas_encuentros_listado_domicilios')==null){
			$this->getIdsMascotasEncuentros($restart);
		}
		return $this->getSession()->getVar('mascotas_encuentros_listado_domicilios');
	}
	private function prepareDomiciliosEncuentros($current=null){
		$domicilios = $this->getDomiciliosEncuentros();
		if(!$domicilios)
			return array();
//		var_dump($domicilios);
//		die(__FILE__.__LINE__);
		$return = array();
		foreach($domicilios as $idx=>$domicilio){
			$dom = array(
				'id'=>$domicilio['id'],
				'lat'=>$domicilio['lat']/60,
				'lng'=>$domicilio['lng']/60,
			);
			if(isset($current)&&$idx==$current){
				$dom['current'] = true;
			}
			$return[] = $dom;
		}
		//die(__FILE__.__LINE__);
		return $return;
	}
	private function getIdsMascotasEncuentros($restart=false){
		if($restart||$this->getSession()->getVar('mascotas_encuentros_listado_ids_mascotas')==null){
			$mascota_encuentro = new Saludmascotas_Model_View_MascotaEncuentro();
			$mascotas_encuentros = $mascota_encuentro->search('rand()', 'ASC', null, 0, get_class($mascota_encuentro));
			$ids_mascotas_encuentros = array();
			$ids_domicilios = array();
			$domicilios = array();
			foreach($mascotas_encuentros as $mascota_encuentro){
				$ids_mascotas_encuentros[] = $mascota_encuentro->getMaId();
				$ids_domicilios[] = $mascota_encuentro->getDoId();
				$domicilios[] = $mascota_encuentro->getDomicilio()->getData();
			}
			$this->getSession()->setVar('mascotas_encuentros_listado_ids_mascotas', $ids_mascotas_encuentros);
			$this->getSession()->setVar('mascotas_encuentros_listado_ids_domicilios', $ids_domicilios);
			$this->getSession()->setVar('mascotas_encuentros_listado_domicilios', $domicilios);
		}
		return $this->getSession()->getVar('mascotas_encuentros_listado_ids_mascotas');
	}
	private function getPaginasVistas(){
		if(!$this->getSession()->getVar('mascotas_encuentros_listado_paginas_vistas')){
			return array();
		}
		return $this->getSession()->getVar('mascotas_encuentros_listado_paginas_vistas');
	}
	private function addPaginaVista($numero_pag){
		$paginas_vistas = $this->getPaginasVistas();
		if(!in_array($numero_pag, $paginas_vistas))
			$paginas_vistas[] = $numero_pag;
		$this->getSession()->setVar('mascotas_encuentros_listado_paginas_vistas', $paginas_vistas);
		return $this;
	}
	protected function pagina($numero_pag=0){
		$this->addPaginaVista($numero_pag);
		$ids_mascotas_encuentros = $this->getIdsMascotasEncuentros();
		$ids_domicilios_encuentros = $this->getIdsDomiciliosEncuentros();
//		var_dump($this->getSession()->getVar('algo'));
//		$this->getSession()->setVar('algo','yeeessss');
//		die(__FILE__.__LINE__);
		$id_mascota = $ids_mascotas_encuentros[$numero_pag];
		$id_domicilio = $ids_domicilios_encuentros[$numero_pag];
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		$mascota->load();
		$mascota->loadNonTableColumn();
		$fotos = $mascota->getListFoto();
		$domicilio = new Frontend_Model_Domicilio();
		$domicilio->setId($id_domicilio);
		$domicilio->load();
//		var_dump($id_domicilio,$ids_domicilios_encuentros,$domicilio->getData());
//		die(__FILE__.__LINE__);
		//var_dump($ids_domicilios_encuentros);
		Core_App::getInstance()->setPagina($numero_pag);
		$this->setPageReference('Mascotas', 'Encuentros');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('mascotas_encuentros')
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
			->setDomicilios($this->prepareDomiciliosEncuentros(intval($numero_pag)))
			->setDomiciliosVistos($this->getPaginasVistas())
		;
		$view_ubicacion = $loaded_layout->getBlock('view_ubicacion')
			->setDomicilio($domicilio)
		;
//			->getBlock('form_edit')//$x = $this->getObjectToEdit();
//			->setObjectToEdit($object_to_edit)
//		;
		$this->setActiveLeftMenu('dashboard_mascotas_encuentros');
	}

}
?>