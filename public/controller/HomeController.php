<?php
require_once "./model/LibroModel.php";
class HomeController {
    public static function Index () {
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
            $year = (isset($_POST["year"]) ? (int)strip_tags($_POST["year"]) : null);
            $edicion = (isset($_POST["edicion"]) ? (int)strip_tags($_POST["edicion"]) : null);
            $leido = (isset($_POST["leido"]) ? (bool)strip_tags($_POST["leido"]) : false);

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
            echo  "No se llenaron los campos necesarios";
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
            $libro = $libro->Get($id_usuario);
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
                $year = (isset($_POST["year"]) ? (int)strip_tags($_POST["year"]) : null);
                $edicion = (isset($_POST["edicion"]) ? (int)strip_tags($_POST["edicion"]) : null);
                $leido = (isset($_POST["leido"]) ? (bool)strip_tags($_POST["leido"]) : false);
    
                $libro = new LibroModel();
                $libro->setIsbn($isbn)
                    ->settitulo($titulo)
                    ->setAutor($autor)
                    ->setDescripcion($descripcion)
                    ->setYear($year)
                    ->setEdicion($edicion)
                    ->setLeido($leido)
                    ->setId(strip_tags($_GET["id"]));
                if ($libro->Update($id_usuario)) {
                    header("Location: /");
                } else {
                    echo "Error";
                }
            } else {
                $id_usuario = $_SESSION["user_id"];
                $libro = new LibroModel();
                $libro->setId(strip_tags($_GET["id"]));
                $libro = $libro->Get($id_usuario);
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
            if($libro->Delete($id_usuario)) {
                header("Location: /");
            } else {
                echo "Error";
            }
        } else {
            header("Location: ..");
        }
    }
};