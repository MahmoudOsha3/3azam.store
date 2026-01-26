@extends('layout.dashboard.app')

@section('title' , 'الاقسام')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/categories.css') }}">
    <style>
        :root { --primary: #4f46e5; --danger: #ef4444; --success: #10b981; }
    </style>
@endsection

@section('content')
    <main class="main-content">
        <div class="table-header">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="ابحث عن قسم...">
            </div>
            <button class="btn-create" style="background-color: #4f46e5;" onclick="openFormModal()">
                <i class="fas fa-plus"></i> إضافة قسم جديد
            </button>
        </div>

        <table class="categories-table">
            <thead>
                <tr>
                    <th>عنوان القسم</th>
                    <th>القسم الأب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="categoriesBody"></tbody>
        </table>
    </main>

    <div id="modalForm" class="modal-overlay">
        <div class="modal-box">
            <h3 id="modalTitle">إضافة قسم جديد</h3>
            <hr style="opacity:0.1; margin-bottom:20px">
            <input type="hidden" id="catId">
            <div class="form-group" style="margin-bottom:15px">
                <label>اسم القسم</label>
                <input type="text" id="catTitle" placeholder="مثلاً: ملابس , احذية...">
            </div>
            <div class="form-group" style="margin-bottom:20px">
                <label>القسم الأب (اختياري)</label>
                <select id="catParent">
                    <option value="">-- قسم أساسي (رئيسي) --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:10px">
                <button class="btn-create" style="flex:2" onclick="saveCategory()">حفظ القسم</button>
                <button onclick="closeModals()" style="flex:1; border:none; background:#eee; border-radius:8px; cursor:pointer">إلغاء</button>
            </div>
        </div>
    </div>

    <div id="modalView" class="modal-overlay">
        <div class="modal-box" style="border-right: 5px solid #3b82f6;">
            <h3>تفاصيل القسم</h3>
            <p style="margin-top:20px"><b>اسم القسم:</b> <span id="viewTitle"></span></p>
            <p><b>القسم الأب:</b> <span id="viewParentName"></span></p>
            <button onclick="closeModals()" style="width:100%; padding:10px; margin-top:15px; border:none; background:#eee; border-radius:8px; cursor:pointer">إغلاق</button>
        </div>
    </div>

    <div id="modalDelete" class="modal-overlay">
        <div class="modal-box" style="width:300px; text-align:center">
            <i class="fas fa-exclamation-triangle fa-3x" style="color:#ef4444"></i>
            <h3 style="margin-top:15px">حذف القسم؟</h3>
            <p>هل أنت متأكد من حذف قسم <b id="deleteTitle"></b>؟</p>
            <input type="hidden" id="deleteId">
            <div style="display:flex; gap:10px; margin-top:20px">
                <button onclick="confirmDelete()" style="flex:1; background:#ef4444; color:white; border:none; padding:10px; border-radius:8px; cursor:pointer">نعم، احذف</button>
                <button onclick="closeModals()" style="flex:1; background:#eee; border:none; padding:10px; border-radius:8px; cursor:pointer">تراجع</button>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    let categories = [];
    const token = localStorage.getItem('admin_token');

    $(document).ready(function() {
        fetchCategories();

        $('#searchInput').on('input', function() {
            const query = $(this).val().toLowerCase();
            const filtered = categories.filter(c => c.name && c.name.toLowerCase().includes(query));
            renderTable(filtered);
        });
    });

    function fetchCategories() {
        $.ajax({
            url: "{{ route('category.index') }}",
            method: "GET",
            success: function(res) {
                categories = res.data;
                renderTable(categories);
            },
            error: function() {
                toastr.error('عذراً، حدث خطأ ما');
            }
        });
    }

    function renderTable(data) {
        const tbody = $('#categoriesBody');
        tbody.empty();
        data.forEach(cat => {
            const parentName = cat.parent ? cat.parent.name : '<span style="color:#94a3b8">قسم أساسي</span>';
            tbody.append(`
                <tr>
                    <td style="font-weight: 600;"><i class="fas fa-folder-open" style="color:#4f46e5; margin-left:8px"></i>${cat.name}</td>
                    <td>${parentName}</td>
                    <td>
                        <button class="btn-action view-btn" onclick="openViewModal(${cat.id})"><i class="fas fa-eye"></i></button>
                        <button class="btn-action edit-btn" onclick="openFormModal(${cat.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn-action delete-btn" onclick="openDeleteModal(${cat.id})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
        });
    }

    function openFormModal(id = null) {
        if (id) {
            const cat = categories.find(c => c.id === id);
            $('#modalTitle').text('تعديل القسم');
            $('#catId').val(cat.id);
            $('#catTitle').val(cat.name);
            $('#catParent').val(cat.parent_id || "");
        } else {
            $('#modalTitle').text('إضافة قسم جديد');
            $('#catId').val(''); $('#catTitle').val(''); $('#catParent').val('');
        }
        $('#modalForm').css('display', 'flex');
    }

    function saveCategory() {
        const id = $('#catId').val();
        const name = $('#catTitle').val();
        const parentId = $('#catParent').val();
        if (!name) return toastr.warning("يرجى إدخال اسم القسم");

        const url = id ? `{{ url('admin/category') }}/${id}` : "{{ route('category.store') }}";
        $.ajax({
            url: url,
            method: id ? 'PUT' : 'POST',
            data: { name: name, parent_id: parentId },
            success: function() {
                toastr.success("تمت العملية بنجاح");
                fetchCategories();
                closeModals();
            }
        });
    }

    function openViewModal(id) {
        const cat = categories.find(c => c.id === id);
        $('#viewTitle').text(cat.name);
        $('#viewParentName').text(cat.parent ? cat.parent.name : "قسم أساسي");
        $('#modalView').css('display', 'flex');
    }

    function openDeleteModal(id) {
        const cat = categories.find(c => c.id === id);
        $('#deleteTitle').text(cat.name);
        $('#deleteId').val(cat.id);
        $('#modalDelete').css('display', 'flex');
    }

    function confirmDelete() {
        const id = $('#deleteId').val();
        let url = "{{ route('category.destroy', ':id') }}".replace(':id', id);
        $.ajax({
            url: url,
            method: "DELETE",
            success: function() {
                toastr.success("تم الحذف بنجاح");
                fetchCategories();
                closeModals();
            }
        });
    }

    function closeModals() {
        $('.modal-overlay').hide();
    }
</script>
@endsection
