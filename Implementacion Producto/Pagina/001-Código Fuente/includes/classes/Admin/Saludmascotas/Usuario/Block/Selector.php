<?
class Admin_Saludmascotas_Usuario_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this->setEntityToList(new Saludmascotas_Model_User());
	}
}
?>