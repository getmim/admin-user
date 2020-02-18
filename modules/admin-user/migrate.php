<?php 

return [
    'LibUserPerm\\Model\\UserPerm' => [
        'data' => [
            'name' => [
                'manage_user'           => ['group'=>'User','about'=>'Allow user to manage users'],
                'manage_user_account'   => ['group'=>'User','about'=>'Allow user to manage exists user account'],
                'manage_user_password'  => ['group'=>'User','about'=>'Allow user to update user\'s password']
            ]
        ]
    ]
];