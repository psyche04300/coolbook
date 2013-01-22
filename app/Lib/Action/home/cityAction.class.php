<?php
class cityAction extends frontendAction {

    /**
     *    城市最新发布的搭配
     *
     */
    public function index() {
        // 最新发布的搭配,每个城市取几条，每人取一条
        $mod=M('');
        $citys=$mod->query("select * from {$this->prefix}user where city !='' group by city");
        $city_style=array();
        foreach($citys as $city){
            $city_style[$city['city']]=$mod->query("select * from {$this->prefix}item where uid={$city['id']} group by uid limit 5");
        }
        $this->assign('city_style',$city_style);
        $this->assign('nav_curr', 'city');
        $this->_config_seo();
        $this->display();
    }
}