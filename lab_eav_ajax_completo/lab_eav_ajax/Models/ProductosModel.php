<?php

class ProductosModel extends Model
{
    protected $table = 'productos';

    public function getCategorias(): array
    {
        $stmt = $this->connect()->query('SELECT id, nombre FROM categorias ORDER BY nombre');
        return $stmt->fetchAll();
    }

    /** Atributos con sus valores únicos para una categoría: [{id, nombre, valores:[...]}] */
    public function getFiltros(int $categoriaId): array
    {
        $sql = 'SELECT a.id AS atributo_id, a.nombre AS atributo, vp.valor
                FROM valores_productos vp
                JOIN productos p ON p.id = vp.producto_id
                JOIN atributos a ON a.id = vp.atributo_id
                WHERE p.id_categoria = :cat
                GROUP BY a.id, a.nombre, vp.valor
                ORDER BY a.nombre, vp.valor';
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(':cat', $categoriaId, PDO::PARAM_INT);
        $stmt->execute();

        $filtros = [];
        foreach ($stmt->fetchAll() as $row) {
            $id = (int) $row['atributo_id'];
            $filtros[$id] ??= ['id' => $id, 'nombre' => $row['atributo'], 'valores' => []];
            $filtros[$id]['valores'][] = $row['valor'];
        }
        return array_values($filtros);
    }

    /** Búsqueda por nombre + categoría + filtros EAV [atributo_id => valor]. */
    public function buscar(int $categoriaId, string $q, array $filtros): array
    {
        $sql = 'SELECT p.id, p.nombre, c.nombre AS categoria
                FROM productos p
                JOIN categorias c ON c.id = p.id_categoria
                WHERE 1=1';
        $params = [];

        if ($categoriaId > 0) {
            $sql .= ' AND p.id_categoria = ?';
            $params[] = $categoriaId;
        }
        if ($q !== '') {
            $sql .= ' AND p.nombre LIKE ?';
            $params[] = '%' . addcslashes($q, '%_\\') . '%';
        }
        foreach ($filtros as $atributoId => $valor) {
            $sql .= ' AND EXISTS (SELECT 1 FROM valores_productos v
                                  WHERE v.producto_id = p.id AND v.atributo_id = ? AND v.valor = ?)';
            $params[] = (int) $atributoId;
            $params[] = (string) $valor;
        }
        $sql .= ' ORDER BY p.nombre LIMIT 100';

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        $productos = $stmt->fetchAll();
        if (!$productos) {
            return [];
        }

        // Atributos de los productos encontrados (una sola consulta)
        $ids = array_column($productos, 'id');
        $in = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->connect()->prepare(
            "SELECT v.producto_id, a.nombre, v.valor
             FROM valores_productos v JOIN atributos a ON a.id = v.atributo_id
             WHERE v.producto_id IN ($in) ORDER BY a.id"
        );
        $stmt->execute($ids);
        $attrs = [];
        foreach ($stmt->fetchAll() as $r) {
            $attrs[$r['producto_id']][] = ['nombre' => $r['nombre'], 'valor' => $r['valor']];
        }
        foreach ($productos as &$p) {
            $p['atributos'] = $attrs[$p['id']] ?? [];
        }
        return $productos;
    }
}
