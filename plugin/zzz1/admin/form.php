<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * MaxSite CMS
 * (c) http://max-3000.com/
 */
	
	mso_cur_dir_lang('admin');
	
	# Форма - работает совместно с edit и new
	
	
	# загрузки
	/*
	
	загрузки пока убираю. фигня получилась.
	
	ob_start();	
	require($MSO->config['admin_plugins_dir'] . 'admin_page/files.php');
	$page_files = ob_get_contents();
	ob_end_clean();
		
	$page_admin_files = '<p>' . t('Скопируйте код в редактор.', 'admin') . ' (<a href="'. $MSO->config['site_admin_url'] . 'files" target="_blank">' . t('Страница «Загрузки»', 'admin') . '</a>)</p>';
	*/
	$page_files = '';
	$page_admin_files = '';
	
	# до 
	$do = '
	<table class="new_or_edit">
	<tr>
		<td class="editor_and_meta">';
	
	
	# после
	$posle = '';

			

?>