<?php

class HomeController extends Controller
{
    public function index($params = '')
    {
        $data = [
            'titulo' => 'Proyecto MVC',
            'subtitulo' => 'Plantilla base para practicar en el laboratorio',
        ];

        $this->views->render($this, 'index', $data);
    }
}
