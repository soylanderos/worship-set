<div class="modal fade" id="createServiceModal" tabindex="-1" aria-labelledby="createServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content shadow rounded-4 border-0">
            
            <!-- Header -->
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold">Create New Service</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <form id="create_service_form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="service_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="service_title" name="title" required>
                        </div>
                        <div class="col-md-6">
                            <label for="service_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="service_date" name="service_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="col-md-6">
                            <label for="notes" class="form-label">Notes (optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="5" placeholder="Enter any additional notes here..."></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-light rounded-bottom-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="create_service_form" class="btn btn-primary">
                    <span class="material-symbols-rounded align-middle me-1">save</span> Save
                </button>
            </div>

        </div>
    </div>
</div>
