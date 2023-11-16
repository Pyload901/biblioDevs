<?php
class UserController {
    public static function Index() {
        $errors = array();
        
        if (Utils::isLogged()) {
            require_once "./model/UserModel.php";
            $user_id = $_SESSION["user_id"];
            $user = new UserModel();
            $user->setId($user_id);
            $db_user = $user->GetById($user_id);
            $pais = $user->GetPais();

            require_once "./view/user/account.phtml";
        } else {
            header("Location: /");
        }
    
    }
    public static function Login() {
        if (Utils::isLogged()) {
            header("Location: /");
        }
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
        if (Utils::isLogged()) {
            header("Location: /");
        }
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
            $email = strip_tags($_POST["email"]);
            $password = strip_tags($_POST["password"]);
            $birthday = strip_tags($_POST["birthday"]);
            if (!Utils::emailChecker($email)) {
                $errors = array_merge($errors, array("Debe ingresar un correo válido"));
            } else if (!Utils::passwordChecker($password)) {
                $errors = array_merge($errors, array("La contraseña tener una longitud de más de 8 caracteres y contener al menos, 1 mayúscula, 1 minúscula, 1 número y 1 símbolo especial"));
            } else if ($birthday < (int)date("Y") - 100 || $birthday > (int)date("Y") + 100) {
                $errors = array_merge($errors, array("Debe ingresar un año de nacimiento válido"));
            }else {
                $nombre = strip_tags($_POST["nombre"]);
                $ocupacion = strip_tags($_POST["ocupacion"]);
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
    
    public static function UpdateUser() {
        if (Utils::isLogged()) {
            $errors = array();
            require_once "./model/UserModel.php";
            require_once "./model/PaisModel.php";
            $user = new UserModel();
            $user_id = $_SESSION["user_id"];
            $user->setId($user_id);

            if (
                !empty($_POST)
                && !empty($_POST["nombre"])
                && !empty($_POST["ocupacion"])
                && !empty($_POST["birthday"])
                && !empty($_POST["pais"])
                && !empty($_POST["nombre"])
                
            ) {
                if ($birthday < (int)date("Y") - 100 || $birthday > (int)date("Y") + 100) {
                    $errors = array_merge($errors, array("Debe ingresar un año válido"));
                } else {
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
            } else {
                $errors = array_merge($errors, array("No se completaron los campos"));
            }
            $db_user = $user->GetById($user_id);
            $paisModel = new PaisModel();
            $paises = $paisModel->GetAll();
            $pais = $user->GetPais();
            require_once "./view/user/account_change.phtml";
        } else {
            header("Location: /user/login");
        }
    }     
    
    public static function ChangePassword() {
        if (Utils::isLogged()) {
            require_once "./model/UserModel.php";
            $errors = array();
            $user_id = $_SESSION["user_id"];

            $user = new UserModel();
            $user->setId($user_id);
    
            if (
                !empty($_POST)
                && !empty($_POST["current_password"])
                && !empty($_POST["new_password"])
                && !empty($_POST["confirm_password"])
            ) {
                $currentPassword = strip_tags($_POST["current_password"]);
                $newPassword = strip_tags($_POST["new_password"]);
                $confirmPassword = strip_tags($_POST["confirm_password"]);
                
                // Verificar que la contraseña actual sea correcta
                $db_user = $user->GetById($user_id);
                if ($db_user && password_verify($currentPassword, $db_user->getPassword())) {
                    if ($newPassword === $confirmPassword) {
                        // Actualizar la contraseña
                        if (Utils::passwordChecker($newPassword)) {
                            $user->UpdatePassword($newPassword);
                            header("Location: /user"); // Redirige al usuario a su perfil o a donde desees
                        } else {
                            $errors = array_merge($errors, array("La contraseña tener una longitud de más de 8 caracteres y contener al menos, 1 mayúscula, 1 minúscula, 1 número y 1 símbolo especial"));
                        }
                    } else {
                        $errors = array_merge($errors, array("Las contraseñas no coinciden"));
                    }
                } else {
                    $errors = array_merge($errors, array("Contraseña actual incorrecta"));
                }
            }
        } else {
            header("Location: /user/login");
        }
        // Código para mostrar la vista change_password.phtml
        require_once "./view/user/password.phtml";
    }   
   
}
