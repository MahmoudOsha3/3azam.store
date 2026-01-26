@extends('layout.dashboard.app')

@section('title', 'إدارة الموظفين')

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

        /* تحسين الحاوية الرئيسية */
        .main-content { padding: 15px; }

        /* جعل الهيدر مرناً */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* للسماح بالعناصر بالنزول لسطر جديد في الشاشات الصغيرة */
            gap: 15px;
            margin-bottom: 20px;
        }

        .search-box { flex: 1; min-width: 280px; position: relative; }
        .search-box input { width: 100%; padding: 10px 35px 10px 15px; border-radius: 8px; border: 1px solid #ddd; }
        .search-box i { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #999; }

        /* تحسين الجدول ليكون قابلاً للتمرير الجانبي */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .meals-table { width: 100%; border-collapse: collapse; min-width: 700px; /* يضمن عدم تكدس البيانات */ }
        .meals-table th, .meals-table td { padding: 15px; text-align: right; border-bottom: 1px solid #eee; }

        /* تحسين الترقيم (Pagination) */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
            background: white;
            padding: 12px;
            border-radius: 12px;
        }

        /* تحسين المودال (النماذج) */
        .modal-box {
            width: 95%;
            max-width: 550px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        /* شاشات الجوال */
        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; /* جعل الحقول تحت بعضها */ }
            .full-width { grid-column: span 1; }
            .table-header { flex-direction: column; align-items: stretch; }
            .btn-create { width: 100%; justify-content: center; }
            .pagination-container { flex-direction: column; text-align: center; }
        }

        .role-badge { background: #eef2ff; color: #4338ca; padding: 4px 10px; border-radius: 6px; font-size: 0.85rem; font-weight: bold; white-space: nowrap; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); align-items: center; justify-content: center; z-index: 1000; padding: 10px; }

        .btn-action { border: none; background: none; cursor: pointer; padding: 5px; font-size: 1.1rem; }
        .edit-btn { color: var(--primary); }
        .delete-btn { color: var(--danger); }
    </style>
@endsection

@section('content')
<main class="main-content">
    <br><br>
    <div class="table-header">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="ابحث عن موظف...">
        </div>
        <button class="btn-create" onclick="openFormModal()">
            <i class="fas fa-user-plus"></i> إضافة موظف جديد
        </button>
    </div>

    <div class="table-responsive">
        <table class="meals-table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>رقم الهاتف</th>
                    <th>البريد الإلكتروني</th>
                    <th>الصلاحية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="adminsBody"></tbody>
        </table>
    </div>

    <div class="pagination-container">
        <div id="paginationInfo"></div>
        <div id="paginationLinks"></div>
    </div>
</main>

<div id="modalForm" class="modal-overlay">
    <div class="modal-box">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
            <h3 id="formTitle" style="margin:0; color:var(--primary)">إضافة مستخدم جديد</h3>
            <button onclick="closeModals()" style="background:none; border:none; font-size:20px; cursor:pointer">&times;</button>
        </div>

        <input type="hidden" id="adminId">
        <div class="form-grid">
            <div class="form-group">
                <label>الاسم الكامل</label>
                <input type="text" id="adminName" style="width:100%; padding:8px; border-radius:5px; border:1px solid #ddd">
            </div>
            <div class="form-group">
                <label>رقم الهاتف</label>
                <input type="text" id="adminPhone" style="width:100%; padding:8px; border-radius:5px; border:1px solid #ddd">
            </div>
            <div class="form-group" style="grid-column: span 2">
                <label>البريد الإلكتروني</label>
                <input type="email" id="adminEmail" style="width:100%; padding:8px; border-radius:5px; border:1px solid #ddd">
            </div>
            <div class="form-group" style="grid-column: span 2">
                <label>الصلاحية</label>
                <select id="adminRole" style="width:100%; padding:8px; border-radius:5px; border:1px solid #ddd"></select>
            </div>
            <div class="form-group" style="grid-column: span 2">
                <label>كلمة المرور</label>
                <input type="password" id="adminPassword" style="width:100%; padding:8px; border-radius:5px; border:1px solid #ddd">
            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:25px">
            <button class="btn-create" style="flex:2; justify-content:center" onclick="saveAdmin()">حفظ البيانات</button>
            <button onclick="closeModals()" style="flex:1; border:none; background:#eee; border-radius:8px; cursor:pointer">إلغاء</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let adminsData = [];
    let rolesList = [];
    let currentP = 1;
    let token = localStorage.getItem('admin_token')

    $(document).ready(function() {
        fetchAdmins(1);
        fetchRoles();

        $('#searchInput').on('input', function() {
            const query = $(this).val().toLowerCase();
            const filtered = adminsData.filter(a =>
                a.name.toLowerCase().includes(query) ||
                a.email.toLowerCase().includes(query) ||
                (a.phone && a.phone.includes(query))
            );
            renderTable(filtered);
        });
    });

    function fetchAdmins(page) {
        currentP = page;
        $.ajax({
            url: `/admin/admin?page=${page}`,
            method: 'GET',
            headers : {
                'Authorization': 'Bearer ' + token,
            } ,
            success: function(res) {
                adminsData = res.data.data;
                renderTable(adminsData);
                renderPagination(res.data);
            }
        });
    }

    function fetchRoles() {
        $.ajax({
            url: '/admin/role',
            method: 'GET',
            headers : {
                'Authorization': 'Bearer ' + token,
            } ,
            success: function(res) {
                rolesList = res.data.roles;
                let options = '<option value="">اختر صلاحية</option>';
                rolesList.forEach(r => options += `<option value="${r.id}">${r.name}</option>`);
                $('#adminRole').html(options);
            }
        });
    }

    function renderTable(data) {
        const tbody = $('#adminsBody').empty();
        if(data.length === 0) {
            tbody.append('<tr><td colspan="6" style="text-align:center">لا يوجد مستخدمين</td></tr>');
            return;
        }
        data.forEach(a => {
            tbody.append(`
                <tr>
                    <td><b>${a.name}</b></td>
                    <td>${a.phone || '---'}</td>
                    <td>${a.email}</td>
                    <td><span class="role-badge">${a.role ? a.role.name : 'مستخدم'}</span></td>
                    <td>
                        <button class="btn-action edit-btn" onclick="openEditModal(${a.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn-action delete-btn" onclick="openDeleteModal(${a.id}, '${a.name}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
        });
    }

    function renderPagination(meta) {
        const links = $('#paginationLinks').empty();
        $('#paginationInfo').text(`عرض ${meta.from || 0}-${meta.to || 0} من ${meta.total} مستخدم`);

        links.append(`<button class="page-link" ${meta.current_page === 1 ? 'disabled' : ''} onclick="fetchAdmins(${meta.current_page - 1})">السابق</button>`);

        for (let i = 1; i <= meta.last_page; i++) {
            if (i === 1 || i === meta.last_page || (i >= meta.current_page - 1 && i <= meta.current_page + 1)) {
                links.append(`<button class="page-link ${i === meta.current_page ? 'active' : ''}" onclick="fetchAdmins(${i})">${i}</button>`);
            }
        }

        links.append(`<button class="page-link" ${meta.current_page === meta.last_page ? 'disabled' : ''} onclick="fetchAdmins(${meta.current_page + 1})">التالي</button>`);
    }

    function openFormModal() {
        $('#formTitle').text('إضافة مستخدم جديد');
        $('#adminId').val('');
        $('#adminName, #adminPhone, #adminEmail,  #adminPassword').val('');
        $('#adminRole').val('');
        $('#modalForm').css('display', 'flex');
    }

    function openEditModal(id) {
        const a = adminsData.find(x => x.id === id);
        $('#formTitle').text('تعديل بيانات: ' + a.name);
        $('#adminId').val(a.id);
        $('#adminName').val(a.name);
        $('#adminPhone').val(a.phone);
        $('#adminEmail').val(a.email);
        $('#adminRole').val(a.role_id);
        $('#adminPassword').val('');
        $('#modalForm').css('display', 'flex');
    }

    function saveAdmin() {
        const id = $('#adminId').val();
        const payload = {
            name: $('#adminName').val(),
            phone: $('#adminPhone').val(),
            email: $('#adminEmail').val(),
            role_id: $('#adminRole').val(),
            password: $('#adminPassword').val(),
            _token: '{{ csrf_token() }}'
        };

        const url = id ? `/admin/admin/${id}` : '/admin/admin';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: payload,
            success: function() {
                toastr.success('تم حفظ البيانات بنجاح');
                closeModals();
                fetchAdmins(currentP);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'خطأ في الحفظ');
            }
        });
    }

    function openDeleteModal(id, name) {
        $('#deleteId').val(id);
        $('#deleteName').text(name);
        $('#modalDelete').css('display', 'flex');
    }

    function confirmDelete() {
        const id = $('#deleteId').val();
        $.ajax({
            url: `/admin/admin/${id}`,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                toastr.success('تم الحذف بنجاح');
                closeModals();
                fetchAdmins(currentP);
            }
        });
    }

    function closeModals() { $('.modal-overlay').hide(); }
</script>
@endsection
