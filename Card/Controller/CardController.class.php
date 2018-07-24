<?php

namespace Addons\Card\Controller;

use Addons\Card\Controller\BaseController;

class CardController extends BaseController {

    function config() {
        $this->getModel();

        if (IS_POST) {
            if ($_POST ['config'] ['background'] == 11) {
                $_POST ['config'] ['background_custom'] = get_cover_url($_POST ['config'] ['bg']);
            }
            $flag = D('Common/AddonConfig')->set(_ADDONS, I('config'));

            if ($flag !== false) {
                $this->success('保存成功', Cookie('__forward__'));
            } else {
                $this->error('保存失败');
            }
        }

        $map ['name'] = _ADDONS;
        $addon = M('Addons')->where($map)->find();
        if (!$addon)
            $this->error('插件未安装');

        $addon_class = get_addon_class($addon ['name']);

        $data = new $addon_class ();
        $addon ['addon_path'] = $data->addon_path;
        $addon ['custom_config'] = $data->custom_config;
        $db_config = D('Common/AddonConfig')->get(_ADDONS);
        $addon ['config'] = include $data->config_file;
        if ($db_config) {
            foreach ($addon ['config'] as $key => $value) {
                if ($value ['type'] != 'group') {
                    !isset($db_config [$key]) || $addon ['config'] [$key] ['value'] = $db_config [$key];
                } else {
                    foreach ($value ['options'] as $gourp => $options) {
                        foreach ($options ['options'] as $gkey => $value) {
                            !isset($db_config [$key]) || $addon ['config'] [$key] ['options'] [$gourp] ['options'] [$gkey] ['value'] = $db_config [$gkey];
                        }
                    }
                }
            }
        }
        $this->assign('data', $addon);

        $this->display();
    }

    function show() {
        $tpl = 'show';
        $map ['uid'] = $this->mid;
        $info = M('card_member')->where($map)->find();
        // dump($info);
        // dump(M ( 'card_member' )->getLastSql());
        if ($info) {
            $tpl = 'show_card';
            $this->assign('info', $info);
        }
        $this->_footer();
        $this->display($tpl);
    }

    // 使用介绍
    function introduction() {
        $this->_footer();
        $this->display();
    }

    // 适用门店
    function storeList() {
        $this->_footer();
        $this->display();
    }

    // 填写会员卡资料
    function writeCardInfo() {
        $this->_footer();
        $map ['uid'] = $this->mid;
        $info = M('card_member')->where($map)->find();

        if (IS_POST) {
            $data ['username'] = I('post.username');
            $data ['phone'] = I('post.phone');

            if ($info) {
                $res = M('card_member')->where($map)->save($data);
            } else {
                $config = getAddonConfig('Card');
                $map_token ['token'] = get_token();
                $data ['number'] = M('card_member')->where($map_token)->getField("max(number) as number");
                if (empty($data ['number'])) {
                    $data ['number'] = $config ['length'];
                } else {
                    $data ['number'] += 1;
                }

                $data ['uid'] = $map2 ['id'] = $this->mid;
                $data ['cTime'] = time();
                $data ['token'] = get_token();

                $res = M('card_member')->add($data);

                M('follow')->where($map2)->setField('status', 3);

                // 增加积分
                add_credit('card_bind');
            }
            redirect(addons_url('Card://Card/showCard'));
        }

        $this->assign('info', $info);
        $this->display('write_cardinfo');
    }

    // 查看、修改会员卡资料
    function editCardInfo() {
        $this->_footer();
        $map ['uid'] = $this->mid;
        $info = M('card_member')->where($map)->find();

        if (IS_POST) {
            $data ['username'] = I('post.username');
            $data ['phone'] = I('post.phone');

            if ($info) {
                $res = M('card_member')->where($map)->save($data);
                $this->success('更新成功', addons_url('Card://Card/showCard'));
            } else {
                $config = getAddonConfig('Card');
                $map_token ['token'] = get_token();
                $data ['number'] = M('card_member')->where($map_token)->getField("max(number) as number");
                if (empty($data ['number'])) {
                    $data ['number'] = $config ['length'];
                } else {
                    $data ['number'] += 1;
                }

                $data ['uid'] = $map2 ['id'] = $this->mid;
                $data ['cTime'] = time();
                $data ['token'] = get_token();

                $res = M('card_member')->add($data);

                M('follow')->where($map2)->setField('status', 3);

                // 增加积分
                add_credit('card_bind');
            }
            redirect(addons_url('Card://Card/showCard'));
        }

        $this->assign('info', $info);
        $this->display('edit_cardinfo');
    }

    function index() {
        $this->_footer();
        $this->display();
    }

    // 绑定实体会员卡
    function bindCard() {
        $this->_footer();
        $this->display('bind_card');
    }

    // 积分
    function score() {
        $this->_footer();
        $this->display();
    }

    // 兑换
    function exchange() {
        $this->_footer();
        $this->display();
    }

    // 优惠券
    function ticket() {
        $this->_footer();
        $this->display();
    }

    // 领取后
    function showCard() {
    	
if (IS_POST) {
	session_start();
	$_SESSION['paymoney'] = I("post.money");
	$this->redirect("Addons://Addons/addon/Card/Expense/pay");
} else {
	        $this->_footer();
        $map ['uid'] = $this->mid;
        $info = M('card_member')->where($map)->find();
        $this->assign('info', $info);

        $this->display('show_card');
}
		

    }

    // 显示消费启示
    /* function showexpense() {
      $this->_footer();
      $map ['uid'] = $this->mid;
      $info = M('card_expense')->where($map)->find();
      $this->assign('info', $info);

      $this->display('show_expense');
      } */

    // 3G页面底部导航
    function _footer() {
        $list = D('Addons://Card/Card')->get_list();
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
