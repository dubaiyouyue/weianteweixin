<?php

namespace Addons\Message\Controller;

use Home\Controller\AddonsController;

class MessageController extends AddonsController {

    function lists() {
        // 获取模型信息
        $model = $this->getModel();

        $map ['token'] = get_token();
        session('common_condition', $map);

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);

        // 获取相关的用户信息
        $uids = getSubByKey($list_data ['list_data'], 'uid');
        $uids = array_filter($uids);
        $uids = array_unique($uids);
        if (!empty($uids)) {
            $map ['id'] = array(
                'in',
                $uids
            );
            $members = M('follow')->where($map)->field('id,nickname,mobile')->select();
            foreach ($members as $m) {
                $user [$m ['id']] = $m;
            }

            foreach ($list_data ['list_data'] as &$vo) {
                empty($vo ['mobile']) || $vo ['mobile'] = $user [$vo ['uid']] ['mobile'];
                empty($vo ['nickname']) || $vo ['nickname'] = $user [$vo ['uid']] ['nickname'];
            }
        }

        $this->assign($list_data);
        $this->assign('add_url', U('message'));

        $this->display($model ['template_list']);
    }

    function message() {
        $this->_footer();        // 获取模型信息
        $model = M('message');

        $map ['token'] = get_token();
        $map ['status'] = '1';

        // 获取模型列表数据
        $list_data = $model->field('*')->where($map)->order('id DESC')->select();
        //dump($list_data);
        $this->assign('list_data',$list_data);
        $this->display();
    }

    function writeMessage() {
        $this->_footer();
        $config = getAddonConfig('Message');
        $this->assign($config);
        // dump ( $config );

        $map ['id'] = $data ['uid'] = $this->mid;
        $user = get_followinfo($this->mid);
        $this->assign('user', $user);

        if (IS_POST) {
            // 保存用户信息
            $nickname = I('nickname');
            if ($config ['need_nickname'] && !empty($nickname)) {
                $data ['nickname'] = $nickname;
            }
            $mobile = I('mobile');
            if ($config ['need_mobile'] && !empty($mobile)) {
                $data ['mobile'] = $mobile;
            }

            // 保存内容
            $data ['cTime'] = time();
            $data ['content'] = I('content');
            $data ['token'] = 'gh_c7cad31bb0c5';//get_token();
			
			 $res = M('Message')->add($data);
			
            if ($res) {
                // 增加积分
                add_credit('Message');
				
                $this->success('留言成功，谢谢您的使用',  addons_url('Message://Message/message'));
            } else
                $this->error('留言失败，请稍后再试');
        } else {
            $this->display('write_message');
        }
    }

    // 3G页面底部导航
    function _footer() {
        $list = D('Addons://Message/Message')->get_list();
        //dump($list);
        // 取一级菜单
        foreach ($list as $k => $vo) {
            if ($vo ['pid'] != 0)
                continue;

            $one_arr [$vo ['id']] = $vo;
            unset($list [$k]);
        }

        foreach ($one_arr as &$p) {
            $two_arr = array();
            foreach ($list as $key => $l) {
                if ($l ['pid'] != $p ['id'])
                    continue;

                $two_arr [] = $l;
                unset($list [$key]);
            }

            $p ['child'] = $two_arr;
        }

        $this->assign('footer', $one_arr);
        $html = $this->fetch(ONETHINK_ADDON_PATH . 'WeiSite/View/default/TemplateFooter/weiante/footer.html');

        $this->assign('footer_html', $html);
    }

}
