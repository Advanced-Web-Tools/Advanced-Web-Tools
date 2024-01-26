<?php

namespace mail;

use admin\profiler;
use database\databaseConfig;
use PHPMailer\PHPMailer\PHPMailer;
use DateTime;
class mail {

    private databaseConfig $databaseConfig;
    private object $mysqli;
    private string $subject;
    private string $content;
    public string $recipient;
    public string $sender;
    private DateTime $date;
    private PHPMailer $mail;
    
    public function __construct(string $sender, string $reciever, string $subject, string $content = "") {

        $this->databaseConfig = new databaseConfig;

        $this->databaseConfig->checkAuthority() == 1 or die("Fatal error database acces not allowed for " . $this->databaseConfig->getCaller());

        $this->mysqli = $this->databaseConfig->getConfig();

        $this->sender = $sender;
        $this->recipient = $reciever;
        $this->content = $content;
        $this->mail = new PHPMailer(true);
        $this->subject = $subject;
        $this->date = new DateTime("now");
    }


    public function sendTestMessage() : bool
    {   
        $this->recipient = $this->sender;

        $hostname = $_SERVER['HTTP_HOST'];
        $this->mail = new PHPMailer;
        $this->mail->From = $this->sender ."@" . $hostname; 
        $this->mail->FromName = "Tester"; 
        $this->mail->addAddress($this->recipient);
        $this->mail->addReplyTo($this->sender, "Reply");
        $this->mail->addCC("cc@" . $hostname); 
        $this->mail->addBCC("bcc@" . $hostname);
        $this->mail->isHTML(true); 
        $this->subject = $this->mail->Subject = "Test"; 
        $this->content = $this->mail->Body = "<p>Mail server test</p>";
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
        $this->mail->addAddress($this->recipient);
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

        $stmt->bind_param("sssssi",$this->sender, $this->recipient, $this->subject, $this->content, $date, $sent);

        $stmt->execute();

        $stmt->close();

    }

    public function fetchMail(int $sent = 1, bool $strict = false) : array
    {
        $result = array();
        $row = array();

        $sql = "SELECT * FROM `awt_mail` WHERE `sent` = ?";

        if($strict) $sql .= " AND `sender` = ?";

        $sql .= " ORDER BY `date` DESC";

        $stmt = $this->mysqli->prepare($sql);
        
        if (!$stmt) {
            return $result;
        }
        
        if(!$strict) $stmt->bind_param("i", $sent);
        if($strict){ 
            $profiler = new profiler;
            $stmt->bind_param("is", $sent, $profiler->email);
        }
    
        $stmt->execute();
    
        $stmt->bind_result($result['id'], $result['sender'], $result['recipient'], $result['subject'], $result['content'], $result['date'], $result['sent']);
    
        while ($stmt->fetch()) {
            $row[] = json_encode(array(
                $result['id'], $result['sender'], $result['recipient'], $result['subject'], $result['content'], $result['date'], $result['sent']
            ), JSON_PRETTY_PRINT);
        }
    
        $stmt->close();
    
        return $row;
    }

    public function fetchMailInbox() : array
    {
        $result = array();
        $row = array();

        $sql = "SELECT * FROM `awt_mail` WHERE `recipient` = ? ORDER BY `date` DESC";


        $stmt = $this->mysqli->prepare($sql);
        
        if (!$stmt) {
            return $result;
        }
        
        $profiler = new profiler();

        $stmt->bind_param("s", $profiler->email);
        
        $stmt->execute();
    
        $stmt->bind_result($result['id'], $result['sender'], $result['recipient'], $result['subject'], $result['content'], $result['date'], $result['sent']);
        
        while ($stmt->fetch()) {
            $row[] = json_encode(array(
                $result['id'], $result['sender'], $result['recipient'], $result['subject'], $result['content'], $result['date'], $result['sent']
            ), JSON_PRETTY_PRINT);
        }
        
        $stmt->close();
        

        return $row;
    }

    public function getMessage(int $id, bool $strict = false) : array
    {   

        $result = array();

        $sql = "SELECT * FROM `awt_mail` WHERE (`id` = ?";

        if($strict) {
            $sql .= " AND `recipient` = ?) OR (`id` = ? AND `sender` = ?";
        }

        $sql .= ");";


        $stmt = $this->mysqli->prepare($sql);


        if($strict) {

            $profiler = new profiler;

            $stmt->bind_param("isis", $id, $profiler->email, $id, $profiler->email);
        } else {
            $stmt->bind_param("i", $id);
        }

        $stmt->execute();
    
        $stmt->bind_result($result['id'], $result['sender'], $result['recipient'], $result['subject'], $result['content'], $result['date'], $result['sent']);

        $stmt->fetch();

        $stmt->close();

        return $result;
    }


}