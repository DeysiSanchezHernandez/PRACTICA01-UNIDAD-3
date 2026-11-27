<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['titulo'] ?? 'Proyecto MVC', ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($data['titulo'] ?? 'Proyecto MVC', ENT_QUOTES, 'UTF-8') ?></h1>
    <p><?= htmlspecialchars($data['subtitulo'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
    <p>La estructura MVC está cargada correctamente.</p>
    <p><a href="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>Productos">Ir al catálogo de productos (EAV)</a></p>
</body>
</html>
