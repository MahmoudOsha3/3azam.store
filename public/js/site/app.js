
let cart = [];
// السلة والفلترة
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelector('.tab-btn.active').classList.remove('active');
        btn.classList.add('active');
        renderMenu(btn.dataset.filter);
    });
});

function toggleCart() {
    document.getElementById('cartSidebar').classList.toggle('active');
}

