<?php

namespace ProjectOnlineShop\Controllers\User;

use ProjectOnlineShop\Attributes\Route;
use ProjectOnlineShop\Core\AbstractController;

class ProductController extends AbstractController
{

    #[Route(path: '/product/{id}', methods: ['GET'])]
    public function index() {

    }
}