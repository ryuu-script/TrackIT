document.addEventListener('DOMContentLoaded', () => {

    const addModal = document.getElementById('addModal');

    /* =========================
       MODAL OPEN / CLOSE
    ========================= */

    window.openAddModal = function () {
        if (addModal) {
            addModal.classList.add('show');
        }
    };

    window.closeModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('show');
        }
    };

    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('users-modal')) {
            e.target.classList.remove('show');
        }
    });

    /* =========================
       INLINE EDIT (UNCHANGED LOGIC)
    ========================= */

    window.startEdit = function (btn) {
        const row = btn.closest('tr');

        row.classList.add('row-editing');

        row.querySelectorAll('.cell-display').forEach(el => el.style.display = 'none');
        row.querySelectorAll('.cell-input').forEach(el => el.style.display = 'inline-block');

        row.querySelector('.edit-btn').style.display = 'none';
        row.querySelector('.confirm-btn').style.display = 'inline-flex';
        row.querySelector('.delete-btn-wrap').style.display = 'none';
        row.querySelector('.cancel-btn').style.display = 'inline-flex';

        row.querySelector('.input-type').value = row.dataset.type;
        row.querySelector('.input-status').value = row.dataset.status;
    };

    window.cancelEdit = function (btn) {
        const row = btn.closest('tr');

        row.classList.remove('row-editing');

        row.querySelectorAll('.cell-display').forEach(el => el.style.display = '');
        row.querySelectorAll('.cell-input').forEach(el => el.style.display = 'none');

        row.querySelector('.edit-btn').style.display = 'inline-flex';
        row.querySelector('.confirm-btn').style.display = 'none';
        row.querySelector('.delete-btn-wrap').style.display = '';
        row.querySelector('.cancel-btn').style.display = 'none';
    };

    window.confirmEdit = function (btn) {
        const row = btn.closest('tr');

        document.getElementById('inline_asset_id').value = row.dataset.id;
        document.getElementById('inline_asset_name').value = row.querySelector('.input-name').value;
        document.getElementById('inline_type').value = row.querySelector('.input-type').value;
        document.getElementById('inline_status').value = row.querySelector('.input-status').value;

        document.getElementById('inline-edit-form').submit();
    };

});