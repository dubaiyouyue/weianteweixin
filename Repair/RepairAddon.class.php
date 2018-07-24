<?php

namespace Addons\Repair;
use Common\Controller\Addon;

/**
 * 在线报修插件
 * @author 李振球
 */

    class RepairAddon extends Addon{

        public $info = array(
            'name'=>'Repair',
            'title'=>'在线报修',
            'description'=>'在线报修提交表单。',
            'status'=>1,
            'author'=>'李振球',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/Repair/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Repair/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }