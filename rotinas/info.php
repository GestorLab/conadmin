<?php

	include ('../files/conecta.php');
	include ('../files/funcoes.php');

    $res = mysql_list_tables($con_bd[banco]);

	$i = 0;

    while ($lin = mysql_fetch_row($res)) {
		
		$sql2 = "select count(*) from $lin[0]";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);

		$i += $lin2[0];

    }
	echo $i;
	echo "\n";

	$file = explode("	",@end(@file('../versao.info')));
	echo $file[0];
?>
