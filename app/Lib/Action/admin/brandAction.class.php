<?php
class brandAction extends backendAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('brand');
    }

    protected function _search() {
        $map = array();
        ($keyword = $this->_request('keyword', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
        return $map;
    }

    public function _before_index() {
        $big_menu = array(
            'title' => "添加品牌",
            'iframe' => U('brand/add'),
            'id' => 'add',
            'width' => '500',
            'height' => '260'
        );
        $this->assign('big_menu', $big_menu);
        $this->list_relation = true;
        $this->_before_add();

        $this->assign('img_dir',$this->_get_imgdir());

        //默认排序
        $this->sort = 'ordid';
        $this->order = 'ASC';
    }

    public function _before_add() {
    }

    public function _before_edit()
    {
        $this->_before_add();
        $this->assign('img_dir',$this->_get_imgdir());
    }

    public function ajax_upload_img() {
        //上传图片
        if (!empty($_FILES['img']['name'])) {
            $result = $this->_upload($_FILES['img'], 'brand');
            if ($result['error']) {
                $this->ajaxReturn(0, $result['info']);
            } else {
                $data['img'] = $result['info'][0]['savename'];
                $this->ajaxReturn(1, L('operation_success'), $data['img']);
            }
        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }

    public function ajax_check_name()
    {
        $name = $this->_get('name', 'trim');
        $id = $this->_get('id', 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            $this->ajaxReturn(0, '品牌名称已经存在');
        } else {
            $this->ajaxReturn();
        }
    }

    /**
     * 品牌图片上传目录
     *
     * @staticvar null $dir
     * @return string
     */
    private function _get_imgdir() {
        static $dir = null;
        if ($dir === null) {
            $dir = './data/upload/brand/';
        }
        return $dir;
    }
}