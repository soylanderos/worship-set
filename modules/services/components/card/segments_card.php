<!-- Segment Card -->
<div class="col-12 col-md-6 mb-4">
    <div class="card border-0 shadow-sm h-100 segment-card" style="background: linear-gradient(135deg, #f9f9f9, #ffffff); border-radius:12px;">
        <div class="card-body d-flex flex-column justify-content-between">

            <!-- Header -->
            <div>
                <!-- Title -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-primary mb-2 d-flex align-items-center gap-2">
                        <i class="material-symbols-rounded text-primary">segment</i>
                        <?= htmlspecialchars($segment['title'] ?? '') ?>
                    </h5>
                    <span class="badge bg-secondary text-light px-2 py-1" style="font-size: 12px;">
                        <i class="material-symbols-rounded align-middle me-1" style="font-size: 14px;">groups</i>
                        <?= htmlspecialchars($segment['team_name'] ?? 'No Team') ?>
                    </span>

                </div>

                <!-- Assignments -->
                <div class="mb-3">
                    <small class="text-muted fw-bold d-block mb-1">Assignments:</small>
                    <?php if (!empty($segment['assignments'])): ?>
                        <ul class="ps-3 mb-0 small">
                            <?php foreach ($segment['assignments'] as $assignment): ?>
                                <li>
                                    <i class="material-symbols-rounded align-middle me-1 text-success" style="font-size:16px;">check_circle</i>
                                    <?= htmlspecialchars($assignment['user_name'] ?? '') ?> —
                                    <em><?= htmlspecialchars($assignment['role'] ?? '') ?></em>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted small fst-italic">
                            <i class="material-symbols-rounded align-middle me-1">info</i>
                            No members assigned yet.
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex flex-wrap gap-2 mt-3">
                <?php
                $segment_team_id = $segment['team_id'];
                $leader_teams = $_SESSION['leader_teams'] ?? [];
                $can_edit = $is_admin || in_array($segment_team_id, array_column($leader_teams, 'team_id'));
                ?>

                <?php if (!$can_edit): ?>
                    <p class="text-muted small fst-italic">
                        <i class="material-symbols-rounded align-middle me-1">lock</i>
                        You don't have permissions to manage this section.
                    </p>
                <?php endif; ?>

                <?php if ($segment['team_name'] === 'Worship Team' && $can_edit): ?>
                    <button class="btn btn-sm btn-primary mt-2" id="btn_manage_setlist" data-team-id="<?= $segment['team_id'] ?>"
                        data-service-id="<?= $segment['service_id'] ?>" data-segment-id="<?= $segment['id'] ?>">
                        <i class="material-symbols-rounded align-middle">queue_music</i> Manage Setlist
                    </button>
                <?php endif; ?>

                <?php if ($segment['team_name'] === 'Consola' && $can_edit): ?>
                    <button class="btn btn-sm btn-warning mt-2 text-dark" id="btn_manage_audio" data-team-id="<?= $segment['team_id'] ?>"
                        data-service-id="<?= $segment['service_id'] ?>" data-segment-id="<?= $segment['id'] ?>">
                        <i class="material-symbols-rounded align-middle">equalizer</i> Manage Audio Setup
                    </button>
                <?php endif; ?>

                <?php if ($segment['team_name'] === 'Medios' && $can_edit): ?>
                    <button class="btn btn-sm btn-info mt-2 text-white" id="btn_manage_media" data-team-id="<?= $segment['team_id'] ?>"
                        data-service-id="<?= $segment['service_id'] ?>" data-segment-id="<?= $segment['id'] ?>">
                        <i class="material-symbols-rounded align-middle">movie</i> Manage Media Setup
                    </button>
                <?php endif; ?>

                <?php if ($segment['team_name'] === 'Redes' && $can_edit): ?>
                    <button class="btn btn-sm btn-success mt-2" id="btn_manage_social" data-team-id="<?= $segment['team_id'] ?>"
                        data-service-id="<?= $segment['service_id'] ?>" data-segment-id="<?= $segment['id'] ?>">
                        <i class="material-symbols-rounded align-middle">share</i> Manage Social Media
                    </button>
                <?php endif; ?>

                <?php if ($segment['team_name'] === 'Pastor/Predicador' && $can_edit): ?>
                    <button class="btn btn-sm btn-dark mt-2 text-white" id="btn_manage_sermon" data-team-id="<?= $segment['team_id'] ?>"
                        data-service-id="<?= $segment['service_id'] ?>" data-segment-id="<?= $segment['id'] ?>">
                        <i class="material-symbols-rounded align-middle">menu_book</i> Manage Sermon
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .segment-card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .segment-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, .15);
    }

    .segment-card .btn {
        border-radius: 30px;
        font-size: 13px;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .segment-card .btn {
            width: 100%;
        }
    }
</style>