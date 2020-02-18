<?php
/**
 * Filter
 * @package admin-user
 * @version 0.0.1
 */

namespace AdminUser\Library;

use LibUser\Library\Fetcher;

class Filter implements \Admin\Iface\ObjectFilter
{
    static function filter(array $cond): ?array{
        $cnd = [];
        if(isset($cond['q']) && $cond['q'])
            $cnd['q'] = (string)$cond['q'];
        $users = Fetcher::get($cnd, 15, 1, ['fullname'=>true]);
        if(!$users)
            return [];

        $result = [];
        
        $u_info = 'fullname';
        if(isset($users[0]->phone))
            $u_info = 'phone';
        elseif(isset($users[0]->email))
            $u_info = 'email';

        foreach($users as $user){
            $result[] = [
                'id'    => (int)$user->id,
                'label' => $user->fullname,
                'info'  => $user->{$u_info},
                'icon'  => NULL
            ];
        }

        return $result;
    }

    static function lastError(): ?string{
        return null;
    }
}