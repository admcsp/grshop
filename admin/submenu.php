<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

mso_cur_dir_lang('admin');

?>
<div class="admin-h-menu">
<?php
	//-- формируем массив с данными подключаемых модулей--
	$plugins_dir = $MSO->config['plugins_dir'];
	$plugins_dir = $plugins_dir.'grshop/plugin/';
	$dirs = directory_map($plugins_dir, true);
	sort($dirs);
	foreach ($dirs as $dir)
	{
		$info_f = $plugins_dir . $dir . '/info.php';
		if (file_exists($info_f))
		{
		require($info_f);
			if (isset( $info )) 
			{
			$titles[] = isset($info['title']) ? mso_strip($info['title']) : '';
			$links[] = isset($info['link']) ? $info['link'] : '';
			}
		}
	};
	//--- конец блока формирования массива модулей---------

	# сделаем меню горизонтальное в текущей закладке
	
	// основной url этого плагина - жестко задается
	$plugin_url = $MSO->config['site_admin_url'] . 'grshop';
	
	// Определим текущую страницу (на основе сегмента url)
	$seg = mso_segment(3);
	if ($seg == '' || $seg == 'ord') $segord = 'ord';
	
	// само меню
	// на первом месте вкладка ЗАКАЗЫ
	$a = mso_admin_link_segment_build($plugin_url, 'ord', t('Заказы', 'plugins/grshop'), 'select'). ' | ';
	
	// дальше из grshopпланигов
	// ---- динамическое добавление в меню пунктов дополн. блоков----
	if (isset( $titles )) 
		{
		foreach ($titles as $i=>$title)
			{
			$a .= mso_admin_link_segment_build($plugin_url, $links[$i], $title, 'select'). ' | ';
			}
		};
	// ---- конец блока добавления в меню пунктов дополн. блоков----	
	
	// в конце категории, товары и общие настройки
	//$a .= mso_admin_link_segment_build($plugin_url, 'export', t('Экспорт', 'plugins/grshop'), 'select'). ' | ';
	$a .= mso_admin_link_segment_build($plugin_url, 'category', t('Категории товаров', 'plugins/grshop'), 'select'). ' | ';
	$a .= mso_admin_link_segment_build($plugin_url, 'product', t('Товары', 'plugins/grshop'), 'select'). ' | ';
	//$a .= mso_admin_link_segment_build($plugin_url, 'actions', t('Акции', 'plugins/grshop'), 'select'). ' | ';
	$a .= mso_admin_link_segment_build($plugin_url, 'general', t('Общие настройки', 'plugins/grshop'), 'select'). ' | ';



	$a = mso_hook('plugin_admin_options_menu', $a);
	echo $a;
?>
</div>

<?php
// Определим текущую страницу (на основе сегмента url)
$seg = mso_segment(3);

// подключаем соответственно нужный файл
if ($seg == '' || $seg == 'ord') require($MSO->config['plugins_dir'] . 'grshop/admin/admin_orders.php');
	elseif ($seg == 'general') require($MSO->config['plugins_dir'] . 'grshop/admin/admin_general.php');
	elseif ($seg == 'category') require($MSO->config['plugins_dir'] . 'grshop/admin/admin_category.php');
	elseif ($seg == 'product') require($MSO->config['plugins_dir'] . 'grshop/admin/admin_product.php');
//	elseif ($seg == 'actions') require($MSO->config['plugins_dir'] . 'grshop/admin/admin_actions.php');
//	elseif ($seg == 'ord') require($MSO->config['plugins_dir'] . 'grshop/admin/admin_orders.php');
//	elseif ($seg == 'export') require($MSO->config['plugins_dir'] . 'grshop/admin/admin_export.php');
//	elseif ($seg == 'page_type') require($MSO->config['admin_plugins_dir'] . 'admin_options/page-type.php');

//----- динамическое подключение дополнительных модулей -------------
	if (isset( $links )) 
		{
		$strcod = '';
		foreach ($links as $i=>$link)
			{
			$strcod .= "if (\$seg == '$link') require (\$MSO->config['plugins_dir'].'grshop/plugin/'.'$link/admin/index.php');";		
			};
		eval ($strcod);
		}
//------ конец динамического подключение дополнительных модулей -----

?>