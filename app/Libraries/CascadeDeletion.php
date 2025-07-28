<?php namespace App\Libraries;

use Config\Cascade;
use Config\Database;

class CascadeDeletion
{
    protected $db;
    protected $relations;

    public function __construct()
    {
        $this->db        = Database::connect();
        $this->relations = (new Cascade())->relations;
    }

    public function delete(string $table, int $id): bool
    {
        // 1. Si no hay hijos configurados, borra el registro y listo
        if (! isset($this->relations[$table])) {
            return (bool) $this->db->table($table)->delete(['id' => $id]);
        }

        // 2. Por cada hijo, borrado recursivo
        foreach ($this->relations[$table] as $child => $fk) {
            $rows = $this->db
                        ->table($child)
                        ->select('id')
                        ->where($fk, $id)
                        ->get()
                        ->getResultObject();

            foreach ($rows as $row) {
                $this->delete($child, $row->id);
            }
        }

        // 3. Al final borramos el padre
        return (bool) $this->db->table($table)->delete(['id' => $id]);
    }
}
