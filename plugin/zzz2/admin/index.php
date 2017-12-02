<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

# проверка прав на редактирование товара
if ( !mso_check_allow('grshop_edit') ) 
	{
	 echo t('Доступ запрещен', 'plugins/grshop');
	 return;
	}

# инициируем библиотеки
$CI = & get_instance();	 // получаем доступ к CodeIgniter
$CI->load->helper('form');	// подгружаем хелпер форм
require_once ($MSO->config['plugins_dir'].'grshop/common/admcom.php');	// подгружаем библиотеку для админки
require_once ($MSO->config['plugins_dir'].'grshop/common/common.php');	// подгружаем библиотеку
require_once ($MSO->config['plugins_dir'].'grshop/config.php');	// подгружаем переменные

# если редактирование товара
if ($post = mso_check_post(array('f_session_id', 'toadd')) or mso_segment(4)=='edit' or $post = mso_check_post(array('f_session_id', 'delladd')))
	{
	mso_checkreferer();
	require_once($MSO->config['plugins_dir'] . 'grshop/plugin/zzz2/admin/edit.php');
	return;
	}
	
# если новый товар
if (mso_segment(4)=='new')
	{
	mso_checkreferer();	
	require_once($MSO->config['plugins_dir'] . 'grshop/plugin/zzz2/admin/new.php');
	return;
	}
	
# если удаляем выбранные товары
if ($post = mso_check_post(array('f_session_id', 'del')))
	{
	mso_checkreferer();
	$postdell = $post['postdell'];	
	require_once($MSO->config['plugins_dir'] . 'grshop/plugin/zzz2/admin/del.php');
	dell_select($postdell);
	//return;
	}
	
# если удаляем все товары текущей категории
if ($post = mso_check_post(array('f_session_id', 'delall')))
	{
	mso_checkreferer();
	$id_cat = '';
	if (isset($post['id_cat'])) $id_cat = $post['id_cat'];
	require_once($MSO->config['plugins_dir'] . 'grshop/plugin/zzz2/admin/del.php');
	dell_all($id_cat);
	};
	
# отображаем список товаров
require_once($MSO->config['plugins_dir'] . 'grshop/plugin/zzz2/admin/list.php');
	
# скрываем блоки
# опции задаются в application/maxsit/admin/plugins/admin_options/editor.php
//function admin_page_hide_blocks($arg = array())
//{
//	$options = mso_get_option('editor_options', 'admin', array());
//	
//	$css = '';
//	if ( isset($options['page_status']) and !$options['page_status']) $css .= 'p.page_status {display: none !important;}' .NR ;
//	if ( isset($options['page_files']) and !$options['page_files']) $css .= 'a.page_files {display: none !important;}' .NR ;
//	
//	if ( isset($options['page_meta']) and !$options['page_meta']) $css .= 'div.page_meta {display: none !important;}' .NR ;
//	if ( isset($options['page_all_cat']) and !$options['page_all_cat']) $css .= 'div.page_all_cat {display: none !important;}' .NR ;
//	if ( isset($options['page_tags']) and !$options['page_tags']) $css .= 'div.page_tags {display: none !important;}' .NR ;
//	if ( isset($options['page_slug']) and !$options['page_slug']) $css .= 'div.page_slug {display: none !important;}' .NR ;
//	if ( isset($options['page_discus']) and !$options['page_discus']) $css .= 'div.page_discus {display: none !important;}' .NR ;
//	if ( isset($options['page_date']) and !$options['page_date']) $css .= 'div.page_date {display: none !important;}' .NR ;
//	if ( isset($options['page_post_type']) and !$options['page_post_type']) $css .= 'div.page_post_type {display: none !important;}' .NR ;
//	if ( isset($options['page_password']) and !$options['page_password']) $css .= 'div.page_password {display: none !important;}' .NR ;
//	if ( isset($options['page_menu_order']) and !$options['page_menu_order']) $css .= 'div.page_menu_order {display: none !important;}' .NR ;
//	if ( isset($options['page_all_parent']) and !$options['page_all_parent']) $css .= 'div.page_all_parent {display: none !important;}' .NR ;
//	if ( isset($options['page_all_users']) and !$options['page_all_users']) $css .= 'div.page_all_users {display: none !important;}' .NR ;
//	
//	
//	if ($css)
//	{
//		echo NR . '<style>' . NR . $css . '</style>' . NR;
//	}
//}

# end file
?>