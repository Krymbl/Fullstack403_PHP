<?php

namespace ProjectOnlineShop\Controllers\User;

use ProjectOnlineShop\Attributes\Route;
use ProjectOnlineShop\Core\AbstractController;

class ProfileController extends AbstractController
{

    #[Route(path: '/profile', methods: ['GET'])]
    public function index() {

    }
    #[Route(path: '/profile', methods: ['POST'])]
    public function editProfile() {

    }

    #[Route(path: '/profile/orders', methods: ['GET'])]
    public function orders() {

    }

    #[Route(path: '/profile/order/{id}', methods: ['GET'])]
    public function orderDetails() {

    }


}