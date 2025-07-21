<div class="card shadow-sm border-0 setlist-card">
    <div class="card-body d-flex justify-content-between align-items-center py-2 px-3">
        <!-- Song Info -->
        <div>
            <h6 class="mb-0 fw-semibold text-primary"><?= htmlspecialchars($song['title']) ?></h6>
            <small class="text-muted">Key: <?= $song_key ?></small>
        </div>

        <!-- Actions -->
        <div class="d-flex gap-2">
            <button data-service-id="<?= $service_id ?>" data-segment-id="<?= $segment_id ?>" data-song-id="<?= $song['id'] ?>" class="btn btn-sm btn-outline-danger btn_remove_setlist_song" title="Remove Song">
                <span class="material-symbols-rounded align-middle">delete</span>
            </button>
        </div>
    </div>
</div>