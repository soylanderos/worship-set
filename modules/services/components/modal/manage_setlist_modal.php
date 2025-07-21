<div class="modal fade" id="manageSetlistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg">

            <!-- HEADER -->
            <div class="modal-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold d-flex align-items-center">
                    <i class="material-symbols-rounded me-2">music_note</i>
                    Manage Worship Setlist
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body p-3 p-md-4">
                <!-- NAV TABS -->
                <ul class="nav nav-tabs mb-3" id="setlistTabs" role="tablist">
                    <li class="nav-item w-50">
                        <button class="nav-link active w-100" id="tab-setlist" data-bs-toggle="tab" data-bs-target="#tabSetlist" type="button" role="tab">
                            <i class="material-symbols-rounded me-1">playlist_play</i> Setlist
                        </button>
                    </li>
                    <li class="nav-item w-50">
                        <button class="nav-link w-100" id="tab-assignments" data-bs-toggle="tab" data-bs-target="#tabAssignments" type="button" role="tab">
                            <i class="material-symbols-rounded me-1">group</i> Assign Musicians
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- TAB 1: Setlist -->
                    <div class="tab-pane fade show active" id="tabSetlist" role="tabpanel">
                        <!-- ADD SONG -->
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary mb-2">Add a Song</h6>
                            <div class="row g-2 align-items-end">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small">Song</label>
                                    <select id="song_to_select" class="form-select">
                                        <option selected disabled>Select a song</option>
                                        <?php foreach ($songs as $song): ?>
                                            <option value="<?= $song['id'] ?>">
                                                <?= htmlspecialchars($song['title']) ?> — <?= htmlspecialchars($song['artist']) ?> (<?= $song['key_signature'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label small">Key</label>
                                    <select id="song_key" class="form-select">
                                        <option value="">Original</option>
                                        <option value="C">C</option>
                                        <option value="Db">Db</option>
                                        <option value="D">D</option>
                                        <option value="Eb">Eb</option>
                                        <option value="E">E</option>
                                        <option value="F">F</option>
                                        <option value="Gb">Gb</option>
                                        <option value="G">G</option>
                                        <option value="Ab">Ab</option>
                                        <option value="A">A</option>
                                        <option value="Bb">Bb</option>
                                        <option value="B">B</option>
                                    </select>
                                </div>
                                <div class="col-6 col-md-3">
                                    <button class="btn btn-success w-100" data-service-id="<?= $service_id ?>" data-segment-id="<?= $segment_id ?>" id="btn_add_song_to_setlist">
                                        <i class="material-symbols-rounded align-middle">add</i> Add
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- CURRENT SETLIST -->
                        <div>
                            <h6 class="fw-bold text-primary mb-2">Current Setlist</h6>
                            <p class="small text-muted mb-1">This is the current setlist for the service.</p>
                            <div id="setlist_songs" class="d-flex flex-column gap-2">
                                <?php if (empty($setlist)): ?>
                                    <div class="text-muted text-center small py-3">No songs added yet</div>
                                <?php else: ?>
                                    <?php foreach ($setlist as $song):
                                        $song_id = $song['song_id'];
                                        $song_key = $song['key_signature'] ?: 'Original';
                                        $song = fetch_song_data($db, $song_id);
                                        include '../components/card/setlist_songs_card.php';
                                    endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: Assignments -->
                    <div class="tab-pane fade" id="tabAssignments" role="tabpanel">
                        <h6 class="fw-bold text-primary mb-3"><i class="material-symbols-rounded me-1">group</i> Assign Musicians & Singers</h6>

                        <!-- SEARCH -->
                        <div class="d-flex flex-column flex-md-row gap-2 mb-3">
                            <input data-church-id="<?= $church_id ?>" type="text" id="musician_search" class="form-control" placeholder="Search musician by name...">
                            <button class="btn btn-primary w-100 w-md-auto" id="btnAddMusician" disabled>
                                <i class="material-symbols-rounded align-middle">person_add</i> Add
                            </button>
                        </div>

                        <!-- RESULTS -->
                        <div id="searchResults" class="border rounded p-2 mb-3 bg-light" style="max-height: 150px; overflow-y: auto;">
                            <p class="text-muted small text-center mb-0">Start typing to search...</p>
                        </div>

                        <!-- ASSIGNED LIST -->
                        <h6 class="fw-bold text-secondary mb-2">Assigned Members</h6>
                        <div class="table-responsive border rounded p-2 bg-white">
                            <div class="table-responsive border rounded p-2 bg-white">
                                <table class="table table-sm align-middle mb-0 assigned-table">
                                    <thead class="table-light d-none d-md-table-header-group">
                                        <tr>    
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Song to Lead</th>
                                            <th>MD</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="assignedMusicians" data-service-id="<?= $service_id ?>" data-segment-id="<?= $segment_id ?>">
                                        <?php if (!empty($assignments)): ?>
                                            <?php foreach ($assignments as $a):
                                                include '../components/row/assigned_musician_row.php';
                                            endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted small">No musicians assigned yet</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <style>
                                /* MOBILE STYLING */
                                @media (max-width: 768px) {
                                    .assigned-table thead {
                                        display: none;
                                    }

                                    .assigned-table tr {
                                        display: block;
                                        margin-bottom: 1rem;
                                        border: 1px solid #ddd;
                                        border-radius: 10px;
                                        padding: 10px;
                                        background: #fff;
                                    }

                                    .assigned-table td {
                                        display: flex;
                                        justify-content: space-between;
                                        align-items: center;
                                        padding: 8px 5px;
                                        font-size: 14px;
                                    }

                                    .assigned-table td::before {
                                        content: attr(data-label);
                                        font-weight: 600;
                                        color: #555;
                                        flex-basis: 40%;
                                        text-align: left;
                                    }

                                    .assigned-table select,
                                    .assigned-table input[type="checkbox"] {
                                        max-width: 55%;
                                    }

                                    .btn-remove-member {
                                        width: 40px;
                                        height: 40px;
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        border-radius: 50%;
                                    }
                                }
                            </style>

                        </div>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer bg-light rounded-bottom-4 d-flex flex-column flex-md-row gap-2">
                <button class="btn btn-outline-secondary w-100 w-md-auto" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary w-100 w-md-auto" id="btn_save_setlist"
                    data-service-id="<?= $service_id ?>" data-segment-id="<?= $segment_id ?>">
                    <i class="material-symbols-rounded align-middle me-1">save</i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
    }

    @media (max-width: 768px) {
        .modal-body {
            padding: 1rem !important;
        }

        .nav-tabs .nav-link {
            font-size: 14px;
            padding: 0.75rem;
        }

        .table thead {
            display: none;
        }

        .table tr {
            display: block;
            margin-bottom: 1rem;
            border-bottom: 1px solid #ddd;
        }

        .table td {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem;
            font-size: 14px;
        }
    }
</style>