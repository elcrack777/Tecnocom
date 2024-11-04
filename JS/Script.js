// Arreglo con las rutas de los videos de publicidad
let videosPublicidad = [
    "Reels/Publicidad1.mp4",
    "Reels/Publicidad2.mp4",
    "Reels/Publicidad3.mp4"
];

// Índice para rastrear el video actual
let indiceVideo = 0;

// Función para mostrar el modal de publicidad y rotar el video
function mostrarPublicidad() {
    const publicidadModal = new bootstrap.Modal(document.getElementById('publicidadModal'));
    
    // Cambiar la fuente del video en el modal de publicidad
    const videoElement = document.getElementById('videoPublicidad');
    videoElement.src = videosPublicidad[indiceVideo];
    videoElement.play();  // Asegurarse de que el video se reproduzca
    
    // Incrementar el índice para el siguiente video
    indiceVideo = (indiceVideo + 1) % videosPublicidad.length; // Volver al inicio después del último video
    
    // Mostrar el modal
    publicidadModal.show();
}

// Configurar el intervalo de tiempo (ejemplo: cada 5 minutos)
const intervaloPublicidad = 300000; // 5 minutos en milisegundos
setInterval(mostrarPublicidad, intervaloPublicidad);
