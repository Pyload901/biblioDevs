const onDeleteConfirm = function (e) {
    if (!confirm("¿Estás seguro de borrar este elemento?")) {
        e.preventDefault();
    }
}