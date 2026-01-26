@extends('layout.dashboard.app')
@section('title', 'إدارة الصلاحيات والأدوار')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/products.css') }}">
    <style>
        :root { --primary: #4f46e5; --danger: #ef4444; --success: #10b981; --bg-light: #f8fafc; }

        /* حاوية الجدول المتجاوبة */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .meals-table { width: 100%; border-collapse: collapse; min-width: 600px; }
        .meals-table th { background: #f1f5f9; padding: 15px; text-align: right; color: #475569; font-weight: 600; }
        .meals-table td { padding: 15px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }

        /* تحسين الهيدر في الموبايل */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .search-box { flex: 1; min-width: 250px; position: relative; }
        .search-box input { width: 100%; padding: 10px 35px 10px 10px; border-radius: 8px; border: 1px solid #ddd; }
        .search-box i { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }

        /* Grid الصلاحيات المتجاوب */
        .perm-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 20px;
            max-height: 450px;
            overflow-y: auto;
            padding: 5px;
        }

        .perm-item {
            background: white; padding: 12px; border-radius: 10px; display: flex; align-items: center;
            justify-content: space-between; border: 1px solid #e2e8f0; transition: 0.3s; cursor: pointer;
        }

        .perm-item.selected { border: 1px solid var(--success); background: #f0fff4; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.1); }
        .custom-checkbox { width: 18px; height: 18px; border-radius: 4px; border: 2px solid #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 10px; }
        .selected .custom-checkbox { background: var(--success); border-color: var(--success); color: white; }

        /* أزرار الإجراءات */
        .btn-action { padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; gap: 5px; }
        .edit-btn { background: #eef2ff; color: var(--primary); }
        .edit-btn:hover { background: var(--primary); color: white; }

        /* التجاوب مع الشاشات الصغيرة جداً */
        @media (max-width: 576px) {
            .btn-create { width: 100%; justify-content: center; }
            .search-box { min-width: 100%; }
            .perm-grid { grid-template-columns: 1fr; }
        }
    </style>
@endsection

@section('content')
<main class="main-content">
    <br><br>
    <div id="listView">
        <div class="table-header">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="roleSearchInput" placeholder="ابحث عن دور معين...">
            </div>
            <button class="btn-create" style="background-color:#4f46e5" onclick="openRoleEditor()">
                <i class="fas fa-plus-circle"></i> إضافة Role جديد
            </button>
        </div>

        <div class="table-responsive">
            <table class="meals-table">
                <thead>
                    <tr>
                        <th>اسم الدور (Role)</th>
                        <th>عدد الصلاحيات</th>
                        <th style="text-align: center;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="rolesBody">
                    </tbody>
            </table>
        </div>
    </div>

    <div id="editorView" style="display: none;">
        <div style="display:flex; align-items:center; gap:15px; margin-bottom:20px">
            <button onclick="backToList()" class="btn-action" style="background:#f1f5f9; color:#475569">
                <i class="fas fa-arrow-right"></i> رجوع
            </button>
            <h2 id="editorTitle" style="font-size: 1.25rem; margin:0">إضافة دور جديد</h2>
        </div>

        <div style="background:white; padding:20px; border-radius:15px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05)">
            <input type="hidden" id="roleId">
            <div class="form-group" style="margin-bottom:20px">
                <label style="font-weight:600; display:block; margin-bottom:8px">اسم الـ Role</label>
                <input type="text" id="roleName" class="form-control" style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:8px" placeholder="مثال: مدير النظام">
            </div>

            <label style="font-weight:600; display:block; margin-bottom:8px">تحديد الصلاحيات</label>
            <div class="search-box" style="width:100%; margin-bottom:15px">
                <i class="fas fa-filter"></i>
                <input type="text" id="permFilter" placeholder="ابحث عن صلاحية معينة...">
            </div>

            <div class="perm-grid" id="permissionsContainer">
                </div>

            <div style="margin-top:30px; display:flex; gap:10px; flex-wrap: wrap;">
                <button class="btn-create btn-success" onclick="saveRole()" style="flex: 1; min-width: 150px; justify-content: center;">حفظ البيانات</button>
                <button class="btn-create" style="background:#f1f5f9; color:#475569; flex: 1; min-width: 150px; justify-content: center;" onclick="backToList()">إلغاء</button>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
<script>
    let allRoles = [];
    let allPermissionsMap = {};
    let selectedPermissions = [];
    let token = localStorage.getItem('admin_token');

    $(document).ready(function() {
        fetchRolesData();

        // بحث في الجدول الرئيسي
        $('#roleSearchInput').on('input', function() {
            const query = $(this).val().toLowerCase();
            const filtered = allRoles.filter(r => r.name.toLowerCase().includes(query));
            renderRolesTable(filtered);
        });

        // فلترة الصلاحيات داخل المحرر
        $('#permFilter').on('input', function() {
            const val = $(this).val().toLowerCase();
            $('.perm-item').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(val));
            });
        });
    });

    function fetchRolesData() {
        $.ajax({
            url: `{{ route('role.index') }}`,
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + token },
            success: function(res) {
                allRoles = res.data.roles;
                allPermissionsMap = res.data.permissions;
                renderRolesTable(allRoles);
                renderPermissionsGrid(allPermissionsMap);
            }
        });
    }

    function renderRolesTable(roles) {
        const tbody = $('#rolesBody').empty();
        roles.forEach(role => {
            tbody.append(`
                <tr>
                    <td><b style="color:#1e293b">${role.name}</b></td>
                    <td>
                        <span class="status-pill" style="background:#f0f7ff; color:#0284c7; padding:4px 12px; border-radius:12px; font-size:13px; font-weight:600">
                            ${role.permissions.length} صلاحية
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <button class="btn-action edit-btn" onclick="openRoleEditor(${role.id})">
                            <i class="fas fa-edit"></i> تعديل
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function renderPermissionsGrid(permsMap) {
        const container = $('#permissionsContainer').empty();
        Object.entries(permsMap).forEach(([slug, name]) => {
            container.append(`
                <div class="perm-item" data-slug="${slug}" onclick="togglePerm(this, '${slug}')">
                    <div style="overflow: hidden;">
                        <div style="font-weight:600; font-size:0.85rem; color:#334155; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">${name}</div>
                        <code style="font-size:0.7rem; color:var(--primary)">${slug}</code>
                    </div>
                    <div class="custom-checkbox"><i class="fas fa-check"></i></div>
                </div>
            `);
        });
    }

    function togglePerm(el, slug) {
        $(el).toggleClass('selected');
        const index = selectedPermissions.indexOf(slug);
        if (index > -1) {
            selectedPermissions.splice(index, 1);
        } else {
            selectedPermissions.push(slug);
        }
    }

    function openRoleEditor(id = null) {
        $('#listView').hide();
        $('#editorView').fadeIn();
        $('.perm-item').removeClass('selected');
        selectedPermissions = [];

        if (id) {
            const role = allRoles.find(r => r.id === id);
            $('#editorTitle').text('تعديل صلاحيات: ' + role.name);
            $('#roleId').val(role.id);
            $('#roleName').val(role.name);
            role.permissions.forEach(p => {
                if(p.authorize === 'allow') {
                    selectedPermissions.push(p.permission);
                    $(`.perm-item[data-slug="${p.permission}"]`).addClass('selected');
                }
            });
        } else {
            $('#editorTitle').text('إضافة دور جديد');
            $('#roleId').val('');
            $('#roleName').val('');
        }
    }

    function saveRole() {
        const id = $('#roleId').val();
        const roleName = $('#roleName').val();
        if (!roleName) {
            toastr.error('يرجى إدخال اسم الدور');
            return;
        }

        let permissionsPayload = {};
        Object.keys(allPermissionsMap).forEach(slug => {
            permissionsPayload[slug] = selectedPermissions.includes(slug) ? 'allow' : 'deny';
        });

        const data = {
            name: roleName,
            permissions: permissionsPayload,
            _token: '{{ csrf_token() }}'
        };

        const url = id ? `/admin/role/${id}` : '/admin/role';
        const method = 'POST';
        if(id) data['_method'] = 'PUT';

        $.ajax({
            url: url,
            method: method,
            data: data,
            headers: { 'Authorization': 'Bearer ' + token },
            success: function(res) {
                toastr.success('تم حفظ البيانات بنجاح');
                backToList();
                fetchRolesData();
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    Object.values(errors).flat().forEach(err => toastr.error(err));
                } else {
                    toastr.error('حدث خطأ غير متوقع');
                }
            }
        });
    }

    function backToList() {
        $('#editorView').hide();
        $('#listView').fadeIn();
    }
</script>
@endsection
