<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['titulo'], ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem auto; max-width: 960px; padding: 0 1rem; }
        .barra { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .barra input, .barra select { padding: .5rem; }
        #filtros { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
        #filtros label { display: flex; flex-direction: column; font-size: .85rem; gap: .25rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: .5rem; text-align: left; }
        th { background: #f3f3f3; }
        #estado { color: #666; margin: .5rem 0; }
    </style>
</head>
<body>
    <h1><?= htmlspecialchars($data['titulo'], ENT_QUOTES, 'UTF-8') ?></h1>

    <div class="barra" id="app" data-base="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>">
        <select id="categoria">
            <option value="">— Todas las categorías —</option>
            <?php foreach ($data['categorias'] as $c): ?>
                <option value="<?= (int) $c['id'] ?>"><?= htmlspecialchars($c['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
        <input type="search" id="buscador" placeholder="Buscar producto..." autocomplete="off">
    </div>

    <div id="filtros"></div>
    <div id="estado"></div>

    <table>
        <thead><tr><th>Producto</th><th>Categoría</th><th>Atributos</th></tr></thead>
        <tbody id="resultados"></tbody>
    </table>

    <script src="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>Assets/js/productos.js"></script>
</body>
</html>
