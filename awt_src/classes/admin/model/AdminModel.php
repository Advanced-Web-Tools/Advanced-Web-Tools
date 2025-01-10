<?php

namespace admin\model;

use model\Model;
use session\SessionHandler;

/**
 * AdminModel Class
 *
 * This class extends the base Model class to manage administrator-related
 * functionality, including session management and role handling.
 *
 */
class AdminModel extends Model
{
    public ?int $id = null;

    public ?string $username;
    public ?string $profile_picture;
    public ?int $permission_level;
    public ?string $token = null;

    public ?string $firstname;
    public ?string $lastname;
    public ?string $email;
    private SessionHandler $session;

    public ?string $role;
    public ?string $password;
    private array $roles = [
        "Admin",
        "Moderator",
        "Author",
    ];

    public function __construct(?int $id = null, bool $manual = false)
    {
        parent::__construct();

        if (is_null($id) && !$manual) {
            $this->session = new SessionHandler();
            $this->session->SessionHandler();
            $id = (int)$_SESSION['admin']['id'];
        }

        if (!$manual) {
            $this->selectByID($id, "awt_admin");
            $this->username = $this->getParam("username");
            $this->profile_picture = $this->getParam("profile_picture");
            $this->permission_level = $this->getParam("permission_level");
            $this->role = $this->roles[$this->permission_level];
            $this->password = $this->getParam("password");

        }

        $this->paramBlackList("role");
    }

    public function setID(int $id)
    {
        $this->id = $id;
        $this->model_id = $id;
        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function setProfilePicture(?string $profile_picture): self
    {
        $this->profile_picture = $profile_picture;
        return $this;
    }

    public function setPermLevel(int $permission_level): self
    {
        $this->permission_level = $permission_level;
        $this->setRole();
        return $this;
    }

    public function setPassword(string $password): self
    {
        $password = hash("SHA512", $password);
        $this->password = $password;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setRole(): self
    {
        $this->role = $this->roles[$this->permission_level];
        return $this;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function createToken(): self
    {
        $this->token = hash("SHA512", time() . $this->password);

        return $this;
    }

    public function changePassword(string $oldPassword, string $newPassword): bool
    {
        if ($this->password === hash("SHA512", $oldPassword)) {
            return $this->setPassword($newPassword)->save();
        }
        return false;
    }

    public function register(): int|bool
    {
        try {
            $this->model_source = "awt_admin";
            return $this->saveModel();
        } catch (\PDOException $e) {
            return false;
        }
    }
}