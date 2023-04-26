








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