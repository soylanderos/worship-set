<div class="modal fade" id="manageAudioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg border-0">

            <!-- HEADER -->
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="material-symbols-rounded">equalizer</i> Manage Audio Setup
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- Mixer Section -->
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2">
                        <i class="material-symbols-rounded align-middle me-1">tune</i> Mixer
                    </h6>
                    <div class="d-flex gap-2 mb-3">
                        <select class="form-select" id="select_mixer_user">
                            <option selected disabled>Select a user...</option>
                            <?php foreach ($audio_users as $user): ?>
                                <option value="<?= $user['user_id'] ?>"><?= htmlspecialchars($user['user_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-success" id="addMixerUser">
                            <i class="material-symbols-rounded align-middle">add</i>
                        </button>
                    </div>
                    <!-- Lista dinámica -->
                    <div id="mixerAssignments" class="border rounded p-2 bg-light" style="min-height: 80px;">
                        <p class="text-muted small mb-0">No users assigned yet.</p>
                    </div>
                </div>

                <!-- Stage Manager Section -->
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2">
                        <i class="material-symbols-rounded align-middle me-1">group</i> Stage Manager
                    </h6>
                    <div class="d-flex gap-2 mb-3">
                        <select class="form-select" id="select_stage_user">
                            <option selected disabled>Select a user...</option>
                            <?php foreach ($audio_users as $user): ?>
                                <option value="<?= $user['user_id'] ?>"><?= htmlspecialchars($user['user_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-success" id="addStageUser">
                            <i class="material-symbols-rounded align-middle">add</i>
                        </button>
                    </div>
                    <!-- Lista dinámica -->
                    <div id="stageAssignments" class="border rounded p-2 bg-light" style="min-height: 80px;">
                        <p class="text-muted small mb-0">No users assigned yet.</p>
                    </div>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer bg-light rounded-bottom-4">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="btn_save_audio_setup">
                    <i class="material-symbols-rounded align-middle me-1">save</i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #mixerAssignments .badge,
    #stageAssignments .badge {
        font-size: 14px;
        padding: 8px 12px;
        margin-right: 6px;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
    }
</style>
