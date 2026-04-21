/**
 * ========================================================
 * KLUANE INVENTARIO - SCRIPT GLOBAL (Cerebro General)
 * ========================================================
 */

// 1. FUNCIÓN MAESTRA DE ALERTAS (Idea de Jefatura)
function mensaje_formulario(icon, msg) {
    Swal.fire({
        title: (icon === 'error') ? '¡Atención!' : msg, // Si es error, pone un título rojo
        text: (icon === 'error') ? msg : '',           // Y el mensaje pasa al texto pequeño
        icon: icon,
        toast: (icon === 'success'), 
        position: (icon === 'success') ? 'top-end' : 'center',
        showConfirmButton: (icon !== 'success'),
        timer: (icon === 'success') ? 3000 : null
    });
}

$(document).ready(function() {

    // ==========================================
    // 2. DETECTOR GLOBAL DE MENSAJES (URL) CON DICCIONARIOS
    // ==========================================
    const urlParams = new URLSearchParams(window.location.search);

    const exitos = {
        'asignado': '¡Equipo Asignado y transferido!',
        'ok': '¡Cambios Guardados correctamente!',
        'guardado': '¡Registro Exitoso en el sistema!',
        'creado': '¡Nuevo Usuario Creado!',
        'eliminado': '¡Registro Eliminado!'
    };

    const errores = {
        'duplicado': '¡Dato Duplicado! El código o correo ya existe.',
        'dependencias': 'No se puede eliminar. Este registro tiene equipos asignados o historial en el sistema.',
        'bd': 'Error interno de la base de datos.'
    };

    if (urlParams.has('msg') && exitos[urlParams.get('msg')]) {
        mensaje_formulario('success', exitos[urlParams.get('msg')]);
    } else if (urlParams.has('error') && errores[urlParams.get('error')]) {
        mensaje_formulario('error', errores[urlParams.get('error')]);
    }

    if (urlParams.has('msg') || urlParams.has('error')) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }

// ==========================================
    // 3. BUSCADORES EN TIEMPO REAL (Genéricos)
    // ==========================================
    
    // Buscador del Dashboard Principal
    $('#buscador_dashboard').on('keyup', function() {
        let v = $(this).val().toLowerCase();
        $("#tbody_dashboard tr.fila-dash").filter(function() { 
            $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1) 
        });
    });

    // Buscador de Usuarios
    $('#buscador_usuarios').on('keyup', function() {
        let v = $(this).val().toLowerCase();
        $("#tbody_usuarios tr.fila-usuario").filter(function() { 
            $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1) 
        });
    });

    // Buscador de Historial
    $('#buscador_historial').on('keyup', function() {
        let v = $(this).val().toLowerCase();
        $("#tbody_historial tr.fila-historial").filter(function() { 
            $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1) 
        });
    });
    // ==========================================
    // 4. INTERCEPTORES GLOBALES (Blindados para AJAX)
    // ==========================================
    $(document).on('click', '.btn-eliminar-activo, .btn-eliminar-usuario', function(e) {
        e.preventDefault(); 
        const href = $(this).attr('href');
        
        // ¡Magia aquí! Detectamos si están intentando borrar un usuario o un activo
        const esUsuario = $(this).hasClass('btn-eliminar-usuario');
        const tituloAlerta = esUsuario ? '¿Eliminar a este usuario?' : '¿Estás completamente seguro?';
        const textoAlerta = esUsuario ? 'Si el usuario tiene equipos asignados, el sistema no te dejará borrarlo por seguridad.' : 'Esta acción no se puede deshacer.';

        Swal.fire({
            title: tituloAlerta,
            text: textoAlerta,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = href;
        });
    });

    $(document).on('click', '.btn-estado-sede', function(e) {
        e.preventDefault(); 
        const href = $(this).attr('href');
        const isCerrar = $(this).data('accion') === 'cerrar';
        const nombre = $(this).data('nombre');
        
        Swal.fire({
            title: isCerrar ? '¿Cerrar este proyecto?' : '¿Reabrir este proyecto?',
            text: isCerrar ? `La sede "${nombre}" ya no aparecerá en los menús.` : `La sede "${nombre}" volverá a estar disponible.`,
            icon: isCerrar ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: isCerrar ? '#ffc107' : '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = href;
        });
    });

    // ==========================================
    // 5. VALIDACIONES DE FORMULARIOS 
    // ==========================================
    function enviarConAnimacion(form, btnElement, mensaje) {
        btnElement.html('<span class="spinner-border spinner-border-sm"></span> Procesando...').prop('disabled', true);
        Swal.fire({
            title: mensaje,
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 800,
            timerProgressBar: true,
            didOpen: () => { Swal.showLoading(); }
        }).then(() => { form.submit(); });
    }

    $('#formNuevoActivo').on('submit', function(e) {
        if (!$('#codigo').val().trim() || !$('#serie').val().trim() || !$('#marca').val().trim() || !$('#modelo').val().trim() || !$('#categoria').val() || !$('#sede').val()) {
            e.preventDefault();
            mensaje_formulario('warning', 'Llena todos los campos obligatorios.');
            return false;
        }
        e.preventDefault(); enviarConAnimacion(this, $(this).find('button[type="submit"]'), 'Registrando Equipo...');
    });

    $('#formEditarActivo').on('submit', function(e) {
        if (!$('#codigo').val().trim() || !$('#serie').val().trim() || !$('#marca').val().trim() || !$('#modelo').val().trim()) {
            e.preventDefault();
            mensaje_formulario('warning', 'Llena todos los campos del equipo.');
            return false;
        }
        e.preventDefault(); enviarConAnimacion(this, $(this).find('button[type="submit"]'), 'Actualizando Equipo...');
    });

    $('#formAsignar').on('submit', function(e) {
        if (!$('#id_usuario').val() || !$('#id_sede').val()) {
            e.preventDefault();
            mensaje_formulario('warning', 'Debes elegir un custodio y una ubicación.');
            return false;
        }
        e.preventDefault(); enviarConAnimacion(this, $(this).find('button[type="submit"]'), 'Procesando Asignación...');
    });

    $('#formNuevoUsuario').on('submit', function(e) {
        let pass = $('#password').val().trim();
        if (!$('#nombre').val().trim() || !$('#email').val().trim() || !pass || !$('#rol').val() || !$('#sede').val()) {
            e.preventDefault();
            mensaje_formulario('warning', 'Llena todos los campos obligatorios.');
            return false;
        }
        if (pass.length < 6) {
            e.preventDefault();
            mensaje_formulario('error', 'La contraseña debe tener al menos 6 caracteres.');
            return false;
        }
        e.preventDefault(); enviarConAnimacion(this, $(this).find('button[type="submit"]'), 'Creando Usuario...');
    });

    $('#formEditarUsuario').on('submit', function(e) {
        let pass = $('#password').val() ? $('#password').val().trim() : '';
        if (!$('#nombre').val().trim() || !$('#email').val().trim()) {
            e.preventDefault();
            mensaje_formulario('warning', 'El nombre y correo son obligatorios.');
            return false;
        }
        if (pass.length > 0 && pass.length < 6) {
            e.preventDefault();
            mensaje_formulario('error', 'Si cambias la clave, usa al menos 6 caracteres.');
            return false;
        }
        e.preventDefault(); enviarConAnimacion(this, $(this).find('button[type="submit"]'), 'Actualizando Perfil...');
    });

});