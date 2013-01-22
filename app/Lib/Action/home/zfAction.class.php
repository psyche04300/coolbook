<?php
class zfAction extends frontendAction {

    /**
     *Style专访
     *
     */
    public function index() {
        $id = $this->_get('id', 'intval');
        !$id && $this->redirect('index/index');
        $info = M('article')->find($id);
        $this->assign('info', $info);
        $this->assign('id', $id);
        //第一页评论不使用AJAX利于SEO
        $zf_comment_mod = M('zf_comment');
        $pagesize = 8;
        $map = array('article_id' => $id);
        $count = $zf_comment_mod->where($map)->count('id');
        $pager = $this->_pager($count, $pagesize);
        $pager->path = 'comment_list';
        $pager_bar = $pager->fshow();
        $cmt_list = $zf_comment_mod->where($map)->order('id DESC')->limit($pager->firstRow . ',' . $pager->listRows)->select();
        foreach($cmt_list as &$v){
            $v['replay']=$zf_comment_mod->where(array('comment_id'=>$v['id']))->order('id DESC')->limit($pager->firstRow . ',' . $pager->listRows)->select();
        }
        $this->assign('cmt_list', $cmt_list);
        $this->_config_seo();
        $this->display();
    }
    /**
     * 评论
     */
    public function comment()
    {
        foreach ($_POST as $key => $val) {
            $_POST[$key] = Input::deleteHtmlTags($val);
        }
        $data = array();
        $data['article_id'] = $this->_post('id', 'intval');
        !$data['article_id'] && $this->ajaxReturn(0,"该专访不存在");
        $data['info'] = $this->_post('content', 'trim');
        !$data['info'] && $this->ajaxReturn(0, L('please_input') . L('comment_content'));
        //敏感词处理
        $check_result = D('badword')->check($data['info']);
        switch ($check_result['code']) {
            case 1: //禁用。直接返回
                $this->ajaxReturn(0, L('has_badword'));
                break;
            case 3: //需要审核
                $data['status'] = 0;
                break;
        }
        $data['info'] = $check_result['content'];
        $data['uid'] = $this->visitor->info['id'];
        $data['uname'] = $this->visitor->info['username'];
        $data['comment_id'] = $this->_post('comment_id', 'trim');

        //验证商品
        $article_mod = M('article');
        $article = $article_mod->field('id,uid,uname')->where(array('id' => $data['article_id'], 'status' => '1'))->find();
        !$article && $this->ajaxReturn(0, "该专访不存在");
        //写入评论
        $article_comment_mod = D('item_comment');
        if (false === $article_comment_mod->create($data)) {
            $this->ajaxReturn(0, $article_comment_mod->getError());
        }
        $comment_id = $article_comment_mod->add();
        if ($comment_id) {
            $this->assign('cmt_list', array(
                array(
                    'uid' => $data['uid'],
                    'uname' => $data['uname'],
                    'info' => $data['info'],
                    'add_time' => time(),
                )
            ));
            $resp = $this->fetch('comment_list');

            $this->ajaxReturn(1, L('comment_success'), $resp);
        } else {
            $this->ajaxReturn(0, L('comment_failed'));
        }
    }
}