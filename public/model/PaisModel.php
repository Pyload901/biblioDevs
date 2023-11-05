<?php
class PaisModel {
    private int $id;
    private string $nombre;
    private PDO $DB;
    public function __construct() {
        $this->DB = Database::Connect();
    }
    public function GetAll(): array | null {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM Pais");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, PaisModel::class);
            $rows = $stmt->fetchAll();
            if ($rows)
                return $rows;
            
            return null;
        } catch (PDOException $e) {
            if (Utils::isDevMode()) {
                echo "". $e->getMessage() ."";
            }
            return null;
        }

    }

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
}