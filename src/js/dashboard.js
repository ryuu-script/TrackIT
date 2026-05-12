document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.dataset.filter;
        document.querySelectorAll('#logTableBody tr').forEach(row => {
            row.style.display = (filter === 'all' || row.dataset.action === filter) ? '' : 'none';
        });
    });
});