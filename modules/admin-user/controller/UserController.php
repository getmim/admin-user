<?php
/**
 * UserController
 * @package admin-user
 * @version 0.1.2
 */

namespace AdminUser\Controller;

use LibFormatter\Library\Formatter;
use LibForm\Library\Form;
use LibForm\Library\Combiner;
use LibPagination\Library\Paginator;
use LibUserMain\Model\User;

class UserController extends \Admin\Controller
{
    private function getParams(string $title): array{
        return [
            '_meta' => [
                'title' => $title,
                'menus' => ['user', 'all-user']
            ],
            'subtitle' => $title,
            'pages' => null
        ];
    }

    public function createAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_user)
            return $this->show404();

        $user = (object)[];

        $params = $this->getParams('Create New User');

        $form           = new Form('admin.user.create');
        $params['form'] = $form;

        $params['fields'] = [];
        $fields = $form->getFields();
        foreach($fields as $fname => $field){
            if(!isset($params['fields'][$field->position]))
                $params['fields'][$field->position] = [];
            $params['fields'][$field->position][] = $fname;
        }
        
        if(!($valid = $form->validate($user)) || !$form->csrfTest('noob'))
            return $this->resp('user/create', $params);

        $valid->password = $this->user->hashPassword($valid->password);

        if(!($id = User::create((array)$valid)))
            deb(User::lastError());

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 1,
            'type'   => 'user',
            'original' => $user,
            'changes'  => $valid
        ]);

        $next = $this->router->to('adminUser');
        $this->res->redirect($next);
    }

    public function indexAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_user)
            return $this->show404();

        $cond = $pcond = [];
        if($q = $this->req->getQuery('q'))
            $pcond['q'] = $cond['q'] = $q;

        list($page, $rpp) = $this->req->getPager(25, 50);

        $cond['status'] = ['__op', '>', 0];
        $users = User::get($cond, $rpp, $page, ['fullname'=>true]) ?? [];
        if($users){
            $fmt = [];
            if(module_exists('lib-user-perm'))
                $fmt[] = 'role';
            $users = Formatter::formatMany('user', $users, $fmt);
        }

        $params          = $this->getParams('Users');
        $params['users'] = $users;
        $params['form']  = new Form('admin.user.index');

        $params['form']->validate( (object)$this->req->get() );

        // pagination
        $params['total'] = $total = User::count($cond);
        if($total > $rpp){
            $params['pages'] = new Paginator(
                $this->router->to('adminUser'),
                $total,
                $page,
                $rpp,
                10,
                $pcond
            );
        }

        $this->resp('user/index', $params);
    }

    public function removeAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_user)
            return $this->show404();

        $id    = $this->req->param->id;
        $user  = User::getOne(['id'=>$id]);
        $next  = $this->router->to('adminUser');
        $form  = new Form('admin.user.index');

        if(!$form->csrfTest('noob'))
            return $this->res->redirect($next);

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 3,
            'type'   => 'user',
            'original' => $user,
            'changes'  => null
        ]);

        $u_set = [
            'name' => time() . '#' . $user->name,
            'status' => 0
        ];
        if (module_exists('lib-user-main-email'))
            $u_set['email'] = time() . '#' . $user->email;
        if (module_exists('lib-user-main-phone'))
            $u_set['phone'] = time() . '#' . $user->phone;

        User::set($u_set, ['id'=>$id]);

        $this->res->redirect($next);
    }
}
