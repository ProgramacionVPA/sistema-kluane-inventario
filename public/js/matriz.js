// ==========================================
// 1. FUNCIONES DE MODALES
// ==========================================
function abrirModal(id, idResp, estado, insumos) {
    $('#modal_id_activo').val(id);
    $('#modal_responsable').val(idResp); 
    $('#modal_estado').val(estado);
    $('#modal_insumos').val(insumos || 'NO');
    var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
    myModal.show();
}

function abrirModalTransferir(id, nombre) {
    $('#trans_id_activo').val(id);
    $('#trans_nombre_equipo').text(nombre);
    var myModal = new bootstrap.Modal(document.getElementById('modalTransferir'));
    myModal.show();
}

// ==========================================
// 2. CARGAR MATRIZ PRINCIPAL (AJAX)
// ==========================================
function filtrarMatrizAjax(idSedeForzada = null) {
    let sedeId = idSedeForzada || $('#select_sede').val();
    const tbody = $('#tbody_matriz');
    const btnPdf = $('#btn_pdf');

    if (!sedeId) {
        tbody.html('<tr><td colspan="6" class="p-5 text-muted text-center"><i class="bi bi-arrow-up-circle fs-1"></i><br>Seleccione un proyecto arriba para ver el inventario.</td></tr>');
        if (btnPdf.length) btnPdf.addClass('d-none');
        return;
    }

    tbody.html('<tr><td colspan="6" class="p-5 text-center text-primary"><div class="spinner-border mb-2" role="status"></div><br>Cargando matriz en tiempo real...</td></tr>');
    
    if (btnPdf.length) {
        btnPdf.attr('href', 'generar_matriz_pdf.php?sede=' + sedeId);
        btnPdf.removeClass('d-none');
    }

    $.ajax({
        url: '../../controllers/ApiMatrizController.php',
        type: 'GET',
        data: { sede: sedeId },
        dataType: 'json',
        success: function(datos) {
            tbody.empty(); 
            if (datos.length === 0) {
                tbody.html('<tr><td colspan="6" class="p-4 text-muted text-center">No hay activos registrados en este proyecto.</td></tr>');
                return;
            }

            let html = '';
            let contador = 1;
            const idRol = ID_ROL_USUARIO;

            datos.forEach(fila => {
                let badgeEstado = fila.estado === 'Operativo' ? '<span class="badge bg-success">OPERATIVO</span>' : `<span class="badge bg-danger">${fila.estado}</span>`;
                let badgeInsumos = fila.necesita_insumos === 'SI' ? '<span class="badge bg-warning text-dark">SI</span>' : '<span class="text-muted">No</span>';
                let responsable = fila.responsable ? fila.responsable : '<span class="text-danger">Sin Asignar</span>';
                
                let botones = '';
                if (idRol !== 3) {
                    let nombreEquipoSeguro = fila.equipo.replace(/'/g, "\\'");
                    botones = `
                        <button class="btn btn-sm btn-primary" title="Editar" onclick="abrirModal('${fila.id_activo}', '${fila.id_responsable || ''}', '${fila.estado}', '${fila.necesita_insumos}')"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn btn-sm btn-warning" title="Transferir" onclick="abrirModalTransferir('${fila.id_activo}', '${nombreEquipoSeguro}')"><i class="bi bi-truck"></i></button>
                    `;
                } else {
                    botones = `<span class="badge bg-light text-muted border py-2 px-3"><i class="bi bi-lock-fill"></i> Solo Lectura</span>`;
                }

                html += `
                    <tr class="fila-activo"> <td>${contador++}</td>
                        <td class="text-start">
                            <span class="fw-bold">${fila.equipo}</span><br>
                            <small class="text-muted">${fila.serie}</small>
                        </td>
                        <td class="text-start">
                            <i class="bi bi-person-fill"></i> ${responsable}<br>
                            <span class="badge bg-secondary">${fila.area || ''}</span>
                        </td>
                        <td>${badgeEstado}</td>
                        <td>${badgeInsumos}</td>
                        <td>${botones}</td>
                    </tr>
                `;
            });
            tbody.html(html);
        }
    });
}

// ==========================================
// 3. INICIALIZACIÓN Y EVENTOS
// ==========================================
$(document).ready(function() {
    // 1. Auto-cargar tabla
    if (SEDE_POR_DEFECTO !== "") {
        filtrarMatrizAjax(SEDE_POR_DEFECTO);
    }

    // 2. BUSCADOR EN TIEMPO REAL (Live Search)
    $('#buscador_matriz').on('keyup', function() {
        let valorBusqueda = $(this).val().toLowerCase();
        $("#tbody_matriz tr.fila-activo").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(valorBusqueda) > -1)
        });
    });

    // 3. INTERCEPTAR FORMULARIO DE EDICIÓN (AJAX)
    $('#modalEditar form').on('submit', function(e) {
        e.preventDefault(); 
        let $form = $(this);
        let btn = $form.find('button[type="submit"]');
        let textoOriginal = btn.text();
        
        btn.html('<span class="spinner-border spinner-border-sm"></span> Guardando...').prop('disabled', true);

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            success: function(respuesta) {
                btn.html(textoOriginal).prop('disabled', false);
                $('#modalEditar').modal('hide'); 
                
                // --- ALERTA SWEETALERT2 TIPO TOAST ---
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Cambios guardados',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                
                // Recargamos la tabla
                let sedeId = $('#select_sede').val() || SEDE_POR_DEFECTO;
                filtrarMatrizAjax(sedeId);
            },
            error: function() {
                Swal.fire('Error', 'Ocurrió un problema de conexión', 'error');
                btn.html(textoOriginal).prop('disabled', false);
            }
        });
    });


    // 4. INTERCEPTAR FORMULARIO DE TRANSFERENCIA (AJAX)
    $('#modalTransferir form').on('submit', function(e) {
        e.preventDefault();
        let $form = $(this);
        let btn = $form.find('button[type="submit"]');
        let textoOriginal = btn.text();
        
        btn.html('<span class="spinner-border spinner-border-sm"></span> Transfiriendo...').prop('disabled', true);

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            success: function(respuesta) {
                btn.html(textoOriginal).prop('disabled', false);
                $('#modalTransferir').modal('hide');
                
                // --- ALERTA SWEETALERT2 TIPO TOAST (Color amarillo/warning) ---
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Equipo transferido',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                
                let sedeId = $('#select_sede').val() || SEDE_POR_DEFECTO;
                filtrarMatrizAjax(sedeId); 
            },
            error: function() {
                Swal.fire('Error', 'No se pudo completar la transferencia', 'error');
                btn.html(textoOriginal).prop('disabled', false);
            }
        });
    });
});