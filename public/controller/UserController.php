<?php
class UserController {
    public static function Index() {
        require_once "./model/UserModel.php";
        $errors = array();
    
        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];
            $user = new UserModel();
            $db_user = $user->GetById($user_id);
    
            if ($db_user) {
            } else {
                $errors = array_merge($errors, array("Usuario no encontrado"));
            }
        } else {
            $errors = array_merge($errors, array("Usuario no autenticado"));
        }
    
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
        require_once "./model/PaisModel.php";

        $pais = new PaisModel();
        $paises = $pais->GetAll();
        
        require_once "./view/user/signup.phtml";
    }
    public static function Logout() {
        unset($_SESSION);
        session_destroy();
        header("Location: /");
    }

        
    public static function ShowUser() {
        require_once "./model/UserModel.php";
        $errors = array();
    
        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];
            $user = new UserModel();
            $db_user = $user->GetById($user_id);
    
            if ($db_user) {
            } else {
                $errors = array_merge($errors, array("Usuario no encontrado"));
            }
        } else {
            $errors = array_merge($errors, array("Usuario no autenticado"));
        }
    
        require_once "./view/user/account_change.phtml";
    }
    
    public static function UpdateUser() {
        require_once "./model/UserModel.php";
        $errors = array();
    
        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];
            $user = new UserModel();
            $user->setId($user_id);
    
            if (!empty($_POST)) {
                // Actualizar los campos con los nuevos valores del formulario
                $user->setNombre(strip_tags($_POST["nombre"]))
                    ->setOcupacion(strip_tags($_POST["ocupacion"]))
                    ->setBirthday(strip_tags($_POST["birthday"]))
                    ->setIdPais(strip_tags($_POST["pais"]));
    
             
                if ($user->Update()) {
                    header("Location: /user"); 
                } else {
                    $errors = array_merge($errors, array("Ha ocurrido un error al guardar los cambios."));
                }
            }
            $db_user = $user->GetById($user_id);
    
            if ($db_user) {
            } else {
                $errors = array_merge($errors, array("Usuario no encontrado"));
            }
            require_once "./view/user/account_change.phtml";
        } else {
            $errors = array_merge($errors, array("Usuario no autenticado"));
        }
    }     
    
    public static function ChangePassword() {
        // Código para mostrar la vista change_password.phtml
        require_once "./view/user/password.phtml";
    }
    
    public static function UpdatePassword() {
        require_once "./model/UserModel.php";
        $errors = array();
    
        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];
            $user = new UserModel();
            $user->setId($user_id);
    
            if (!empty($_POST)) {
                $currentPassword = strip_tags($_POST["current_password"]);
                $newPassword = strip_tags($_POST["new_password"]);
                $confirmPassword = strip_tags($_POST["confirm_password"]);
    
                // Verificar que la contraseña actual sea correcta
                $db_user = $user->GetById($user_id);
                if ($db_user && password_verify($currentPassword, $db_user->getPassword())) {
                    if ($newPassword === $confirmPassword) {
                        // Actualizar la contraseña
                        $user->UpdatePassword($newPassword);
                        header("Location: /user"); // Redirige al usuario a su perfil o a donde desees
                    } else {
                        $errors = array_merge($errors, array("Las contraseñas no coinciden"));
                    }
                } else {
                    $errors = array_merge($errors, array("Contraseña actual incorrecta"));
                }
            }
    
            // Cargar la vista nuevamente con los errores
            echo "hola";
            require_once "./view/user/password.phtml";
        } else {
            $errors = array_merge($errors, array("Usuario no autenticado"));
        }
    }
    
    
   
}
