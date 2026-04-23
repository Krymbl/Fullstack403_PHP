<?php

namespace ProjectOnlineShop\Controllers\Admin;

use ProjectOnlineShop\Attributes\Route;
use ProjectOnlineShop\Core\AbstractController;

class EditController extends AbstractController
{

    #[Route(path: '/admin/edit/{id}', methods: ["GET"])]
    public function showEditForm() : void {

    }

    #[Route(path: '/admin/edit/{id}', methods: ["POST"])]
    public function edit() : void {

    }

}