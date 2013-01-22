<?php
class postAction extends frontendAction {

    public function _initialize() {
        parent::_initialize();
        $this->assign('nav_curr', 'post');
    }

    /**
     * 欢迎
     */
    public function post1() {
        $this->display();
    }
    public function post2() {
        $this->display();
    }
    public function post3() {
        $this->display();
    }
    //上传图片
    public function ajax_upload_img() {
        $type = $this->_get('type', 'trim', 'img');
        if (!empty($_FILES[$type]['name'])) {
            $dir = date('ym/d/');
            $result = $this->_upload($_FILES[$type], 'style/'. $dir );
            if ($result['error']) {
                $this->ajaxReturn(0, $result['info']);
            } else {
                $savename = $dir . $result['info'][0]['savename'];
                $this->ajaxReturn(1, L('operation_success'), $savename);
            }
        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }
}