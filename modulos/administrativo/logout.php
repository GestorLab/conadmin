<?php
	$gmtDate = gmdate("D, d M Y H:i:s");
	header("Expires:{$gmtDate}GMT");
	header("Last-Modified: {$gmtDate}GMT");
	header("ache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");

	include ("../../files/conecta.php");
	include ("../../files/funcoes.php");
	include ("../../rotinas/verifica.php");
	
	$local_Login	=	$_SESSION['Login'];
	
	$sql3	=	"select IdAtendimento,IdPessoa from Atendimento where IdStatus != 0 and Login = '$local_Login' group by IdAtendimento";
	$res3	=	mysql_query($sql3,$con);
	if(@mysql_num_rows($res3)>= 1){
		while($lin3	=	mysql_fetch_array($res3)){
			if($lin3[IdPessoa]!=NULL){
				$sql2	=	"select (max(IdAtendimentoMensagem)+1) IdAtendimentoMensagem from Atendimento where IdAtendimento = $lin3[IdAtendimento]";
				$res2	=	mysql_query($sql2,$con);
				$lin2	=	@mysql_fetch_array($res2);
						
				if($lin2[IdAtendimentoMensagem]!=NULL){
					$local_IdAtendimentoMensagem	=	$lin2[IdAtendimentoMensagem];
				}else{
					$local_IdAtendimentoMensagem	=	1;
				}
				
				$IdStatus	=	0;
				
				///$sql4 = "select * from Atendimento where IdAtendimento = '$lin3[IdAtendimento]' and Mensagem = '[saiu do chat]' and IdStatus != '0'";
			///	$res4 = @mysql_query($sql4,$con);
			//	if(@mysql_num_rows($res4) > 0){
			//		$IdStatus	=	0;
			//	}else{
					if($lin3[IdPessoa] == ''){
						$IdStatus = 0;
					}
			//	}
					
				$sql =	"INSERT INTO Atendimento SET
							IdAtendimento			=	'$lin3[IdAtendimento]',
							IdAtendimentoMensagem	=	'$local_IdAtendimentoMensagem',
							Login					=	'$local_Login',
							Origem					=	'1',
							Mensagem				=	'[saiu do chat]',	
							IdPessoa				=	'$lin3[IdPessoa]',
							Data					=	concat(curdate(),' ',curtime()),
							IdStatus				=	'$IdStatus'";
				mysql_query($sql,$con);
				
				if($IdStatus == 0){
					$sql = "update Atendimento SET IdStatus='0' where IdAtendimento = '$lin3[IdAtendimento]'";
					mysql_query($sql,$con);
				}
			}else{
				$sql= "update Atendimento SET IdStatus='0' where IdAtendimento = '$lin3[IdAtendimento]'";
				mysql_query($sql,$con);
			}
		}
	}else{
		$sql= "update Atendimento SET IdStatus='0' where Login = '$local_Login'";
		mysql_query($sql,$con);
	}

	echo"<script>javascript:window.close();</script>";

?>
