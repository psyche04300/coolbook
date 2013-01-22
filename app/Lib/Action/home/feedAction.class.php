<?php
class feedAction extends frontendAction {

    /**
     *    我关注的人发布的搭配
     *
     */
    public function index() {
        $this->visitor->is_login && $this->redirect('user/index');
        $this->is_login = true;
        $mod=M('');
        $info = $this->visitor->get();
        $uid=$info['id'];
        //我关注的人发布的搭配
        $follow_user_item=$mod->query("select * from {$this->prefix}user_follow a left join {$this->prefix}item b on a.follow_uid=b.uid where a.uid=$uid ");
        $this->assign('follow_user_item',$follow_user_item);
        //我关注的人
        $follow_user=$mod->query("select * from {$this->prefix}user_follow a left join {$this->prefix}user b on a.follow_uid=b.id where a.uid=$uid ");
        $this->assign('follow_user',$follow_user);
        $this->assign('nav_curr', 'feed');
        $this->_config_seo();
        $this->display();
    }
}