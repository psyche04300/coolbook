<?php
class topAction extends frontendAction {

    /**
     *    一周内喜欢数最多的人气搭配
     *
     */
    public function index() {

        $mod=M('');
        $info = $this->visitor->get();
        $uid=$info['id'];
        //一周内喜欢数最多的人气搭配
        $month=mktime(0, 0, 0, date("m"), date("d"), date("y"));
        $like_month_style=$mod->query("select count(b.id) as count,b.* from {$this->prefix}item_like a left join {$this->prefix}item b on a.item_id=b.id where a.add_time>$month order by count desc");
        $this->assign('like_month_style',$like_month_style);
        $this->assign('nav_curr', 'top');
        // 周人气排行存档
        $save=array();
        $start_day = strtotime('this week');
        for($i=0;$i<10;$i++){
            $start_day =strtotime("-$i  week",$start_day);
            $end_day=$start_day+6*60*60*24;
            $count=$mod->query("select count(*) as count from {$this->prefix}item_like a left join {$this->prefix}item b on a.item_id=b.id where a.add_time between {$start_day} and {$end_day} order by count desc");
            if($count){
                $start_time=date('y/m/d',$start_day);
                $end_time=date('y/m/d',$end_day);
                $save[]="<a href='".U('Top/index',array('t'=>$start_time))."'>". date('y/m/d',$start_day).' - '.date('y/m/d',$end_day)."</a>";
            }
        }
        $this->assign('save',$save);
        $this->_config_seo();
        $this->display();
    }
}