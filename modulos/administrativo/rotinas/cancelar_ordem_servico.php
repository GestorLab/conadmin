<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"C") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$sql3	=	"SELECT IdGrupoUsuarioAtendimento, LoginAtendimento,  LoginResponsavel, IdStatus, OrdemServico.LoginCriacao from OrdemServico,OrdemServicoHistorico where OrdemServico.IdOrdemServico = OrdemServicoHistorico.IdOrdemServico and OrdemServicoHistorico.IdLoja = $local_IdLoja and OrdemServicoHistorico.IdLoja = OrdemServico.IdLoja and OrdemServicoHistorico.IdOrdemServico = $local_IdOrdemServico order by DataHora DESC limit 0,1";
		$res3	=	mysql_query($sql3,$con);
		$lin3	=	mysql_fetch_array($res3);
		
		if($lin3[IdGrupoUsuarioAtendimento] != ''){
			$local_IdGrupoUsuarioAtendimento	=	"'$lin3[IdGrupoUsuarioAtendimento]'";
		}else{
			$local_IdGrupoUsuarioAtendimento	=	'NULL';	
		}
		
		if($lin3[LoginAtendimento] != ''){
			$local_LoginAtendimento	=	"'$lin3[LoginAtendimento]'";
		}else{
			$local_LoginAtendimento	=	'NULL';	
		}
		
		$sql2	=	"select (max(IdHistorico)+1) IdHistorico from OrdemServicoHistorico where IdLoja = $local_IdLoja and IdOrdemServico=$local_IdOrdemServico";
		$res2	=	mysql_query($sql2,$con);
		$lin2	=	@mysql_fetch_array($res2);
		
		if($lin2[IdHistorico]!=NULL){
			$local_IdHistorico	=	$lin2[IdHistorico];
		}else{
			$local_IdHistorico	=	1;
		}
		
		$sql	=	"
			INSERT INTO OrdemServicoHistorico SET 
				IdLoja						= $local_IdLoja,
				IdOrdemServico				= $local_IdOrdemServico,
				IdHistorico					= $local_IdHistorico,
				LoginResponsavel			= '$local_Login',
				LoginAtendimento			= $local_LoginAtendimento,
				IdGrupoUsuarioAtendimento	= $local_IdGrupoUsuarioAtendimento,
				DataHora					= (concat(curdate(),' ',curtime())),
				DataHoraAgendamento			= NULL,
				IdStatus					= 0";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 67;			
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 68;			
		}
		mysql_query($sql,$con);
	}
?>
