@extends('layout.dashboard.app')

@section('title', 'إدارة المنتجات')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/products.css') }}">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --danger: #ef4444;
            --success: #10b981;
            --bg-light: #f9fafb;
            --text-main: #111827;
            --text-muted: #6b7280;
        }

        /* تحسينات الحاوية الرئيسية */
        .main-content { padding: 20px; background-color: var(--bg-light); min-height: 100vh; }

        /* الهيدر المتجاوب */
        .table-header {
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 15px; margin-bottom: 25px;
        }

        .search-box { position: relative; flex: 1; min-width: 250px; }
        .search-box input {
            width: 100%; padding: 12px 15px; border: 1px solid #e5e7eb;
            border-radius: 10px; outline: none; transition: 0.3s;
        }
        .search-box input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }

        .btn-create {
            padding: 12px 20px; background-color: var(--primary); color: white;
            border: none; border-radius: 10px; cursor: pointer; font-weight: 600;
            display: flex; align-items: center; gap: 8px; transition: 0.3s;
        }
        .btn-create:hover { background-color: var(--primary-hover); transform: translateY(-1px); }

        /* الجدول المتجاوب */
        .table-container {
            background: white; border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            overflow: hidden; margin-top: 20px;
        }

        .table-responsive {
            width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;
        }

        .products-table { width: 100%; border-collapse: collapse; text-align: right; min-width: 900px; }
        .products-table th { background: #f8fafc; padding: 15px; color: #64748b; border-bottom: 2px solid #edf2f7; font-size: 14px; }
        .products-table td { padding: 12px 15px; border-bottom: 1px solid #edf2f7; vertical-align: middle; font-size: 14px; }
        .products-table tr:hover { background-color: #f8fafc; }

        /* المودالات */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
            align-items: center; justify-content: center; z-index: 1000; padding: 20px;
        }
        .modal-box {
            background: white; padding: 25px; border-radius: 16px;
            width: 700px; max-width: 100%; max-height: 90vh; overflow-y: auto;
        }

        /* التنسيق الشبكي للنموذج */
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1 !important; }
            .table-header { flex-direction: column; align-items: stretch; }
        }

        /* الستايلات المساعدة */
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; white-space: nowrap; }
        .badge-active { background: #dcfce7; color: var(--success); }
        .badge-inactive { background: #fee2e2; color: var(--danger); }
        .sku-tag { font-family: monospace; background: #f1f5f9; padding: 4px 8px; border-radius: 6px; color: #475569; font-size: 12px; }

        .pagination-container {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px; background: white; border-top: 1px solid #edf2f7; flex-wrap: wrap; gap: 10px;
        }
        .page-link {
            padding: 8px 16px; border: 1px solid #e2e8f0; border-radius: 8px;
            cursor: pointer; background: white; transition: 0.2s; font-weight: 500;
        }
        .page-link.active { background: var(--primary); color: white; border-color: var(--primary); }
        .page-link:hover:not(:disabled):not(.active) { background: #f8fafc; border-color: var(--primary); color: var(--primary); }
    </style>
@endsection

@section('content')
<main class="main-content">
    <br><br>

    <div class="table-header">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="ابحث باسم المنتج أو الكود...">
        </div>
        <button class="btn-create" onclick="openFormModal()">
            <i class="fas fa-plus"></i> إضافة منتج جديد
        </button>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>كود المنتج</th>
                        <th>القسم</th>
                        <th>السعر</th>
                        <th>المخزون</th>
                        <th>تم بيع</th>
                        <th>الحالة</th>
                        <th style="text-align: center;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="productsBody"></tbody>
            </table>
        </div>

        <div class="pagination-container">
            <div id="paginationInfo" style="color: var(--text-muted); font-size: 14px;"></div>
            <div style="display:flex; gap:8px; align-items:center">
                <button class="page-link" id="prevPage" onclick="changePage('prev')">السابق</button>
                <div id="pageNumbers" style="display:flex; gap:5px"></div>
                <button class="page-link" id="nextPage" onclick="changePage('next')">التالي</button>
            </div>
        </div>
    </div>
</main>

<div id="modalForm" class="modal-overlay">
    <div class="modal-box">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
            <h3 id="formTitle" style="margin:0">إضافة منتج جديد</h3>
            <button onclick="closeModals()" style="background:none; border:none; font-size:24px; cursor:pointer; color:var(--text-muted)">&times;</button>
        </div>

        <input type="hidden" id="productId">
        <div class="form-grid">
            <div class="form-group">
                <label>اسم المنتج</label>
                <input type="text" id="productTitle">
            </div>
            <div class="form-group">
                <label>القسم</label>
                <select id="productCategoryId"></select>
            </div>

            <div class="form-group" style="grid-column: span 2">
                <label>الوصف</label>
                <textarea id="productDescription" rows="3" style="width:100%; border-radius:8px; border:1px solid #ddd; padding:10px"></textarea>
            </div>

            <div class="form-group">
                <label>المخزون</label>
                <input type="number" id="productStock">
            </div>
            <div class="form-group">
                <label>الحالة</label>
                <select id="productStatus">
                    <option value="active">نشط (يظهر للعملاء)</option>
                    <option value="inactive">مخفي (مسودة)</option>
                </select>
            </div>

            <div class="form-group">
                <label>السعر الحالي</label>
                <input type="number" id="productPrice" placeholder="0.00">
            </div>
            <div class="form-group">
                <label>السعر السابق (اختياري)</label>
                <input type="number" id="comparePrice" placeholder="0.00">
            </div>

            <div class="form-group" style="grid-column: span 2">
                <label>صورة المنتج</label>
                <input type="file" id="productImage" accept="image/*" style="border: 1px dashed #4f46e5; padding: 20px; background: #f5f3ff;">
            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:25px">
            <button class="btn-create" style="flex:2; justify-content:center" onclick="saveProduct()">حفظ البيانات</button>
            <button onclick="closeModals()" style="flex:1; background:#f1f5f9; border:none; border-radius:10px; cursor:pointer; font-weight:600">إلغاء</button>
        </div>
    </div>
</div>

<div id="modalDelete" class="modal-overlay">
    <div class="modal-box" style="width:400px; text-align:center">
        <div style="background:#fef2f2; width:70px; height:70px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 15px">
            <i class="fas fa-trash-alt" style="color:var(--danger); font-size: 30px;"></i>
        </div>
        <h3 style="margin-bottom:10px">هل أنت متأكد؟</h3>
        <p style="color:#64748b; margin-bottom:25px" id="deleteText"></p>
        <input type="hidden" id="deleteId">
        <div style="display:flex; gap:12px">
            <button onclick="confirmDelete()" style="flex:1; background:var(--danger); color:white; border:none; padding:12px; border-radius:10px; cursor:pointer; font-weight:600">نعم، احذف الآن</button>
            <button onclick="closeModals()" style="flex:1; background:#f1f5f9; border:none; padding:12px; border-radius:10px; cursor:pointer; font-weight:600">تراجع</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// ... (نفس منطق الـ JS الخاص بك مع تحسين بسيط في عرض أرقام الصفحات)
let products = [];
let categories = [];
let currentPage = 1;
let lastPage = 1;
let currentSearch = '';

$(document).ready(function() {
    fetchProducts(1);
    fetchCategories();

    $('#searchInput').on('keyup', function(e) {
        currentSearch = $(this).val();
        fetchProducts(1);
    });
});

function fetchProducts(page) {
    $.get(`{{ route('product.index') }}?page=${page}&title=${currentSearch}`, function(res) {
        products = res.data.data || [];
        currentPage = res.data.current_page;
        lastPage = res.data.last_page;

        $('#paginationInfo').text(`عرض صفحة ${currentPage} من أصل ${lastPage}`);
        renderTable(products);
        renderPagination();
    });
}

function fetchCategories() {
    $.get("{{ route('category.index') }}", function(res) {
        categories = res.data || [];
    });
}

function renderTable(data) {
    const tbody = $('#productsBody');
    tbody.empty();

    if (!data.length) {
        tbody.append('<tr><td colspan="7" style="text-align:center; padding:50px; color:#94a3b8">لا توجد منتجات تطابق بحثك</td></tr>');
        return;
    }

    data.forEach(p => {
        const statusClass = p.status === 'active' ? 'badge-active' : 'badge-inactive';
        const statusText = p.status === 'active' ? 'نشط' : 'مخفي';

        tbody.append(`
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:10px">
                        <img src="${p.image_url ?? '/default-product.png'}" style="width:40px; height:40px; border-radius:8px; object-fit:cover">
                        <span style="font-weight:600">${p.title ?? '-'}</span>
                    </div>
                </td>
                <td><span class="sku-tag">SKU-${p.sku || p.id}</span></td>
                <td>${p.category ? (p.category.name || p.category.title) : '<span style="color:#cbd5e1">غير مصنف</span>'}</td>
                <td>
                    <div style="font-weight:bold; color:var(--primary)">${p.price} ج.م</div>
                    ${p.compare_price ? `<del style="font-size:11px; color:#94a3b8">${p.compare_price} ج.م</del>` : ''}
                </td>
                <td><span style="font-weight:600">${p.stock ?? 0}</span> قطعه</td>
                <td><span style="font-weight:600">${p.sales_count ?? 0}</span> قطعه</td>

                <td><span class="badge ${statusClass}">${statusText}</span></td>
                <td style="text-align: center;">
                    <button class="page-link" style="color:var(--primary); padding:6px 10px" onclick="openFormModal(${p.id})"><i class="fas fa-edit"></i></button>
                    <button class="page-link" style="color:var(--danger); padding:6px 10px" onclick="openDeleteModal(${p.id})"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `);
    });
}

function renderPagination() {
    const wrapper = $('#pageNumbers');
    wrapper.empty();

    // عرض عدد محدود من الصفحات لضمان التجاوب
    let start = Math.max(1, currentPage - 2);
    let end = Math.min(lastPage, currentPage + 2);

    for (let i = start; i <= end; i++) {
        wrapper.append(`<button class="page-link ${i === currentPage ? 'active' : ''}" onclick="fetchProducts(${i})">${i}</button>`);
    }

    $('#prevPage').prop('disabled', currentPage === 1);
    $('#nextPage').prop('disabled', currentPage === lastPage);
}

// ... بقية دوال openFormModal, saveProduct, confirmDelete (تبقي كما هي في منطقك)
// تأكد من تحديث دالة saveProduct لإرسال البيانات بالشكل الصحيح
function openFormModal(id = null) {
    const select = $('#productCategoryId');
    select.empty().append('<option value="">اختر القسم</option>');

    if (categories.length) {
        categories.forEach(c => select.append(`<option value="${c.id}">${c.name || c.title}</option>`));
    }

    if (id) {
        const p = products.find(x => x.id === id);
        $('#formTitle').text('تعديل المنتج');
        $('#productId').val(p.id);
        $('#productTitle').val(p.title);
        $('#productDescription').val(p.description);
        $('#productStock').val(p.stock);
        $('#productPrice').val(p.price);
        $('#comparePrice').val(p.compare_price);
        $('#productStatus').val(p.status ?? 'active');
        $('#productCategoryId').val(p.category_id);
    } else {
        $('#formTitle').text('إضافة منتج جديد');
        $('#productId').val('');
        $('#modalForm input:not([type=file]), #modalForm textarea').val('');
        $('#productStatus').val('active');
    }
    $('#modalForm').css('display', 'flex');
}

function saveProduct() {
    const id = $('#productId').val();
    let formData = new FormData();
    formData.append('title', $('#productTitle').val());
    formData.append('description', $('#productDescription').val());
    formData.append('stock', $('#productStock').val());
    formData.append('price', $('#productPrice').val());
    formData.append('compare_price', $('#comparePrice').val());
    formData.append('category_id', $('#productCategoryId').val());
    formData.append('status', $('#productStatus').val());

    const img = $('#productImage')[0].files[0];
    if (img) formData.append('image', img);
    if (id) formData.append('_method', 'PUT');

    $.ajax({
        url: id ? `/admin/product/${id}` : "{{ route('product.store') }}",
        type: 'POST',
        data: formData,
        processData: false, contentType: false,
        success: function() {
            toastr.success('تم الحفظ بنجاح');
            closeModals();
            fetchProducts(currentPage);
        },
        error: function(xhr) {
            toastr.error('فشل في حفظ البيانات، تأكد من المدخلات');
        }
    });
}

function openDeleteModal(id) {
    const p = products.find(x => x.id === id);
    $('#deleteId').val(id);
    $('#deleteText').text(`سيتم حذف المنتج "${p.title}" نهائياً من قاعدة البيانات.`);
    $('#modalDelete').css('display', 'flex');
}

function confirmDelete() {
    const id = $('#deleteId').val();
    $.ajax({
        url: `/admin/product/${id}`,
        type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function() {
            toastr.success('تم الحذف');
            closeModals();
            fetchProducts(currentPage);
        }
    });
}

function closeModals() { $('.modal-overlay').hide(); }
function changePage(dir) {
    if(dir === 'next' && currentPage < lastPage) fetchProducts(currentPage+1);
    if(dir === 'prev' && currentPage > 1) fetchProducts(currentPage-1);
}
</script>
@endsection
