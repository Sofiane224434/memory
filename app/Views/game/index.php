<?php
use App\Helpers\LoginHelper;
use App\Models\Card;

if (!LoginHelper::isLogged()) {
    LoginHelper::redirectToLogin();
}
?>