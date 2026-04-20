<?php

require_once "User.php";
require_once "UserRepository.php";
$user1 = new User(
    "Анна Иванова",
    "anna@example.com",
    "25"
);

$user11 = new User(
    "Анна Иванова",
    "anna@example.com",
    "30",
    1
);

$user2 = new User(
    name: "Петр Сидоров",
    email: "petr@example.com",
    age: "30"
);

$user3 = new User(
    name: "Мария Петрова",
    email: "maria@example.com",
    age: "28"
);

$user4 = new User(
    name: "Иван Козлов",
    email: "ivan@example.com",
    age: "35"
);

$userRepository = new UserRepository();

print_r($userRepository->all());

$userRepository -> delete(2);

print_r($userRepository->all());
