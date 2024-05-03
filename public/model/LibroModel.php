<?php
class LibroModel {
    // insecure id
    //private int $id;
    private string $id;
    private string $isbn;
    private string $titulo;
    private string | null $descripcion;
    private string | null $autor;
    private int | null $year;
    private int | null $edicion;
    private bool $leido;
    private int $id_usuario;

    private PDO $DB;
    public function __construct() {
        $this->DB = Database::Connect();
    }

    public function Save(int $id_usuario): bool {
        try {
            $stmt = $this->DB->prepare("INSERT INTO Libro(isbn, titulo, autor, descripcion, year, edicion, leido, id_usuario) VALUES(?,?,?,?,?,?,?,?)");
            $stmt->bindParam(1, $this->isbn, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->titulo, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->autor, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->descripcion, PDO::PARAM_STR);
            $stmt->bindParam(5, $this->year, PDO::PARAM_INT);
            $stmt->bindParam(6, $this->edicion, PDO::PARAM_STR);
            $stmt->bindParam(7, $this->leido, PDO::PARAM_BOOL);
            $stmt->bindParam(8, $id_usuario, PDO::PARAM_INT);
            $res = $stmt->execute();
            if ($res) {
                return true;
            }
            return false; 
        } catch (PDOException $e) {
            if (Utils::isDevMode()) {
                echo "". $e->getMessage() ."";
            }
            return false;
        }
    }
    public function Update(int $id_usuario, bool $isAdmin = false): bool {
        try {
            $query = "UPDATE Libro SET isbn=?, titulo=?, autor=?, descripcion=?, year=?, edicion=?, leido=? WHERE id = ?";
            if (!$isAdmin) {
                $query .= " AND id_usuario = ?";
            }
            $stmt = $this->DB->prepare($query);
            $stmt->bindParam(1, $this->isbn, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->titulo, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->autor, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->descripcion, PDO::PARAM_STR);
            $stmt->bindParam(5, $this->year, PDO::PARAM_INT);
            $stmt->bindParam(6, $this->edicion, PDO::PARAM_STR);
            $stmt->bindParam(7, $this->leido, PDO::PARAM_BOOL);
            $stmt->bindParam(8, $this->id, PDO::PARAM_INT);
            if (!$isAdmin) {
                $stmt->bindParam(9, $id_usuario, PDO::PARAM_INT);
            }
            $res = $stmt->execute();
            if ($res) {
                return true;
            }
            return false; 
        } catch (PDOException $e) {
            if (Utils::isDevMode()) {
                echo "". $e->getMessage() ."";
            }
            return false;
        }
    }
    public function Delete(int $id_usuario, bool $isAdmin = false): bool {
        try {
            $query = "DELETE FROM Libro WHERE id = ?";
            if (!$isAdmin) {
                $query .= " AND id_usuario = ?";
            }
            $stmt = $this->DB->prepare($query);
            $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
            if (!$isAdmin) {
                $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
            }
            $res = $stmt->execute();
            if ($res) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            if (Utils::isDevMode()) {
                echo "". $e->getMessage() ."";
            }
            return false;
        }
    }
    public function Get(int $id_usuario, bool $isAdmin = false): LibroModel | null {
        try {
            // $query = "SELECT * FROM Libro WHERE id = ?";
            // if (!$isAdmin) {
            //     $query .= " AND id_usuario = ?";
            // }
            // $stmt = $this->DB->prepare($query);
            // $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
                
            // non prepared stmt
            $query = "SELECT * FROM Libro WHERE id = $this->id";
            if (!$isAdmin) {
                $query .= " AND id_usuario = $id_usuario";
            }
            $stmt = $this->DB->query($query);
            // end

            // if (!$isAdmin) {
            //     $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
            // }
            // $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, LibroModel::class);
            $res = $stmt->fetch();
            if ($res)
                return $res;
            return null;
        } catch (PDOException $e) {
            if (Utils::isDevMode()) {
                echo "". $e->getMessage() ."";
            }
            return null;
        }
    }
    public function GetByISBN(int $id_usuario, string $isbn): LibroModel | null {
        try {
            $query = "SELECT id FROM Libro WHERE isbn = ? AND id_usuario = ?";
            
            $stmt = $this->DB->prepare($query);
            $stmt->bindParam(1, $isbn, PDO::PARAM_STR);
            $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, LibroModel::class);
            $res = $stmt->fetch();
            if ($res)
                return $res;
            return null;
        } catch (PDOException $e) {
            if (Utils::isDevMode()) {
                echo "". $e->getMessage() ."";
            }
            return null;
        }
    }
    public function GetAll(int $id_usuario): array | null {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM Libro WHERE id_usuario = ?");
            $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, LibroModel::class);
            $res = $stmt->fetchAll();
            if ($res) {
                return $res;
            }
            return null;
        } catch(PDOException $e) {
            if (Utils::isDevMode()) {
                echo "". $e->getMessage() ."";
            }
            return null;
        }
    }

    /**
     * Get the value of id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param string $id
     *
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of isbn
     *
     * @return string
     */
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * Set the value of isbn
     *
     * @param string $isbn
     *
     * @return self
     */
    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get the value of titulo
     *
     * @return string
     */
    public function getTitulo(): string
    {
        return $this->titulo;
    }

    /**
     * Set the value of titulo
     *
     * @param string $titulo
     *
     * @return self
     */
    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }


    /**
     * Get the value of descripcion
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     */
    public function setDescripcion($descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of autor
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Set the value of autor
     */
    public function setAutor($autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Get the value of year
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set the value of year
     */
    public function setYear($year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get the value of edicion
     */
    public function getEdicion()
    {
        return $this->edicion;
    }

    /**
     * Set the value of edicion
     */
    public function setEdicion($edicion): self
    {
        $this->edicion = $edicion;

        return $this;
    }

    /**
     * Get the value of leido
     *
     * @return bool
     */
    public function getLeido(): bool
    {
        return $this->leido;
    }

    /**
     * Set the value of leido
     *
     * @param bool $leido
     *
     * @return self
     */
    public function setLeido(bool $leido): self
    {
        $this->leido = $leido;

        return $this;
    }

    /**
     * Get the value of id_usuario
     *
     * @return int
     */
    public function getIdUsuario(): int
    {
        return $this->id_usuario;
    }

    /**
     * Set the value of id_usuario
     *
     * @param int $id_usuario
     *
     * @return self
     */
    public function setIdUsuario(int $id_usuario): self
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }
}