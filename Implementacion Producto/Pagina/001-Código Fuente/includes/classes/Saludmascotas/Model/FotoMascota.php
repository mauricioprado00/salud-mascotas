<?
/**
 *@referencia Mascota(id_mascota) Saludmascotas_Model_Mascota(id)
 *@referencia Usuario(id_usuario) Saludmascotas_Model_Usuario(id)
*/
class Saludmascotas_Model_FotoMascota extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setRuta(null)
			->setIdMascota(null)
			->setIdUsuario(null)
			->setFechaCarga(null)
		;
		$this->addAutofilterFieldInput('fecha_carga', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_carga', array('Mysql_Helper','filterTimestampOutput'));
	}
	public function getUrl(){
		return Core_App::getUrlModel()->getUrl(CONF_SUBPATH_UPLOADS.$this->getRuta());
	}
	public function getThumbUrl($max_width='72', $max_height='72'){
		$image = new Core_Image_Cache(CFG_PATH_ROOT.'/'.CONF_SUBPATH_UPLOADS.$this->getRuta(), $max_width, $max_height);
		return Core_App::getUrlModel()->getUrl($image->getLinkUrl());
	}
	public function getDbTableName() 
	{
		return 'sm_foto_mascota';
	}
}
?>