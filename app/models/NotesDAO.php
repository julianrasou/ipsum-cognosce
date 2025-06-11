<?php

class NotesDAO
{
    public static function getNote($id)
    {
        // Requiere la base de datos
        require_once 'app/models/Database.php';

        // Recupera y sanea los datso necesarios
        $id_filtered = htmlspecialchars(trim($id));

        // Ejecuta la operación en la base de datos en un bloque try catch
        // Si todo va bien, devuelve los datos del nuevo usuario y un mensaje de success
        // Si no, devuelve un mensaje de error
        try {
            $db = Database::connect();
            $stm = $db->prepare("SELECT * FROM notes where id = ? and user_id = ?");
            $stm->bindParam(1, $id_filtered);
            $stm->bindParam(2, $_SESSION['user_id']);
            $stm->execute();
            $note = $stm->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'note' => $note
            ];
        } catch (PDOException $e) {
            return [
                'error' => 'Error en la base de datos.',
                'details' => $e
            ];
        }
    }

    public static function createNote($category)
    {
        // Requiere la base de datos
        require_once 'app/models/Database.php';

        // Ejecuta la operación en la base de datos en un bloque try catch
        // Si todo va bien, devuelve los datos de la nota y un mensaje de success
        // Si no, devuelve un mensaje de error
        try {
            $db = Database::connect();

            if ($category === "none") {
                $stm = $db->prepare("INSERT INTO notes(user_id, title) VALUES (?,'Nueva nota')");
                $stm->bindParam(1, $_SESSION["user_id"]);
            } else {
                $stm = $db->prepare("INSERT INTO notes(user_id, title, category_id) VALUES (?,'Nueva nota',?)");
                $stm->bindParam(1, $_SESSION["user_id"]);
                $stm->bindParam(2, $category);
            }
            $stm->execute();
            $result = $db->lastInsertId();


            return [
                'success' => true,
                'id' => $result
            ];
        } catch (PDOException $e) {
            return [
                'error' => 'Error en la base de datos.',
                'details' => $e
            ];
        }
    }

    public static function saveNote($noteId, $title, $content)
    {
        // Requiere la base de datos
        require_once 'app/models/Database.php';

        // Recupera y sanea los datso necesarios
        $title_filtered = htmlspecialchars(trim($title));
        $content_filtered = htmlspecialchars(trim($content));

        // Ejecuta la operación en la base de datos en un bloque try catch
        // Si todo va bien, devuelve los datos de la nota y un mensaje de success
        // Si no, devuelve un mensaje de error
        try {
            $db = Database::connect();
            $stm = $db->prepare("UPDATE notes SET title=?, content=? WHERE id=? AND user_id=?");
            $stm->bindParam(1, $title_filtered);
            $stm->bindParam(2, $content_filtered);
            $stm->bindParam(3, $noteId);
            $stm->bindParam(4, $_SESSION["user_id"]);
            $stm->execute();

            return [
                'success' => true
            ];
        } catch (PDOException $e) {
            return [
                'error' => 'Error en la base de datos.',
                'details' => $e
            ];
        }
    }
}
