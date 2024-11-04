<!-- Modal de Publicidad con Video -->
<div class="modal fade" id="publicidadModal" tabindex="-1" aria-labelledby="publicidadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="publicidadModalLabel">Publicidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¡Aprovecha nuestras promociones exclusivas!</p>
                <video id="videoPublicidad" class="img-fluid" autoplay muted loop playsinline>
                    <source src="Reels/publicidad1.mp4" type="video/mp4">
                    Tu navegador no soporta el video.
                </video>
            </div>
        </div>
    </div>
</div>

<script>
// Arreglo con las rutas de los videos de publicidad
let videosPublicidad = [
    "Reels/publicidad1.mp4",
    "Reels/publicidad2.mp4",
    "Reels/publicidad3.mp4"
];

// Índice para rastrear el video actual
let indiceVideo = 0;

// Función para mostrar el modal de publicidad y rotar el video
function mostrarPublicidad() {
    const publicidadModal = new bootstrap.Modal(document.getElementById('publicidadModal'));
    
    // Cambiar la fuente del video en el modal de publicidad
    const videoElement = document.getElementById('videoPublicidad');
    videoElement.src = videosPublicidad[indiceVideo];
    videoElement.load(); // Cargar el video nuevamente
    videoElement.play().catch(error => {
        console.error("Error al reproducir el video:", error);
    });
    
    // Incrementar el índice para el siguiente video
    indiceVideo = (indiceVideo + 1) % videosPublicidad.length; // Volver al inicio después del último video
    
    // Mostrar el modal
    publicidadModal.show();
}

// Configurar el intervalo de tiempo (ejemplo: cada 5 minutos)
const intervaloPublicidad = 300000; // 5 minutos en milisegundos
setInterval(mostrarPublicidad, intervaloPublicidad);
</script>
