<?php
/**
 * PasswordController
 * @package admin-user
 * @version 0.0.1
 */

namespace AdminUser\Controller;

use LibFormatter\Library\Formatter;
use LibForm\Library\Form;
use LibForm\Library\Combiner;
use LibPagination\Library\Paginator;
use LibUserMain\Model\User;

class PasswordController extends EditorController
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

    public function editAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_user_password)
            return $this->show404();

        $user = (object)[];

        $id = $this->req->param->id;
        $user = User::getOne(['id'=>$id]);
        if(!$user)
            return $this->show404();
        $params = $this->getParams('Edit User Password');

        $form            = new Form('admin.user.password');
        $params['form']  = $form;
        $params['saved'] = false;
        $params['user']  = $user;

        if(!($valid = $form->validate($user)) || !$form->csrfTest('noob'))
            return $this->resp('user/password', $params);

        if($valid->{'password'} != $valid->{'retype-password'}){
            $form->addError('retype-password', '0.0', 'The password is different');
            return $this->resp('user/password', $params);
        }

        $nval = [
            'password' => $this->user->hashPassword( $valid->password )
        ];

        if(!User::set($nval, ['id'=>$id]))
            deb(User::lastError());

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 2,
            'type'   => 'user',
            'original' => $user,
            'changes'  => $valid
        ]);

        $params['saved'] = true;
        $this->resp('user/password', $params);
    }
}