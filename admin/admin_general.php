<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
	mso_cur_dir_lang('admin');

if ( !mso_check_allow('grshop_edit') ) 
	{
	 echo t('Доступ запрещен', 'plugins/grshop');
	 return;
	}


global $MSO;
require_once ($MSO->config['plugins_dir'].'grshop/config.php');	// подгружаем переменные
$CI = & get_instance();	
$CI->load->helper('form');	// подгружаем хелпер форм	

$out = '';
$email_notice = '';


if ($post = mso_check_post(array('f_session_id', 'ch_opt')))
	{
	mso_checkreferer();

	//--- сохранение измененных опций---------------------------
	$newoptions['main_slug'] = 'catalog'; if (isset($post['main_slug']))	$newoptions['main_slug'] = $post['main_slug'];
	$newoptions['main_title'] = 'GrShop'; if (isset($post['main_title']))	$newoptions['main_title'] = $post['main_title'];
	$newoptions['money'] = 'руб.'; if (isset($post['money']))	$newoptions['money'] = $post['money'];
	$newoptions['mode'] = 'shop'; if (isset($post['mode'])) $newoptions['mode'] = $post['mode'];
	$newoptions['id_fp_cat'] = '1'; if (isset($post['id_fp_cat'])) $newoptions['id_fp_cat'] = $post['id_fp_cat'];
	$newoptions['email'] = mso_get_option('admin_email', 'general'); if (isset($post['email'])) $newoptions['email'] = $post['email'];
	$newoptions['email_notice'] = $email_notice; if (isset($post['email_notice'])) $newoptions['email_notice'] = $post['email_notice'];

	$newoptions['tip_out_prod'] = 'table'; if (isset($post['tip_out_prod']))	$newoptions['tip_out_prod'] = $post['tip_out_prod'];
	$newoptions['pag_limit_prod_list'] = '20'; if (isset($post['pag_limit_prod_list']))	$newoptions['pag_limit_prod_list'] = $post['pag_limit_prod_list'];
	$newoptions['pag_limit_prod_table'] = '25'; if (isset($post['pag_limit_prod_table']))	$newoptions['pag_limit_prod_table'] = $post['pag_limit_prod_table'];

	$newoptions['echo_photo_prod_list'] = '0'; if (isset($post['echo_photo_prod_list'])) $newoptions['echo_photo_prod_list'] = $post['echo_photo_prod_list'];
	$newoptions['echo_articul_prod_list'] = '0'; if (isset($post['echo_articul_prod_list'])) $newoptions['echo_articul_prod_list'] = $post['echo_articul_prod_list'];
	$newoptions['echo_name_prod_list'] = '0'; if (isset($post['echo_name_prod_list'])) $newoptions['echo_name_prod_list'] = $post['echo_name_prod_list'];
	$newoptions['echo_cost_prod_list'] = '0'; if (isset($post['echo_cost_prod_list'])) $newoptions['echo_cost_prod_list'] = $post['echo_cost_prod_list'];
	$newoptions['echo_descr_prod_list'] = '0'; if (isset($post['echo_descr_prod_list'])) $newoptions['echo_descr_prod_list'] = $post['echo_descr_prod_list'];
	$newoptions['echo_id_sklad_prod_list'] = '0'; if (isset($post['echo_id_sklad_prod_list'])) $newoptions['echo_id_sklad_prod_list'] = $post['echo_id_sklad_prod_list'];

	$newoptions['echo_articul_prod_table'] = '0'; if (isset($post['echo_articul_prod_table'])) $newoptions['echo_articul_prod_table'] = $post['echo_articul_prod_table'];
	$newoptions['echo_name_prod_table'] = '0'; if (isset($post['echo_name_prod_table'])) $newoptions['echo_name_prod_table'] = $post['echo_name_prod_table'];
	$newoptions['echo_cost_prod_table'] = '0'; if (isset($post['echo_cost_prod_table'])) $newoptions['echo_cost_prod_table'] = $post['echo_cost_prod_table'];
	$newoptions['echo_descr_prod_table'] = '0'; if (isset($post['echo_descr_prod_table'])) $newoptions['echo_descr_prod_table'] = $post['echo_descr_prod_table'];
	$newoptions['echo_id_sklad_prod_table'] = '0'; if (isset($post['echo_id_sklad_prod_table'])) $newoptions['echo_id_sklad_prod_table'] = $post['echo_id_sklad_prod_table'];
	$newoptions['echo_add_prod_table'] = '0'; if (isset($post['echo_add_prod_table'])) $newoptions['echo_add_prod_table'] = $post['echo_add_prod_table'];

	$grsh_options = mso_add_option($grsh['main_key_options'], $newoptions, 'plugins'); //записываем измененные опции
	};



$grsh_options = mso_get_option($grsh['main_key_options'], 'plugins', array()); // получение опций


$settbl = TRUE; $setlst = FALSE; if ($grsh_options['tip_out_prod'] == 'list') {$settbl = FALSE; $setlst = TRUE;};
$setmodeshop = TRUE; $setmodecat = FALSE; if ($grsh_options['mode'] == 'cat') {$setmodeshop = FALSE; $setmodecat = TRUE;};

