<?
class Admin_Saludmascotas_Raza_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this->setEntityToList(new Saludmascotas_Model_Raza());
	}
}
?>