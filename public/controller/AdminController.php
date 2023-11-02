<?php
class AdminController{
    public static function Index() {
        if (!Utils::isAdmin()) {
            header("Location: /");
        }
        require_once "./model/UserModel.php";
        $user = new UserModel();
        $users = $user->GetAll();
        require_once "./view/admin/users.phtml";
    }
    public static function User() {
        if (!Utils::isAdmin()) {
            header("Location: /");
        }
        require_once "./view/admin/user.phtml";
    }
}