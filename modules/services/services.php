<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Services</h1>
            <p class="text-muted mb-0">View and manage upcoming services.</p>
        </div>
        <button class="btn btn-primary" id="create_service">
            <span class="material-symbols-rounded align-middle me-1">add_circle</span> New Service
        </button>
    </div>

    <div id="services_list" class="row g-4">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): 
               include 'components/card/services_card.php';
            endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-secondary text-center">No services scheduled yet.</div>
            </div>
        <?php endif; ?>
    </div>
</div>