<div class="modal fade" id="viewScheduleModal" tabindex="-1" aria-labelledby="viewScheduleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <!-- Header -->
            <div class="modal-header bg-gradient-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="viewScheduleLabel">
                    <i class="material-symbols-rounded">event_note</i>
                    Full Schedule for <?= htmlspecialchars($service['title']) ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <?php if (!empty($segments)): ?>
                    <div class="timeline">
                        <?php foreach ($segments as $segment): ?>
                            <div class="timeline-item d-flex flex-column flex-md-row gap-3 p-3 rounded-3 mb-3 shadow-sm bg-white">
                                <!-- Time Block -->
                                <div class="text-center text-primary fw-bold" style="min-width: 100px;">
                                    <div style="font-size: 1rem;">
                                        <?= $segment['start_time'] ? date('g:i A', strtotime($segment['start_time'])) : '—' ?>
                                    </div>
                                    <small class="text-muted"><?= $segment['duration_minutes'] ? $segment['duration_minutes'] . ' min' : '—' ?></small>
                                </div>

                                <!-- Content -->
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">
                                        <?= htmlspecialchars($segment['title']) ?>
                                        <span class="badge bg-secondary ms-2"><?= htmlspecialchars($segment['team_name'] ?? 'N/A') ?></span>
                                    </h6>
                                    <?php if (!empty($segment['description'])): ?>
                                        <p class="text-muted mb-2 small"><?= nl2br(htmlspecialchars($segment['description'])) ?></p>
                                    <?php endif; ?>

                                    <!-- Leader & Assignments -->
                                    <?php if (!empty($segment['leader_name']) || !empty($segment['assignments'])): ?>
                                        <div class="d-flex flex-column flex-sm-row gap-2">
                                            <?php if (!empty($segment['leader_name'])): ?>
                                                <span class="badge bg-primary text-white">
                                                    <i class="material-symbols-rounded align-middle me-1" style="font-size:16px;">star</i>
                                                    Leader: <?= htmlspecialchars($segment['leader_name']) ?>
                                                </span>
                                            <?php endif; ?>

                                            <?php if (!empty($segment['assignments'])): ?>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <?php foreach ($segment['assignments'] as $assignment): ?>
                                                        <span class="badge bg-light border text-dark small">
                                                            <?= htmlspecialchars($assignment['user_name'] ?? 'Unknown') ?> (<?= htmlspecialchars($assignment['role'] ?? 'N/A') ?>)
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted p-4">
                        <i class="material-symbols-rounded mb-2" style="font-size: 2rem;">info</i><br>
                        No segments available yet.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-light rounded-bottom-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<style>
    .timeline {
        position: relative;
        padding-left: 20px;
        border-left: 3px solid #dee2e6;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -12px;
        top: 12px;
        width: 14px;
        height: 14px;
        background: #4a6cf7;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    @media (max-width: 768px) {
        .timeline-item {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>