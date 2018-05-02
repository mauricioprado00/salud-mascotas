<?php
class Frontend_Block_RandomEncuentro extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this->setTemplate('dashboard/random_encuentro.phtml');
	}
	private $mascotas;
	public function getMascotas(){
		if(!isset($this->mascotas)){
			$max_items = $this->getMaxItems();
			if(!isset($max_items)||!$max_items)
				$max_items = 10;
			//die(__FILE__.__LINE__);
			$mascota_encuentro = new Saludmascotas_Model_View_MascotaEncuentro();
			$where = array();
			$where[] = Db_Helper::equal('en_activo','si');
			$where[] = Db_Helper::equal('ma_activa','si');
			$mascota_encuentro->setWhereByArray($where);
			$mascotas_encuentros = $mascota_encuentro->search('rand()', 'ASC', $max_items, 0, get_class($mascota_encuentro));
			$this->mascotas = array();
			foreach($mascotas_encuentros as $mascota_encuentro){
				$mascota = new Frontend_Model_Mascota();
				$this->mascotas[] = $mascota_encuentro->getMascota($mascota);
			}
		}
		return $this->mascotas;
	}
}