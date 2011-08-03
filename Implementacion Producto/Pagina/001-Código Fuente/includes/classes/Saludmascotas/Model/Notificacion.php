<?//es útf8
/**
 *@referencia Mascota(id_mascota) Saludmascotas_Model_Mascota(id)
 *@referencia Perdida(id_perdida) Saludmascotas_Model_Perdida(id)
 *@referencia Encuentro(id_encuentro) Saludmascotas_Model_Encuentro(id)
 *@referencia Reencuentro(id_reencuentro) Saludmascotas_Model_Reencuentro(id)
 *@referencia UsuarioFrom(id_usuario_from) Saludmascotas_Model_User(id)
 *@referencia UsuarioTo(id_usuario_to) Saludmascotas_Model_User(id)
*/
//*@listar Localidad Saludmascotas_Model_Localidad
class Saludmascotas_Model_Notificacion extends Core_Model_Abstract{
	private $mailer;
	public function init(){
		parent::init();
		$this->setNonTableColumn('mailer');
		$this->setTableColumn(
			'id'
			,'asunto_type'
			,'asunto'
			,'mensaje'
			,'hora'
			,'id_usuario_to'
			,'id_usuario_from'
			,'email_from'
			,'nombre_from'
			,'id_mascota'
			,'id_perdida'
			,'id_encuentro'
			,'id_reencuentro'
		);
		$this->addAutofilterFieldInput('hora', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('hora', array('Mysql_Helper','filterTimestampOutput'));
		$this->mailer = new Core_Mailer2();
	}
	public function getDbTableName() 
	{
		return 'sm_notificacion';
	}
	public function enviar($email_to=null, $nombre_to=null){
		$email_from = $this->mailer->From;
		$nombre_from = $this->mailer->FromName;
		if(!$email_from){
			$email_from = $this->getEmailFrom();
			$nombre_from = $this->getNombreFrom();
			if(!$email_from){
				$usuario_from = $this->getUsuarioFrom();
				if($usuario_from){
					$email_from = $usuario->getEmail();
					$nombre_from = $usuario->getNombre() . ' '. $usuario->getApellido();
				}
				else $this->fromSelf();
			}
		}
		if(!isset($email_to)){
			$usuario_to = $this->getUsuarioTo();
			if(!$usuario_to)
				return false;
			$email_to = $usuario_to->getEmail();
			$nombre_to = $usuario_to->getNombre().' '.$usuario_to->getApellido();
		}
		$this->AddAddress($email_to, $nombre_to);
		return $this->_enviar($this->getMensaje(), $this->getAsunto());
	}
	//metodos de email
	public function agregarNotificacion($tobbc=true, $tocc=false){
		$emails = Saludmascotas_Model_Config::findConfigValue('notificacion/bbc_emails','mauricio notificacion<mauricio.insis@gmail.com>');
		//$emails = array('mauricioprado00@gmail.com');
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
	private function _enviar($contenido, $subject=null, $altBody=null){
		return $this->Send(utf8_decode($contenido), utf8_decode($subject), $altBody);
	}
	//herencia  multiple de phpmailer
	public function setFrom($from, $fromname=null){
		$this->mailer->From = $from;
		if(isset($fromname)){
			$this->mailer->FromName = $fromname;
		}
	}
	public function __call($method, $args){
		if(method_exists($this->mailer, $method)){
			return call_user_func_array(array($this->mailer, $method), $args);
		}
		return parent::__call($method, $args);
	}
}
?>