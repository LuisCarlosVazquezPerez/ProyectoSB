function cambiarEstado(event) {
    event.preventDefault();
  
    const url = event.target.href;
    const esPublicada = event.target.classList.contains('boton-verde--estado');
    
    Swal.fire({
      icon: 'success',
      title: esPublicada ? 'Felicidades' : 'Excelente',
      text: esPublicada ? 'La propiedad ha sido vendida.' : 'La propiedad ha sido publicada.',
      showConfirmButton: false,
      timer: 1500,
      willClose: () => {
        window.location.href = url;
      }
    });
  }

function confirmarEliminacion(event) {
    event.preventDefault(); // previene el envío del formulario por defecto
    Swal.fire({
        title: '¿Estás seguro de querer eliminar esta propiedad?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
        console.log("llego");
            event.target.submit(); // envía el formulario si se confirma la eliminación     
        }
    });
}