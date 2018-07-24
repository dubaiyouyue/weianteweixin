<?php
return array (
		'need_nickname' => array (
				'title' => '是否需要填写昵称:',
				'type' => 'radio',
				'options' => array (
						'1' => '是',
						'0' => '否' 
				),
				'value' => '0' 
		),
		'need_mobile' => array (
				'title' => '是否需要填写手机号:',
				'type' => 'radio',
				'options' => array (
						'1' => '是',
						'0' => '否' 
				),
				'value' => '0' 
		),
		'need_email' => array (
				'title' => '设置接收邮箱:',
				'type' => 'hidden',
				'value' => '2500152288@qq.com' 
		) ,			
		'need_bxnrqq' => array (
				'title' => '报修内容:',
				'type' => 'textarea',
				'value' => '',
				'tip' => '一行一个报修内容，请勿有空格' 
		),			
		'need_ssbbjjll' => array (
				'title' => '商务经理:',
				'type' => 'textarea',
				'value' => '',
				'tip' => '一行一个商务经理，用#分割邮箱，请勿有空格' 
		)
);
					