<?php

return [
    'role_structure' => [
        'super_admin' => [

            'stores' => 'c,r,u,d',

            'categories' => 'c,r,u,d',
            'products' => 'c,r,u,d',
             'clients' => 'c,r,u,d',
             'suppliers' => 'c,r,u,d',
            'orders' => 'c,r,u,d',
            'orders_suppliers' => 'c,r,u,d',
            'orders_return' => 'c,r,u,d',
            'orders_suppliers_return' => 'c,r,u,d',

            
            'sales_reports' => 'r',
            'purch_reports' => 'r',
            'prod_reports' => 'r',

            'users' => 'c,r,u,d',
            'roles' => 'c,r,u,d',

           // 'tags' => 'c,r,u,d',
           // 'posts' => 'c,r,u,d',




        ],
        'admin' => [
            'categories' => 'c,r',
            'products' => 'c,r',
            'clients' => 'c,r,u,d',
            'orders' => 'c,r,u,d',
            'users' => 'r',


        ]
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
