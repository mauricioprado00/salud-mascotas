<?php

class Frontend_Block_Menu extends Core_Block_Template{
	private $_items;
	public function __construct(){
		parent::__construct();
		$this->_items = new Core_Collection();
		
		$this->setTemplate('page/menu.phtml');
	}
	public function addItem($name, $text, $link, $weight=0, $parent=null){
		if(isset($parent)&&$parent){
			
		}
		else{
			$parent = null;
		}
		$item = new Core_Object();
		$item
			->setName($name)
			->setText($text)
			->setLink($link)
			->setWeight($weight)
			->setParent($parent)
		;
		$this->_items->addItem($item, $name);
	}
	protected function getItems(){
		return $this->_items;
	}
	protected function getOrderedItems(){
		$parent_items = $this->_items->addFilterEq('parent', null);
		$parent_items = $parent_items->OrderBy('weight');
		$ordered = array();
		foreach($parent_items as $item){
			$ordered[] = $item;
			$child_items = $this->_items->addFilterEq('parent', $item->getName());
			$child_items = $child_items->OrderBy('weight');
			if(count($child_items)){
				$childrens = array();
				foreach($child_items as $child_item){
					$childrens[] = $child_item;
					if($child_item->getActive())
						$item->setActive(true);
				}
				$item->setChildrens($childrens);
			}
		}
		return $ordered;
	}
	public function setActive($name){
		$active = $this->_items->addFilterEq('active', true);
		foreach($active as $item)
			$item->setActive(null);
		$items_match = $this->_items->addFilterEq('name', $name);
		if($item = $items_match->getFirst()){
			$item->setActive(true);
		}
	}
}