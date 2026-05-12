// ======================================
// ELEMENTS
// ======================================

const usersSearchInput =
    document.getElementById('userSearch');

const usersRoleFilter =
    document.getElementById('roleFilter');

const usersAddModal =
    document.getElementById('addModal');

const usersEditModal =
    document.getElementById('editModal');

// ======================================
// SEARCH + FILTER
// ======================================

function filterUsers() {

    const searchText =
        usersSearchInput.value.toLowerCase();

    const selectedRole =
        usersRoleFilter.value;

    // UPDATED UNIQUE CLASS
    const userCards =
        document.querySelectorAll('.users-card');

    userCards.forEach(card => {

        const username =
            card.dataset.username;

        const role =
            card.dataset.role;

        const matchesSearch =
            username.includes(searchText);

        const matchesRole =
            selectedRole === 'all'
            || role === selectedRole;

        // SHOW / HIDE
        if (matchesSearch && matchesRole) {

            card.style.display = 'block';

        } else {

            card.style.display = 'none';
        }
    });
}

// ======================================
// EVENT LISTENERS
// ======================================

if (usersSearchInput) {

    usersSearchInput.addEventListener(
        'input',
        filterUsers
    );
}

if (usersRoleFilter) {

    usersRoleFilter.addEventListener(
        'change',
        filterUsers
    );
}

// ======================================
// OPEN ADD MODAL
// ======================================

document
    .querySelectorAll('.open-add-btn')
    .forEach(button => {

        button.addEventListener('click', () => {

            if (usersAddModal) {

                usersAddModal.style.display = 'flex';
            }
        });
    });

// ======================================
// CLOSE MODAL
// ======================================

function closeModal(modalId) {

    const modal =
        document.getElementById(modalId);

    if (modal) {

        modal.style.display = 'none';
    }
}

// ======================================
// OPEN EDIT MODAL
// ======================================

function openEditModal(id, username, role) {

    const editId =
        document.getElementById('edit_id');

    const editUsername =
        document.getElementById('edit_username');

    const editRole =
        document.getElementById('edit_role');

    if (editId) {

        editId.value = id;
    }

    if (editUsername) {

        editUsername.value = username;
    }

    if (editRole) {

        editRole.value = role;
    }

    if (usersEditModal) {

        usersEditModal.style.display = 'flex';
    }
}

// ======================================
// CLICK OUTSIDE MODAL
// ======================================

window.addEventListener('click', (event) => {

    if (
        event.target.classList.contains(
            'users-modal'
        )
    ) {

        event.target.style.display = 'none';
    }
});

// ======================================
// ESC CLOSE
// ======================================

window.addEventListener('keydown', (event) => {

    if (event.key === 'Escape') {

        if (usersAddModal) {

            usersAddModal.style.display = 'none';
        }

        if (usersEditModal) {

            usersEditModal.style.display = 'none';
        }
    }
});

const usernameInput = document.querySelector('input[name="username"]');
const createBtn = document.getElementById('createUserBtn');

// collect existing usernames from the page
const existingUsers = Array.from(document.querySelectorAll('.users-card'))
    .map(card => card.dataset.username);

if (usernameInput && createBtn) {

    usernameInput.addEventListener('input', () => {

        const username = usernameInput.value.trim().toLowerCase();

        // reset state if empty
        if (username.length === 0) {
            createBtn.disabled = false;
            createBtn.innerText = "Create User";
            return;
        }

        const isTaken = existingUsers.includes(username);

        if (isTaken) {
            createBtn.disabled = true;
            createBtn.innerText = "Username Taken";
            createBtn.style.opacity = "0.5";
        } else {
            createBtn.disabled = false;
            createBtn.innerText = "Create User";
            createBtn.style.opacity = "1";
        }
    });
}