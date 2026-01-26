function toggleSidebar() {
    const sidebar = document.getElementById('mainSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const icon = document.getElementById('toggleIcon');
    const body = document.body;

    // تبديل الكلاسات
    sidebar.classList.toggle('active');
    overlay.classList.toggle('show');
    body.classList.toggle('sidebar-open');

    // تغيير الأيقونة
    if (sidebar.classList.contains('active')) {
        icon.classList.replace('fa-bars', 'fa-times');
    } else {
        icon.classList.replace('fa-times', 'fa-bars');
    }
}

// إغلاق السايد بار تلقائياً عند تكبير الشاشة لضمان عدم حدوث مشاكل في التصميم
window.addEventListener('resize', function() {
    if (window.innerWidth > 1024) {
        document.getElementById('mainSidebar').classList.remove('active');
        document.getElementById('sidebarOverlay').classList.remove('show');
        document.body.classList.remove('sidebar-open');
        document.getElementById('toggleIcon').classList.replace('fa-times', 'fa-bars');
    }
});
