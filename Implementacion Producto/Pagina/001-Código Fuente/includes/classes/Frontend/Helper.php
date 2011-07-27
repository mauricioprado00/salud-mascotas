<?php
class Frontend_Helper extends Core_Helper{
	protected static function getLogedUser(){
		return Frontend_Usuario_Model_User::getLogedUser();
	}
	protected static function getUserSessionContext(){
		return array(__CLASS__);
	}
	protected static function setUserSessionVar($varname, $value, $contexts=null){
		if(!isset($contexts))
			$contexts = self::getUserSessionContext();
		$usuario = self::getLogedUser();
		return $usuario->setSessionVar($varname, $value, $contexts);
	}
	protected static function getUserSessionVar($varname, $contexts=null){
		if(!isset($contexts))
			$contexts = self::getUserSessionContext();
		$usuario = self::getLogedUser();
		return $usuario->getSessionVar($varname, $contexts);
	}
}
?>