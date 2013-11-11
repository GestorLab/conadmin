<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_pessoa_atualizacao_cadastro(){
		global $con;
		global $_GET;
		
		$IdPessoa = $_GET['IdPessoa'];
		
		$sql = "select
					PessoaSolicitacao.IdPessoaSolicitacao,
					PessoaSolicitacao.IdPessoa,
					substr(PessoaSolicitacao.Nome, 1, 25) Nome,
					substr(PessoaSolicitacao.RazaoSocial, 1, 25) RazaoSocial,
					LogAcessoCDA.IP,
					LogAcessoCDA.IdNavegador,
					PessoaSolicitacao.LoginAprovacao,
					PessoaSolicitacao.DataAprovacao,
					PessoaSolicitacao.DataCriacao,
					PessoaSolicitacao.IdStatus
				from
					PessoaSolicitacaoEndereco,
					PessoaSolicitacao,
					LogAcessoCDA,
					Pessoa,
					Estado,
					Cidade,
					Pais 
				where 
					Pessoa.IdPessoa = $IdPessoa and 
					Pessoa.IdPessoa = PessoaSolicitacao.IdPessoa and 
					PessoaSolicitacao.IdPessoa = PessoaSolicitacaoEndereco.IdPessoa and 
					PessoaSolicitacao.IdPessoaSolicitacao = PessoaSolicitacaoEndereco.IdPessoaSolicitacao and 
					LogAcessoCDA.IdLogAcesso = PessoaSolicitacao.IdLogAcesso and 
					PessoaSolicitacao.IdEnderecoDefault = PessoaSolicitacaoEndereco.IdPessoaEndereco and 
					Pais.IdPais = PessoaSolicitacaoEndereco.IdPais and 
					Estado.IdPais = Pais.IdPais and 
					PessoaSolicitacaoEndereco.IdEstado = Estado.IdEstado and 
					Cidade.IdEstado = Estado.IdEstado and 
					Cidade.IdCidade = PessoaSolicitacaoEndereco.IdCidade 
				group by 
					PessoaSolicitacao.IdPessoaSolicitacao;";
		$res = @mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin	=	@mysql_fetch_array($res)){
			
            if($lin[DataAlteracao] != ''){
                $data = $lin[DataAlteracao];
                $lin[DataAlteracao] = dataConv($data,'Y-m-d H:i:s','d/m/Y H:i:s');
			}
            if($lin['IdNavegador'] != ''){
                $id_codigo = $lin['IdNavegador'];
                $lin['IdNavegador'] = getParametroSistema(89,$id_codigo);
            }
			
			$lin[Status] = getParametroSistema(124, $lin[IdStatus]);
			$lin[Cor] = getParametroSistema(15, 1);
			
			$dados	.=	"\n<IdPessoa>$lin[IdPessoa]</IdPessoa>";
			$dados	.=	"\n<IdPessoaSolicitacao>$lin[IdPessoaSolicitacao]</IdPessoaSolicitacao>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<LoginAprovacao><![CDATA[$lin[LoginAprovacao]]]></LoginAprovacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<DataAprovacao><![CDATA[$lin[DataAprovacao]]]></DataAprovacao>";
			$dados	.=	"\n<IP><![CDATA[$lin[IP]]]></IP>";
			$dados	.=	"\n<Navegador><![CDATA[$lin[IdNavegador]]]></Navegador>";
			$dados	.=	"\n<Cor><![CDATA[$lin[Cor]]]></Cor>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			
			$cont++;
		}
		
		if(mysql_num_rows($res) >= 1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_pessoa_atualizacao_cadastro();
?>