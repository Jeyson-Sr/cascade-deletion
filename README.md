# CI4 Cascade Deletion

💣 Librería de borrado en cascada recursiva para CodeIgniter 4 (CI4). Borra cualquier registro y todos sus hijos relacionados con solo una línea de código.

## 🧪 Instalación

Si aún no estás en Packagist, instala así:

```bash
composer require jeyson-srs/ci4-cascade-deletion



use App\Libraries\CascadeDeletion;

$deleter = new CascadeDeletion();
$deleter->delete('courses', 1); // Borra el curso y todo lo relacionado
