<?php
    function fetch_users($db, $church_id) {
        try {
            $query = "SELECT 
                    users.*, 
                    teams.name AS team_name,
                    team_members.is_leader,
                    team_members.position
                FROM users
                LEFT JOIN team_members ON users.id = team_members.user_id
                LEFT JOIN teams ON team_members.team_id = teams.id
                WHERE users.church_id = :church_id
            ";

            $stmt = $db->prepare($query);
            $stmt->bindValue(':church_id', $church_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_users: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_users: " . $e->getMessage());
            throw $e;
        }
    }


    function fetch_teams($db, $church_id) {
        try {
            $query = "SELECT 
                    teams.*, 
                    churches.name as church_name
                FROM teams
                LEFT JOIN churches ON teams.church_id = churches.id
                WHERE teams.church_id = :church_id
            ";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':church_id', $church_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_teams: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_teams: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_team_details($db, $team_id, $church_id) {
        try {
            $query = "SELECT 
                    teams.*, 
                    users.name AS leader_name,
                    users.id AS leader_id,
                    churches.name as church_name
                FROM teams
                LEFT JOIN team_members ON teams.id = team_members.team_id AND team_members.is_leader = 1
                LEFT JOIN users ON team_members.user_id = users.id
                LEFT JOIN churches ON teams.church_id = churches.id
                WHERE teams.id = :team_id AND teams.church_id = :church_id
            ";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':team_id', $team_id);
            $stmt->bindValue(':church_id', $church_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_team_details: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_team_details: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_team_members($db, $team_id) {
        try {
            $query = "SELECT 
                    users.id AS user_id, 
                    users.name AS user_name, 
                    team_members.is_leader,
                    team_members.position
                FROM team_members
                JOIN users ON team_members.user_id = users.id
                WHERE team_members.team_id = :team_id
            ";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':team_id', $team_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_team_members: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_team_members: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_team_leaders($db, $team_id) {
        try {
            $query = "SELECT
                    team_members.position,
                    users.id AS user_id, 
                    users.name AS user_name
                FROM team_members
                JOIN users ON team_members.user_id = users.id
                WHERE team_members.team_id = :team_id AND team_members.is_leader = 1
            ";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':team_id', $team_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_team_leaders: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_team_leaders: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_services($db, $church_id) {
        try {
            $query = "SELECT 
                    services.*, 
                    churches.name as church_name
                FROM services
                LEFT JOIN churches ON services.church_id = churches.id
                WHERE services.church_id = :church_id
            ";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':church_id', $church_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_services: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_services: " . $e->getMessage());
            throw $e;
        }
    }

    function create_service($db, $church_id, $title, $service_date, $start_time, $notes = null, $created_by = null) {
        try {
            $query = "INSERT INTO services (church_id, title, service_date, start_time, notes, created_by) 
                      VALUES (:church_id, :title, :service_date, :start_time, :notes, :created_by)";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':church_id', $church_id);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':service_date', $service_date);
            $stmt->bindValue(':start_time', $start_time);
            $stmt->bindValue(':notes', $notes);
            $stmt->bindValue(':created_by', $created_by);
            $stmt->execute();
            return $db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database error in create_service: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in create_service: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_service_details($db, $service_id, $church_id) {
        try {
            $query = "SELECT 
                    services.*, 
                    churches.name as church_name
                FROM services
                LEFT JOIN churches ON services.church_id = churches.id
                WHERE services.id = :service_id AND services.church_id = :church_id
            ";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':service_id', $service_id);
            $stmt->bindValue(':church_id', $church_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_service_details: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_service_details: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_segments_by_service_id($db, $service_id) {
        try {
            $query = "SELECT ss.*, 
                    t.name AS team_name
                FROM service_segments ss
                LEFT JOIN teams t ON ss.team_id = t.id
                WHERE ss.service_id = :service_id
            ";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':service_id', $service_id);
            $stmt->execute();
            $segments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($segments as &$segment) {
                // Obtener assignments
                $assignment_query = "SELECT sa.*, u.name AS user_name
                    FROM segment_assignments sa
                    LEFT JOIN users u ON sa.user_id = u.id
                    WHERE sa.segment_id = :segment_id
                ";
                $stmt_assign = $db->prepare($assignment_query);
                $stmt_assign->bindValue(':segment_id', $segment['id']);
                $stmt_assign->execute();
                $segment['assignments'] = $stmt_assign->fetchAll(PDO::FETCH_ASSOC);

                // Obtener contenidos extras (opcional)
                $content_query = "SELECT content_type, content
                    FROM segment_content
                    WHERE segment_id = :segment_id
                ";
                $stmt_content = $db->prepare($content_query);
                $stmt_content->bindValue(':segment_id', $segment['id']);
                $stmt_content->execute();
                $segment['extra_contents'] = $stmt_content->fetchAll(PDO::FETCH_ASSOC);
            }

            return $segments;

        } catch (PDOException $e) {
            error_log("Error in fetch_segments_by_service_id: " . $e->getMessage());
            throw $e;
        }
    }


    function fetch_teams_by_user_id($db, $user_id) {
        try {
            $query = "SELECT 
                        teams.id, 
                        teams.name 
                    FROM team_members
                    JOIN teams ON team_members.team_id = teams.id
                    WHERE team_members.user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_teams_by_user_id: " . $e->getMessage());
            throw $e;
        }
    }


    function create_default_segments($db, $service_id, $church_id) {
        // Lista de teams y títulos base
        $segments = [
            ['team_name' => 'Worship Team',         'title' => 'Worship Set'],
            ['team_name' => 'Consola',          'title' => 'Audio Setup'],
            ['team_name' => 'Medios',           'title' => 'Visuals'],
            ['team_name' => 'Redes',            'title' => 'Social Media Post'],
            ['team_name' => 'Pastor/Predicador','title' => 'Mensaje']
        ];

        foreach ($segments as $seg) {
            // Obtener el ID del equipo según nombre e iglesia
            $stmt = $db->prepare("SELECT id FROM teams WHERE church_id = :church_id AND name = :name");
            $stmt->execute([':church_id' => $church_id, ':name' => $seg['team_name']]);
            $team = $stmt->fetch(PDO::FETCH_ASSOC);

            $team_id = $team ? $team['id'] : null;

            $stmt = $db->prepare("INSERT INTO service_segments (service_id, team_id, title)
                                VALUES (:service_id, :team_id, :title)");
            $stmt->execute([
                ':service_id'   => $service_id,
                ':team_id'      => $team_id,
                ':title'        => $seg['title']
            ]);
        }
    }

    function fetch_all_songs($db, $church_id) {
        try {
            $query = "SELECT * FROM songs WHERE church_id = :church_id ORDER BY id DESC";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':church_id', $church_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_all_songs: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_all_songs: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_segment_songs($db, $segment_id) {
        try {
            $query = "SELECT ss.id AS setlist_id, songs.title, songs.artist, songs.key_signature
                    FROM segment_songs ss
                    JOIN songs ON ss.song_id = songs.id
                    WHERE ss.segment_id = :segment_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':segment_id', $segment_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Database error in fetch_segment_songs: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_segment_songs: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_segment_by_id($db, $segment_id, $service_id) {
        try {
            $query = "SELECT * FROM service_segments WHERE id = :segment_id AND service_id = :service_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':segment_id', $segment_id);
            $stmt->bindValue(':service_id', $service_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_segment_by_id: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_available_musicians($db, $church_id) {
        try {
            $query = "SELECT 
                    u.id AS user_id,
                    u.name AS user_name,
                    u.email,
                    tm.position
                FROM team_members tm
                INNER JOIN users u ON tm.user_id = u.id
                INNER JOIN teams t ON tm.team_id = t.id
                WHERE 
                    t.name = 'Worship Team'
                    AND t.church_id = :church_id
                    AND u.status = 'active'
                ORDER BY u.name
            ";

            $stmt = $db->prepare($query);
            $stmt->bindValue(':church_id', $church_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_available_musicians: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_available_musicians: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_segment_setlist($db, $segment_id) {
        try {
            $query = "SELECT * FROM worship_setlist WHERE segment_id = :segment_id ORDER BY sort_order ASC";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':segment_id', $segment_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_segment_setlist: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_segment_setlist: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_segment_setlist_song_data($db, $segment_id) {
        try {
            $query = "SELECT ws.*, s.title, s.artist, s.key_signature
                FROM worship_setlist ws
                JOIN songs s ON ws.song_id = s.id
                WHERE ws.segment_id = :segment_id
                ORDER BY ws.sort_order ASC";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':segment_id', $segment_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_segment_setlist: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_segment_setlist: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_segment_assignments($db, $segment_id) {
        try {
            $query = "SELECT 
                    sa.*, u.name AS user_name
                FROM segment_assignments sa
                JOIN users u ON sa.user_id = u.id
                WHERE sa.segment_id = :segment_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':segment_id', $segment_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_segment_assignments: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_segment_assignments: " . $e->getMessage());
            throw $e;
        }
    }


    function add_song_to_setlist($db, $service_id, $segment_id, $song_id, $song_key) {
        try {
            $query = "INSERT INTO worship_setlist (service_id, segment_id, song_id, key_signature) VALUES (:service_id, :segment_id, :song_id, :key_signature)";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':service_id', $service_id);
            $stmt->bindValue(':segment_id', $segment_id);
            $stmt->bindValue(':song_id', $song_id);
            $stmt->bindValue(':key_signature', $song_key);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error in add_song_to_setlist: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in add_song_to_setlist: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_song_data($db, $song_id) {
        try {
            $query = "SELECT * FROM songs WHERE id = :song_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':song_id', $song_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in fetch_song_data: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_song_data: " . $e->getMessage());
            throw $e;
        }
    }

    function remove_song_from_setlist($db, $service_id, $segment_id, $song_id) {
        try {
            $query = "DELETE FROM worship_setlist WHERE service_id = :service_id AND segment_id = :segment_id AND song_id = :song_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':service_id', $service_id);
            $stmt->bindValue(':segment_id', $segment_id);
            $stmt->bindValue(':song_id', $song_id);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error in remove_song_from_setlist: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in remove_song_from_setlist: " . $e->getMessage());
            throw $e;
        }
    }

    function search_musicians_to_add_to_setlist($db, $query, $church_id, $segment_id) {
        try {
            $like_query = "%" . $query . "%";

            $sql = "SELECT 
                        u.id,
                        u.name,
                        u.email,
                        tm.position,
                        tm.is_leader,
                        t.name AS team_name
                    FROM users u
                    INNER JOIN team_members tm ON u.id = tm.user_id
                    INNER JOIN teams t ON tm.team_id = t.id
                    WHERE 
                        u.church_id = :church_id
                        AND u.status = 'active'
                        AND t.name = 'Worship Team'
                        AND (u.name LIKE :query OR u.email LIKE :query)
                        AND u.id NOT IN (
                            SELECT user_id FROM segment_assignments WHERE segment_id = :segment_id
                        )
                    ORDER BY u.name ASC
                    LIMIT 10";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':church_id', $church_id, PDO::PARAM_INT);
            $stmt->bindValue(':query', $like_query, PDO::PARAM_STR);
            $stmt->bindValue(':segment_id', $segment_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Database error in search_musicians_to_add_to_setlist: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in search_musicians_to_add_to_setlist: " . $e->getMessage());
            throw $e;
        }
    }


    function save_segment_assignments($db, $segment_id, $assignments) {
        try {
            // 1. Eliminar asignaciones previas del segmento
            $query_delete = "DELETE FROM segment_assignments WHERE segment_id = :segment_id";
            $stmt_delete = $db->prepare($query_delete);
            $stmt_delete->execute([':segment_id' => $segment_id]);

            // 2. Insertar nuevas asignaciones
            $query_insert = "INSERT INTO segment_assignments 
                (segment_id, user_id, role, song_id, is_md) 
                VALUES (:segment_id, :user_id, :role, :song_id, :is_md)";
            $stmt_insert = $db->prepare($query_insert);

            foreach ($assignments as $a) {
                $stmt_insert->execute([
                    ':segment_id' => $segment_id,
                    ':user_id'    => $a['user_id'],
                    ':role'       => $a['role'],
                    ':song_id'    => !empty($a['song_id']) ? $a['song_id'] : null,
                    ':is_md'      => isset($a['is_md']) ? (int)$a['is_md'] : 0
                ]);
            }
        } catch (PDOException $e) {
            error_log("Database error in save_segment_assignments: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in save_segment_assignments: " . $e->getMessage());
            throw $e;
        }
    }
