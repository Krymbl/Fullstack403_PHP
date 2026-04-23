<?php

namespace ProjectOnlineShop\Controllers\User;

use ProjectOnlineShop\Attributes\Route;
use ProjectOnlineShop\Core\AbstractController;

class HomeController extends AbstractController
{

    #[Route(path: '/', methods: ["GET"])]
    public function index() : void {

    }

}