<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/7/17
 * Time: 1:08 PM
 */

return [
    'auth_url' => 'http://172.29.236.11:35357/v3/',
    'auth_url_v2' => 'http://172.29.236.11:35357/v2.0/',
    'region' => 'RegionOne',
    'users_admin_id' => '8baa72a1d4a2fc4254fdb47417b395ab525dc07d04396af3de9aa5d5ea083dd3',
    'users_admin_name' => 'admin',
    'user_domain_name' => 'Users',
    'user_domain_id' => '14dc410ec6394da49bae9ca1ae6d28cd',  // Users
    'admin_password' => 'Cloud$erver',
    'dummy_project_id' => '17781cb64c5d41f184b9cca01c4937e6', //dummy
    'user_role_id' => '9fe2ff9ee4384b1894a90878d3e92bab',  // _member_
    'heat_stack_owner_role_id' => '8b04847f2b4c41759c62e43507bd8cb3', // heat_stack_owner
    'heat_stack_user_role_id' => '2c3488d114934dec8d4498cf6cb2c3e6', // heat_stack_user

    'adminAuthOptions' => [
        'authUrl' => 'http://172.29.236.11:35357/v3/',
        'region' => 'RegionOne',
        'user' => [
            'id' => '8baa72a1d4a2fc4254fdb47417b395ab525dc07d04396af3de9aa5d5ea083dd3',
            'password' => 'Cloud$erver',
        ],
        'scope' => [
            'project' => ['id' => '17781cb64c5d41f184b9cca01c4937e6']
        ]
    ]
];