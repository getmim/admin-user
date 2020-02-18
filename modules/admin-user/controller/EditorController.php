<?php
/**
 * EditorController
 * @package admin-user
 * @version 0.0.1
 */

namespace AdminUser\Controller;


class EditorController extends \Admin\Controller
{
    public function resp(string $view, array $params=[], string $layout='default'){
        $bcrumb = [
            ['Home', $this->router->to('adminHome')],
            ['User', $this->router->to('adminUser')],
            [$params['_meta']['title'], '#']
        ];

        $croute = $this->req->route->name;
        $menus = [];
        $c_menus = $this->config->adminUser->menu;
        foreach($c_menus as $menu){
            if(!$this->can_i->{$menu->perm})
                continue;

            $menu->route  = arrayfy($menu->route);
            $menu->active = $menu->route[0] == $croute;
            $menu->link   = to_route($menu->route, $params['user']);
            $menus[] = $menu;
        }

        usort($menus, function($a,$b){ return $a->index - $b->index; });

        $params['_meta']['subtitle'] = $params['_meta']['title'];
        $params['_meta']['title']    = 'User';
        $params['_meta']['bcrumb']   = $bcrumb;
        $params['_meta']['menus']    = $menus;

        parent::resp($view, $params, 'admin-user');
    }
}