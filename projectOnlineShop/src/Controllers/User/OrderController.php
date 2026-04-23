<?php

namespace ProjectOnlineShop\Controllers\User;

use ProjectOnlineShop\Attributes\Route;
use ProjectOnlineShop\Core\AbstractController;

class OrderController extends AbstractController
{

    #[Route(path: "/order", methods: ["GET"])]
    public function showOrderForm()
    {

    }

    #[Route(path: "/order", methods: ["POST"])]
    public function order()
    {

    }

    #[Route(path: "/order/success", methods: ["GET"])]
    public function success()
    {

    }

    #[Route(path: "/order/error", methods: ["GET"])]
    public function error()
    {

    }
}