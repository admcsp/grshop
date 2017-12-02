<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
	mso_cur_dir_lang('admin');

if ( !mso_check_allow('grshop_edit') ) 
	{
	 echo t('Доступ запрещен', 'plugins/grshop');
	 return;
	}
	
	global $MSO;
	require_once ($MSO->config['plugins_dir'].'grshop/common/admcom.php');	// подгружаем библиотеку для админки
	require_once ($MSO->config['plugins_dir'].'grshop/common/common.php');	// подгружаем библиотеку
	$CI = & get_instance();	
	$CI->load->helper('form');	// подгружаем хелпер форм	

/* ф-ция просто повторяется во всех условных блоках кроме всех действующих */
function isss($post, $CI)
	{
	if (isset($post['status']))
		{
		$CI->db->set('status_order', $post['status']);
		$CI->db->where('id_ord', $post['id_ord']);
		$CI->db->update('grsh_ord');
		}
	}


if (mso_segment(4) == 'del')	//--если что-то делаем с конкретным заказом--
	{
	$CI->db->delete('grsh_ord', array('id_ord' => mso_segment(5)));
	$CI->db->delete('grsh_ordprod', array('id_ord' => mso_segment(5)));	
	}

$status = '';
$out = '<h1 class="content">'.t('Заказы', 'plugins/grshop').' ';
$CI->db->where('status_order !=', '4');	

if ($post = mso_check_post(array('f_session_id', 'all_orders')))
	{
	mso_checkreferer();
	$status = ' '.t('текущие', 'plugins/grshop');
	$CI->db->where('status_order !=', '4');	
	}

if ($post = mso_check_post(array('f_session_id', 'step0_orders')))
	{
	mso_checkreferer();
	isss(&$post, &$CI);
	$status = ' '.t('поступившие', 'plugins/grshop');
	$CI->db->where('status_order', '0');
	}

if ($post = mso_check_post(array('f_session_id', 'step1_orders')))
	{
	mso_checkreferer();
	isss(&$post, &$CI);
	$status = ' '.t('подтверждённые', 'plugins/grshop');
	$CI->db->where('status_order', '1');
	}

if ($post = mso_check_post(array('f_session_id', 'step2_orders')))
	{
	mso_checkreferer();
	isss(&$post, &$CI);
	$status = ' '.t('на комплектации', 'plugins/grshop');
	$CI->db->where('status_order', '2');
	}

if ($post = mso_check_post(array('f_session_id', 'step3_orders')))
	{
	mso_checkreferer();
	isss(&$post, &$CI);
	$status = ' '.t('отгруженные', 'plugins/grshop');
	$CI->db->where('status_order', '3');
	}

if ($post = mso_check_post(array('f_session_id', 'step4_orders')))
	{
	mso_checkreferer();
	$query = $CI->db->get('grsh_ord');	//-- это я не знаю как по другому сбросить условие в where
	isss(&$post, &$CI);
	$status = ' '.t('исполненные', 'plugins/grshop');
	$CI->db->where('status_order', '4');
	}

//---- тут вывод таблицы заказов с параметрами сформированными в условиях выбора
//---- в зависимости от нажатой кнопы------------------------------------------------------
	$CI->db->order_by('status_order', 'asc');
	$CI->db->order_by('start_data_order', 'desc');
	$query = $CI->db->get('grsh_ord');
	if ($query->num_rows() != 0)
		{
		$arrord[0] = array 	(
				'id_ord'=>'id', 
				'num_ord'=>t('Номер', 'plugins/grshop'), 
				'start_data_order'=>t('Отправлен', 'plugins/grshop'), 
				'status_order'=>t('статус', 'plugins/grshop'), 
				'email_order'=>'e-mail', 
				'telephon_order'=>t('телефон', 'plugins/grshop'),
				'delete_order'=>t('удалить', 'plugins/grshop'),
				);
		$j = 1;
		foreach ($query->result_array() as $row)
			{
			$ordernum = get_order_number($row['id_ord'], strtotime($row['start_data_order']));
			$arrord[$j]['id_ord'] = $row['id_ord'];
			$arrord[$j]['num_ord'] = '<a href="'.$plugin_url.'/ord/edit/'.$row['id_ord'].'">'.$ordernum.'</a>';
			$arrord[$j]['start_data_order'] = $row['start_data_order'];
			$arrord[$j]['status_order'] = $row['status_order'];
			$arrord[$j]['email_order'] = $row['email_order'];
			$arrord[$j]['telephon_order'] = $row['telephon_order'];
			$arrord[$j]['delete_order'] = '<a href="'.$plugin_url.'/ord/del/'.$row['id_ord'].'"><fontcolor = "red">X</fontcolor></a>';
			$j++;
			} 
		}

