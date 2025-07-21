<tr data-user-id="<?= $a['user_id'] ?>" data-segment-id="<?= $segment_id ?>" class="assigned-musician-row">
    <td data-label="Name" class="musician-name"><?= htmlspecialchars($a['user_name']) ?></td>
    <td data-label="Role">
        <select class="form-select form-select-sm musician-role w-100">
            <option selected disabled>Select Role</option>
            <option value="Lead Vocal" <?= $a['role'] == 'Lead Vocal' ? 'selected' : '' ?>>Lead Vocal</option>
            <option value="Background Vocal" <?= $a['role'] == 'Background Vocal' ? 'selected' : '' ?>>Background Vocal</option>
            <option value="Guitar" <?= $a['role'] == 'Guitar' ? 'selected' : '' ?>>Guitar</option>
            <option value="Bass" <?= $a['role'] == 'Bass' ? 'selected' : '' ?>>Bass</option>
            <option value="Drums" <?= $a['role'] == 'Drums' ? 'selected' : '' ?>>Drums</option>
            <option value="Keyboard" <?= $a['role'] == 'Keyboard' ? 'selected' : '' ?>>Keyboard</option>
        </select>
    </td>
    <td data-label="Song to Lead">
        <select class="form-select form-select-sm song-select <?= $a['role'] == 'Lead Vocal' ? '' : 'd-none' ?> w-100">
            <option selected disabled>Choose Song</option>
            <?php foreach ($setlist as $song_item):
                $song_data = fetch_song_data($db, $song_item['song_id']); ?>
                <option value="<?= $song_item['song_id'] ?>" <?= $a['song_id'] == $song_item['song_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($song_data['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </td>
    <td data-label="MD" class="text-center">
        <input type="checkbox" class="form-check-input md-checkbox" <?= $a['is_md'] ? 'checked' : '' ?>>
    </td>
    <td data-label="Actions">
        <button class="btn btn-sm btn-danger btn-remove-member" 
                data-segment-id="<?= $segment_id ?>" 
                data-user-id="<?= $a['user_id'] ?>" 
                title="Remove Member">
            <i class="material-symbols-rounded">delete</i>
        </button>
    </td>
</tr>