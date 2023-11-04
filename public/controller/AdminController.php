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
        if (isset($_GET["id"])) {
            require_once "./model/LibroModel.php";
            $id_usuario = $_GET["id"];
            
            $_SESSION["editing_user"] = $id_usuario;

            $libro = new LibroModel();
            $libros = $libro->GetAll($id_usuario);
            require_once "./view/admin/user.phtml";
            require_once "./view/home/estanteria.phtml";
        } else {
            header("Location: /admin");
        }
    }
}