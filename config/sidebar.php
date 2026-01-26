<?php

return [
    'dashboard' => [
        'route' => 'admin/dashboard' ,
        'icon' => 'fas fa-chart-pie' ,
        'label' => 'لوحة التحكم ',
        'active' => ['admin/dashboard'] ,
        'permission' => 'dashboard.view',
    ] ,

    'category' => [
        'route' => '/admin/categories' ,
        'icon' => 'fas fa-list' ,
        'label' => 'الاقسام',
        'active' => ['admin/categories'] ,
        'permission' => 'category.view'  ,
    ],
    'products' => [
        'route' => '/admin/products' ,
        'icon' => 'fas fa-chart-pie' ,
        'label' => 'المنتجات',
        'active' => ['admin/products']  ,
        'permission' => 'meal.view' ,
    ] ,

    'orders' => [
        'route' => '/admin/orders' ,
        'icon' => 'fas fa-desktop' ,
        'label' => 'مراقبة الطلبات ',
        'active' => ['admin/orders']  ,
        'permission' => 'order.view' ,
    ] ,

    'delivery' => [
        'route' => '/admin/deliveries' ,
        'icon' => 'fas fa-plus-circle' ,
        'label' => 'رسوم التوصيل',
        'active' => ['admin/deliveries']  ,
        'permission' => 'delivery.view',
    ] ,

    'coupons' => [
        'route' => '/admin/coupons' ,
        'icon' => 'fas fa-tags' ,
        'label' => 'الكوبونات',
        'active' => ['admin/coupons']  ,
        'permission' => 'coupon.view',
    ] ,

    'users' => [
        'route' => '/admin/users' ,
        'icon' => 'fas fa-user-shield' ,
        'label' => 'إدارة المستخدمين',
        'active' => ['admin/users']  ,
        'permission' => 'user.view',
    ] ,

    'roles' => [
        'route' => '/admin/roles' ,
        'icon' => 'fas fa-user-shield' ,
        'label' => 'الادوار والصلاحيات',
        'active' => ['admin/roles']  ,
        'permission' => 'role.view' ,
    ] ,


    'payments' => [
        'route' => '/admin/payments' ,
        'icon' => 'fas fa-history' ,
        'label' => 'سجل المدفوعات',
        'active' => ['admin/payments']  ,
        'permission' => 'payment.show' ,
    ],

    'admins' => [
        'route' => '/admin/admins' ,
        'icon' => 'fas fa-users' ,
        'label' => 'المواظفين',
        'active' => ['admin/admins']  ,
        'permission' => 'admin.view' ,
    ] ,

    'invoice' => [
        'route' => '/invoices' ,
        'icon' => 'fas fa-receipt' ,
        'label' => 'إدارة المصاريف والفواتير',
        'active' => ['invoices']  ,
        'permission' => 'invoice.view' ,
    ] ,

] ;
