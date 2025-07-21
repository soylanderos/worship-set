<?php
session_start();
$session_user_id = $_SESSION["id"];
$church_id = $_SESSION["church_id"];
$user_role = $_SESSION["role"];
require "../model/admin_queries.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_request = filter_input(INPUT_POST, 'user_request');
} else {
    $user_request = filter_input(INPUT_GET, 'user_request');
}

switch ($user_request) {
    case 'fetch_admin_data':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            ob_start();
            include '../admin.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'message' => 'Login Successfully', 'view' => $content]);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'fetch_users':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $users = fetch_users($db, $church_id);

            ob_start();
            include '../components/users/users.php';
            $content = ob_get_clean();

            if (empty($users)) {
                $content = '<div class="alert alert-info">No users found.</div>';
            }

            echo json_encode(['status' => 'success', 'message' => 'Users fetched successfully', 'view' => $content]);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'fetch_teams':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $teams = fetch_teams($db, $church_id);

            ob_start();
            include '../components/teams/teams.php';
            $content = ob_get_clean();

            if (empty($teams)) {
                $content = '<div class="alert alert-info">No Teams found.</div>';
            }

            echo json_encode(['status' => 'success', 'message' => 'Teams fetched successfully', 'view' => $content]);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'fetch_team_details':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $team_id = filter_input(INPUT_POST, 'team_id', FILTER_SANITIZE_NUMBER_INT);
            $team = fetch_team_details($db, $team_id, $church_id);
            $team_members = fetch_team_members($db, $team_id, $church_id);


            if ($team) {
                ob_start();
                include '../components/teams/components/modal/team_details_modal.php';
                $content = ob_get_clean();

                echo json_encode(['status' => 'success', 'message' => 'Team details fetched successfully', 'view' => $content]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Team not found']);
            }
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
}