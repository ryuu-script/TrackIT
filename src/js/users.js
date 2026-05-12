// ELEMENTS
const searchInput = document.getElementById('userSearch');
const roleFilter = document.getElementById('roleFilter');
const usersGrid = document.getElementById('usersGrid');
const addModal = document.getElementById('addModal');
const editModal = document.getElementById('editModal');

// LIVE SEARCH & FILTER
function filterUsers() {
    const search = searchInput.value.toLowerCase();
    const role = roleFilter.value;
    const cards = document.querySelectorAll('.user-card');

    cards.forEach(card => {
        const username = card.dataset.username;
        const userRole = card.dataset.role;
        const matchesSearch = username.includes(search);
        const matchesRole = role === 'all' || userRole === role;
        card.style.display = (matchesSearch && matchesRole) ? 'block' : 'none';
    });
}

if (searchInput) searchInput.addEventListener('input', filterUsers);
if (roleFilter) roleFilter.addEventListener('change', filterUsers);

// MODAL CONTROLS
document.querySelectorAll('.open-add-btn').forEach(btn => {
    btn.onclick = () => { if (addModal) addModal.style.display = 'flex'; };
});

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function openEditModal(id, username, role) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_role').value = role;
    editModal.style.display = 'flex';
}

// WINDOW CLICKS
window.onclick = (e) => {
    if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
    }
};

window.onkeydown = (e) => {
    if (e.key === 'Escape') {
        if (addModal) addModal.style.display = 'none';
        if (editModal) editModal.style.display = 'none';
    }
};