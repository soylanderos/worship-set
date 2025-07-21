$(function () {
    const admin_controller = 'modules/admin/controller/admin_controller.php';

     // Función para abrir/cerrar dropdown
    function toggleDropdown($dropdown, isOpen) {
        const $menu = $dropdown.find(".dropdown-menu");
        $dropdown.toggleClass("open", isOpen);
        $menu.css("height", isOpen ? $menu.prop("scrollHeight") + "px" : 0);
    }

    // Cerrar todos los dropdowns abiertos
    function closeAllDropdowns() {
        $(".dropdown-container.open").each(function () {
            toggleDropdown($(this), false);
        });
    }

    // Click en toggles de dropdown
    $(".dropdown-toggle").on("click", function (e) {
        e.preventDefault();
        const $dropdown = $(this).closest(".dropdown-container");
        const isOpen = $dropdown.hasClass("open");
        closeAllDropdowns();
        toggleDropdown($dropdown, !isOpen);
    });

    // Toggle del sidebar (botones)
    $(".sidebar-toggler, .sidebar-menu-button, .menu-toggle-btn").on("click", function () {
        closeAllDropdowns();
        $(".sidebar").toggleClass("collapsed");

        // Cambiar ícono dinámicamente
        const $icon = $(this).find(".material-symbols-rounded");
        if ($(".sidebar").hasClass("collapsed")) {
            $icon.text("menu");
        } else {
            $icon.text("close");
        }
    });

    // Colapsar por defecto en pantallas pequeñas
    if ($(window).width() <= 1024) {
        $(".sidebar").addClass("collapsed");
    }

    function fetchAdminData(){
        var user_request = 'fetch_admin_data';

        // Submit admin data request
        $.post(admin_controller, {
            user_request: user_request
        }, function(data) {
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#app-content').html(response.view);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    }

    function verifyRole() {
        var user_role = $('body').data('user-role');
        if(user_role !== 'admin') {
            Swal.fire({
                icon: 'error',
                title: 'Access Denied',
                text: 'You do not have permission to access this page.'
            }).then(() => {
                window.location.href = 'login.php';
            });
        } else {
            fetchAdminData();
        }
    }

    verifyRole();

    // Handle form submission for admin
    $(document).on('click', '#btn_admin', function() {
    });

   

    $(document).on('click', '.team_card', function () {
        const teamId = $(this).data('team-id');
        $.ajax({
            url: admin_controller,
            type: 'POST',
            data: { user_request: 'fetch_team_details', team_id: teamId },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#modal-container').html(response.view);
                    $('#teamDetailsModal').modal('show');
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
                    text: 'An error occurred while fetching team details.'
                });
            }
        });
    });

    $(document).on('hide.bs.modal', '#teamDetailsModal', function () {
        $(this).closest('.modal-backdrop').remove();
        $(this).remove();
    });
});