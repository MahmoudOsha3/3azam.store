
document.addEventListener('DOMContentLoaded', () => {
    const html = document.documentElement;
    const themeToggle = document.getElementById('theme-toggle');
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');
    const searchToggle = document.getElementById('search-toggle');
    const searchContainer = document.getElementById('search-container');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const usernameDisplay = document.getElementById('username-display');
    const mobileUsernameDisplay = document.getElementById('mobile-username-display'); // This will be used for mobile admin sidebar later

    // Language elements
    const langDropdownBtn = document.getElementById('lang-dropdown-btn');
    const langDropdown = document.querySelector('.language-dropdown');
    const langOptions = document.querySelectorAll('.language-dropdown-content a');
    const currentLangDisplay = document.querySelector('.current-lang-display');

    // Cart elements (for header cart dropdown)
    const headerCartItemsContainer = document.getElementById('cart-items');
    const headerCartCountSpan = document.getElementById('cart-count');
    const headerCartTotalSpan = document.getElementById('cart-total');
    const headerEmptyCartMessage = document.querySelector('.empty-cart-message');

    // Checkout Modal elements
    const checkoutModal = document.getElementById('checkout-modal');
    const closeModalBtn = document.querySelector('.close-button');
    const paymentSection = document.getElementById('payment-section');
    const loginRegisterSection = document.getElementById('login-register-section');

    // Admin Dashboard specific elements
    const adminSidebarLinks = document.querySelectorAll('.admin-sidebar-link');
    const adminSubmenus = document.querySelectorAll('.admin-submenu');
    const adminSubmenuLinks = document.querySelectorAll('.admin-submenu-link');
    const adminSections = document.querySelectorAll('.admin-section');

    // Product Management elements
    const productForm = document.getElementById('product-form');
    const productIdInput = document.getElementById('product-id');
    const productNameArInput = document.getElementById('product-name-ar');
    const productNameEnInput = document.getElementById('product-name-en');
    const productPriceInput = document.getElementById('product-price');
    const productImageInput = document.getElementById('product-image');
    // const productsTableBody = document.getElementById('products-table-body');
    const noProductsMessage = document.getElementById('no-products-message');
    const cancelEditProductBtn = document.getElementById('cancel-edit-product');

    // Order Management elements
    const ordersTableBody = document.getElementById('orders-table-body');
    const noOrdersMessage = document.getElementById('no-orders-message');

    // User Management elements
    const usersTableBody = document.getElementById('users-table-body');
    const noUsersMessage = document.getElementById('no-users-message');

    // Dashboard Overview elements
    const totalProductsCount = document.getElementById('total-products-count');
    const pendingOrdersCount = document.getElementById('pending-orders-count');
    const totalUsersCount = document.getElementById('total-users-count');
    const totalSalesAmount = document.getElementById('total-sales-amount');


    // Initialize data from localStorage or empty arrays
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let products = JSON.parse(localStorage.getItem('adminProducts')) || [];
    let orders = JSON.parse(localStorage.getItem('adminOrders')) || [];
    let users = JSON.parse(localStorage.getItem('adminUsers')) || [];

    let isLoggedIn = true; // Assume admin is logged in for the dashboard page

    // --- Theme Toggle Functionality ---
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        html.classList.add('dark');
        moonIcon.classList.remove('hidden');
        sunIcon.classList.add('hidden');
    } else {
        html.classList.remove('dark');
        sunIcon.classList.remove('hidden');
        moonIcon.classList.add('hidden');
    }

    themeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        if (html.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
            moonIcon.classList.remove('hidden');
            sunIcon.classList.add('hidden');
        } else {
            localStorage.setItem('theme', 'light');
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
        }
    });

    // --- Search Toggle Functionality ---
    searchToggle.addEventListener('click', () => {
        searchContainer.classList.toggle('hidden');
        if (!searchContainer.classList.contains('hidden')) {
            document.getElementById('search-input').focus();
        }
    });

    // --- Mobile Menu Toggle Functionality (for admin sidebar on mobile) ---
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // --- User Login Simulation (Placeholder) ---
    // This is for the main store, not the admin login itself
    const userName = "المدير"; // Example admin name
    // For a real app, admin login would be handled separately
    // For now, we assume admin is "logged in" to this page
    // Removed the null checks here as the elements are now present in HTML
    if (usernameDisplay) {
        usernameDisplay.textContent = `مرحباً، ${userName}`;
        usernameDisplay.classList.remove('hidden');
    }
    if (mobileUsernameDisplay) {
        mobileUsernameDisplay.textContent = `مرحباً، ${userName}`;
        mobileUsernameDisplay.classList.remove('hidden');
    }

    // --- Language Switcher Functionality ---
    let currentLang = localStorage.getItem('lang') || 'ar'; // Default to Arabic

    function setLanguage(lang) {
        currentLang = lang;
        html.lang = lang;
        html.dir = (lang === 'ar') ? 'rtl' : 'ltr';

        document.querySelectorAll('[data-lang-ar], [data-lang-en]').forEach(element => {
            if (lang === 'ar' && element.dataset.langAr) {
                if (element.tagName === 'INPUT' && element.hasAttribute('placeholder')) {
                    element.placeholder = element.dataset.langAr;
                } else {
                    element.textContent = element.dataset.langAr;
                }
            } else if (lang === 'en' && element.dataset.langEn) {
                if (element.tagName === 'INPUT' && element.hasAttribute('placeholder')) {
                    element.placeholder = element.dataset.langEn;
                } else {
                    element.textContent = element.dataset.langEn;
                }
            }
        });

        currentLangDisplay.textContent = lang === 'ar' ? currentLangDisplay.dataset.langAr : currentLangDisplay.dataset.langEn;

        const dropdownContent = document.querySelector('.language-dropdown-content');
        if (lang === 'ar') {
            dropdownContent.style.right = '0';
            dropdownContent.style.left = 'auto';
        } else {
            dropdownContent.style.left = '0';
            dropdownContent.style.right = 'auto';
        }

        localStorage.setItem('lang', lang);
        renderHeaderCart(); // Re-render header cart for language
        renderProducts(); // Re-render products for language
        renderOrders(); // Re-render orders for language
        renderUsers(); // Re-render users for language
        updateDashboardOverview(); // Update dashboard overview for language
    }

    langDropdownBtn.addEventListener('click', (event) => {
        langDropdown.classList.toggle('show');
        event.stopPropagation();
    });

    langOptions.forEach(option => {
        option.addEventListener('click', (event) => {
            event.preventDefault();
            const selectedLang = event.target.dataset.langOption;
            setLanguage(selectedLang);
            langDropdown.classList.remove('show');
        });
    });

    document.addEventListener('click', (event) => {
        if (!langDropdown.contains(event.target)) {
            langDropdown.classList.remove('show');
        }
    });

    setLanguage(currentLang);

    // --- Shopping Cart Functionality (Header Dropdown - Minimal for Admin Page) ---
    function renderHeaderCart() {
        headerCartItemsContainer.innerHTML = '';
        let total = 0;
        let itemCount = 0;

        if (cart.length === 0) {
            headerEmptyCartMessage.classList.remove('hidden');
        } else {
            headerEmptyCartMessage.classList.add('hidden');
            cart.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.classList.add('flex', 'justify-between', 'items-center', 'py-2', 'border-b', 'border-gray-200', 'dark:border-gray-700');
                itemElement.innerHTML = `
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">${currentLang === 'ar' ? item.name_ar : item.name_en}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span data-lang-ar="الكمية:" data-lang-en="Qty:">الكمية:</span> ${item.quantity} | SAR ${(item.price * item.quantity).toFixed(2)}
                        </p>
                    </div>
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <button class="qty-decrease-btn text-gray-700 dark:text-gray-200 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-full w-6 h-6 flex items-center justify-center text-lg font-bold" data-product-id="${item.id}">-</button>
                        <span class="text-gray-800 dark:text-gray-100 font-semibold">${item.quantity}</span>
                        <button class="qty-increase-btn text-gray-700 dark:text-gray-200 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-full w-6 h-6 flex items-center justify-center text-lg font-bold" data-product-id="${item.id}">+</button>
                    </div>
                `;
                headerCartItemsContainer.appendChild(itemElement);
                total += item.price * item.quantity;
                itemCount += item.quantity;
            });
        }

        headerCartCountSpan.textContent = itemCount;
        headerCartTotalSpan.textContent = `SAR ${total.toFixed(2)}`;
        localStorage.setItem('cart', JSON.stringify(cart));
    }
    renderHeaderCart(); // Initial render of the header cart

    // --- Checkout Modal Functionality (Copied for consistency) ---
    closeModalBtn.addEventListener('click', () => {
        checkoutModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === checkoutModal) {
            checkoutModal.style.display = 'none';
        }
    });

    // --- Admin Dashboard Core Functionality ---
    let activeMainSection = 'home'; // Keep track of the active main section
    let activeSubSection = 'home-dashboard'; // Keep track of the active sub-section

    // Function to switch sections
    function showSection(sectionId) {
        adminSections.forEach(section => {
            section.classList.add('hidden');
        });
        document.getElementById(sectionId).classList.remove('hidden');

        // Update active state for sidebar links
        adminSidebarLinks.forEach(link => {
            link.classList.remove('active', 'bg-indigo-600', 'text-white', 'hover:bg-indigo-700', 'dark:bg-indigo-600', 'dark:hover:bg-indigo-700');
            // Reset arrow rotation for all main links
            const svg = link.querySelector('svg');
            if (svg) svg.classList.remove('rotate-180');
        });
        adminSubmenuLinks.forEach(link => {
            link.classList.remove('active', 'bg-indigo-600', 'text-white', 'hover:bg-indigo-700', 'dark:bg-indigo-600', 'dark:hover:bg-indigo-700', 'font-semibold');
        });

        // Find the main link associated with the sectionId
        let mainLinkFound = false;
        adminSidebarLinks.forEach(link => {
            if (link.dataset.targetSection === sectionId) { // Direct main section link
                link.classList.add('active', 'bg-indigo-600', 'text-white', 'hover:bg-indigo-700', 'dark:bg-indigo-600', 'dark:hover:bg-indigo-700');
                activeMainSection = link.dataset.mainSection || sectionId;
                activeSubSection = sectionId;
                mainLinkFound = true;
            } else if (link.dataset.mainSection) { // Check if it's a parent of a submenu link
                const submenu = document.querySelector(`.admin-submenu[data-submenu="${link.dataset.mainSection}"]`);
                if (submenu && submenu.querySelector(`[data-target-section="${sectionId}"]`)) {
                    link.classList.add('active', 'bg-indigo-600', 'text-white', 'hover:bg-indigo-700', 'dark:bg-indigo-600', 'dark:hover:bg-indigo-700');
                    submenu.classList.add('open'); // Open the submenu
                    const svg = link.querySelector('svg');
                    if (svg) svg.classList.add('rotate-180'); // Rotate arrow
                    activeMainSection = link.dataset.mainSection;
                    activeSubSection = sectionId;
                    mainLinkFound = true;
                }
            }
        });

        // If a submenu link is clicked, activate it
        const activeSubLink = document.querySelector(`.admin-submenu-link[data-target-section="${sectionId}"]`);
        if (activeSubLink) {
            activeSubLink.classList.add('active', 'bg-indigo-600', 'text-white', 'hover:bg-indigo-700', 'dark:bg-indigo-600', 'dark:hover:bg-indigo-700', 'font-semibold');
        }

        // If no direct link or submenu link was found (e.g., for home-dashboard initially)
        if (!mainLinkFound && sectionId === 'home-dashboard') {
            const homeLink = document.querySelector('.admin-sidebar-link[data-main-section="home"]');
            if (homeLink) {
                homeLink.classList.add('active', 'bg-indigo-600', 'text-white', 'hover:bg-indigo-700', 'dark:bg-indigo-600', 'dark:hover:bg-indigo-700');
                activeMainSection = 'home';
                activeSubSection = 'home-dashboard';
            }
        }
    }

    // Event listeners for sidebar main links (toggle submenu and show first sub-section)
    adminSidebarLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            const mainSection = link.dataset.mainSection;
            const submenu = document.querySelector(`.admin-submenu[data-submenu="${mainSection}"]`);
            const svg = link.querySelector('svg');

            // If it's a main link with a submenu
            if (submenu) {
                // Close all other submenus
                adminSubmenus.forEach(sm => {
                    if (sm !== submenu) {
                        sm.classList.remove('open');
                        const parentLink = document.querySelector(`.admin-sidebar-link[data-main-section="${sm.dataset.submenu}"]`);
                        if (parentLink) {
                            const parentSvg = parentLink.querySelector('svg');
                            if (parentSvg) parentSvg.classList.remove('rotate-180');
                        }
                    }
                });

                // Toggle current submenu
                submenu.classList.toggle('open');
                if (svg) svg.classList.toggle('rotate-180');

                // If opening a submenu, show its first sub-section
                if (submenu.classList.contains('open')) {
                    const firstSubLink = submenu.querySelector('.admin-submenu-link');
                    if (firstSubLink) {
                        showSection(firstSubLink.dataset.targetSection);
                    }
                } else {
                    // If closing a submenu, and it was the active main section,
                    // revert to home-dashboard or keep current main section active
                    if (activeMainSection === mainSection) {
                        showSection('home-dashboard'); // Fallback to home
                    }
                }
            } else if (link.dataset.targetSection) { // If it's a direct link (like Home)
                // Close all submenus
                adminSubmenus.forEach(sm => {
                    sm.classList.remove('open');
                    const parentLink = document.querySelector(`.admin-sidebar-link[data-main-section="${sm.dataset.submenu}"]`);
                    if (parentLink) {
                        const parentSvg = parentLink.querySelector('svg');
                        if (parentSvg) parentSvg.classList.remove('rotate-180');
                    }
                });
                showSection(link.dataset.targetSection);
            }
        });
    });

    // Event listeners for submenu links
    adminSubmenuLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            showSection(event.target.dataset.targetSection);
        });
    });

    // Initial section load
    showSection('home-dashboard');

    // --- Dashboard Overview Data ---
    function updateDashboardOverview() {
        totalProductsCount.textContent = products.length;
        pendingOrdersCount.textContent = orders.filter(order => order.status === 'pending').length;
        totalUsersCount.textContent = users.length;
        const totalSales = orders.filter(order => order.status === 'delivered').reduce((sum, order) => sum + order.total, 0);
        totalSalesAmount.textContent = `SAR ${totalSales.toFixed(2)}`;
    }

    // --- Product Management Functions ---
    function renderProducts() {
        productsTableBody.innerHTML = ''; // Clear existing rows
        if (products.length === 0) {
            noProductsMessage.classList.remove('hidden');
        } else {
            noProductsMessage.classList.add('hidden');
            products.forEach(product => {
                const row = document.createElement('tr');
                row.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700', 'hover:bg-gray-50', 'dark:hover:bg-gray-700');
                row.innerHTML = `
                    <td class="py-3 px-4">
                        <img src="${product.image || 'https://placehold.co/50x50/cccccc/333333?text=N/A'}" alt="${currentLang === 'ar' ? product.name_ar : product.name_en}" class="w-12 h-12 object-cover rounded-md">
                    </td>
                    <td class="py-3 px-4 text-gray-800 dark:text-gray-200">${currentLang === 'ar' ? product.name_ar : product.name_en}</td>
                    <td class="py-3 px-4 text-gray-800 dark:text-gray-200">SAR ${product.price.toFixed(2)}</td>
                    <td class="py-3 px-4">
                        <button class="edit-product-btn bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600 transition-colors duration-200 ml-2 rtl:mr-2" data-product-id="${product.id}" data-lang-ar="تعديل" data-lang-en="Edit">تعديل</button>
                        <button class="delete-product-btn bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600 transition-colors duration-200" data-product-id="${product.id}" data-lang-ar="حذف" data-lang-en="Delete">حذف</button>
                    </td>
                `;
                productsTableBody.appendChild(row);
            });
        }
        updateDashboardOverview();
    }

    productForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const id = productIdInput.value || Date.now().toString(); // Generate ID if new
        const name_ar = productNameArInput.value;
        const name_en = productNameEnInput.value;
        const price = parseFloat(productPriceInput.value);
        const image = productImageInput.value;

        if (!name_ar || !name_en || isNaN(price) || price <= 0) {
            alert(currentLang === 'ar' ? 'الرجاء ملء جميع الحقول المطلوبة (الاسم والسعر).' : 'Please fill all required fields (Name and Price).');
            return;
        }

        const existingProductIndex = products.findIndex(p => p.id === id);

        if (existingProductIndex > -1) {
            // Edit existing product
            products[existingProductIndex] = { id, name_ar, name_en, price, image };
        } else {
            // Add new product
            products.push({ id, name_ar, name_en, price, image });
        }

        localStorage.setItem('adminProducts', JSON.stringify(products));
        productForm.reset();
        productIdInput.value = ''; // Clear hidden ID
        cancelEditProductBtn.classList.add('hidden');
        renderProducts();
        showSection('products-all'); // Go back to all products list after save
    });

    // productsTableBody.addEventListener('click', (event) => {
    //     const target = event.target;
    //     const productId = target.dataset.productId;

    //     if (target.classList.contains('edit-product-btn')) {
    //         const productToEdit = products.find(p => p.id === productId);
    //         if (productToEdit) {
    //             productIdInput.value = productToEdit.id;
    //             productNameArInput.value = productToEdit.name_ar;
    //             productNameEnInput.value = productToEdit.name_en;
    //             productPriceInput.value = productToEdit.price;
    //             productImageInput.value = productToEdit.image;
    //             cancelEditProductBtn.classList.remove('hidden');
    //             showSection('products-add-new'); // Switch to add new section to edit
    //             productNameArInput.focus();
    //         }
    //     } else if (target.classList.contains('delete-product-btn')) {
    //         if (confirm(currentLang === 'ar' ? 'هل أنت متأكد أنك تريد حذف هذا المنتج؟' : 'Are you sure you want to delete this product?')) {
    //             products = products.filter(p => p.id !== productId);
    //             localStorage.setItem('adminProducts', JSON.stringify(products));
    //             renderProducts();
    //         }
    //     }
    // });

    cancelEditProductBtn.addEventListener('click', () => {
        productForm.reset();
        productIdInput.value = '';
        cancelEditProductBtn.classList.add('hidden');
        showSection('products-all'); // Go back to all products list
    });


    // --- Order Management Functions ---
    function renderOrders() {
        ordersTableBody.innerHTML = '';
        if (orders.length === 0) {
            noOrdersMessage.classList.remove('hidden');
        } else {
            noOrdersMessage.classList.add('hidden');
            orders.forEach(order => {
                const row = document.createElement('tr');
                row.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700', 'hover:bg-gray-50', 'dark:hover:bg-gray-700');

                // Determine status text based on language
                let statusText = order.status;
                if (currentLang === 'ar') {
                    switch (order.status) {
                        case 'pending': statusText = 'قيد الانتظار'; break;
                        case 'processing': statusText = 'قيد المعالجة'; break;
                        case 'shipped': statusText = 'تم الشحن'; break;
                        case 'delivered': statusText = 'تم التسليم'; break;
                        case 'cancelled': statusText = 'ملغاة'; break;
                    }
                }

                row.innerHTML = `
                    <td class="py-3 px-4 text-gray-800 dark:text-gray-200">${order.id}</td>
                    <td class="py-3 px-4 text-gray-800 dark:text-gray-200">${order.customerName}</td>
                    <td class="py-3 px-4 text-gray-800 dark:text-gray-200">SAR ${order.total.toFixed(2)}</td>
                    <td class="py-3 px-4">
                        <select class="order-status-select p-1 border border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100" data-order-id="${order.id}">
                            <option value="pending" ${order.status === 'pending' ? 'selected' : ''}>${currentLang === 'ar' ? 'قيد الانتظار' : 'Pending'}</option>
                            <option value="processing" ${order.status === 'processing' ? 'selected' : ''}>${currentLang === 'ar' ? 'قيد المعالجة' : 'Processing'}</option>
                            <option value="shipped" ${order.status === 'shipped' ? 'selected' : ''}>${currentLang === 'ar' ? 'تم الشحن' : 'Shipped'}</option>
                            <option value="delivered" ${order.status === 'delivered' ? 'selected' : ''}>${currentLang === 'ar' ? 'تم التسليم' : 'Delivered'}</option>
                            <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>${currentLang === 'ar' ? 'ملغاة' : 'Cancelled'}</option>
                        </select>
                    </td>
                    <td class="py-3 px-4">
                        <button class="delete-order-btn bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600 transition-colors duration-200" data-order-id="${order.id}" data-lang-ar="حذف" data-lang-en="Delete">حذف</button>
                    </td>
                `;
                ordersTableBody.appendChild(row);
            });
        }
        updateDashboardOverview();
    }

    ordersTableBody.addEventListener('change', (event) => {
        const target = event.target;
        if (target.classList.contains('order-status-select')) {
            const orderId = target.dataset.orderId;
            const newStatus = target.value;
            const orderIndex = orders.findIndex(o => o.id === orderId);
            if (orderIndex > -1) {
                orders[orderIndex].status = newStatus;
                localStorage.setItem('adminOrders', JSON.stringify(orders));
                updateDashboardOverview(); // Status change might affect pending count
            }
        }
    });

    ordersTableBody.addEventListener('click', (event) => {
        const target = event.target;
        if (target.classList.contains('delete-order-btn')) {
            const orderId = target.dataset.orderId;
            if (confirm(currentLang === 'ar' ? 'هل أنت متأكد أنك تريد حذف هذا الطلب؟' : 'Are you sure you want to delete this order?')) {
                orders = orders.filter(o => o.id !== orderId);
                localStorage.setItem('adminOrders', JSON.stringify(orders));
                renderOrders();
            }
        }
    });

    // --- User Management Functions ---
    function renderUsers() {
        usersTableBody.innerHTML = '';
        if (users.length === 0) {
            noUsersMessage.classList.remove('hidden');
        } else {
            noUsersMessage.classList.add('hidden');
            users.forEach(user => {
                const row = document.createElement('tr');
                row.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700', 'hover:bg-gray-50', 'dark:hover:bg-gray-700');
                row.innerHTML = `
                    <td class="py-3 px-4 text-gray-800 dark:text-gray-200">${user.id}</td>
                    <td class="py-3 px-4 text-gray-800 dark:text-gray-200">${user.name}</td>
                    <td class="py-3 px-4 text-gray-800 dark:text-gray-200">${user.email}</td>
                    <td class="py-3 px-4">
                        <button class="delete-user-btn bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600 transition-colors duration-200" data-user-id="${user.id}" data-lang-ar="حذف" data-lang-en="Delete">حذف</button>
                    </td>
                `;
                usersTableBody.appendChild(row);
            });
        }
        updateDashboardOverview();
    }

    usersTableBody.addEventListener('click', (event) => {
        const target = event.target;
        if (target.classList.contains('delete-user-btn')) {
            const userId = target.dataset.userId;
            if (confirm(currentLang === 'ar' ? 'هل أنت متأكد أنك تريد حذف هذا المستخدم؟' : 'Are you sure you want to delete this user?')) {
                users = users.filter(u => u.id !== userId);
                localStorage.setItem('adminUsers', JSON.stringify(users));
                renderUsers();
            }
        }
    });

    // --- Dummy Data Initialization (for first load) ---
    if (products.length === 0) {
        products = [
            { id: 'p1', name_ar: 'تي شيرت رياضي', name_en: 'Sport T-Shirt', price: 99.00, image: 'https://placehold.co/50x50/6366f1/ffffff?text=T-Shirt' },
            { id: 'p2', name_ar: 'حذاء جري', name_en: 'Running Shoes', price: 249.00, image: 'https://placehold.co/50x50/4c51bf/ffffff?text=Shoes' },
            { id: 'p3', name_ar: 'شورت رياضي', name_en: 'Sport Shorts', price: 75.00, image: 'https://placehold.co/50x50/7c3aed/ffffff?text=Shorts' }
        ];
        localStorage.setItem('adminProducts', JSON.stringify(products));
    }

    if (orders.length === 0) {
        orders = [
            { id: 'ORD001', customerName: 'أحمد علي', total: 198.00, status: 'pending' },
            { id: 'ORD002', customerName: 'فاطمة محمد', total: 249.00, status: 'processing' },
            { id: 'ORD003', customerName: 'خالد سعيد', total: 75.00, status: 'delivered' }
        ];
        localStorage.setItem('adminOrders', JSON.stringify(orders));
    }

    if (users.length === 0) {
        users = [
            { id: 'USR001', name: 'أحمد علي', email: 'ahmed@example.com' },
            { id: 'USR002', name: 'فاطمة محمد', email: 'fatima@example.com' },
            { id: 'USR003', name: 'خالد سعيد', email: 'khalid@example.com' }
        ];
        localStorage.setItem('adminUsers', JSON.stringify(users));
    }

    // Initial renders for all admin sections
    renderProducts();
    renderOrders();
    renderUsers();
    updateDashboardOverview();
});
