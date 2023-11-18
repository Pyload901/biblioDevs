<?php
require_once "./model/LibroModel.php";
class HomeController {
    public static function Index () {
        var_dump(Utils::isInteger("3-2"));
        if (!Utils::isLogged()) {
            header("Location: /user/login");
        }
        $libro = new LibroModel();
        $user_id = $_SESSION["user_id"];
        $libros = $libro->GetAll($user_id);
        require_once "./view/home/estanteria.phtml";
    }
    public static function Agregar () {
        if (!Utils::isLogged()) {
            header("Location: /user/login");
        }
        $errors = array();
        if (
            !empty($_POST["isbn"])
            && !empty($_POST["titulo"])
            && !empty($_POST["autor"])
        ) {
            $id_usuario = strip_tags($_SESSION["user_id"]);

            $isbn = strip_tags($_POST["isbn"]);
            $titulo = strip_tags($_POST["titulo"]);
            $autor = strip_tags($_POST["autor"]);
            $descripcion = (isset($_POST["descripcion"]) ? strip_tags($_POST["descripcion"]) : null);
            $year = (isset($_POST["year"]) ? strip_tags($_POST["year"]) : null);
            $edicion = (isset($_POST["edicion"]) ? strip_tags($_POST["edicion"]) : null);
            $leido = (isset($_POST["leido"]) ? (bool)strip_tags($_POST["leido"]) : false);

            if (!empty($year) && (!Utils::isInteger($year) || ($year < 1600 || $year > (int)date("Y")))) {
                $errors = array_merge($errors, array("Debe ingresar un año válido"));
                return;
            }
            if (!empty($edicion) && (!Utils::isInteger($edicion) || $edicion < 1)) {
                $errors = array_merge($errors, array("Debe ingresar una edición válida"));
                return;
            }
            $year = (int)$year;
            $edicion = (int)$edicion;
            $libro = new LibroModel();
            $libro->setIsbn($isbn)
                ->settitulo($titulo)
                ->setAutor($autor)
                ->setDescripcion($descripcion)
                ->setYear($year)
                ->setEdicion($edicion)
                ->setLeido($leido);

            if ($libro->Save($id_usuario)) {
                header("Location: /");
            } else {
                echo "Ha ocurrido un error";
            }
        } else {
            $errors = array_merge($errors, array("No se completaron los campos necesarios"));
        }
        require_once "./view/home/agregar.phtml";
    }
    public static function Libro() {
        if (!Utils::isLogged()) {
            header("Location: /user/login");
        }

        if (isset($_GET["id"])) {
            $id_usuario = $_SESSION["user_id"];
            $libro = new LibroModel();
            $libro->setId($_GET["id"]);
            $libro = $libro->Get($id_usuario, Utils::isAdmin());
            require_once "./view/home/libro.phtml";
        } else {
            header("Location: /");
        }
    }

    public static function Editar() {
        if (!Utils::isLogged()) {
            header("Location: /");
        }
        if (isset($_GET["id"])) {
            $errors = array();
            if (
                !empty($_POST["isbn"])
                && !empty($_POST["titulo"])
                && !empty($_POST["autor"])
            ) {
                $id_usuario = strip_tags($_SESSION["user_id"]);

                $isbn = strip_tags($_POST["isbn"]);
                $titulo = strip_tags($_POST["titulo"]);
                $autor = strip_tags($_POST["autor"]);
                $descripcion = (isset($_POST["descripcion"]) ? strip_tags($_POST["descripcion"]) : null);
                $year = (isset($_POST["year"]) ? strip_tags($_POST["year"]) : null);
                $edicion = (isset($_POST["edicion"]) ? strip_tags($_POST["edicion"]) : null);
                $leido = (isset($_POST["leido"]) ? (bool)strip_tags($_POST["leido"]) : false);
                
                if (!empty($year) && (!Utils::isInteger($year) || ($year < 1600 || $year > (int)date("Y")))) {
                    $errors = array_merge($errors, array("Debe ingresar un año válido"));
                    return;
                }
                if (!empty($edicion) && (!Utils::isInteger($edicion) || $edicion < 1)) {
                    $errors = array_merge($errors, array("Debe ingresar una edición válida"));
                    return;
                }

                $year = (int)$year;
                $edicion = (int)$edicion;
                $libro = new LibroModel();
                $libro->setIsbn($isbn)
                    ->settitulo($titulo)
                    ->setAutor($autor)
                    ->setDescripcion($descripcion)
                    ->setYear($year)
                    ->setEdicion($edicion)
                    ->setLeido($leido)
                    ->setId(strip_tags($_GET["id"]));
                if ($libro->Update($id_usuario, Utils::isAdmin())) {
                    header("Location: /home/libro&id={$libro->getId()}");
                } else {
                    $errors = array_merge($errors, array("Ha ocurrido un error, intentalo nuevamente"));
                }
            } else {
                $id_usuario = $_SESSION["user_id"];
                $libro = new LibroModel();
                $libro->setId(strip_tags($_GET["id"]));
                $libro = $libro->Get($id_usuario, Utils::isAdmin());
                require_once "./view/home/agregar.phtml";
            }
        } else {
            header("Location: /");
        }
    }
    public static function Eliminar() {
        if (!Utils::isLogged()) {
            header("Location: /user/login");
        }
        if (isset($_GET["id"])) {
            $id_usuario = $_SESSION["user_id"];
            $libro = new LibroModel();
            $libro->setId(strip_tags($_GET["id"]));
            if($libro->Delete($id_usuario, Utils::isAdmin())) {
                if (Utils::isAdmin()) {
                    if (isset($_SESSION["editing_user"]))
                        header("Location: /admin/user&id={$_SESSION['editing_user']}");
                    else
                        header("Location: /");
                } else {
                    header("Location: /");
                }
            } else {
                echo "Error";
            }
        } else {
            header("Location: /");
        }
    }
};