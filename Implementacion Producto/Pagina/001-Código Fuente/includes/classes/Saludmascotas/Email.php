<?
class Saludmascotas_Email extends Core_Email2{
	public function agregarNotificacion($tobbc=true, $tocc=false){
		//$emails = Reservas_Model_Config::findConfigValue('reservas_email/bbc_emails','mauricio<mauricio.insis@gmail.com>');
		$emails = array('mauricioprado00@gmail.com');
		return $this->AddAddresses($emails, $tobbc, $tocc);
	}
	public function fromSelf(){
//		$fromemail = Reservas_Model_Config::findConfigValue('reservas_email/email_send_from_email','info@reservas.com.ar');
//		$fromname = Reservas_Model_Config::findConfigValue('reservas_email/email_send_from_name','Reservas');
		$fromemail = 'mauricioprado00@gmail.com';
		$fromname = 'mauricio prado';
		$this->setFrom($fromemail, $fromname);
		//return $this->AddAddresses($emails, true);
	}
	public function enviar($subject=null, $altBody=null){
		return $this->Send($this->renderOutput(false), $subject, $altBody);
	}
}
?>