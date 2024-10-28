<?php

class MailQueueElement extends BaseModel {
    
    protected ?string $uuid;
    
    protected $timestamp;
    
    protected $retries;
    
    protected ?string $recipient;

    protected ?string $subject;
    
    protected ?string $body;
    
    public function getUuid() : ?string {
        return $this->uuid;
    }
    
    public function getTimestamp() {
        return $this->timestamp;
    }
    
    public function getRetries() {
        return $this->retries;
    }

    public function getRecipient() : ?string  {
        return $this->recipient;
    }
    
    public function getSubject() : ?string  {
        return $this->subject;
    }

    public function getBody() : ?string  {
        return $this->body;
    }

    
    public function setUuid(?string $uuid) {
        $this->uuid = $uuid;
    }
    
    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }
    
    public function setRetries($retries) {
        $this->retries = $retries;
    }
    
    public function setRecipient(?string $recipient) {
        $this->recipient = $recipient;
    }
    
    public function setSubject(?string $subject) {
        $this->subject = $subject;
    }

    public function setBody(?string $body) {
        $this->body = $body;
    }
    
    function __construct() {
        $this->uuid = NULL;
        $this->timestamp = NULL;
        $this->retries = 0;
        $this->body = NULL;
        $this->recipient = NULL;
        $this->subject = NULL;
    }
    
    public static function fromMail(?string $recipient, ?string $subject, ?string $body){
        $mail = new MailQueueElement();
        $mail->setTimestamp(date('Y-m-d H:i:s'));
        $mail->setRecipient($recipient);
        $mail->setSubject($subject);
        $mail->setBody($body);
        return $mail;
    }
    
    public static function retry(){
        $this->setTimestamp(date('Y-m-d H:i:s'));
        $this->setRetries($this->getRetries() + 1);
    }
    
    public function jsonSerialize() {
        return [
            'uuid' => $this->uuid,
            'timestamp' => $this->timestamp,
            'retries' => $this->retries,
            'body' => $this->body,
            'recipient' => $this->recipient,
            'subject' => $this->subject,
        ];
    }
}