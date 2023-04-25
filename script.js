const form = document.querySelector('#eliminar-propiedad-form');
const submitButton = form.querySelector('input[type="button"]');

  submitButton.addEventListener('click', (event) => {
    // Evita que el formulario se envíe inmediatamente
    event.preventDefault();

    // Mostrar la alerta de confirmación
    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Esta acción eliminará permanentemente la propiedad',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        // Si el usuario hace clic en el botón "Sí, eliminar", envía el formulario
        form.submit();
      }
    });
  });
