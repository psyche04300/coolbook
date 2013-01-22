<?php
class indexAction extends frontendAction {

    /**
     * 首页，热门搭配
     *
     */
    public function index() {
        // 根据人气（喜欢）及新鲜度来排行的热门搭配
        $mod=M('');
        $hot_style=$mod->query("select count(a.item_id) as count,a.*,b.* from {$this->prefix}item_like a left join {$this->prefix}item b on a.item_id=b.id group by a.uid order by count desc,a.add_time desc limit 10");
        $this->assign('hot_style',$hot_style);
      /*  foreach($hot_style as $style){
            //喜欢数

        }*/

        //每日Styler
        $today=mktime(0, 0, 0, date("m"), date("d"), date("y"));
        $day_styler=$mod->query("select * from {$this->prefix}item_like a left join {$this->prefix}item b on a.item_id=b.id where a.add_time>=$today order by a.add_time desc limit 3");
        $this->assign('day_styler',$day_styler);
        //本周Styler
        $week=mktime(0, 0, 0, date("m"), date("d")-7, date("y"));
        $week_styler=$mod->query("select * from {$this->prefix}item_like a left join {$this->prefix}item b on a.item_id=b.id where a.add_time>=$week order by a.add_time desc limit 3");
        $this->assign('week_styler',$week_styler);
        //最新评论
        $laster_comments=$mod->query("select * from {$this->prefix}item_comment order by add_time desc limit 8");
        $this->assign('laster_comments',$laster_comments);
        //最新发布
        $newpub_style=$mod->query("select * from {$this->prefix}item order by add_time desc limit 6");
        $this->assign('newpub_style',$newpub_style);
        //服饰类型
        $item_cate=$mod->query("select * from {$this->prefix}item_cate where pid=0 and is_index=1");
        foreach($item_cate as &$v){
            $v['sub']=$mod->query("select * from {$this->prefix}item_cate where pid={$v['id']} and is_index=1");
        }
        $this->assign('item_cate',$item_cate);
        //品牌
        $hot_brand=$mod->query("select b.*,count(a.id) as count from {$this->prefix}item_style a inner JOIN {$this->prefix}brand b on a.brand_id=b.id group by a.brand_id order by count desc");
        $this->assign('hot_brand',$hot_brand);
        //合作媒体

die;






      /*  //分类
        if (false === $index_cate_list = F('index_cate_list')) {
            $item_cate_mod = M('item_cate');
            //分类关系
            if (false === $cate_relate = F('cate_relate')) {
                $cate_relate = D('item_cate')->relate_cache();
            }
            //分类缓存
            if (false === $cate_data = F('cate_data')) {
                $cate_data = D('item_cate')->cate_data_cache();
            }
            //推荐到首页的大类
            $index_cate_list = $item_cate_mod->field('id,name,img')->where(array('pid'=>'0' ,'is_index'=>'1', 'status'=>'1'))->order('ordid')->select();
            foreach ($index_cate_list as $key=>$val) {
                //推荐到首页的子类
                $where = array('status'=>'1', 'is_index'=>'1', 'spid'=>array('like', $val['id'] . '|%'));
                $index_cate_list[$key]['index_sub'] = $item_cate_mod->field('id,name,img')->where($where)->order('ordid')->select();
                //普通子类
                $index_cate_list[$key]['sub'] = array();
                foreach ($cate_relate[$val['id']]['sids'] as $sid) {
                    if ($cate_data[$sid]['type'] == '0' && $cate_data[$sid]['pid'] != $val['id']) {
                        $index_cate_list[$key]['sub'][] = $cate_data[$sid];
                    }
                    if (count($index_cate_list[$key]['sub']) >= 6) {
                        break;
                    }
                }
            }
            F('index_cate_list', $index_cate_list);
        }

        //发现
        $hot_tags = explode(',', C('pin_hot_tags')); //热门标签
        $hot_tags = array_slice($hot_tags, 0, 12);
        $this->waterfall('', 'hits DESC,id DESC', '', C('pin_book_page_max'), 'book/index');

        $this->assign('index_cate_list', $index_cate_list);
        $this->assign('hot_tags', $hot_tags);*/
        $this->assign('nav_curr', 'index');
        $this->_config_seo();
        $this->display();
    }
}