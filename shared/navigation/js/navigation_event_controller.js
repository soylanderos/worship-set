$(function () {

    $(document).on('click', '[data-section]', function() {
        if($('.modal').length){
            $('.modal').modal('hide').remove();
            $('.modal-backdrop').remove();
        }
    });

    $(document).on('hidden.bs.modal', '.modal', function() {
        $('.modal-backdrop').remove();
    });

     // Evento genérico para todos los links del sidebar
    $(document).on('click', '.sidebar-item', function (e) {
        e.preventDefault();

        // Guardamos el link clickeado
        const $link = $(this);
        const user_request = $link.attr('id'); // ID del link usado como request

        if (!user_request) return; // Si no tiene ID, no hace nada
        // definir el controlador en base al ID del link
        const controller = $link.data('controller');
        //armar controller URL
        const controllerUrl = 'modules/'  + controller + '/controller/' + controller +  '_controller.php';

        console.log('Controller URL:', controllerUrl);

        $.ajax({
            url: controllerUrl,
            type: 'POST',
            data: { user_request: user_request },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#app-content').html(response.view); 

                    // ✅ Cerrar el sidebar
                    $('.sidebar').addClass('collapsed');
                    // ✅ Cambiar icono del botón menú si aplica
                    $('.menu-toggle-btn .material-symbols-rounded').text('menu');

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while fetching content.'
                });
            }
        });
    });

});




