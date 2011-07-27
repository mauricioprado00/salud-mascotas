<?php
class Frontend_Helper extends Core_Helper{
	protected static function getLogedUser(){
		return Frontend_Usuario_Model_User::getLogedUser();
	}
}
?>