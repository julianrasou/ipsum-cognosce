<main>
    <form action="?c=notes&a=save&id=<?php echo $note["id"]; ?>" method="post" class="editNoteForm">
        <input type="text" name="title" value="<?php echo $note["title"]; ?>" class="noteTitleForm">
        <textarea name="content" class="noteContentForm"><?php echo $note["content"];?></textarea>
        <button type="submit">Guardar</button>
    </form>
    
</main>