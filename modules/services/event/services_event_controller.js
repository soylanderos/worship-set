$(function () {
    const services_controller = 'modules/services/controller/services_controller.php';

    $(document).on('click', '#create_service', function (e) {
        e.preventDefault();
        $.ajax({
            url: services_controller,
            type: 'POST',
            data: { user_request: 'create_service_modal' },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#modal-container').html(response.view);
                    $('#createServiceModal').modal('show');
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
                    text: 'An error occurred while loading the create service modal.'
                });
            }
        });
    });

    $(document).on('submit', '#create_service_form', function (e) {
        e.preventDefault();
        var form = $(this)[0];
        var formData = new FormData(form);
        formData.append('user_request', 'create_service');

        $.ajax({
            url: services_controller,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#createServiceModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then(() => {
                        $('#app-content').html(response.view);
                    });
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
                    text: 'An error occurred while creating the service.'
                });
            }
        });
    });

    $(document).on('click', '.btn_view_service', function () {
        const serviceId = $(this).data('service-id');
        $.ajax({
            url: services_controller,
            type: 'POST',
            data: { user_request: 'fetch_view_service', service_id: serviceId },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#app-content').html(response.view);
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
                    text: 'An error occurred while fetching service details.'
                });
            }
        });
    });

    $(document).on('click', '#btn_view_schedule', function () {
        const serviceId = $(this).data('service-id');
        $.ajax({
            url: services_controller,
            type: 'POST',
            data: { user_request: 'fetch_service_schedule', service_id: serviceId },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#modal-container').append(response.view);
                    $('#viewScheduleModal').modal('show');
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
                    text: 'An error occurred while fetching the service schedule.'
                });
            }
        });
    });

    $(document).on('hide.bs.modal', '#viewScheduleModal', function () {
        $(this).remove();
        $('#modal-container').empty();
    });

    $(document).on('click', '#btn_manage_setlist', function () {
        const serviceId = $(this).data('service-id');
        const segmentId = $(this).data('segment-id');
        $.ajax({
            url: services_controller,
            type: 'POST',
            data: { user_request: 'fetch_manage_setlist', service_id: serviceId, segment_id: segmentId },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#modal-container').append(response.view);
                    $('#manageSetlistModal').modal('show');
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
                    text: 'An error occurred while fetching the setlist.'
                });
            }
        });
    });

    $(document).on('hide.bs.modal', '#manageSetlistModal', function () {
        $(this).remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('click', '#btn_add_song_to_setlist', function () {
        const serviceId = $(this).data('service-id');
        const segmentId = $(this).data('segment-id');
        const songId = $('#song_to_select').val();
        let songKey = $('#song_key').val();

        if (!songId) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Please select a song to add to the setlist.'
            });
            return;
        }

        $.ajax({
            url: services_controller,
            type: 'POST',
            data: { user_request: 'add_song_to_setlist', service_id: serviceId, segment_id: segmentId, song_id: songId, song_key: songKey },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                   $('#setlist_songs').html(response.view);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                }
            }
        });
    });

    $(document).on('click', '.btn_remove_setlist_song', function () {
        const serviceId = $(this).data('service-id');
        const segmentId = $(this).data('segment-id');
        const songId = $(this).data('song-id');

        $.ajax({
            url: services_controller,
            type: 'POST',
            data: { user_request: 'remove_song_from_setlist', service_id: serviceId, segment_id: segmentId, song_id: songId },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#setlist_songs').html(response.view);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                }
            }
        });
    });

    let selectedMember = null;

    // Buscar músicos (AJAX)
    $(document).on('input', '#musician_search', function() {
        let query = $(this).val();
        let church_id = $(this).data('church-id');
        let segment_id = $('#assignedMusicians').data('segment-id');

        //set timeout to avoid too many requests
        clearTimeout($.data(this, 'timer'));
        $(this).data('timer', setTimeout(() => {
            if (query.length < 1) {
                $('#searchResults').html('<p class="text-muted small text-center mb-0">Start typing to search...</p>');
                return;
            }

            $.ajax({
                url: services_controller,
                type: 'POST',
                data: { user_request: 'search_musicians', query: query, church_id: church_id, segment_id: segment_id },
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status === 'success') {
                        if (response.members.length > 0) {
                            let resultsHtml = '';
                            response.members.forEach(member => {
                                resultsHtml += `
                                    <div class="search-result-item p-2" data-user-id="${member.id}" data-name="${member.name}">
                                        <span class="fw-bold">${member.name}</span>
                                        <span class="text-muted small">(${member.position})</span>
                                    </div>
                                `;
                            });
                            $('#searchResults').html(resultsHtml);
                        } else {
                            $('#searchResults').html('<p class="text-muted small text-center mb-0">No results found.</p>');
                        }
                    }
                },
                error: function() {
                    $('#searchResults').html('<p class="text-danger small text-center mb-0">An error occurred while searching.</p>');
                }
            });
        }, 300)); // 300ms delay before sending the request
        
    });

    // Seleccionar resultado
    $(document).on('click', '.search-result-item', function() {
        selectedMember = {
            id: $(this).data('user-id'),
            name: $(this).data('name')
        };
        $('.search-result-item').removeClass('bg-light');
        $(this).addClass('bg-secondary text-white');
        $('#btnAddMusician').prop('disabled', false);
    });

    // Agregar a la lista
    $(document).on('click', '#btnAddMusician', function() {
        if (!selectedMember) return;
        let user_id = selectedMember;
        let user_request = 'add_musician_to_setlist';
        let service_id = $('#assignedMusicians').data('service-id');
        let segment_id = $('#assignedMusicians').data('segment-id');
        
        $.ajax({
            url: services_controller,
            type: 'POST',
            data: {
                user_request: user_request,
                service_id: service_id,
                segment_id: segment_id,
                user_id: user_id.id
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#assignedMusicians').append(response.view);
                    $('#musician_search').val('');
                    $('#searchResults').html('<p class="text-muted small text-center mb-0">Start typing to search...</p>');
                    selectedMember = null;
                    $('#btnAddMusician').prop('disabled', true);
                    $('#assignedMusicians tr').each(function () {
                        updateRowVisibility($(this));
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
        });
    });

    function updateRowVisibility(row) {
        const role = row.find('.musician-role').val();
        const songSelect = row.find('.song-select');

        if (role === 'Lead Vocal') {
            row.find('td[data-label="Song to Lead"]').removeClass('d-none');
            row.find('td[data-label="MD"]').addClass('d-none');
            songSelect.removeClass('d-none');
        } else if (role === '') {
            // Si no tiene rol, mostramos todo
            row.find('td[data-label="Song to Lead"]').removeClass('d-none');
            row.find('td[data-label="MD"]').removeClass('d-none');
            songSelect.addClass('d-none'); // ocultamos select hasta que se seleccione
        } else {
            // Otro rol
            row.find('td[data-label="Song to Lead"]').addClass('d-none');
            row.find('td[data-label="MD"]').removeClass('d-none');
            songSelect.addClass('d-none').val('');
        }
    }

    // Aplicar cuando cambia
    $(document).on('change', '.musician-role', function () {
        const row = $(this).closest('tr');
        updateRowVisibility(row);

        // Si es Lead Vocal, además hacemos la carga por AJAX
        if ($(this).val() === 'Lead Vocal') {
            const songSelect = row.find('.song-select');
            const segmentId = $('#assignedMusicians').data('segment-id');
            const serviceId = $('#assignedMusicians').data('service-id');

            songSelect.html('<option>Loading...</option>').removeClass('d-none');

            $.ajax({
                url: services_controller,
                type: 'POST',
                data: {
                    user_request: 'fetch_setlist_songs',
                    segment_id: segmentId,
                    service_id: serviceId
                },
                success: function (data) {
                    const response = JSON.parse(data);
                    if (response.status === 'success' && response.songs.length > 0) {
                        songSelect.empty().append('<option value="">Choose Song</option>');
                        response.songs.forEach(song => {
                            songSelect.append(`<option value="${song.id}">${song.title}</option>`);
                        });
                    } else {
                        songSelect.html('<option>No songs available</option>');
                    }
                },
                error: function () {
                    songSelect.html('<option>Error loading songs</option>');
                }
            });
        }
    });

    // Eliminar miembro
    $(document).on('click', '.btn-remove-member', function() {
        let user_id = $(this).data('user-id');
        let service_id = $('#assignedMusicians').data('service-id');
        let segment_id = $('#assignedMusicians').data('segment-id');
        $.ajax({
            url: services_controller,
            type: 'POST',
            data: {
                user_request: 'remove_musician_from_setlist',
                service_id: service_id,
                segment_id: segment_id,
                user_id: user_id
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $(`#assignedMusicians tr[data-user-id="${user_id}"]`).remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            }
        });
    });

    // Solo un MD permitido
    $(document).on('change', '.md-checkbox', function() {
        if ($(this).is(':checked')) {
            $('.md-checkbox').not(this).prop('checked', false);
        }

        let name = $(this).closest('.assigned-musician-row').find('.musician-name').text().trim();

        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: `You have selected ${name} as the Music Director (MD). Only one MD is allowed per segment.`
        });
    });

    $(document).on('click', '#btn_save_setlist', function () {
        let service_id = $(this).data('service-id');
        let segment_id = $(this).data('segment-id');
        let assignments = [];

        $('#assignedMusicians tr').each(function () {
            let user_id = $(this).data('user-id');
            let role = $(this).find('.musician-role').val();
            let song_id = $(this).find('.song-select').val() || null;
            let is_md = $(this).find('.is-md-checkbox').is(':checked') ? 1 : 0;

            if (user_id && role) {
                assignments.push({
                    user_id: user_id,
                    role: role,
                    song_id: song_id,
                    is_md: is_md
                });
            }
        });

        $.ajax({
            url: services_controller,
            type: 'POST',
            data: {
                user_request: 'save_setlist_assignments',
                service_id: service_id,
                segment_id: segment_id,
                assignments: JSON.stringify(assignments)
            },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                    $('#manageSetlistModal').modal('hide');
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
                    text: 'Unexpected error occurred.'
                });
            }
        });
    });

    $(document).on('click', '#tab-assignments', function (e) {
        e.preventDefault();
        let songs_count = $('.setlist-card').length;
        if (songs_count < 4) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'You need at least 4 songs in the setlist to assign musicians.'
            });
            $('#tab-setlist').tab('show');
            return false;
        }
    });

    // Manage Audio Setup
    $(document).on('click', '#btn_manage_audio', function () {
        let serviceId = $(this).data('service-id');
        let segmentId = $(this).data('segment-id');
        let teamId = $(this).data('team-id');
        if (!serviceId || !segmentId || !teamId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Service, segment, and team IDs are required to manage audio setup.'
            });
            return;
        }
        $.ajax({
            url: services_controller,
            type: 'POST',
            data: { user_request: 'fetch_manage_audio', service_id: serviceId, segment_id: segmentId, team_id: teamId },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#modal-container').append(response.view);
                    $('#manageAudioModal').modal('show');
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
                    text: 'Unexpected error occurred.'
                });
            }
        });
    });

    $(document).on('hide.bs.modal', '#manageAudioModal', function () {
        $(this).closest('.modal-backdrop').remove();
        $(this).remove();
    });

    $(document).on('click', '#addMixerUser', function () {
        let select = $('#select_mixer_user');
        let userId = select.val();
        let userName = select.find('option:selected').text();

        if (userId) {
            $('#mixerAssignments').append(`
                <span class="mixer_assignment_users badge bg-primary d-inline-flex align-items-center mb-2" data-user-id="${userId}">
                    ${userName}
                    <button type="button" class="btn btn-sm btn-light ms-2 remove-user">
                        <i class="material-symbols-rounded" style="font-size:16px;">close</i>
                    </button>
                </span>
            `);
            select.val('');
            $('#mixerAssignments p').remove();
        }
    });

    $(document).on('click', '#addStageUser', function () {
        let select = $('#select_stage_user');
        let userId = select.val();
        let userName = select.find('option:selected').text();

        if (userId) {
            $('#stageAssignments').append(`
                <span class="stage_assignment_users badge bg-secondary d-inline-flex align-items-center mb-2" data-user-id="${userId}">
                    ${userName}
                    <button type="button" class="btn btn-sm btn-light ms-2 remove-user">
                        <i class="material-symbols-rounded" style="font-size:16px;">close</i>
                    </button>
                </span>
            `);
            select.val('');
            $('#stageAssignments p').remove();
        }
    });

    // Eliminar usuario
    $(document).on('click', '.remove-user', function () {
        $(this).closest('.badge').remove();
    });


});