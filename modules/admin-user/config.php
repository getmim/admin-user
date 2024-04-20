<?php

return [
    '__name' => 'admin-user',
    '__version' => '0.3.0',
    '__git' => 'git@github.com:getmim/admin-user.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/admin-user' => ['install','update','remove'],
        'theme/admin/user' => ['install','update','remove'],
        'theme/admin/layout/admin-user.phtml' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'admin' => NULL
            ],
            [
                'lib-form' => NULL
            ],
            [
                'lib-formatter' => NULL
            ],
            [
                'lib-user' => NULL
            ],
            [
                'lib-pagination' => NULL
            ]
        ],
        'optional' => [
            [
                'lib-upload' => NULL
            ]
        ]
    ],
    'autoload' => [
        'classes' => [
            'AdminUser\\Controller' => [
                'type' => 'file',
                'base' => 'modules/admin-user/controller'
            ],
            'AdminUser\\Library' => [
                'type' => 'file',
                'base' => 'modules/admin-user/library'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'admin' => [
            'adminUser' => [
                'path' => [
                    'value' => '/user'
                ],
                'method' => 'GET',
                'handler' => 'AdminUser\\Controller\\User::index'
            ],
            'adminUserCreate' => [
                'path' => [
                    'value' => '/user/create'
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminUser\\Controller\\User::create'
            ],
            'adminUserEditAccount' => [
                'path' => [
                    'value' => '/user/(:id)/account',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminUser\\Controller\\Account::edit'
            ],
            'adminUserEditPassword' => [
                'path' => [
                    'value' => '/user/(:id)/password',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminUser\\Controller\\Password::edit'
            ],
            'adminUserEditProfile' => [
                'path' => [
                    'value' => '/user/(:id)/profile',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminUser\\Controller\\Profile::edit'
            ],
            'adminUserRemove' => [
                'path' => [
                    'value' => '/user/(:id)/remove',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminUser\\Controller\\User::remove'
            ]
        ]
    ],
    'adminUi' => [
        'sidebarMenu' => [
            'items' => [
                'user' => [
                    'label' => 'User',
                    'icon' => '<i class="fas fa-user"></i>',
                    'priority' => 0,
                    'children' => [
                        'all-user' => [
                            'label' => 'All Users',
                            'icon' => '<i></i>',
                            'route' => ['adminUser'],
                            'perms' => 'manage_user'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'libForm' => [
        'forms' => [
            'admin.user.account' => [
                'name' => [
                    'label' => 'Name',
                    'type' => 'text',
                    'slugof' => 'fullname',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'text' => 'slug',
                        'unique' => [
                            'model' => 'LibUserMain\\Model\\User',
                            'field' => 'name',
                            'self' => [
                                'service' => 'req.param.id',
                                'field' => 'id'
                            ]
                        ]
                    ],
                    'position' => 'top-left'
                ],
                'status' => [
                    'label' => 'Status',
                    'type' => 'select',
                    'options' => [
                        2 => 'Unverified',
                        3 => 'Verified'
                    ],
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE
                    ],
                    'position' => 'top-left'
                ]
            ],
            'admin.user.create' => [
                'name' => [
                    'label' => 'Name',
                    'type' => 'text',
                    'slugof' => 'fullname',
                    'position' => 'center',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'text' => 'slug',
                        'unique' => [
                            'model' => 'LibUserMain\\Model\\User',
                            'field' => 'name'
                        ]
                    ]
                ],
                'fullname' => [
                    'label' => 'Fullname',
                    'type' => 'text',
                    'position' => 'center',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'type' => 'password',
                    'meter' => TRUE,
                    'position' => 'center',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'length' => [
                            'min' => 6
                        ]
                    ]
                ],
                'avatar' => [
                    'label' => 'Avatar',
                    'type' => 'image',
                    'form' => 'std-image',
                    'position' => 'left',
                    'rules' => [
                        'upload' => TRUE
                    ]
                ],
                'status' => [
                    'label' => 'Status',
                    'type' => 'select',
                    'options' => [
                        2 => 'Unverified',
                        3 => 'Verified'
                    ],
                    'position' => 'left',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE
                    ]
                ]
            ],
            'admin.user.index' => [
                'q' => [
                    'label' => 'Search',
                    'type' => 'search',
                    'nolabel' => TRUE,
                    'rules' => []
                ]
            ],
            'admin.user.password' => [
                'password' => [
                    'label' => 'Password',
                    'type' => 'password',
                    'meter' => TRUE,
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'length' => [
                            'min' => 6
                        ]
                    ]
                ],
                'retype-password' => [
                    'label' => 'Retype Password',
                    'type' => 'password',
                    'rules' => []
                ]
            ],
            'admin.user.profile' => [
                'avatar' => [
                    'label' => 'Avatar',
                    'type' => 'image',
                    'form' => 'std-image',
                    'position' => 'top-left',
                    'rules' => [
                        'upload' => TRUE
                    ]
                ],
                'fullname' => [
                    'label' => 'Fullname',
                    'type' => 'text',
                    'position' => 'top-left',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE
                    ]
                ]
            ]
        ]
    ],
    'admin' => [
        'objectFilter' => [
            'handlers' => [
                'user' => 'AdminUser\\Library\\Filter'
            ]
        ]
    ],
    'adminUser' => [
        'menu' => [
            'profile' => [
                'label' => 'Profile',
                'perm' => 'manage_user',
                'route' => ['adminUserEditProfile', ['id'=>'$id']],
                'index' => 1000
            ],
            'account' => [
                'label' => 'Account',
                'perm' => 'manage_user_account',
                'route' => ['adminUserEditAccount', ['id'=>'$id']],
                'index' => 2000
            ],
            'password' => [
                'label' => 'Password',
                'perm' => 'manage_user_password',
                'route' => ['adminUserEditPassword', ['id'=>'$id']],
                'index' => 3000
            ]
        ]
    ]
];
