<?php
class Frontend_Block_RandomPerdida extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this->setTemplate('dashboard/random_perdida.phtml');
	}
	private $mascotas;
	public function getMascotas(){
		if(!isset($this->mascotas)){
			$max_items = $this->getMaxItems();
			if(!isset($max_items)||!$max_items)
				$max_items = 10;
			//die(__FILE__.__LINE__);
			$mascota_perdida = new Saludmascotas_Model_View_MascotaPerdida();
			$where = array();
			$where[] = Db_Helper::equal('pe_activo','si');
			$where[] = Db_Helper::equal('ma_activa','si');
			$mascota_perdida->setWhereByArray($where);
			$mascotas_perdidas = $mascota_perdida->search('rand()', 'ASC', $max_items, 0, get_class($mascota_perdida));
			$this->mascotas = array();
			foreach($mascotas_perdidas as $mascota_perdida){
				$mascota = new Frontend_Model_Mascota();
				$this->mascotas[] = $mascota_perdida->getMascota($mascota);
			}
		}
		return $this->mascotas;
	} 
}