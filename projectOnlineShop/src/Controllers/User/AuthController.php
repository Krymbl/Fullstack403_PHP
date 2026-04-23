<?php

namespace ProjectOnlineShop\Controllers\User;

use ProjectOnlineShop\Attributes\Route;
use ProjectOnlineShop\Core\AbstractController;

class AuthController extends AbstractController
{

    #[Route(path: '/register', methods: ["GET"])]
    public function showRegisterForm(): void
    {

    }

    #[Route(path: '/register', methods: ["POST"])]
    public function register(): void
    {

    }

    #[Route(path: '/login', methods: ["GET"])]
    public function showLoginForm(): void
    {

    }

    #[Route(path: '/login', methods: ["POST"])]
    public function login(): void
    {

    }

    #[Route(path: '/logout', methods: ["POST"])]
    public function logout(): void
    {

    }
}