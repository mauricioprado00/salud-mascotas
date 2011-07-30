<?
class Core_Page_Block_Html_Head_Script extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setBooleanData('inline_script')
			->setBooleanData('external')
			->setTemplate('page/html/head/script.phtml')
			->setCharset('ISO-8859-1')
			->setInlineContent(true)
			->setVersion(Core_App::getInstance()->getVersion())
		;
	}
    protected function _beforeToHtml()
    {
    	if(!$this->getInlineScript()){
    		$ic = $this->getDataStrtrConstants(null, 'inline_content');
			$this->setInlineContent($ic);
		}
        return $this;
    }
	public function getUid(){
		return md5($this->getCharset().$this->getInlineContent().$this->getVersion());
	}
}
?>