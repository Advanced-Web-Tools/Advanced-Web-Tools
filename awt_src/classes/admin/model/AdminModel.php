<?php

/**
 * Class AdminModel
 * Represents an administrative user with properties such as username, email,
 * permission level, role, and session management. Provides methods for managing
 * user information, including updating credentials, roles, profile details, and
 * registering a new admin user.
 */

namespace admin\model;

use /**
 * The Model class typically serves as the base class for all data-driven models
 * in the application, providing core functionalities for data manipulation,
 * validation, and interaction with data storage mechanisms such as databases.
 *
 * Responsibilities of this class may include:
 * - Defining and managing properties that represent data fields.
 * - Handling data retrieval and storage via external sources such as databases.
 * - Providing methods for data validation and transformation within the model.
 * - Acting as a common interface for various entity-specific models in the system.
 *
 * This class is expected to be extended by specific implementations tailored to
 * the application's needs and should follow common design patterns for managing
 * and structuring data logic.
 */
    model\Model;
use /**
 * The SessionHandler class provides a way to manage session operations in a standardized manner.
 * It implements custom session storage handlers for reading, writing, updating, and deleting session data.
 *
 * This class works as an interface between PHP's session management system and any custom session storage implementation.
 */
    session\SessionHandler;

/**
 * Class AdminModel
 *
 * Represents an administrative user with various properties and methods for managing user data,
 * including authentication, session handling, and role-based permissions.
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