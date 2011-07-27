<?
class Admin_Prototype_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEditRaza','listar_raza','datalist_raza',
			'addEditEspecie','listar_especie','datalist_especie',
			'delete',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_prototype');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function addEditRaza($id_prototype=null){
		Core_App::getInstance()->clearLastErrorMessages();
		Core_App::getLayout()->addActions('entity_addedit', 'admin_prototype_addedit_raza');
		$layout = Core_App::getLoadedLayout();
		$objeto = new Core_Object();
		foreach($layout->getBlocks('raza_add_edit_form') as $block){
			$block->setIdToEdit($objeto->getId());
			$block->setObjectToEdit($objeto);
		}
	}
	protected function listar_raza(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_prototype_raza');
		$this->cambiarUrlAjax('administrator/prototype/listar_raza');
	}
	protected function datalist_raza(){
		Core_App::getLayout()->setActions(array());//reset
		Core_Http_Header::ContentType('text/xml');
		//Core_App::getLayout()->addActions('datalist', 'datalist_admin_prototype_raza');
		echo <<<aaa
<?xml version='1.0' encoding='utf-8'?>
<rows>
<page>1</page>
<total>1</total>
<records>2</records> 
	<row id="1"> 
		<cell special="ui"><![CDATA[
					<a href="#agencia/listar" onclick="getGrid(this).setSelection('1')"><div class="lstse" ></div></a>
					<a href="#agencia/addEdit/1"><div class="lsted"></div></a>
					<a href="#agencia/delete/1" onclick="getGrid(this).setSelection('1'); return confirm('Desea continuar eliminando la raza seleccionada?')"><div class="lstde"></div></a>
					]]></cell> 
		<cell><![CDATA[1]]></cell> 
		<cell><![CDATA[Coker]]></cell> 
		<cell><![CDATA[Perro]]></cell> 
	</row> 
	<row id="2"> 
		<cell special="ui"><![CDATA[
					<a href="#agencia/listar" onclick="getGrid(this).setSelection('2')"><div class="lstse" ></div></a>
					<a href="#agencia/addEdit/2"><div class="lsted"></div></a>
					<a href="#agencia/delete/2" onclick="getGrid(this).setSelection('2'); return confirm('Desea continuar eliminando el anuncio seleccionado?')"><div class="lstde"></div></a>
					]]></cell> 
		<cell><![CDATA[2]]></cell> 
		<cell><![CDATA[Siames]]></cell> 
		<cell><![CDATA[Gato]]></cell> 
	</row> 
</rows>
aaa;
		die();
	}
	protected function addEditEspecie($id_prototype=null){
		Core_App::getInstance()->clearLastErrorMessages();
		Core_App::getLayout()->addActions('entity_addedit', 'admin_prototype_addedit_especie');
		$layout = Core_App::getLoadedLayout();
		$objeto = new Core_Object();
		foreach($layout->getBlocks('especie_add_edit_form') as $block){
			$block->setIdToEdit($objeto->getId());
			$block->setObjectToEdit($objeto);
		}
	}
	protected function listar_especie(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_prototype_especie');
		$this->cambiarUrlAjax('administrator/prototype/listar_especie');
	}
	protected function datalist_especie(){
		Core_App::getLayout()->setActions(array());//reset
		Core_Http_Header::ContentType('text/xml');
		//Core_App::getLayout()->addActions('datalist', 'datalist_admin_prototype_especie');
		echo <<<aaa
<?xml version='1.0' encoding='utf-8'?>
<rows>
<page>1</page>
<total>1</total>
<records>2</records> 
	<row id="1"> 
		<cell special="ui"><![CDATA[
					<a href="#agencia/listar" onclick="getGrid(this).setSelection('1')"><div class="lstse" ></div></a>
					<a href="#agencia/addEdit/1"><div class="lsted"></div></a>
					<a href="#agencia/delete/1" onclick="getGrid(this).setSelection('1'); return confirm('Desea continuar eliminando la especie seleccionada?')"><div class="lstde"></div></a>
					]]></cell> 
		<cell><![CDATA[1]]></cell> 
		<cell><![CDATA[Perro]]></cell> 
		<cell><![CDATA[El perro, cuyo nombre científico es Canis lupus....]]></cell> 
	</row> 
	<row id="2"> 
		<cell special="ui"><![CDATA[
					<a href="#agencia/listar" onclick="getGrid(this).setSelection('2')"><div class="lstse" ></div></a>
					<a href="#agencia/addEdit/2"><div class="lsted"></div></a>
					<a href="#agencia/delete/2" onclick="getGrid(this).setSelection('2'); return confirm('Desea continuar eliminando el anuncio seleccionado?')"><div class="lstde"></div></a>
					]]></cell> 
		<cell><![CDATA[2]]></cell> 
		<cell><![CDATA[Gato]]></cell> 
		<cell><![CDATA[El gato o gato doméstico (Felis silvestris catus)....]]></cell> 
	</row> 
</rows>
aaa;
		die();
	}
	protected function dispatchNode(){
		return;
	}
}
?>