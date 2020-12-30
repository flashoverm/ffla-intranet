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
	protected ?string $uuid;
	
	
	protected $timestamp;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $recipient;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $subject;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected int $state;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $body;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $error;
	
	/**
	 * @return mixed
	 */
	public function getUuid() : ?string {
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
	public function getRecipient() : ?string  {
		return $this->recipient;
	}

	/**
	 * @return mixed
	 */
	public function getSubject() : ?string  {
		return $this->subject;
	}

	/**
	 * @return mixed
	 */
	public function getState() : int {
		return $this->state;
	}

	/**
	 * @return mixed
	 */
	public function getBody() : ?string  {
		return $this->body;
	}

	/**
	 * @return mixed
	 */
	public function getError() : ?string  {
		return $this->error;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid(?string $uuid) {
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
	public function setRecipient(?string $recipient) {
		$this->recipient = $recipient;
	}

	/**
	 * @param mixed $subject
	 */
	public function setSubject(?string $subject) {
		$this->subject = $subject;
	}

	/**
	 * @param mixed $state
	 */
	public function setState(int $state) {
		$this->state = $state;
	}

	/**
	 * @param mixed $body
	 */
	public function setBody(?string $body) {
		$this->body = $body;
	}

	/**
	 * @param mixed $error
	 */
	public function setError(?string $error) {
		$this->error = $error;
	}
	
	
	
	function __construct() {
		parent::__construct();
		$this->uuid = NULL;
		$this->timestamp = NULL;
		$this->body = NULL;
		$this->error = NULL;
		$this->recipient = NULL;
		$this->state = 0;
		$this->subject = NULL;
	}
	
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public static function fromMail(?string $recipient, ?string $subject, int $state, ?string $body, ?string $error = NULL){
		$entry = new MailLog();
		$entry->setTimestamp(date('Y-m-d H:i:s'));
		$entry->setRecipient($recipient);
		$entry->setSubject($subject);
		$entry->setState($state);
		$entry->setBody($body);
		$entry->setError($error);
		return $entry;
	}

}