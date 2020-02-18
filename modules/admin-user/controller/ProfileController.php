<?php
/**
 * ProfileController
 * @package admin-user
 * @version 0.0.1
 */

namespace AdminUser\Controller;

use LibFormatter\Library\Formatter;
use LibForm\Library\Form;
use LibForm\Library\Combiner;
use LibPagination\Library\Paginator;
use LibUserMain\Model\User;

class ProfileController extends EditorController
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
        if(!$this->can_i->manage_user)
            return $this->show404();

        $user = (object)[];

        $id = $this->req->param->id;
        $user = User::getOne(['id'=>$id]);
        if(!$user)
            return $this->show404();
        $params = $this->getParams('Edit User Profile');

        $form            = new Form('admin.user.profile');
        $params['form']  = $form;
        $params['saved'] = false;
        $params['user']  = $user;

        $params['fields'] = [];

        $fields = $form->getFields();
        foreach($fields as $fname => $field){
            if(!isset($params['fields'][$field->position]))
                $params['fields'][$field->position] = [];
            $params['fields'][$field->position][] = $fname;
        }

        if(!($valid = $form->validate($user)) || !$form->csrfTest('noob'))
            return $this->resp('user/general', $params);

        if(!User::set((array)$valid, ['id'=>$id]))
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
        $this->resp('user/general', $params);
    }
}