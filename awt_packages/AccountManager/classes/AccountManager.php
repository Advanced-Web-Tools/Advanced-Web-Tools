<?php

namespace AccountManager\classes;

use admin\Admin;
use admin\model\AdminModel;
use database\DatabaseManager;

class AccountManager
{
    private DatabaseManager $database;

    public? array $accounts = null;

    public function __construct()
    {
        $this->database = new DatabaseManager();
    }

    public function fetchAccounts(): self
    {

        $accounts = $this->database->table("awt_admin")
            ->select(["id", "username", "email", "firstname", "lastname", "profile_picture", "permission_level"])
            ->where(["1" => "1"])->get();


        foreach ($accounts as $account) {
            $model = new AdminModel(null, true);

            $model->setUsername($account["username"])
            ->setEmail($account["email"])
            ->setFirstname($account["firstname"])
            ->setLastname($account["lastname"])
            ->setProfilePicture($account["profile_picture"])
            ->setPermLevel($account["permission_level"])->setRole()
            ->setId($account["id"]);

            $this->accounts[$model->id] = $model;

        }

        return $this;
    }

    public function getAccounts(): array
    {
        return $this->accounts;
    }


    public function deleteAccount(int $id): bool
    {
        if($this->accounts == null)
            $this->fetchAccounts();
        $this->accounts[$id]->model_source = "awt_admin";
        return $this->accounts[$id]->deleteModel();
    }

    public function createAccount(string $username, string $firstname, string $lastname, string $email, string $password, ?string $profile_picture, int $permission_level): bool
    {
        $model = new AdminModel(null, true);

        $model->setUsername($username)
            ->setEmail($email)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setProfilePicture($profile_picture)
            ->setPassword($password)
            ->setPermLevel($permission_level)
            ->createToken();


        return $model->register();
    }


}