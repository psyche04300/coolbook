<?php
/**
 * 404错误
 */
class EmptyAction extends Action
{
    public function _empty()
    {
        send_http_status(404);
        $id = $this->_get('_URL_');
        $id = intval($id[1]);
        !$id && $this->display(TMPL_PATH . '404.html');
        $info = M('article')->find($id);
        !$info && $this->display(TMPL_PATH . '404.html');
        $this->display();
    }
}