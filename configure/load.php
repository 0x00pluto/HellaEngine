<?php
//use Symfony\Component\Yaml\Yaml;
///**
// * 常亮配置
// *
// * @author lijiang
// */
//// 加载常量定义
//include_once dirname ( __FILE__ ) . "/configure_constants.php";
//// 加载常量处理函数
////include_once dirname ( __FILE__ ) . "/configure_functions.php";
//
//// 加载通用配置
//include_once dirname ( __FILE__ ) . "/configure_default.php";
//
//// 加载全局站点配置
//$site_config = dirname ( __FILE__ ) . '/configure_' . $_SERVER ['HTTP_HOST'] . '.php';
//load_config ( $site_config );
//
//// 设置应用路径
//$apppath = dirname ( dirname ( __FILE__ ) ) . DIRECTORY_SEPARATOR . strtr ( C ( configure_constants::APP_NAMESPACE ), '\\', DIRECTORY_SEPARATOR );
//C ( configure_constants::APP_PATH, $apppath );
//
//// 加载应用配置
//$app_configure = C ( configure_constants::APP_PATH ) . '/configure/configure_app.php';
//load_config ( $app_configure );
//
//// 加载应用站点配置
//$app_site_config = C ( configure_constants::APP_PATH ) . '/configure/configure_app_' . $_SERVER ['HTTP_HOST'] . '.php';
//load_config ( $app_site_config );
//
//// 加载扩展配置
//$app_ext_configure = C ( configure_constants::APP_PATH ) . '/configure/configure_app.yaml';
//$filecontent = file_get_contents ( $app_ext_configure );
//$config_array = Yaml::parse ( $filecontent );
//if (isset ( $config_array ['config_exts'] )) {
//	foreach ( $config_array ['config_exts'] as $configpath ) {
//		$ext_config_path = C ( configure_constants::APP_PATH ) . '/' . $configpath;
//		load_config ( $ext_config_path );
//	}
//}
return [];