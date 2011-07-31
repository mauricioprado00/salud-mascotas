<?
class Admin_Saludmascotas_Especie_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this->setEntityToList(new Saludmascotas_Model_Especie());
	}
}
?>