<?php
class UserModel {
    private int $id;
    private string $nombre;
    private string $email;
    private string $password;
    private string $birthday;
    private int $id_pais;
    private string $ocupacion;
    private string $role;
    private $DB;
    public function __construct() {
        $this->DB = Database::Connect();
    }
    // public function __construct(string $nombre, string $email, string $password, string $birthday, int $id_pais, string $ocupacion){
    //     $this->nombre = $nombre;
    //     $this->email = $email;
    //     $this->password = $password;
    //     $this->birthday = $birthday;
    //     $this->id_pais = $id_pais;
    //     $this->ocupacion = $ocupacion;
    //     $this->DB = Database::Connect();
    // }
    public function Save() : bool {
        try {
            $stmt = $this->DB->prepare("INSERT INTO Usuario(nombre, email, password, birthday, id_pais, ocupacion, role) VALUES(?,?,?,?,?,?,'USER')");
            $stmt->bindParam(1, $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->email, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->password, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->birthday, PDO::PARAM_STR);
            $stmt->bindParam(5, $this->id_pais, PDO::PARAM_INT);
            $stmt->bindParam(6, $this->ocupacion, PDO::PARAM_STR);
    
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function Get(): UserModel | null {
        try {
            $stmt = $this->DB->prepare("SELECT id, email, password, role FROM Usuario WHERE email = ?");
            $stmt->bindParam(1, $this->email, PDO::PARAM_STR);
            if ($stmt->execute()) {
                $stmt->setFetchMode(PDO::FETCH_CLASS, UserModel::class);
                $row = $stmt->fetch();
                if ($row) {
                    return $row;
                }
                return null;
            }
            return null;
        } catch(PDOException $e) {
            return null;
        }
    }

    public function GetAll(): array | null {
        try {
            $stmt = $this->DB->prepare("SELECT id, nombre, email FROM Usuario");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, UserModel::class);
            $rows = $stmt->fetchAll();
            if ($rows)
                return $rows;
            return null;
        } catch(PDOException $e) {
            if ($_ENV["DEV"]) {
                echo $e->getMessage();
            }
            return null;
        }
    }

    // Getters & setter
   
    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nombre
     *
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @param string $nombre
     *
     * @return self
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of birthday
     *
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * Set the value of birthday
     *
     * @param string $birthday
     *
     * @return self
     */
    public function setBirthday(string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get the value of id_pais
     *
     * @return int
     */
    public function getIdPais(): int
    {
        return $this->id_pais;
    }

    /**
     * Set the value of id_pais
     *
     * @param int $id_pais
     *
     * @return self
     */
    public function setIdPais(int $id_pais): self
    {
        $this->id_pais = $id_pais;

        return $this;
    }

    /**
     * Get the value of ocupacion
     *
     * @return string
     */
    public function getOcupacion(): string
    {
        return $this->ocupacion;
    }

    /**
     * Set the value of ocupacion
     *
     * @param string $ocupacion
     *
     * @return self
     */
    public function setOcupacion(string $ocupacion): self
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

     /**
     * Get the value of role
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @param string $role
     *
     * @return self
     */
    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}