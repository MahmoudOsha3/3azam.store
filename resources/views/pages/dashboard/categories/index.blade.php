@extends('layout.dashboard.app')

@section('title' , 'الاقسام')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/categories.css') }}">
    <style>
        :root { --primary: #4f46e5; --danger: #ef4444; --success: #10b981; }
        
        /* ستايل معاينة الصورة */
        .image-preview-container {
            width: 100%;
            height: 150px;
            border: 2px dashed #ddd;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-top: 10px;
            position: relative;
            cursor: pointer;
            transition: 0.3s;
            background: #f8fafc;
        }
        .image-preview-container:hover { border-color: var(--primary); background: #f0f1ff; }
        .image-preview-container img { width: 100%; height: 100%; object-fit: cover; display: none; }
        .image-preview-container i { font-size: 30px; color: #94a3b8; }
        
        /* صورة الجدول */
        .cat-img-table {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
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
                    <th>الصورة</th>
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
            
            <form id="categoryForm" enctype="multipart/form-data">
                <input type="hidden" id="catId">
                
                <div class="form-group" style="margin-bottom:15px">
                    <label>اسم القسم</label>
                    <input type="text" id="catTitle" name="name" placeholder="مثلاً: ملابس , احذية...">
                </div>

                <div class="form-group" style="margin-bottom:15px">
                    <label>القسم الأب (اختياري)</label>
                    <select id="catParent" name="parent_id">
                        <option value="">-- قسم أساسي (رئيسي) --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:20px">
                    <label>صورة القسم</label>
                    <div class="image-preview-container" onclick="document.getElementById('catImage').click()">
                        <i class="fas fa-cloud-upload-alt" id="uploadIcon"></i>
                        <img src="" id="imagePreview">
                    </div>
                    <input type="file" id="catImage" name="image" style="display:none" accept="image/*" onchange="previewFile()">
                </div>

                <div style="display:flex; gap:10px">
                    <button type="button" class="btn-create" style="flex:2" onclick="saveCategory()">حفظ القسم</button>
                    <button type="button" onclick="closeModals()" style="flex:1; border:none; background:#eee; border-radius:8px; cursor:pointer">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    @endsection

@section('js')
<script>
    let categories = [];

    $(document).ready(function() {
        fetchCategories();
    });

    // معاينة الصورة المرفوعة
    function previewFile() {
        const file = document.getElementById('catImage').files[0];
        const preview = document.getElementById('imagePreview');
        const icon = document.getElementById('uploadIcon');
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            $(preview).show();
            $(icon).hide();
        }
        if (file) reader.readAsDataURL(file);
    }

    function fetchCategories() {
        $.ajax({
            url: "{{ route('category.index') }}",
            method: "GET",
            success: function(res) {
                categories = res.data;
                renderTable(categories);
            }
        });
    }

    function renderTable(data) {
        const tbody = $('#categoriesBody');
        tbody.empty();
        data.forEach(cat => {
            const parentName = cat.parent ? cat.parent.name : '<span style="color:#94a3b8">قسم أساسي</span>';
            const imgPath = cat.image_url ? `${cat.image_url}` : 'https://via.placeholder.com/50';
            
            tbody.append(`
                <tr>
                    <td><img src="${imgPath}" class="cat-img-table"></td>
                    <td style="font-weight: 600;">${cat.name}</td>
                    <td>${parentName}</td>
                    <td>
                        <button class="btn-action edit-btn" onclick="openFormModal(${cat.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn-action delete-btn" onclick="openDeleteModal(${cat.id})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
        });
    }

    function openFormModal(id = null) {
        // إعادة ضبط المودال
        $('#imagePreview').hide().attr('src', '');
        $('#uploadIcon').show();
        $('#categoryForm')[0].reset();

        if (id) {
            const cat = categories.find(c => c.id === id);
            $('#modalTitle').text('تعديل القسم');
            $('#catId').val(cat.id);
            $('#catTitle').val(cat.name);
            $('#catParent').val(cat.parent_id || "");
            if(cat.image) {
                $('#imagePreview').attr('src', `/storage/${cat.image}`).show();
                $('#uploadIcon').hide();
            }
        } else {
            $('#modalTitle').text('إضافة قسم جديد');
            $('#catId').val('');
        }
        $('#modalForm').css('display', 'flex');
    }

    function saveCategory() {
        const id = $('#catId').val();
        const name = $('#catTitle').val();
        if (!name) return toastr.warning("يرجى إدخال اسم القسم");

        let formData = new FormData();
        formData.append('name', name);
        formData.append('parent_id', $('#catParent').val());
        
        const imageFile = document.getElementById('catImage').files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        let url = id ? `{{ url('admin/category') }}/${id}` : "{{ route('category.store') }}";
        
        if(id) formData.append('_method', 'PUT');

        $.ajax({
            url: url,
            method: 'POST', 
            data: formData,
            processData: false, 
            contentType: false, 
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function() {
                toastr.success("تم حفظ البيانات بنجاح");
                fetchCategories();
                closeModals();
            },
            error: function(err) {
                toastr.error("حدث خطأ أثناء الحفظ");
            }
        });
    }

    function closeModals() { $('.modal-overlay').hide(); }
</script>
@endsection