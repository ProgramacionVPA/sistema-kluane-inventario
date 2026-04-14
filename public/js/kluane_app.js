/**
 * ========================================================
 * KLUANE INVENTARIO - SCRIPT MAESTRO DE INTERFACES
 * Controla: Alertas, Validaciones, Buscadores y Modales
 * ========================================================
 */

$(document).ready(function() {

    // ==========================================
    // 1. DETECTOR GLOBAL DE MENSAJES (URL)
    // ==========================================
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('msg') || urlParams.has('error')) {
        const msg = urlParams.get('msg');
        const error = urlParams.get('error');

        let titulo = '', texto = '', icono = 'success';

        // Mensajes de Éxito
        if (msg === 'asignado') { titulo = '¡Equipo Asignado!'; texto = 'La asignación se guardó y el equipo se movió de proyecto.'; }
        else if (msg === 'ok') { titulo = '¡Cambios Guardados!'; texto = 'El registro se actualizó correctamente.'; }
        else if (msg === 'guardado') { titulo = '¡Registro Exitoso!'; texto = 'La información se ha guardado en el sistema.'; }
        else if (msg === 'creado') { titulo = '¡Usuario Creado!'; texto = 'El nuevo empleado ya puede acceder al sistema.'; }
        else if (msg === 'eliminado') { titulo = '¡Registro Eliminado!'; texto = 'El elemento ha sido borrado de la base de datos.'; }
        
        // Mensajes de Error
        if (error === 'duplicado') { icono = 'error'; titulo = '¡Dato Duplicado!'; texto = 'El código o correo ingresado ya existe en la base de datos.'; }
        else if (error === 'dependencias') { icono = 'error'; titulo = 'Acción Denegada'; texto = 'No se puede eliminar porque tiene historial o equipos vinculados.'; }
        else if (error === 'bd') { icono = 'error'; titulo = 'Error Interno'; texto = 'Ocurrió un problema de comunicación con la base de datos.'; }

        // Disparar Alerta
        if (titulo !== '') {

            mensaje_formulario('error', 'No tienes nada')
            
        }
        
        // Limpiar URL silenciosamente
        window.history.replaceState({}, document.title, window.location.pathname);
    }


      function mensaje_formulario (icon, msg){
    
       Swal.fire({
            title: msg,
            icon: icon
          });

      }
    // ==========================================
    // 2. BUSCADORES EN TIEMPO REAL
    // ==========================================
    // Dashboard
    $('#buscador_dashboard').on('keyup', function() {
        let v = $(this).val().toLowerCase();
        $("#tbody_dashboard tr.fila-dash").filter(function() { $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1) });
    });
    // Usuarios
    $('#buscador_usuarios').on('keyup', function() {
        let v = $(this).val().toLowerCase();
        $("#tbody_usuarios tr.fila-usuario").filter(function() { $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1) });
    });
    // Historial
    $('#buscador_historial').on('keyup', function() {
        let v = $(this).val().toLowerCase();
        $("#tbody_historial tr.fila-historial").filter(function() { $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1) });
    });

    // ==========================================
    // 3. INTERCEPTORES DE BOTONES DE ELIMINAR/ESTADO
    // ==========================================
    
    // Eliminar Activos o Usuarios
    $('.btn-eliminar-activo, .btn-eliminar-usuario').on('click', function(e) {
        e.preventDefault(); 
        const href = $(this).attr('href');
        Swal.fire({
            title: '¿Estás completamente seguro?',
            text: "Esta acción no se puede deshacer.",
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

    // Cambiar estado de Sedes (Cerrar/Reabrir)
    $('.btn-estado-sede').on('click', function(e) {
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
    // 4. VALIDACIONES DE FORMULARIOS (El Tratamiento Premium)
    // ==========================================
    
    // Función auxiliar para evitar repetir código en cada formulario
    function enviarConAnimacion(form, btnElement, mensaje) {
        btnElement.html('<span class="spinner-border spinner-border-sm"></span> Procesando...').prop('disabled', true);
        Swal.fire({
            title: mensaje,
            text: 'Guardando datos en el sistema...',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 800,
            timerProgressBar: true,
            didOpen: () => { Swal.showLoading(); }
        }).then(() => { form.submit(); });
    }

    // A. Nuevo Activo
    $('#formNuevoActivo').on('submit', function(e) {
        if (!$('#codigo').val().trim() || !$('#serie').val().trim() || !$('#marca').val().trim() || !$('#modelo').val().trim() || !$('#categoria').val() || !$('#sede').val()) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Datos Incompletos', text: 'Llena todos los campos obligatorios.' });
            return false;
        }
        e.preventDefault(); enviarConAnimacion(this, $(this).find('button[type="submit"]'), 'Registrando Equipo');
    });

    // B. Editar Activo
    $('#formEditarActivo').on('submit', function(e) {
        if (!$('#codigo').val().trim() || !$('#serie').val().trim() || !$('#marca').val().trim() || !$('#modelo').val().trim()) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Datos Incompletos', text: 'Llena todos los campos del equipo.' });
            return false;
        }
        e.preventDefault(); enviarConAnimacion(this, $(this).find('button[type="submit"]'), 'Actualizando Equipo');
    });

    // C. Asignar Activo
    $('#formAsignar').on('submit', function(e) {
        if (!$('#id_usuario').val() || !$('#id_sede').val()) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Faltan Datos', text: 'Debes elegir un custodio y una ubicación.' });
            return false;
        }
        e.preventDefault(); enviarConAnimacion(this, $(this).find('button[type="submit"]'), 'Procesando Asignación');
    });

    // D. Nuevo Usuario
    $('#formNuevoUsuario').on('submit', function(e) {
        let pass = $('#password').val().trim();
        if (!$('#nombre').val().trim() || !$('#email').val().trim() || !pass || !$('#rol').val() || !$('#sede').val()) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Faltan Datos', text: 'Llena todos los campos obligatorios.' });
            return false;
        }
        if (pass.length < 6) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Contraseña muy corta', text: 'Debe tener al menos 6 caracteres.' });
            return false;
        }
        e.preventDefault(); enviarConAnimacion(this, $(this).find('button[type="submit"]'), 'Creando Usuario');
    });

    // E. Editar Usuario
    $('#formEditarUsuario').on('submit', function(e) {
        let pass = $('#password').val() ? $('#password').val().trim() : '';
        if (!$('#nombre').val().trim() || !$('#email').val().trim()) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Faltan Datos', text: 'El nombre y correo son obligatorios.' });
            return false;
        }
        if (pass.length > 0 && pass.length < 6) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Contraseña muy corta', text: 'Si cambias la clave, usa al menos 6 caracteres.' });
            return false;
        }
        e.preventDefault(); enviarConAnimacion(this, $(this).find('button[type="submit"]'), 'Actualizando Perfil');
    });

    // F. Sedes / Proyectos (Nuevo y Editar)
    $('#formNuevaSede, #formEditarSede').on('submit', function(e) {
        let inputNombre = $(this).attr('id') === 'formNuevaSede' ? $(this).find('input[name="nombre"]').val().trim() : $('#edit_nombre_sede').val().trim();
        if (!inputNombre) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Nombre Requerido', text: 'El proyecto debe tener un nombre.' });
            return false;
        }
        e.preventDefault(); enviarConAnimacion(this, $(this).find('button[type="submit"]'), 'Guardando Proyecto');
    });

});