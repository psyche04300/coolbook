<?php
class stylerAction extends frontendAction {

    /**
     *最受欢迎的Styler
     *
     */
    public function index() {
        // 今日20个
        $today=mktime(0, 0, 0, date("m"), date("d"), date("y"));
        $this->assign('day_styler',$this->getInfo($today));
        $this->assign('nav_curr', 'day_styler');
        $this->_config_seo();
        $this->display();
    }
    public function week() {
        // 本周20个
        $week=mktime(0, 0, 0, date("m"), date("d")-7, date("y"));
        $this->assign('week_styler',$this->getInfo($week));
        $this->assign('nav_curr', 'week_styler');
        $this->_config_seo();
        $this->display();
    }
    public function month() {
        // 本月20个
        $month=mktime(0, 0, 0, date("m"), 0, date("y"));
        $this->assign('month_styler',$this->getInfo($month));
        $this->assign('nav_curr', 'month_styler');
        $this->_config_seo();
        $this->display();
    }

    private function getInfo($time){
        $mod=M('');
        $stylers=$mod->query("select * from {$this->prefix}item_like a left join {$this->prefix}item b on a.item_id=b.id where a.add_time>=$time group by a.uid order by a.add_time desc limit 20");
        //取4条
        foreach($stylers as &$styler){
            $styler['user_info']=$mod->query("select * from {$this->prefix}user where id={$styler['uid']} limit 1");
            $styler['style_item']=$mod->query("select * from {$this->prefix}item where uid={$styler['uid']} order by add_time desc limit 4");
        }
        return $styler;
    }
}