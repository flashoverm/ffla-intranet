<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="maillog")
 */
class MailLog extends BaseModel {
	
	const Sent = 1;
	const Failed = 2;
	const AttachmentError = 3;
	const MailConnectError = 4;
	const Deactivated = 5;
	const InvalidMailAddress = 6;
	
	const MAILLOG_STATES = array(
			
			1 => "Gesendet",
			2 => "Fehlgeschlagen",
			3 => "Anhang-Fehler",
			4 => "Server-Verbindungs-Fehler",
			5 => "Mailausgang deaktiviert",
			6 => "Ungültige E-Mail-Adresse",
	);
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected $uuid;
	
	
	protected $timestamp;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $recipient;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $subject;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected $state;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $body;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $error;
	
	
	function __construct() {
	}
	
	
	/**
	 * @return mixed
	 */
	public function getUuid() {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @return mixed
	 */
	public function getRecipient() {
		return $this->recipient;
	}

	/**
	 * @return mixed
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @return mixed
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @return mixed
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * @return mixed
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $timestamp
	 */
	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	/**
	 * @param mixed $recipient
	 */
	public function setRecipient($recipient) {
		$this->recipient = $recipient;
	}

	/**
	 * @param mixed $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * @param mixed $state
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @param mixed $body
	 */
	public function setBody($body) {
		$this->body = $body;
	}

	/**
	 * @param mixed $error
	 */
	public function setError($error) {
		$this->error = $error;
	}
	
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public static function fromMail($recipient, $subject, $state, $body, $error = NULL){
		$entry = new MailLog();
		$entry->setUuid(getGUID ());
		$entry->setTimestamp(date('Y-m-d H:i:s'));
		$entry->setRecipient($recipient);
		$entry->setSubject($subject);
		$entry->setState($state);
		$entry->setBody($body);
		$entry->setError($error);
		return $entry;
	}

}