<?php

namespace Addons\Message\Model;
use Think\Model;

/**
 * Message模型
 */
class MessageModel extends Model{
    protected $tableName = 'weisite_footer';
    function get_list($map) {
        $map ['token'] = get_token();
        $list = $this->where($map)->order('pid asc, sort asc')->select();

        foreach ($list as &$vo) {
            $vo ['icon'] = get_cover_url($vo ['icon']);
            if ($vo ['icon']) {
                $vo ['icon'] = '<img src="' . $vo ['icon'] . '" >';
            } else {
                $vo ['icon'] = '';
            }
        }

        return $list;
    }

}
