<?php
class newAction extends frontendAction {

    /**
     *    最新发布的搭配
     *
     */
    public function index() {
        // 根据人气（喜欢）及新鲜度来排行的热门搭配
        $mod=M('');
        $new_style=$mod->query("select * from {$this->prefix}item order by add_time desc limit 10");
        $this->assign('new_style',$new_style);
        $this->assign('nav_curr', 'new');
        $this->_config_seo();
        $this->display();
    }
}