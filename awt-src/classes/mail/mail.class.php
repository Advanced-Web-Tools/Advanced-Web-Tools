<?php

namespace mail;

use database\databaseConfig;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class mail {

    private databaseConfig $databaseConfig;
    private object $mysqli;
    private string $subject;
    private string $content;
    private string $reciever;
    private string $sender;
    private PHPMailer $mail;
    
    public function __construct(string $sender, string $reciever, string $subject, string $content = "") {

        $this->databaseConfig = new databaseConfig;

        $this->databaseConfig->checkAuthority() == 1 or die("Fatal error database acces not allowed for " . $this->databaseConfig->getCaller());

        $this->mysqli = $this->databaseConfig->getConfig();

        $this->sender = $sender;
        $this->reciever = $reciever;
        $this->content = $content;
        $this->mail = new PHPMailer(true);
        $this->subject = $subject;

    }


    public function sendTestMessage() : bool
    {
        $hostname = $_SERVER['HTTP_HOST'];
        $this->mail = new PHPMailer;
        $this->mail->From = $this->sender ."@" . $hostname; 
        $this->mail->FromName = "Tester"; 
        $this->mail->addAddress($this->reciever);
        $this->mail->addAddress($this->reciever); 
        $this->mail->addReplyTo($this->sender ."@" . $hostname , "Reply");
        $this->mail->addCC("cc@" . $hostname); 
        $this->mail->addBCC("bcc@" . $hostname);
        $this->mail->isHTML(true); 
        $this->mail->Subject = "Subject Text"; 
        $this->mail->Body = "<i>Test message</i>";
        $this->mail->AltBody = "Test message"; 


        if(!$this->mail->send()) 
        {
            return false;
        } else {
            return true;
        }


    }

    public function sendMessage(string $name = "") : bool
    {
        $hostname = $_SERVER['HTTP_HOST'];
        $this->mail = new PHPMailer;
        $this->mail->From = $this->sender; 
        $this->mail->FromName = $name; 
        $this->mail->addAddress($this->reciever);
        $this->mail->addAddress($this->reciever); 
        $this->mail->addReplyTo($this->sender, "Reply");
        $this->mail->addCC("cc@" . $hostname); 
        $this->mail->addBCC("bcc@" . $hostname);
        $this->mail->isHTML(true); 
        $this->mail->Subject = $this->subject; 
        $this->mail->Body = $this->content;
        $this->mail->AltBody = $this->content; 


        if(!$this->mail->send()) 
        {
            return false;
        } 
        return true;
    }


}