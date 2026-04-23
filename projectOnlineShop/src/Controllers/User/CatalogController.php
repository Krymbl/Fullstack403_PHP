<?php

namespace ProjectOnlineShop\Controllers\User;

use ProjectOnlineShop\Attributes\Route;
use ProjectOnlineShop\Core\AbstractController;

class CatalogController extends AbstractController
{

    #[Route(path: '/catalog', methods: ["GET"])]
    public function index() : void {

    }
}