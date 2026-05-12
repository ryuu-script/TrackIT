/* =========================
   ADD MODAL
========================= */

const addModal = document.getElementById('addModal');

function openAddModal() {
    if (addModal) addModal.style.display = 'flex';
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none';
}

/* =========================
   INLINE EDIT — START
========================= */

function startEdit(btn) {
    const row = btn.closest('tr');

    row.classList.add('row-editing');

    row.querySelectorAll('.cell-display').forEach(el => el.style.display = 'none');
    row.querySelectorAll('.cell-input').forEach(el => el.style.display = 'inline-block');

    row.querySelector('.edit-btn').style.display        = 'none';
    row.querySelector('.confirm-btn').style.display     = 'inline-flex';
    row.querySelector('.delete-btn-wrap').style.display = 'none';
    row.querySelector('.cancel-btn').style.display      = 'inline-flex';

    // Pre-select the current type value
    const typeSelect = row.querySelector('.input-type');
    if (typeSelect) typeSelect.value = row.dataset.type;

    const nameInput = row.querySelector('.input-name');
    if (nameInput) { nameInput.focus(); nameInput.select(); }

    const statusSelect = row.querySelector('.input-status');
    if (statusSelect) {
        statusSelect.value = row.dataset.status.trim();
    }
}

/* =========================
   INLINE EDIT — CANCEL
========================= */

function cancelEdit(btn) {
    const row = btn.closest('tr');

    row.classList.remove('row-editing');

    const nameInput   = row.querySelector('.input-name');
    const typeInput   = row.querySelector('.input-type');
    const statusInput = row.querySelector('.input-status');

    if (nameInput)   nameInput.value   = row.dataset.name;
    if (typeInput)   typeInput.value   = row.dataset.type;
    if (statusInput) statusInput.value = row.dataset.status;

    row.querySelectorAll('.cell-input').forEach(el => el.classList.remove('input-error'));
    row.querySelectorAll('.cell-display').forEach(el => el.style.display = '');
    row.querySelectorAll('.cell-input').forEach(el => el.style.display = 'none');

    row.querySelector('.edit-btn').style.display        = 'inline-flex';
    row.querySelector('.confirm-btn').style.display     = 'none';
    row.querySelector('.delete-btn-wrap').style.display = ''; 
    row.querySelector('.cancel-btn').style.display      = 'none';
}

/* =========================
   INLINE EDIT — CONFIRM
========================= */

function confirmEdit(btn) {
    const row = btn.closest('tr');

    const nameInput   = row.querySelector('.input-name');
    const typeInput   = row.querySelector('.input-type');
    const statusInput = row.querySelector('.input-status');

    [nameInput, typeInput, statusInput].forEach(el => el.classList.remove('input-error'));

    let valid = true;

    if (!nameInput.value.trim()) {
        nameInput.classList.add('input-error');
        nameInput.focus();
        valid = false;
    }

    if (!typeInput.value.trim()) {
        typeInput.classList.add('input-error');
        if (valid) typeInput.focus();
        valid = false;
    }

    if (!valid) return;

    document.getElementById('inline_asset_id').value   = row.dataset.id;
    document.getElementById('inline_asset_name').value = nameInput.value.trim();
    document.getElementById('inline_type').value       = typeInput.value.trim();
    document.getElementById('inline_status').value     = statusInput.value;

    document.getElementById('inline-edit-form').submit();
}

/* =========================
   CLICK OUTSIDE CLOSE (modal)
========================= */

window.addEventListener('click', (e) => {
    if (e.target.classList.contains('users-modal')) {
        e.target.style.display = 'none';
    }
});

/* =========================
   ESC — close modal OR cancel active edit
========================= */

window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (addModal && addModal.style.display === 'flex') {
            addModal.style.display = 'none';
            return;
        }

        const editingRow = document.querySelector('tr.row-editing');
        if (editingRow) {
            const cancelBtn = editingRow.querySelector('.cancel-btn');
            if (cancelBtn) cancelEdit(cancelBtn);
        }
    }

    if (e.key === 'Enter') {
        const editingRow = document.querySelector('tr.row-editing');
        if (editingRow) {
            const confirmBtn = editingRow.querySelector('.confirm-btn');
            if (confirmBtn) confirmEdit(confirmBtn);
        }
    }
});