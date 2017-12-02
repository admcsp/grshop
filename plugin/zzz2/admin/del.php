<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

function dell_select($selectprod = array())
	{
	echo ("попали в функцию удаления выделенных позиций пока заглушка");
	};

function dell_all ($idcat='')
# функция удаления товаров категории
# входной параметр id-категории
	{
	$CI = & get_instance();	 // получаем доступ к CodeIgniter
	if ($idcat!='')	//если вызов из категории, то удаляем товары только этой категории
		{
		$CI->db->where('id_cat', $idcat);
		$query = $CI->db->get('grsh_catprod');
		if ($query->num_rows()>0)
			{
			foreach ($query->result_array() as $row)
				{
				$CI->db->or_where('id_prod', $row['id_prod']);
				}
			$table = array('grsh_prod', 'grsh_prodadd', 'grsh_catprod');
			$query = $CI->db->delete($table);
			};
		};
	else
		{
		//тут надо обеспечить полную очистку таблиц
		echo ("Пока чистим только по категориям");
		};
	};
?>