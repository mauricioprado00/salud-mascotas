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
	private function getIdsDomiciliosPerdidas($restart=false){
		if($restart||$this->getSession()->getVar('mascotas_perdidas_listado_ids_domicilios')==null){
			$this->getIdsMascotasPerdidas($restart);
		}
		return $this->getSession()->getVar('mascotas_perdidas_listado_ids_domicilios');
	}
	private function getDomiciliosPerdidas($restart=false){
		if($restart||$this->getSession()->getVar('mascotas_perdidas_listado_domicilios')==null){
			$this->getIdsMascotasPerdidas($restart);
		}
		return $this->getSession()->getVar('mascotas_perdidas_listado_domicilios');
	}
	private function prepareDomiciliosPerdidas($current=null){
		$domicilios = $this->getDomiciliosPerdidas();
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
	private function getIdsMascotasPerdidas($restart=false){
		if($restart||$this->getSession()->getVar('mascotas_perdidas_listado_ids_mascotas')==null){
			$mascota_perdida = new Saludmascotas_Model_View_MascotaPerdida();
			$mascotas_perdidas = $mascota_perdida->search('rand()', 'ASC', null, 0, get_class($mascota_perdida));
			$ids_mascotas_perdidas = array();
			$ids_domicilios = array();
			$domicilios = array();
			foreach($mascotas_perdidas as $mascota_perdida){
				$ids_mascotas_perdidas[] = $mascota_perdida->getMaId();
				$ids_domicilios[] = $mascota_perdida->getDoId();
				$domicilios[] = $mascota_perdida->getDomicilio()->getData();
			}
			$this->getSession()->setVar('mascotas_perdidas_listado_ids_mascotas', $ids_mascotas_perdidas);
			$this->getSession()->setVar('mascotas_perdidas_listado_ids_domicilios', $ids_domicilios);
			$this->getSession()->setVar('mascotas_perdidas_listado_domicilios', $domicilios);
		}
		return $this->getSession()->getVar('mascotas_perdidas_listado_ids_mascotas');
	}
	private function getPaginasVistas(){
		if(!$this->getSession()->getVar('mascotas_perdidas_listado_paginas_vistas')){
			return array();
		}
		return $this->getSession()->getVar('mascotas_perdidas_listado_paginas_vistas');
	}
	private function addPaginaVista($numero_pag){
		$paginas_vistas = $this->getPaginasVistas();
		if(!in_array($numero_pag, $paginas_vistas))
			$paginas_vistas[] = $numero_pag;
		$this->getSession()->setVar('mascotas_perdidas_listado_paginas_vistas', $paginas_vistas);
		return $this;
	}
	protected function pagina($numero_pag=0){
		$this->addPaginaVista($numero_pag);
		$ids_mascotas_perdidas = $this->getIdsMascotasPerdidas();
		$ids_domicilios_perdidas = $this->getIdsDomiciliosPerdidas();
//		var_dump($this->getSession()->getVar('algo'));
//		$this->getSession()->setVar('algo','yeeessss');
//		die(__FILE__.__LINE__);
		$id_mascota = $ids_mascotas_perdidas[$numero_pag];
		$id_domicilio = $ids_domicilios_perdidas[$numero_pag];
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
			->setDomicilios($this->prepareDomiciliosPerdidas(intval($numero_pag)))
			->setDomiciliosVistos($this->getPaginasVistas())
		;
		$view_ubicacion = $loaded_layout->getBlock('view_ubicacion')
			->setDomicilio($domicilio)
		;
//			->getBlock('form_edit')//$x = $this->getObjectToEdit();
//			->setObjectToEdit($object_to_edit)
//		;
		$this->setActiveLeftMenu('dashboard_mascotas_perdidas');
	}

}
?>