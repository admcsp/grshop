﻿<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); //--------- вывод таблицы товаров ----------------------$nmcat = t('всех категорий', 'plugins/grshop');$catarray = get_array_db('cat', '','', $cache=FALSE);if ($catarray) 	$slug_id = array_slug_id('cat', $catarray);// переменная для передачи через пост номера категории.// нужно через пост, что бы передать на кнопку удаления всех//$postidcat = '';// если в post-е пришел ID-категории, или он был в mso_segment(4)$id_cat = '';if (mso_segment(4) !='next')	{	if (mso_segment(4) !='') $id_cat = $slug_id[mso_segment(4)];	if (isset($post['id_cat']))  $id_cat = $post['id_cat'];	}$postidcat = form_hidden('id_cat', $id_cat);//---Блок формирования параметров пагинации-------------if ($id_cat !='' && mso_segment(4) !='next' && mso_segment(4) !='del')	{	$CI->db->join('grsh_catprod', 'grsh_catprod.id_prod = grsh_prod.id_prod');	$CI->db->where('id_cat', $id_cat);	}$query = $CI->db->get('grsh_prod');$pag_row = $query->num_rows();	// количество результатов запроса$query->free_result();		//освобождаем память от результатов запроса$pagination['maxcount']=1;		//инициируем начальным значением$pagination['offset']=0;$pagination['limit']=20;		//количество извлекаемых данных на одну страницу будем в настройках хранить$current_paged = mso_current_paged();  // текущая страница пагинацииif ($pag_row > 0)	{	$pagination['maxcount'] = ceil($pag_row / $pagination['limit']); // всего станиц пагинации		if ($current_paged > $pagination['maxcount']) $current_paged = $pagination['maxcount'];	$pagination['offset'] = $current_paged * $pagination['limit'] - $pagination['limit'];		}else	{	$pagination = false;	}//---Конец блока формирования параметров пагинации-------------if ($id_cat !='' && mso_segment(4) !='next' && mso_segment(4) !='del')	{	$CI->db->join('grsh_catprod', 'grsh_catprod.id_prod = grsh_prod.id_prod');	$CI->db->where('id_cat', $id_cat);	}	$CI->db->order_by("grsh_prod.id_prod", "asc");$query = $CI->db->get('grsh_prod', $pagination['limit'], $pagination['offset']);//--подготавливаем данные для таблицы для вывода$tbl[1][1]='id'; $tbl[1][2]=t('арт.', 'plugins/grshop'); $tbl[1][3]=t('имя', 'plugins/grshop');$tbl[1][4]=t('цена', 'plugins/grshop');$tbl[1][5]=t('кол-во', 'plugins/grshop');$tbl[1][6]=t('резерв', 'plugins/grshop');$tbl[1][7]=t('править', 'plugins/grshop');$tbl[1][8]=t('удалить', 'plugins/grshop');$i=1;foreach ($query->result_array() as $row)	{		$i++;		$tbl[$i][1]=$row['id_prod']; 		$tbl[$i][2]=$row['articul_prod'];		$tbl[$i][3]='<a href="'.$plugin_url.'/zzz2/edit/'.$row['id_prod'].'">'.$row['name_prod'].'</a>';		$tbl[$i][4]=$row['cost_prod'];		$tbl[$i][5]=$row['quantity_prod'];		$tbl[$i][6]=$row['reserve_prod'];		$tbl[$i][7]='<a href="'.$plugin_url.'/zzz2/edit/'.$row['id_prod'].'">'.t('редактировать', 'plugins/grshop').'</a>';				$tbl[$i][8]='<input name="postdell[]" type="checkbox" value="'.$row['id_prod'].'">';		};if ($id_cat != '') 		{		$arrnmcat = array_name_id($data_tip = 'cat', $catarray);		$nmcat = ' категории: "'.array_search($id_cat, $arrnmcat).'"';		}$out=	'<h1 class="content">'.t('Товары', 'plugins/grshop').' '.$nmcat.'</h1>	<table style="width: 99%; border: none; line-height: 1.4em;"><tr><td style="vertical-align: top; padding: 0 10px 0 0;">'.	form_open($plugin_url .'/zzz2').mso_form_session('f_session_id').$postidcat.	form_submit('toadd', t('добавить товар', 'plugins/grshop') ).	form_submit('delall', t('удалить всё', 'plugins/grshop') ).	form_submit('del', t('удалить отмеченные', 'plugins/grshop') ).		buildtable($tbl).form_close();    // ф-ция отрисовки таблицы из библиотеки	echo $out; // вывод таблицы.	$out = '';	mso_hook('pagination', $pagination);	$in['link'] = $plugin_url.'/zzz2/';	$in['admin'] = TRUE;	$in['check'] = mso_segment(4);$out.= 	'</td><td style="vertical-align: top; width: 250px;">'.	catalink($in).NR.'</td></tr></table>'; // ф-ция вывода дерева категорий	echo $out;?>