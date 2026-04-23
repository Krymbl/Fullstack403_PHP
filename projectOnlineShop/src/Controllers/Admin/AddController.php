<?php

namespace ProjectOnlineShop\Controllers\Admin;

use ProjectOnlineShop\Attributes\Route;
use ProjectOnlineShop\Core\AbstractController;

class AddController extends AbstractController
{

    #[Route(path: '/admin/add', methods: ["GET"])]
    public function showAddForm() : void {

    }

    #[Route(path: '/admin/add', methods: ["POST"])]
    public function add() : void {

    }


}