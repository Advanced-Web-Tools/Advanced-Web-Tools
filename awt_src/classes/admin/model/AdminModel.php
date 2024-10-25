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
    public ?string $username;
    public ?string $profile_picture;
    public ?int $permLevel;
    public ?string $token = null;
    private SessionHandler $session;

    public ?string $role;
    public ?string $password;
    private array $roles = [
        "Admin",
        "Moderator",
        "Author",
    ];

    public function __construct(?int $id = null)
    {
        parent::__construct();

        if (is_null($id)) {
            $this->session = new SessionHandler();
            $this->session->SessionHandler();
            $id = (int)$_SESSION['admin']['id'];
        }

        $this->selectByID($id, "awt_admin");
        $this->username = $this->getParam("username");
        $this->profile_picture = $this->getParam("profile_picture");
        $this->permLevel = $this->getParam("permission_level");
        $this->role = $this->roles[$this->permLevel];
        $this->password = $this->getParam("password");
    }

}