<?php

namespace admin;

use mail\mail;

use DateTime;

use database\databaseConfig;

class reset extends admin 
{

    private string $email;

    private string $fname;

    private int $user_id;

    private int $code;

    private mail $mail;
    

    public function __construct(string $email = "")
    {
        $this->email = $email;

    }

    private function connectToDatabase()
    {

        $this->database = new databaseConfig();

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");

        $this->mysqli = $this->database->getConfig();
    }

    private function createCode() : int
    {
        $this->code = random_int(1000, 9999) . random_int(1000, 9999);
        return $this->code;
    }

    public function generateResetLink() : bool
    {
        $account = $this->getAccountByEmailOrUsername($this->email);

        if($account['num_rows'] == 0) return false;

        $this->createCode();

        $this->user_id = $account['id'];
        $this->fname = $account['firstname'];

        $currentDatetime = new DateTime();

        $datetime = clone $currentDatetime;
        $datetime->modify('+15 minutes');
        
        $expiration = $datetime->format('Y-m-d H:i:s');

        $status = 1;

        $this->connectToDatabase();

        $stmt = $this->mysqli->prepare("INSERT INTO `awt_password_reset`(`id`, `account_id`, `code`, `expires`, `status`) VALUES (NULL, ?, ?, ?, ?);");
        
        $content = "
        <h1>Password reset request</h1>
        <p>Hello ". $this->fname .", </p>
        <p>We have generated a password reset link as you requested.</p>
        <p>If you did not request this, please ignore it.</p>
        <a href='". HOSTNAME . "awt-admin/passwordreset.php?code=". $this->code ."' target='_blank' rel='noreferer'>Reset password</a>
        <p><b>This link will expire at ". $expiration ."(server time) or 15 minutes after receiving this email.</b></p>
        ";

        if ($stmt) {
            $stmt->bind_param('issi', $this->user_id, $this->code, $expiration, $status);
            if ($stmt->execute()) {
                $stmt->close();

                $this->mail = new mail(CONTACT_EMAIL, $this->email, "Password reset AWT Dashboard", $content);

                $this->mail->sendMessage("Password Reset");
        
                return true;

            } else {
                 echo $stmt->error;
                 exit();
            }
        }

    }

    public function checkPasswordResetCode(int $code) : bool
    {
        $this->connectToDatabase();
    
        $status = 1;
    
        $account_id = 0;
        $expiration_date = null;
    
        $currentDatetime = new DateTime("now");
        $current = $currentDatetime->format('Y-m-d H:i:s');
    
        $stmt = $this->mysqli->prepare("SELECT `account_id`, `expires` FROM `awt_password_reset` WHERE `code` = ? AND `status` = ?");
    
        $stmt->bind_param("ii", $code, $status);
    
        if ($stmt->execute()) {
            $stmt->store_result();
            $stmt->bind_result($account_id, $expiration_date);
            $stmt->fetch();
            
            $numRows = $stmt->num_rows;
            
            $stmt->close();
        } else {
            die($stmt->error);
        }
    
        if ($numRows > 0 && $expiration_date !== null) {
            $expiration_datetime = new DateTime($expiration_date);
            if($currentDatetime < $expiration_datetime) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }


    private function makeCodeUnusable(int $code)
    {
        $this->connectToDatabase();

        $stmt = $this->mysqli->prepare("UPDATE `awt_password_reset` SET `status` = 0 WHERE `code` = ?");
        $stmt->bind_param("i", $code);
        $stmt->execute();
        $stmt->close();

    }

    public function forgotPasswordRestart(int $code, string $password) : bool
    {
        
        $this->connectToDatabase();
        
        
        $status = 1;

        $account_id = 0;
        
        $stmt = $this->mysqli->prepare("SELECT `account_id` FROM `awt_password_reset` WHERE `code` = ? AND `status` = ?");
        
        $stmt->bind_param("ii", $code, $status);
        
        if ($stmt->execute()) {
            $stmt->store_result();
            $stmt->bind_result($account_id);
            $stmt->fetch();
            
            $numRows = $stmt->num_rows;
            
            $stmt->close();

            $this->makeCodeUnusable($code);

            return $this->changePassword($password, $account_id);

        } else {
            die($stmt->error);
        }

        return false;

    }

}