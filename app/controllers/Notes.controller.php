<?php

/**
 * Controlador para la página de notas
 * Requiere las vistas correspondientes
 * Si no se ha iniciado sesión se carga una vista de contenido restringido
 */

class Notes
{
    private $model;

    public function __construct()
    {
        require_once("app/models/NotesDAO.php");
        $this->model = new NotesDAO();
    }

    public function index()
    {

        $controller = "notes";
        if (!isset($_SESSION["user_id"])) {
            require_once 'app/views/partials/header.view.php';
            require_once 'app/views/unverified.view.php';
            require_once 'app/views/partials/footer.view.php';
        } else {
            require_once 'app/views/partials/header.view.php';
            require_once 'app/views/notes/notes.view.php';
            require_once 'app/views/partials/footer.view.php';
        }
    }

    /**
     * Función que se ejecuta al editar una nota
     * Si se quiere crear una nota nueva, es decir no se pasa id, primero se crea y se añade a la BD
     */
    public function edit()
    {
        $noteId = $_REQUEST["id"] ?? null ;

        $note;
        if ($noteId) {
            $note = $this->model::getNote($noteId);
        } else {
            $category = $_REQUEST["cat"] ?? "none";
            $noteId = $this->model::createNote($category)['id'];
            $note = $this->model::getNote($noteId);
        }

        if ($note["success"]) {
            $controller = "notes";
            $note = $this->model::getNote($noteId)["note"];
            require_once 'app/views/partials/header.view.php';
            require_once 'app/views/notes/edit.view.php';
            require_once 'app/views/partials/footer.view.php';
        } else {
            header("Location: ?c=notes");
            echo "<script type='text/javascript'>alert('No se ha podido abrir la nota.');</script>";
        }
    }

    /**
     * Acción que se encarga de guardar en la base de datos la nota modificada
     */
    public function save()
    {
        $noteId = $_REQUEST["id"] ? $_REQUEST["id"] : null ;
        $newTitle = $_POST["title"];
        $newContent = $_POST["content"];
        $result = $this->model::saveNote($noteId, $newTitle, $newContent);
        if ($result["success"]) {
            header("Location: ?c=notes");
        } else {
            echo "<script type='text/javascript'>alert('No se ha podido guardar la nota.');</script>";
            header("Location: ?c=notes");
        }
    }
}