$out.=	
	'<h1 class="content">'.t('Общие настройки плагина GrShop', 'plugins/grshop').'</h1><br />'.
	form_open($plugin_url .'/general/').mso_form_session('f_session_id').
	'<div class="block_page"><h3>'.t('Cсылка плагина', 'plugins/grshop').'</h3>http://www.yousitename.ru/ '.form_input('main_slug',$grsh_options['main_slug']).NR.'</div>'.
	'<div class="block_page"><h3>'.t('TITLE плагина', 'plugins/grshop').'</h3>'.form_input('main_title',$grsh_options['main_title']).NR.
	t('Вместо [product] будет вставлено название товара, вместо [category] - название категории товаров.', 'plugins/grshop').
	'</div>'.
	'<div class="block_page"><h3>'.t('Обозначение денежной единицы', 'plugins/grshop').'</h3>'.form_input('money',$grsh_options['money']).NR.'</div>'.
	'<div class="block_page"><h3>'.t('Номер (id) категории главной страницы', 'plugins/grshop').'</h3>'.form_input('id_fp_cat', $grsh_options['id_fp_cat']).NR.'</div>'.
	'<div class="block_page"><h3>'.t('Режим работы', 'plugins/grshop').'</h3>'.NR.
	form_radio('mode', 'cat', $setmodecat).'  '.t('каталог', 'plugins/grshop').'  '.NR.
	form_radio('mode', 'shop', $setmodeshop).'  '.t('магазин', 'plugins/grshop').'  '.NR.
	'</div>'.
	'<div class="block_page"><h3>'.t('Адреса электропочты для получения уведомлений (через точку с запятой)', 'plugins/grshop').'</h3>'.form_input('email', $grsh_options['email']).NR.'</div>'.
	'<div class="block_page"><h3>'.t('Текст уведомления для клиента', 'plugins/grshop').'</h3>'.
	form_textarea($data = array('name'=>'email_notice', 'value'=>$grsh_options['email_notice'], 'rows'=>'10')).NR.
	t('Поле [urlname] заменяется на название сайта, [email] - адрес электропочты покупателя, [tel] - телефон, [adress] - адрес, [person] - контактное лицо, [description] - дополнительные данные, [num_ord] - номер заказа, [checklist] - товары в заказе, [price] - общая цена заказа.', 'plugins/grshop').
	'</div>'.NR.
	'<div class="block_page"><h3>'.t('Отображение товаров в категориях каталога', 'plugins/grshop').'</h3>'.NR.
	form_radio('tip_out_prod', 'list', $setlst).'  '.t('списком', 'plugins/grshop').'  '.NR.
	form_radio('tip_out_prod', 'table', $settbl).'  '.t('в таблице', 'plugins/grshop').'  '.NR.


	'<div class="block_page"><h3>'.t('Данные о товаре, отображаемые в списке товаров', 'plugins/grshop').'</h3>'.NR.
	form_checkbox('echo_articul_prod_list', 1, $grsh_options['echo_articul_prod_list']).' '.t('артикул', 'plugins/grshop').' '.NR.
	form_checkbox('echo_name_prod_list', 1, $grsh_options['echo_name_prod_list']).' '.t('название', 'plugins/grshop').' '.NR.
	form_checkbox('echo_cost_prod_list', 1, $grsh_options['echo_cost_prod_list']).' '.t('цена', 'plugins/grshop').' '.NR.
	form_checkbox('echo_descr_prod_list', 1, $grsh_options['echo_descr_prod_list']).' '.t('описание', 'plugins/grshop').' '.NR.
	form_checkbox('echo_id_sklad_prod_list', 1, $grsh_options['echo_id_sklad_prod_list']).' '.t('складской номер', 'plugins/grshop').' '.NR.
	form_checkbox('echo_photo_prod_list', 1, $grsh_options['echo_photo_prod_list']).' '.t('минифото', 'plugins/grshop').' '.NR.
	'</div>'.

	'<div class="block_page"><h3>'.t('Данные о товаре, отображаемые в таблице', 'plugins/grshop').'</h3>'.NR.
	form_checkbox('echo_articul_prod_table', 1, $grsh_options['echo_articul_prod_table']).' '.t('артикул', 'plugins/grshop').' '.NR.
	form_checkbox('echo_name_prod_table', 1, $grsh_options['echo_name_prod_table']).' '.t('название', 'plugins/grshop').' '.NR.
	form_checkbox('echo_cost_prod_table', 1, $grsh_options['echo_cost_prod_table']).' '.t('цена', 'plugins/grshop').' '.NR.
	form_checkbox('echo_descr_prod_table', 1, $grsh_options['echo_descr_prod_table']).' '.t('описание', 'plugins/grshop').' '.NR.
	form_checkbox('echo_id_sklad_prod_table', 1, $grsh_options['echo_id_sklad_prod_table']).' '.t('складской номер', 'plugins/grshop').' '.NR.
	form_checkbox('echo_add_prod_table', 1, $grsh_options['echo_add_prod_table']).' '.t('дополнительные характеристики', 'plugins/grshop').' '.NR.
	'</div>'.
	
	'<div class="block_page"><h3>'.t('Количество товаров на одной странице списка товаров', 'plugins/grshop').'</h3>'.
	form_input('pag_limit_prod_list',$grsh_options['pag_limit_prod_list']).NR.'</div>'.
	'<div class="block_page"><h3>'.t('Количество товаров в таблице товаров на одной странице', 'plugins/grshop').'</h3>'.
	form_input('pag_limit_prod_table',$grsh_options['pag_limit_prod_table']).NR.'</div>'.

	'</div>'.
	form_submit('ch_opt', t('Сохранить', 'plugins/grshop') ).
	form_close( );

echo $out;
?>