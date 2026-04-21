/**
 * ========================================================
 * KLUANE INVENTARIO - SCRIPT ESPECÍFICO DE SEDES
 * Funciones exclusivas para la vista de Gestión de Proyectos
 * ========================================================
 */

// Función para abrir el modal de edición y cargar los datos
function abrirModalEditar(id, nombre) {
    document.getElementById('edit_id_sede').value = id;
    document.getElementById('edit_nombre_sede').value = nombre;
    var modal = new bootstrap.Modal(document.getElementById('modalEditarSede'));
    modal.show();
}