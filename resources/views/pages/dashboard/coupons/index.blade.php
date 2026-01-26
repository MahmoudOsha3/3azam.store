@extends('layout.dashboard.app')

@section('title', 'إدارة الكوبونات')

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

        .main-content { padding: 20px; background-color: var(--bg-light); min-height: 100vh; }

        .table-header {
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 15px; margin-bottom: 25px;
        }

        .search-box { position: relative; flex: 1; min-width: 250px; }
        .search-box input {
            width: 100%; padding: 12px 15px; border: 1px solid #e5e7eb;
            border-radius: 10px; outline: none; transition: 0.3s;
        }

        .btn-create {
            padding: 12px 20px; background-color: var(--primary); color: white;
            border: none; border-radius: 10px; cursor: pointer; font-weight: 600;
            display: flex; align-items: center; gap: 8px; transition: 0.3s;
        }

        .table-container { background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; }
        .table-responsive { width: 100%; overflow-x: auto; }
        .products-table { width: 100%; border-collapse: collapse; text-align: right; min-width: 1000px; }
        .products-table th { background: #f8fafc; padding: 15px; color: #64748b; border-bottom: 2px solid #edf2f7; font-size: 13px; }
        .products-table td { padding: 12px 15px; border-bottom: 1px solid #edf2f7; font-size: 13px; }

        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
            align-items: center; justify-content: center; z-index: 1000; padding: 20px;
        }
        .modal-box { background: white; padding: 25px; border-radius: 16px; width: 750px; max-width: 100%; max-height: 90vh; overflow-y: auto; }

        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: var(--text-main); }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; }

        .badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .badge-active { background: #dcfce7; color: var(--success); }
        .badge-inactive { background: #fee2e2; color: var(--danger); }
        .coupon-code { font-family: monospace; background: #eef2ff; color: #4f46e5; padding: 4px 8px; border-radius: 6px; font-weight: bold; }
    </style>
@endsection

@section('content')
<main class="main-content">
    <br><br>
    <div class="table-header">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="ابحث عن طريق كود الكوبون...">
        </div>
        <button class="btn-create" onclick="openFormModal()">
            <i class="fas fa-plus"></i> إضافة كوبون جديد
        </button>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>نوع الخصم</th>
                        <th>القيمة</th>
                        <th>الاستخدام</th>
                        <th>الحد الأدنى للطلب</th>
                        <th>تاريخ البدء</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الحالة</th>
                        <th style="text-align: center;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="couponsBody"></tbody>
            </table>
        </div>
        <div id="paginationInfo" style="padding:15px; color: var(--text-muted);"></div>
    </div>
</main>

<div id="modalForm" class="modal-overlay">
    <div class="modal-box">
        <h3 id="formTitle">إضافة كوبون جديد</h3>
        <input type="hidden" id="couponId">
        <div class="form-grid">
            <div class="form-group">
                <label>كود الكوبون</label>
                <input type="text" id="coupon_code" placeholder="مثلاً: SAVE20">
            </div>
            <div class="form-group">
                <label>نوع الخصم</label>
                <select id="discount_type">
                    <option value="fixed">مبلغ ثابت</option>
                    <option value="percentage">نسبة مئوية %</option>
                </select>
            </div>
            <div class="form-group">
                <label>قيمة الخصم</label>
                <input type="number" id="discount_value">
            </div>
            <div class="form-group">
                <label>أقصى عدد استخدام</label>
                <input type="number" id="max_uses">
            </div>
            <div class="form-group">
                <label>تاريخ البدء</label>
                <input type="date" id="start_at">
            </div>
            <div class="form-group">
                <label>تاريخ الانتهاء</label>
                <input type="date" id="end_at">
            </div>
            <div class="form-group">
                <label>الحد الأدنى للطلب</label>
                <input type="number" id="min_order_amount">
            </div>
            <div class="form-group">
                <label>الحالة</label>
                <select id="coupon_status">
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>
        </div>
        <div style="display:flex; gap:10px; margin-top:25px">
            <button class="btn-create" style="flex:2; justify-content:center" onclick="saveCoupon()">حفظ الكوبون</button>
            <button onclick="closeModals()" style="flex:1; background:#f1f5f9; border:none; border-radius:10px; cursor:pointer">إلغاء</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
let coupons = [];
let currentSearch = '';

$(document).ready(function() {
    fetchCoupons(1);

    $('#searchInput').on('keyup', function() {
        currentSearch = $(this).val();
        fetchCoupons(1);
    });
});

function fetchCoupons(page) {
    // تم تغيير المعامل من title إلى code كما طلبت
    $.get(`{{ route('coupons.index') }}?page=${page}&code=${currentSearch}`, function(res) {
        coupons = res.data.data || [];
        renderTable(coupons);
    });
}

function renderTable(data) {
    const tbody = $('#couponsBody');
    tbody.empty();

    if (!data.length) {
        tbody.append('<tr><td colspan="9" style="text-align:center; padding:50px; color:#94a3b8">لا توجد كوبونات</td></tr>');
        return;
    }

    data.forEach(c => {
        const statusClass = c.status == 'active' ? 'badge-active' : 'badge-inactive';
        const statusText = c.status == 'active' ? 'نشط' : 'معطل';
        const discountLabel = c.discount_type === 'percentage' ? '%' : 'ج.م';

        tbody.append(`
            <tr>
                <td><span class="coupon-code">${c.code}</span></td>
                <td>${c.discount_type === 'fixed' ? 'مبلغ ثابت' : 'نسبة مئوية'}</td>
                <td><b style="color:var(--primary)">${c.discount_value} ${discountLabel}</b></td>
                <td>${c.used_count} / ${c.max_uses}</td>
                <td>${c.min_order_amount} ج.م</td>
                <td>${c.start_at ? c.start_at.substring(0, 10) : '-'}</td>
                <td>${c.end_at ? c.end_at.substring(0, 10) : '-'}</td>
                <td><span class="badge ${statusClass}">${statusText}</span></td>
                <td style="text-align: center;">
                    <button class="btn-create" style="padding:5px 10px; background:none; color:var(--primary)" onclick="openFormModal(${c.id})"><i class="fas fa-edit"></i></button>
                </td>
            </tr>
        `);
    });
}

function openFormModal(id = null) {
    if (id) {
        const c = coupons.find(x => x.id === id);
        $('#formTitle').text('تعديل كوبون');
        $('#couponId').val(c.id);
        $('#coupon_code').val(c.code);
        $('#discount_type').val(c.discount_type);
        $('#discount_value').val(c.discount_value);
        $('#max_uses').val(c.max_uses);
        $('#start_at').val(c.start_at ? c.start_at.replace(' ', 'T') : '');
        $('#end_at').val(c.end_at ? c.end_at.replace(' ', 'T') : '');
        $('#min_order_amount').val(c.min_order_amount);
        $('#coupon_status').val(c.status);
    } else {
        $('#formTitle').text('إضافة كوبون جديد');
        $('#couponId').val('');
        $('#modalForm input').val('');
        $('#coupon_status').val('active');
    }
    $('#modalForm').css('display', 'flex');
}

function saveCoupon() {
    const id = $('#couponId').val();
    const data = {
        code: $('#coupon_code').val(),
        discount_type: $('#discount_type').val(),
        discount_value: $('#discount_value').val(),
        max_uses: $('#max_uses').val(),
        start_at: $('#start_at').val(),
        end_at: $('#end_at').val(),
        min_order_amount: $('#min_order_amount').val(),
        status: $('#coupon_status').val(),
        _token: '{{ csrf_token() }}'
    };

    updateUrl = "{{ route('coupons.update' , 'id') }}".replace('id' , id) ;
    const url = id ? updateUrl : "{{ route('coupons.store') }}";
    const type = id ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: type,
        data: data,
        success: function() {
            toastr.success('تم حفظ الكوبون بنجاح');
            closeModals();
            fetchCoupons(1);
        },
        error: function(xhr)
        {
            toastr.error(xhr.responseJSON?.message || 'خطأ في الحفظ');
            closeModals();

        }
    });
}

function closeModals() { $('.modal-overlay').hide(); }
</script>
@endsection
