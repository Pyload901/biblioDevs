<?php
class AdminController{
    public static function Index() {
        if (!Utils::isAdmin()) {
            header("Location: /");
        }
        $admin_id = $_SESSION["user_id"];
        require_once "./model/UserModel.php";
        $user = new UserModel();
        $users = $user->GetAll($admin_id);
        require_once "./view/admin/users.phtml";
    }
    public static function User() {
        if (!Utils::isAdmin()) {
            header("Location: /");
        }
        if (isset($_GET["id"])) {
            require_once "./model/LibroModel.php";
            require_once "./model/UserModel.php";
            $id_usuario = $_GET["id"];

            $usuario = new UserModel();
            $usuario->setId($id_usuario);
            $role = $usuario->GetUserRole();
            $session_role = $_SESSION["user_role"];

            if (!$role) {
                header("Location: /admin");
            } else if (
                ($role->role == Role::ADMIN->value && $session_role == Role::ADMIN->value)
                || ($session_role == Role::ADMIN->value && $role->role == Role::SUPER->value)
            ) {
                header("Location: /admin");
            }

            $_SESSION["editing_user"] = $id_usuario;

            $libro = new LibroModel();
            $libros = $libro->GetAll($id_usuario);
            if (Utils::isSuper() && $id_usuario != $_SESSION["user_id"]) {
                if (isset($_POST) && isset($_POST["role"])) {
                    $role = strip_tags($_POST["role"]);
                    if ($role != Role::SUPER->value) {
                        $usuario->setRole($role);
                        if ($usuario->ChangeRole()) {

                        } else {
                            
                        }
                    }
                }
                $role = $usuario->GetUserRole()->role;
                require_once "./view/admin/user.phtml";
            }
            require_once "./view/home/estanteria.phtml";
        } else {
            header("Location: /admin");
        }
    }
}