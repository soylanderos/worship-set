<?php
session_start();
$session_user_id = $_SESSION["id"];
$church_id = $_SESSION["church_id"];
$user_role = $_SESSION["role"];
require "../model/services_queries.php";
require "../../admin/model/admin_queries.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_request = filter_input(INPUT_POST, 'user_request');
} else {
    $user_request = filter_input(INPUT_GET, 'user_request');
}

switch ($user_request) {
    case 'fetch_services':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $services = fetch_services($db, $church_id);
            $is_admin = $_SESSION['role'] === 'admin';


            ob_start();
            include '../services.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'message' => 'Users fetched successfully', 'view' => $content]);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'create_service_modal':
        try {
            ob_start();
            include '../components/modal/create_service_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'message' => 'Create service modal loaded', 'view' => $content]);
        } catch (Exception $e) {
            error_log('Error loading create service modal: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Failed to load create service modal']);
        }
        break;

    case 'create_service':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $title = filter_input(INPUT_POST, 'title');
            $service_date = filter_input(INPUT_POST, 'service_date');
            $start_time = filter_input(INPUT_POST, 'start_time');
            $notes = filter_input(INPUT_POST, 'notes');

            if (!$title || !$service_date || !$start_time) {
                throw new Exception('Title, date and start time are required.');
            }

            $service_id = create_service($db, $church_id, $title, $service_date, $start_time, $notes, $session_user_id);
            // Agregar segmentos base automáticamente
            create_default_segments($db, $service_id, $church_id);

            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Service created successfully', 'service_id' => $service_id]);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'fetch_view_service':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $service_id = filter_input(INPUT_POST, 'service_id');

            // Traer datos del servicio
            $service = fetch_service_details($db, $service_id, $church_id);
            $segments = fetch_segments_by_service_id($db, $service_id);
            $is_admin = $_SESSION['role'] === 'admin';
            $user_teams = fetch_teams_by_user_id($db, $session_user_id);


            if ($service) {
                ob_start();
                include '../components/modal/service_details.php';
                $content = ob_get_clean();

                echo json_encode(['status' => 'success', 'message' => 'Service details fetched successfully', 'view' => $content, 'is_admin' => $is_admin, 'user_teams' => $user_teams]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Service not found']);
            }
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'fetch_service_schedule':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $service_id = filter_input(INPUT_POST, 'service_id');

            // Traer datos del servicio
            $service = fetch_service_details($db, $service_id, $church_id);
            $is_admin = $_SESSION['role'] === 'admin';
            $segment_assignments = fetch_all_segment_assignments($db, $service_id);
            if ($service) {
                ob_start();
                include '../components/modal/view_schedule_modal.php';
                $content = ob_get_clean();

                echo json_encode(['status' => 'success', 'message' => 'Service schedule fetched successfully', 'view' => $content, 'is_admin' => $is_admin, 'segment_assignments' => $segment_assigments]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Service not found']);
            }
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'fetch_manage_setlist':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $service_id = filter_input(INPUT_POST, 'service_id');
            $segment_id = filter_input(INPUT_POST, 'segment_id');
            $segment = fetch_segment_by_id($db, $segment_id, $service_id);
            $songs = fetch_all_songs($db, $church_id);
            $worship_team_members = fetch_available_musicians($db, $church_id);
            // Actualizar el setlist después de agregar la canción
            $setlist = fetch_segment_setlist($db, $segment_id);

            $assignments = fetch_segment_assignments($db, $segment_id);


            if ($segment) {
                ob_start();
                include '../components/modal/manage_setlist_modal.php';
                $content = ob_get_clean();

                echo json_encode(['status' => 'success', 'message' => 'Manage setlist modal loaded', 'view' => $content]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Segment not found']);
            }
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'add_song_to_setlist':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $service_id = filter_input(INPUT_POST, 'service_id', FILTER_SANITIZE_NUMBER_INT);
            $segment_id = filter_input(INPUT_POST, 'segment_id', FILTER_SANITIZE_NUMBER_INT);
            $song_id = filter_input(INPUT_POST, 'song_id', FILTER_SANITIZE_NUMBER_INT);
            $song_key = filter_input(INPUT_POST, 'song_key');

            if (!$song_id || !$segment_id) {
                throw new Exception('Song and segment are required.');
            }

            add_song_to_setlist($db, $service_id, $segment_id, $song_id, $song_key);

            // Actualizar el setlist después de agregar la canción
            $setlist = fetch_segment_setlist($db, $segment_id);

            $content = '';
            ob_start();
            foreach ($setlist as $song) :
                $song_id = $song['song_id'];
                $song = fetch_song_data($db, $song_id);
                $song_key = $song['key_signature'] ?: 'Original';
                $song['title'] = htmlspecialchars($song['title']);
                $song['artist'] = htmlspecialchars($song['artist']);
                include '../components/card/setlist_songs_card.php';
            endforeach;

            $content .= ob_get_clean();

            echo json_encode(['status' => 'success', 'message' => 'Song added to setlist successfully', 'view' => $content]);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'remove_song_from_setlist':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $service_id = filter_input(INPUT_POST, 'service_id', FILTER_SANITIZE_NUMBER_INT);
            $segment_id = filter_input(INPUT_POST, 'segment_id', FILTER_SANITIZE_NUMBER_INT);
            $song_id = filter_input(INPUT_POST, 'song_id', FILTER_SANITIZE_NUMBER_INT);

            if (!$song_id || !$segment_id) {
                throw new Exception('Song and segment are required.');
            }

            remove_song_from_setlist($db, $service_id, $segment_id, $song_id);

            // Actualizar el setlist después de eliminar la canción
            $setlist = fetch_segment_setlist($db, $segment_id);

            $content = '';
            ob_start();
            foreach ($setlist as $song) :
                $song_id = $song['song_id'];
                $song = fetch_song_data($db, $song_id);
                $song_key = $song['key_signature'] ?: 'Original';
                $song['title'] = htmlspecialchars($song['title']);

                include '../components/card/setlist_songs_card.php';
            endforeach;

            $content .= ob_get_clean();

            echo json_encode(['status' => 'success', 'message' => 'Song removed from setlist successfully', 'view' => $content]);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'search_musicians':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $query = filter_input(INPUT_POST, 'query');
            $segment_id = filter_input(INPUT_POST, 'segment_id', FILTER_SANITIZE_NUMBER_INT);

            if (!$query) {
                throw new Exception('Search term is required.');
            }

            $musicians = search_musicians_to_add_to_setlist($db, $query, $church_id, $segment_id);

            echo json_encode(['status' => 'success', 'members' => $musicians]);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'fetch_setlist_songs':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $service_id = filter_input(INPUT_POST, 'service_id', FILTER_SANITIZE_NUMBER_INT);
            $segment_id = filter_input(INPUT_POST, 'segment_id', FILTER_SANITIZE_NUMBER_INT);
            $setlist = fetch_segment_setlist_song_data($db, $segment_id);

            echo json_encode(['status' => 'success', 'message' => 'Setlist songs fetched successfully', 'songs' => $setlist]);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'save_setlist_assignments':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $segment_id = filter_input(INPUT_POST, 'segment_id', FILTER_SANITIZE_NUMBER_INT);
            $service_id = filter_input(INPUT_POST, 'service_id', FILTER_SANITIZE_NUMBER_INT);
            $assignments = json_decode($_POST['assignments'], true);

            if (!$segment_id || !$assignments) {
                throw new Exception('Segment ID and assignments are required.');
            }

            $db->beginTransaction();
            save_segment_assignments($db, $segment_id, $assignments);
            $db->commit();

            echo json_encode(['status' => 'success', 'message' => 'Assignments saved successfully']);
        } catch (PDOException | Exception $e) {
            if ($db->inTransaction()) $db->rollBack();
            error_log('Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'add_musician_to_setlist':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $segment_id = filter_input(INPUT_POST, 'segment_id', FILTER_SANITIZE_NUMBER_INT);
            $service_id = filter_input(INPUT_POST, 'service_id', FILTER_SANITIZE_NUMBER_INT);
            $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

            if (!$segment_id || !$user_id) {
                throw new Exception('Segment ID and user ID are required.');
            }

            $segment_assignment_id = add_musician_to_setlist($db, $segment_id, $user_id);

            // Actualizar las asignaciones después de agregar el músico
            $assignments = fetch_segment_assignments_by_user($db, $segment_id, $user_id);
            $content = '';
            ob_start();
            foreach ($assignments as $a) :
                include '../components/row/assigned_musician_row.php';
            endforeach;
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'message' => 'Musician added to setlist successfully', 'view' => $content]);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'remove_musician_from_setlist':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $segment_id = filter_input(INPUT_POST, 'segment_id', FILTER_SANITIZE_NUMBER_INT);
            $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

            if (!$segment_id || !$user_id) {
                throw new Exception('Segment ID and user ID are required.');
            }

            remove_musician_from_setlist($db, $segment_id, $user_id);

            echo json_encode(['status' => 'success', 'message' => 'Musician removed from setlist successfully']);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // Manage Audio
    case 'fetch_manage_audio':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $service_id = filter_input(INPUT_POST, 'service_id');
            $segment_id = filter_input(INPUT_POST, 'segment_id');
            $team_id = filter_input(INPUT_POST, 'team_id');

            $audio_users = fetch_team_members($db, $team_id);

            ob_start();
            include '../components/modal/manage_audio_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'message' => 'Manage audio modal loaded', 'view' => $content, 'audio_users' => $audio_users]);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
}
