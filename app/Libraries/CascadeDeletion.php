<?php

namespace App\Libraries;

use Config\Database;

class CascadeDeletion
{
    protected $db;
    protected $config;

    public function __construct()
    {
        $this->db     = Database::connect();
        $this->config = require APPPATH . 'Config/Cascade.php';
    }

    public function delete(string $table, $id)
    {
        if (!isset($this->config[$table])) {
            return $this->db->table($table)->delete(['id' => $id]);
        }

        $children = $this->config[$table];

        foreach ($children as $childTable => $foreignKey) {
            $builder = $this->db->table($childTable);
            $rows    = $builder->where($foreignKey, $id)->get()->getResult();

            foreach ($rows as $row) {
                $this->delete($childTable, $row->id);
            }
        }

        return $this->db->table($table)->delete(['id' => $id]);
    }
}
