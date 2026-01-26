@extends('layout.dashboard.app')

@section('title', 'ٍسجل المدفوعات')

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

        /* الهيدر */
        .table-header {
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 15px; margin-bottom: 25px;
        }

        .search-box { position: relative; flex: 1; min-width: 250px; }
        .search-box input {
            width: 100%; padding: 12px 15px; border: 1px solid #e5e7eb;
            border-radius: 10px; outline: none; transition: 0.3s;
        }

        /* الجدول */
        .table-container {
            background: white; border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            overflow: hidden; margin-top: 20px;
        }
        .table-responsive { width: 100%; overflow-x: auto; }
        .orders-table { width: 100%; border-collapse: collapse; text-align: right; min-width: 900px; }
        .orders-table th { background: #f8fafc; padding: 15px; color: #64748b; border-bottom: 2px solid #edf2f7; font-size: 14px; }
        .orders-table td { padding: 12px 15px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }

        /* المودال */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
            align-items: center; justify-content: center; z-index: 1000; padding: 20px;
        }
        .modal-box {
            background: white; padding: 25px; border-radius: 16px;
            width: 600px; max-width: 100%; max-height: 90vh; overflow-y: auto;
        }

        /* عرض الفاتورة داخل المودال */
        .receipt-preview {
            width: 100%; border-radius: 12px; border: 1px solid #e5e7eb;
            margin-top: 15px; cursor: zoom-in;
        }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed #eee; }
        .detail-label { color: var(--text-muted); font-weight: 500; }
        .detail-value { color: var(--text-main); font-weight: 600; }

        .btn-view {
            padding: 8px 16px; background-color: var(--primary); color: white;
            border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 6px;
        }
    </style>
@endsection

@section('content')
<main class="main-content">
    <br><br>
    <div class="table-header">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="ابحث برقم الطلب ...">
        </div>
        <h2 style="font-size: 1.5rem; color: var(--text-main);">سجل المدفوعات</h2>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>رقم الطلب (Order ID)</th>
                        <th>العميل</th>
                        <th>قيمة المبلغ</th>
                        <th>تاريخ الدفع</th>
                        <th>الحالة</th>
                        <th style="text-align: center;">التفاصيل / الفاتورة</th>
                    </tr>
                </thead>
                <tbody id="ordersBody">
                    </tbody>
            </table>
        </div>

        <div class="pagination-container">
            <div id="paginationInfo"></div>
            <div style="display:flex; gap:8px;">
                <button class="page-link" id="prevPage" onclick="changePage('prev')">السابق</button>
                <div id="pageNumbers" style="display:flex; gap:5px"></div>
                <button class="page-link" id="nextPage" onclick="changePage('next')">التالي</button>
            </div>
        </div>
    </div>
</main>

<div id="modalOrderDetails" class="modal-overlay">
    <div class="modal-box">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
            <h3 style="margin:0">تفاصيل الدفع والفاتورة</h3>
            <button onclick="closeModals()" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
        </div>

        <div id="orderDetailsContent">
            <div class="detail-row">
                <span class="detail-label">رقم الطلب:</span>
                <span class="detail-value" id="viewOrderId">#12345</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">اسم العميل:</span>
                <span class="detail-value" id="viewCustomerName">أحمد محمد</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">المبلغ المدفوع:</span>
                <span class="detail-value" id="viewAmount" style="color: var(--success);">500 ج.م</span>
            </div>

            <div style="margin-top: 20px;">
                <label class="detail-label">صورة وصل تحويل </label>
                <a id="receiptLink" target="_blank">
                    <img src="" style="height: 300px" id="viewReceiptImage" class="receipt-preview" alt="وصل الفاتورة">
                </a>
            </div>
        </div>

        <div style="margin-top:25px">
            <button onclick="closeModals()" style="width:100%; padding:12px; background:#f1f5f9; border:none; border-radius:10px; cursor:pointer; font-weight:600">إغلاق</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
let payments = [];
let currentPage = 1;
let lastPage = 1;

$(document).ready(function() {
    fetchOrders(1);

    $('#searchInput').on('keyup', function() {
        fetchOrders(1, $(this).val());
    });
});

function fetchOrders(page, search = '') {
    $.get(`{{ url('/admin/payment') }}?page=${page}&number_order=${search}`, function(res) {
        payments = res.data.data;
        currentPage = res.data.current_page;
        lastPage = res.data.last_page;
        renderTable(payments);
        renderPagination();
    });
}

function renderTable(payments) {
    const tbody = $('#ordersBody');
    tbody.empty();

    if (!payments.length) {
        tbody.append('<tr><td colspan="6" style="text-align:center; padding:50px; color:#94a3b8">لا توجد طلبات دفع متاحة</td></tr>');
        return;
    }

    payments.forEach(payment => {
        tbody.append(`
            <tr>
                <td><span class="sku-tag">#${payment.order.number_order}</span></td>
                <td><span style="font-weight:600">${payment.user.name}</span></td>
                <td><b style="color:var(--primary)">${payment.order.total_price} ج.م</b></td>
                <td>${new Date(payment.created_at).toLocaleDateString('ar-EG')}</td>
                <td><span class="badge badge-active">تم التحويل</span></td>
                <td style="text-align: center;">
                    <button class="btn-view" onclick="openOrderModal(${payment.id})">
                        <i class="fas fa-eye"></i> عرض الوصل
                    </button>
                </td>
            </tr>
        `);
    });
}

function openOrderModal(id) {
    const payment = payments.find(p => p.id === id);
    if (payment) {
        $('#viewOrderId').text('#' + payment.order.number_order);
        $('#viewCustomerName').text(payment.user.name);
        $('#viewAmount').text(payment.order.total_price + ' ج.م');
        $('#viewReceiptImage').attr('src', payment.invoice_url);
        $('#receiptLink').attr('href', payment.invoice_url);

        $('#modalOrderDetails').css('display', 'flex');
    }
}

function closeModals() {
    $('.modal-overlay').hide();
}

function renderPagination() {
    const wrapper = $('#pageNumbers');
    wrapper.empty();
    let start = Math.max(1, currentPage - 2);
    let end = Math.min(lastPage, currentPage + 2);
    for (let i = start; i <= end; i++) {
        wrapper.append(`<button class="page-link ${i === currentPage ? 'active' : ''}" onclick="fetchOrders(${i})">${i}</button>`);
    }
    $('#prevPage').prop('disabled', currentPage === 1);
    $('#nextPage').prop('disabled', currentPage === lastPage);
}
</script>
@endsection
