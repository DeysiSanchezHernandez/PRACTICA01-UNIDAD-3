<?php

class ProductosController extends Controller
{
    // GET /Productos  -> vista con el buscador y los filtros
    public function index($params = '')
    {
        $this->views->render($this, 'index', [
            'titulo'     => 'Catálogo de productos (EAV)',
            'categorias' => $this->model->getCategorias(),
        ]);
    }

    // GET /Productos/apiGetFiltros/1   (o ?categoria_id=1)
    public function apiGetFiltros($params = '')
    {
        $categoriaId = (int) ($params !== '' ? $params : ($_GET['categoria_id'] ?? 0));

        if ($categoriaId <= 0) {
            $this->json(['ok' => false, 'error' => 'categoria_id inválido'], 400);
        }
        $this->json(['ok' => true, 'filtros' => $this->model->getFiltros($categoriaId)]);
    }

    // GET /Productos/apiBuscar?categoria_id=1&q=polo&filtros={"3":"L"}
    public function apiBuscar($params = '')
    {
        $categoriaId = (int) ($_GET['categoria_id'] ?? 0);
        $q = trim((string) ($_GET['q'] ?? ''));
        $filtros = json_decode($_GET['filtros'] ?? '{}', true);
        if (!is_array($filtros)) {
            $filtros = [];
        }
        $this->json(['ok' => true, 'productos' => $this->model->buscar($categoriaId, $q, $filtros)]);
    }

    private function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE); // echo, no return (ver pizarra)
        exit;
    }
}
