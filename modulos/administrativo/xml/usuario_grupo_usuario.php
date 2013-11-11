<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_usuario_grupo_usuario(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdLoja							= $_SESSION['IdLoja'];
		$Login_Usuario					= $_GET['Login'];
		$NomeUsuario				  	= $_GET['NomeUsuario'];
		$IdGrupoUsuario				  	= $_GET['IdGrupoUsuario'];
		$IdPessoa					  	= $_GET['IdPessoa'];
		$IdOrdemServico				  	= $_GET['IdOrdemServico'];
		$where						  	= "";
		$order							="order";
		
		if($Login_Usuario != ''){	$where .= " and UsuarioGrupoUsuario.Login = '$Login_Usuario'";							}
		if($NomeUsuario !=''){   	$where .= " and (Pessoa.Nome like '$NomeUsuario%' or Pessoa.RazaoSocial like '$NomeUsuario%')";		}	
		if($IdGrupoUsuario !='' && $IdGrupoUsuario !='0'){   
			$where .= " and UsuarioGrupoUsuario.IdGrupoUsuario = '$IdGrupoUsuario'";	
		}else{
			$order	= "group";
		}
		$sql = "select 
					UsuarioGrupoUsuario.IdGrupoUsuario, 
					GrupoUsuario.DescricaoGrupoUsuario,
					Usuario.Login,
					Pessoa.Nome,
					UsuarioGrupoUsuario.DataCriacao,
					UsuarioGrupoUsuario.LoginCriacao
				from 
					UsuarioGrupoUsuario, 
					GrupoUsuario, 
					Usuario, 
					Pessoa 
				where 
					UsuarioGrupoUsuario.IdLoja = $IdLoja and 
					UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
					UsuarioGrupoUsuario.Login = Usuario.Login and 
					UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
					Usuario.IdPessoa = Pessoa.IdPessoa and 
					Pessoa.TipoUsuario = 1 and
					Usuario.IdStatus = 1 $where 
					$order by
					Usuario.Login $Limit";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$lin[UltimaAtendimento] = '';
				
				if($IdPessoa != '') {
					$sql_tmp = "select 
									*
								from
									(
										select 
											LoginAtendimento 
										from 
											OrdemServico
										where
											IdLoja = $IdLoja and
											IdPessoa = $IdPessoa and
											LoginAtendimento != ''
										order by 
											DataAlteracao desc
										limit 1
									) temp
								where
									LoginAtendimento = '$lin[Login]';";
					$res_tmp = mysql_query($sql_tmp,$con);
					
					if(@mysql_num_rows($res_tmp)) {
						$lin[UltimaAtendimento] = " ***";
					}
				}
				
				$sql_tmp = "select 
								count(*) QTDAberto
							from
								OrdemServico
							where 
								IdLoja = $IdLoja and
								LoginAtendimento = '$lin[Login]' and
								IdStatus in (100, 300);";
				$res_tmp = mysql_query($sql_tmp,$con);
				$lin_tmp = @mysql_fetch_array($res_tmp);
				$lin[QTDAberto] = $lin_tmp[QTDAberto];
				
				$sqlSupervisor = "	select 
										GrupoUsuario.LoginSupervisor
									from
										GrupoUsuario 
									where
										GrupoUsuario.IdLoja = $IdLoja and
										GrupoUsuario.IdGrupoUsuario = '$IdGrupoUsuario'";
				$resSupervisor = mysql_query($sqlSupervisor,$con);
				$linSupervisor = @mysql_fetch_array($resSupervisor);
				
				$sqlSupervisorAtual = "	select 
											OrdemServico.LoginSupervisor,
											OrdemServico.LoginAtendimento 
										from
											OrdemServico 
										where
											OrdemServico.IdLoja = '$IdLoja' and
											OrdemServico.IdGrupoUsuarioAtendimento = '$IdGrupoUsuario' and
											OrdemServico.IdOrdemServico = '$IdOrdemServico'";
				$resSupervisorAtual = mysql_query($sqlSupervisorAtual,$con);
				$linSupervisorAtual = @mysql_fetch_array($resSupervisorAtual);
				
				if($linSupervisor[LoginSupervisor] != ""){
					$linSupervisorAtual[LoginSupervisor] = $linSupervisor[LoginSupervisor];
				}
				
				$dados	.=	"\n<Login><![CDATA[$lin[Login]]]></Login>";
				$dados	.=	"\n<NomeUsuario><![CDATA[$lin[Nome]]]></NomeUsuario>";
				$dados	.=	"\n<UltimaAtendimento><![CDATA[$lin[UltimaAtendimento]]]></UltimaAtendimento>";
				$dados	.=	"\n<QTDAberto><![CDATA[$lin[QTDAberto]]]></QTDAberto>";
				$dados	.=	"\n<IdGrupoUsuario><![CDATA[$lin[IdGrupoUsuario]]]></IdGrupoUsuario>";
				$dados	.=	"\n<DescricaoGrupoUsuario><![CDATA[$lin[DescricaoGrupoUsuario]]]></DescricaoGrupoUsuario>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<LoginSupervisor><![CDATA[$linSupervisor[LoginSupervisor]]]></LoginSupervisor>";
				$dados	.=	"\n<LoginSupervisorAtual><![CDATA[$linSupervisorAtual[LoginSupervisor]]]></LoginSupervisorAtual>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_usuario_grupo_usuario();
?>