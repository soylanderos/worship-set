<div class="container py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="fw-bold"><?= htmlspecialchars($service['title']) ?></h2>
            <p class="text-muted mb-1">Service Date: <strong><?= date('F j, Y', strtotime($service['service_date'])) ?></strong></p>
            <p class="text-muted">Time: <strong><?= date('g:i A', strtotime($service['start_time'])) ?></strong></p>
        </div>

        <div class="d-flex gap-2">
            <!-- Nuevo botón para ver el Schedule -->
            <button class="btn btn-outline-dark" id="btn_view_schedule" data-service-id="<?= $service['id'] ?>">
                <i class="material-symbols-rounded align-middle">schedule</i> View Schedule
            </button>
        </div>
    </div>

    <!-- Lista de Segmentos -->
    <h4 class="fw-bold mb-3">Order of Service</h4>

    <?php if (empty($segments)): ?>
        <div class="alert alert-light text-center shadow-sm p-4">
            <i class="material-symbols-rounded text-primary" style="font-size: 2rem;">info</i><br>
            <span class="fw-semibold">No segments have been added yet.</span>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($segments as $segment):
                include '../components/card/segments_card.php';
            endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .segment-card {
        border-radius: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .segment-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-size: 0.8rem;
    }

    .btn-sm {
        font-size: 14px;
        border-radius: 50px;
    }
</style>

