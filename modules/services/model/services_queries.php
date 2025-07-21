<?php
    function add_musician_to_setlist($db, $segment_id, $user_id) {
        try {
            $query = "INSERT INTO segment_assignments (segment_id, user_id) VALUES (:segment_id, :user_id)";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':segment_id', $segment_id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            // Return the last inserted ID
            return $db->lastInsertId();
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            throw new Exception('Failed to add musician to setlist');
        }
    }

    function remove_musician_from_setlist($db, $segment_id, $user_id) {
        try {
            $query = "DELETE FROM segment_assignments WHERE segment_id = :segment_id AND user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':segment_id', $segment_id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            // Return the number of affected rows
            return $stmt->rowCount();
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            throw new Exception('Failed to remove musician from setlist');
        }
    }

    function fetch_segment_assignments_by_user($db, $segment_id, $user_id) {
        try {
            $query = "SELECT segment_assignments.*, users.name AS user_name, users.id AS user_id
                      FROM segment_assignments
                      JOIN users ON segment_assignments.user_id = users.id
                      WHERE segment_assignments.segment_id = :segment_id AND segment_assignments.user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':segment_id', $segment_id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            throw new Exception('Failed to fetch segment assignments');
        }
    }

    function fetch_all_segment_assignments($db, $service_id) {
        try {
            $query = "SELECT segment_assignments.*, users.name AS user_name, users.id AS user_id
                      FROM segment_assignments
                      JOIN users ON segment_assignments.user_id = users.id
                      WHERE segment_assignments.service_id = :service_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':service_id', $service_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            throw new Exception('Failed to fetch all segment assignments');
        }
    }

    function fetch_audio_users($db, $church_id) {
        try {
            $query = "SELECT * FROM users WHERE church_id = :church_id AND role IN ('audio', 'admin')";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':church_id', $church_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException | ErrorException | Exception $e) {
            error_log('Database Error: ' . $e->getMessage());
            throw new Exception('Failed to fetch audio users');
        }
    }