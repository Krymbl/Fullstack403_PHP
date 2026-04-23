<?php

namespace ProjectOnlineShop\Controllers\User;

use ProjectOnlineShop\Attributes\Route;
use ProjectOnlineShop\Core\AbstractController;

class CartController extends AbstractController
{

    #[Route(path: '/cart', methods: ["GET"])]
    public function index(): void {

    }

    #[Route(path: '/cart', methods: ["POST"])]
    public function cart(): void {

    }
}