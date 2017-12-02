<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

mso_cur_dir_lang('templates');
global $MSO;
require_once ($MSO->config['plugins_dir'].'grshop/common/common.php');	//подгружаем библиотеку c ф-циями вывода
require_once ($MSO->config['plugins_dir'].'grshop/config.php');	// подгружаем переменные
$grsh_options = mso_get_option($grsh['main_key_options'], 'plugins', array()); // получение опций

$out = '';
$pagination = false;

if ($post = mso_check_post(array('f_session_id', 'addbasket')))
	{
	mso_checkreferer();
	$res = addbasket(&$post);	//-ф-ция добляет в корзину из коммона	
	};
if (mso_segment(2) == '')		require_once ($MSO->config['plugins_dir'].'grshop/public/frontpage.php');//главная страница каталога
elseif (mso_segment(2) == 'cat') 	require_once ($MSO->config['plugins_dir'].'grshop/public/catalog.php');	// каталог
elseif (mso_segment(2) == 'bas')	require_once ($MSO->config['plugins_dir'].'grshop/public/basket.php');	// корзина
elseif (mso_segment(2) == 'prod')	require_once ($MSO->config['plugins_dir'].'grshop/public/product.php');	// один товар
else 	{//-- формируем массив с данными подключенных модулей--
		 //-- для вывода страниц модулей, если есть по их ссылкам--
			$CI = & get_instance(); // получаем доступ к CodeIgniter
			$CI->load->helper('directory');
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
			if (isset( $links )) 
			{
			$strcod = '';
			foreach ($links as $i=>$link)
				{
				$strcod .= "if (mso_segment(2) == '$link') require (\$MSO->config['plugins_dir'].'grshop/plugin/'.'$link/public/index.php');";		
				};
			eval ($strcod);
			}
		}
	//--- конец блока динамического формирования массива ссылок модулей плагина---------			


//require_once ($MSO->config['plugins_dir'].'grshop/public/frontpage.php');//главная страница каталога НАДО ПРОБОВАТЬ ТУТ ВМЕСТО ЭТОГО ВСТАВИТЬ ОЧЕРЕДНОЙ ЕЛСЕИФ КОТОРЫЙ РАСКИДЫВАЕТ ПО ВЫВОДАМ ПЛАГИНА

//---- собственно вывод---------
require(getinfo('template_dir') . 'main-start.php');	// начальная часть шаблона
echo $out;
mso_hook('pagination', $pagination);
echo '<br>';	
require(getinfo('template_dir') . 'main-end.php');		//# конечная часть шаблона
	
?>