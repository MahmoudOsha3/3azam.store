@extends('layout.dashboard.app')

@section('title' , 'رسوم التوصيل')

@section('css')
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

        .main-content { padding: 20px; background-color: var(--bg-light); min-height: 100vh; }

        .table-header {
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 15px; margin-bottom: 25px;
        }

        .search-box { position: relative; flex: 1; min-width: 250px; }
        .search-box i { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
        .search-box input {
            width: 100%; padding: 12px 45px 12px 15px; border: 1px solid #e5e7eb;
            border-radius: 10px; outline: none; transition: 0.3s;
        }
        .search-box input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }

        .table-container { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; width: 100%; }
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        .delivery-table { width: 100%; border-collapse: collapse; text-align: right; min-width: 600px; }
        .delivery-table th { background-color: #f8fafc; padding: 15px; color: var(--text-muted); font-weight: 600; border-bottom: 1px solid #edf2f7; }
        .delivery-table td { padding: 15px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }

        .btn-action {
            width: 35px; height: 35px; border-radius: 8px; border: none;
            cursor: pointer; transition: 0.2s; display: inline-flex;
            align-items: center; justify-content: center; margin-left: 5px;
        }
        .edit-btn { background: #f5f3ff; color: #7c3aed; }
        .edit-btn:hover { background: #7c3aed; color: white; }

        .modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;
        }
        .modal-box { background: white; width: 100%; max-width: 400px; padding: 25px; border-radius: 15px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }

        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-main); }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; outline: none; background: #f9fafb; }
        .form-group input:focus { border-color: var(--primary); background: white; }

        .tax-badge { background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 20px; font-weight: bold; font-size: 0.9em; }

        @media (max-width: 640px) { .table-header { flex-direction: column; align-items: stretch; } }
    </style>
@endsection

@section('content')
    <main class="main-content">
        <br><br>
        <div class="table-header">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="ابحث عن محافظة...">
            </div>
            <div style="color: var(--text-muted); font-weight: 600;">إدارة رسوم توصيل المحافظات</div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="delivery-table">
                    <thead>
                        <tr>
                            <th>المحافظة (Government)</th>
                            <th>رسوم التوصيل (Tax)</th>
                            <th style="text-align: center;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="deliveryBody"></tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modalForm" class="modal-overlay">
        <div class="modal-box">
            <h3 id="modalTitle" style="margin-bottom: 15px;">تعديل رسوم التوصيل</h3>
            <hr style="opacity:0.1; margin-bottom:20px">

            <input type="hidden" id="govId">

            <div class="form-group" style="margin-bottom:15px">
                <label>المحافظة</label>
                <input type="text" id="govName" disabled style="cursor: not-allowed; background: #eee;">
            </div>

            <div class="form-group" style="margin-bottom:20px">
                <label>قيمة الضريبة / التوصيل</label>
                <input type="number" id="taxValue" placeholder="0.00">
            </div>

            <div style="display:flex; gap:10px">
                <button class="edit-btn" style="flex:2; height: 45px; font-weight: bold;" onclick="saveTax()">حفظ التعديلات</button>
                <button onclick="closeModals()" style="flex:1; border:none; background:#f3f4f6; border-radius:10px; cursor:pointer">إلغاء</button>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    let deliveries = [];
    const token = localStorage.getItem('admin_token');

    $(document).ready(function() {
        fetchDeliveries();

        $('#searchInput').on('input', function() {
            const query = $(this).val().toLowerCase();
            const filtered = deliveries.filter(g => g.government && g.government.toLowerCase().includes(query));
            renderTable(filtered);
        });
    });

    function fetchDeliveries() {
        $.ajax({
            url: "{{ route('delivery.index') }}",
            method: "GET",
            success: function(res) {
                deliveries = res.data;
                renderTable(deliveries);
            },
            error: function() {
                toastr.error('عذراً، حدث خطأ أثناء جلب البيانات');
            }
        });
    }

    function renderTable(deliveries) {
        const tbody = $('#deliveryBody');
        tbody.empty();
        deliveries.forEach(delivery => {
            tbody.append(`
                <tr>
                    <td style="font-weight: 600;">
                        <i class="fas fa-map-marker-alt" style="color:var(--primary); margin-left:8px"></i>${delivery.government}
                    </td>
                    <td>
                        <span class="tax-badge">${delivery.tax || 0} ج.م</span>
                    </td>
                    <td style="text-align: center;">
                        <button class="btn-action edit-btn" title="تعديل الرسوم" onclick="openEditModal(${delivery.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function openEditModal(id) {
        const delivery = deliveries.find(g => g.id === id);
        if(delivery) {
            $('#govId').val(delivery.id);
            $('#govName').val(delivery.government);
            $('#taxValue').val(delivery.tax || 0);
            $('#modalForm').css('display', 'flex');
        }
    }

    function saveTax() {
        const id = $('#govId').val();
        const tax = $('#taxValue').val();

        if (tax === "") return toastr.warning("يرجى إدخال قيمة الرسوم");

        let url = "{{ route('delivery.update', ':id') }}".replace(':id', id);
        $.ajax({
            url: url,
            method: 'PUT',
            data: {
                tax: tax,
                government: $('#govName').val()
            },
            success: function() {
                toastr.success("تم تحديث رسوم التوصيل بنجاح");
                fetchDeliveries();
                closeModals();
            },
            error: function() {
                toastr.error("فشل في تحديث البيانات");
            }
        });
    }

    function closeModals() {
        $('.modal-overlay').hide();
    }
</script>
@endsection

