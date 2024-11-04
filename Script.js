// Función para mostrar una notificación
function mostrarNotificacion(mensaje, tipo) {
    const notificacion = document.getElementById('notification');
    notificacion.textContent = mensaje;
    notificacion.className = `notification ${tipo}`;
    notificacion.style.display = 'block';

    // Ocultar la notificación después de unos segundos
    setTimeout(() => {
        notificacion.style.display = 'none';
    }, 3000);
}

// Función para actualizar el estado de un pendiente
function actualizarEstado(idPendiente) {
    // Aquí puedes implementar la lógica AJAX para enviar la actualización al servidor

    // Simulación de actualización exitosa
    mostrarNotificacion('Pendiente marcado como atendido.', 'success');
}



function actualizarEstado(idPendiente) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'actualizar_estado.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            mostrarNotificacion('Pendiente marcado como atendido.', 'success');
            // Opcional: Cambiar el estado en la interfaz directamente sin recargar
            document.querySelector(`#status-${idPendiente}`).textContent = 'Atendido';
            document.querySelector(`#status-${idPendiente}`).className = 'status atendido';
        } else {
            mostrarNotificacion('Error al actualizar el pendiente.', 'error');
        }
    };
    xhr.send(`id=${idPendiente}`);
}
