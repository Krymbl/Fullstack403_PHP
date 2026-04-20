<?php

require_once "Database.php";
require_once "User.php";
class UserRepository
{
    private $connection;

    public function __construct() {
        $this->connection = Database::getConnection();
    }
    public function save(User $user) : int{
        $id = $user->getId();
        if (!is_null($id) and !is_null($this->find($id))) {
            $this->update($user);
            return $id;
        }
        $stmt = $this->connection->prepare("insert into users (name, email, age) values (:name, :email, :age)");
        $stmt->execute([
            ":name" => $user->getName(),
            ":email" => $user->getEmail(),
            ":age" => $user->getAge()
        ]);
        return $this->connection->lastInsertId();
    }


    public function delete(int $id) : bool{
        $stmt = $this->connection->prepare("delete from users where id = :id");
        $stmt->execute([
            ":id" => $id
        ]);
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function find(int $id) : ?User{
        $stmt = $this->connection->prepare("select * from users where id = :id");
        $stmt->execute([
            ":id" => $id
        ]);
        $result = $stmt->fetch();
        return new User($result["name"], $result["email"], $result["age"], $result["id"]);
    }

    public function all() : array{
        $stmt = $this->connection->prepare("select * from users");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function update(User $user) : void
    {
        $stmt = $this->connection->prepare("update users set name = :name, email = :email, age = :age where id = :id");
        $stmt->execute([
            ":name" => $user->getName(),
            ":email" => $user->getEmail(),
            ":age" => $user->getAge(),
            ":id" => $user->getId()
        ]);

    }
}