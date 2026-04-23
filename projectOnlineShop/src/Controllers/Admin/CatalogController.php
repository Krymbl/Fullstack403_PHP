<?php

namespace ProjectOnlineShop\Controllers\Admin;

use ProjectOnlineShop\Attributes\Route;
use ProjectOnlineShop\Core\AbstractController;

class CatalogController extends AbstractController
{
    #[Route(path: '/admin/catalog', methods: ["GET"])]
    public function index() : void {

    }

}