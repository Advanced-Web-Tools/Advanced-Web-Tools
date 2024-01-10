<?php

namespace mail;

use database\databaseConfig;
use PHPMailer\PHPMailer\PHPMailer;
use DateTime;
class mail {

    private databaseConfig $databaseConfig;
    private object $mysqli;
    private string $subject;
    private string $content;
    private string $reciever;
    private string $sender;

    private DateTime $date;

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
        $this->date = new DateTime("now");
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
            $this->logMessage(0);
            return false;
        } 

        $this->logMessage(1);
        return true;
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
            $this->logMessage(0);
            return false;
        } 

        $this->logMessage(1);
        return true;
    }

    private function logMessage(int $sent) : void
    {   
        $date = $this->date->format('Y-m-d H:i:s');

        $stmt = $this->mysqli->prepare("INSERT INTO `awt_mail` (`sender`,`recipient`, `subject`, `content`,`date`,`sent`) VALUES (?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssi",$this->sender, $this->reciever, $this->subject, $this->content, $date, $sent);

        $stmt->execute();

        $stmt->close();

    }

    private function fetchMail(int $sent = 1) : array
    {
        $result = array();

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_mail` WHERE `sent` = ?");
        
        if (!$stmt) {
            return $result;
        }
    
        $stmt->bind_param("i", $sent);
    
        $stmt->execute();
    
        $stmt->bind_result($result[0], $result[1], $result[2], $result[3], $result[4], $result[5], $result[6]);
    
        while ($stmt->fetch()) {
            $row[] = array(
                $result[0], $result[1], $result[2], $result[3], $result[4], $result[5], $result[6]
            );
        }
    
        $stmt->close();
    
        return $row;
    }


}