$out .= 	$status.'</h1><br />';
$out .= 	form_open($plugin_url.'/ord/').mso_form_session('f_session_id').
	form_submit('all_orders', t('Текущие заказы', 'plugins/grshop') ).
	form_submit('step0_orders', t('Поступившие', 'plugins/grshop') ).
	form_submit('step1_orders', t('Подтвержденные', 'plugins/grshop') ).
	form_submit('step2_orders', t('Комплектуются', 'plugins/grshop') ).
	form_submit('step3_orders', t('Отгруженные', 'plugins/grshop') ).
	form_submit('step4_orders', t('Исполненные', 'plugins/grshop') );

if (isset($arrord)) $out .= buildtable($arrord);	//--ф-ция строит таблицу из коммона----
$out .= form_close( );



if (mso_segment(4) == 'edit')	//--если что-то делаем с конкретным заказом--
	{

	$arr = get_order(mso_segment(5));	//--получили данные о заказе--
	$order = $arr['order'];
	$prodord = $arr['prod'];
	$number_status = '0';

	//$out надо инициализировать снова, что бы не выводилось все что до этого


	if ($order != FALSE)
		{
		$number_status = $order['status_order'];
		$st['0']=t('поступил', 'plugins/grshop'); 
		$st['1']=t('подтвержден', 'plugins/grshop'); 
		$st['2']=t('комплектуется', 'plugins/grshop'); 
		$st['3']=t('отгружен', 'plugins/grshop'); 
		$st['4']=t('исполнен', 'plugins/grshop');

		$out = '<h1 class="content">'.t('Заказ', 'plugins/grshop').'№: '.$order['num_order'].'</h1><br />'.
		form_open($plugin_url.'/ord/').mso_form_session('f_session_id').

		'<div class="block_page"><h3>'.t('статус', 'plugins/grshop').' </h3>'.
		form_dropdown('status', $st , $order['status_order'], ' style="margin-top: 5px; width: 12em;" ').NR.form_submit('step'.$number_status.'_orders', t('Сохранить', 'plugins/grshop')).'</div>'.

		t('ID заказа', 'plugins/grshop').' <div class="block_page">'.''.$order['id_ord'].NR.'</div>'.
		t('Контактное лицо', 'plugins/grshop').' <div class="block_page">'.$order['person_order'].NR.'</div>'.
		t('Дата поступления заказа', 'plugins/grshop').' <div class="block_page">'.''.$order['start_data_order'].NR.'</div>'.
		t('e-mail заказчика', 'plugins/grshop').' <div class="block_page">'.''.$order['email_order'].NR.'</div>'.
		t('телефон заказчика', 'plugins/grshop').' <div class="block_page">'.''.$order['telephon_order'].NR.'</div>'.
		t('контактный адрес', 'plugins/grshop').' <div class="block_page">'.''.$order['adress_order'].NR.'</div>'.
		t('дополнительно', 'plugins/grshop').' <div class="block_page">'.''.$order['description_order'].NR.'</div>'.
		t('Дата поступления заказа', 'admin').' <div class="block_page">'.''.$order['start_data_order'].NR.'</div>';
		};
	if ($prodord != FALSE) 
		{
		foreach ($prodord as $id => $prod)
			{
			if ($prod['name_prod'] != false)
				{
				$prodord[$id]['id_prod'] = '<a href="'.$plugin_url.'/product/edit/'.$prod['id_prod'].'">'.$prod['id_prod'].'</a>';
				$prodord[$id]['name_prod'] = '<a href="'.$plugin_url.'/product/edit/'.$prod['id_prod'].'">'.$prod['name_prod'].'</a>';
				}
			else{
				$prodord[$id]['id_prod'] = $prod['id_prod'];
				$prodord[$id]['name_prod'] = t('данные о товаре удалены из базы данных');
				}
			}		
		$out .= buildtable(arr_2_buildtbl($prodord));
		}
	$out .= form_hidden('id_ord', $order['id_ord']).form_close( );
	}
echo $out;
?>
