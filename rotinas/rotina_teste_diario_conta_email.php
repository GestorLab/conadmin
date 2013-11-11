<?php	
	$sql = "select 
				 IdLoja,
				 IdContaEmail				 
			  from 
				 ContaEmail 
			  where 
				 Usuario != '' and
  				 Senha != '' and
				 IdContaEmail > 0";
	$resTesteContaEmail = @mysql_query($sql,$con);
	while($linTesteContaEmail = @mysql_fetch_array($resTesteContaEmail)){		
		enviarEmailTesteContaEmail($linTesteContaEmail[IdLoja], $linTesteContaEmail[IdContaEmail]);		
	}
?>