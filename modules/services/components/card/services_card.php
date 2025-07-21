<div class="col-12 col-md-6 col-lg-4">
    <div class="service-card card shadow-sm border-0 h-100 rounded-4 overflow-hidden">

        <!-- Header con color -->
        <div class="card-header text-white bg-gradient-primary p-3 d-flex align-items-center justify-content-between">
            <span class="fw-bold"><?= htmlspecialchars($service['title']) ?></span>
        </div>

        <!-- Body -->
        <div class="card-body d-flex flex-column">
            <!-- Fecha -->
            <div class="mb-3">
                <p class="mb-2 text-muted small">
                    <i class="material-symbols-rounded align-middle me-1 text-primary">event</i>
                    <?= date('F j, Y', strtotime($service['service_date'])) ?>
                </p>
                <p class="mb-0 text-muted small">
                    <i class="material-symbols-rounded align-middle me-1 text-primary">schedule</i>
                    <?= date('g:i A', strtotime($service['start_time'])) ?>
                </p>
            </div>

            <!-- Footer -->
            <div class="mt-auto d-flex justify-content-end gap-2">
                <button class="btn btn-outline-primary btn-sm btn_view_service" data-service-id="<?= $service['id'] ?>">
                    <i class="material-symbols-rounded align-middle me-1">visibility</i> View
                </button>
                <?php if ($is_admin): ?>
                    <button class="btn btn-light btn-sm">
                        <i class="material-symbols-rounded">more_vert</i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>