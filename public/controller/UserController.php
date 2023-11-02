<?php
class UserController {
    public static function Index() {
        require_once "./view/user/account.phtml";
    }
    public static function Login() {
        $errors = array();
        require_once "./model/UserModel.php";
        if (
            !empty($_POST["email"]) 
            && !empty($_POST["password"])
        ) {
            $email = strip_tags($_POST["email"]);
            $password = strip_tags($_POST["password"]);
            $user = new UserModel();
            $user->setEmail($email)
                ->setPassword($password);

            $db_user = $user->Get();
            
            if (isset($db_user)) {
                if (password_verify($user->getPassword(), $db_user->getPassword())) {
                    $_SESSION["user_id"] = $db_user->getId();
                    $_SESSION["user_email"] = $db_user->getEmail();
                    $_SESSION["user_role"] = $db_user->getRole();
                    header("Location: /");
                } else {
                    $errors = array_merge($errors, array("No se ha podido iniciar sesión, Usuario inválido"));
                }
            } else {
                $errors = array_merge($errors, array("No se ha podido iniciar sesión, Usuario inválido"));
            }
        } else {
            $errors = array_merge($errors, array("No se han completado los campos"));
        }
        // view
        require_once "./view/user/login.phtml";
    }
    public static function Signup() {
        require_once "./model/UserModel.php";
        $errors = array();
        if(
            !empty($_POST)
            && !empty($_POST["nombre"])
            && !empty($_POST["email"])
            && !empty($_POST["password"])
            && !empty($_POST["ocupacion"])
            && !empty($_POST["birthday"])
            && !empty($_POST["pais"])
        ) {
            $nombre = strip_tags($_POST["nombre"]);
            $email = strip_tags($_POST["email"]);
            $password = strip_tags($_POST["password"]);
            $ocupacion = strip_tags($_POST["ocupacion"]);
            $birthday = strip_tags($_POST["birthday"]);
            $pais = strip_tags($_POST["pais"]);
            
            // encrypt password
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $user = new UserModel();
            $user->setNombre($nombre)
                ->setEmail($email)
                ->setPassword($hash)
                ->setBirthday($birthday)
                ->setIdPais($pais)
                ->setOcupacion($ocupacion);

            if ($user->Save()) {
                header("Location: /user/login");
            } else {
                $errors = array_merge($errors, array("Ha ocurrido un error, intenta nuevamente. Es posible que este correo ya haya sido registrado"));
            }   
        } else {
            $errors = array_merge($errors, array("No se han completado los campos"));
        }

        require_once "./view/user/signup.phtml";
    }
    public static function Logout() {
        unset($_SESSION);
        session_destroy();
        header("Location: /");
    }
}