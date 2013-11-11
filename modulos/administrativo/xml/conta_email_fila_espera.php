<?
	$localModulo	= 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_EmailFilaEspera(){
		
		global $con;
		global $_GET;
		
		$local_IdLoja		= $_SESSION['IdLoja'];
		$local_IdContaEmail	= $_GET['IdContaEmail'];
		$local_IdStatus		= $_GET['IdStatus'];
		$where				= '';
		
		if($local_IdContaEmail != ''){
			$where .= " AND TipoMensagem.IdContaEmail = $local_IdContaEmail";
		}
		
		$sql ="SELECT 
					HistoricoMensagem.IdHistoricoMensagem,
					IF(
						HistoricoMensagem.Celular != NULL,
						HistoricoMensagem.Celular,
						HistoricoMensagem.Email
					) Email,
					HistoricoMensagem.DataEnvio,
					HistoricoMensagem.DataCriacao,
					HistoricoMensagem.IdStatus,
					HistoricoMensagem.Titulo,
					TipoMensagem.IdContaEmail,
					Pessoa.Nome,
					Pessoa.TipoPessoa 
				FROM
					HistoricoMensagem 
					LEFT JOIN Pessoa 
						ON (
							HistoricoMensagem.IdPessoa = Pessoa.IdPessoa
						) 
					LEFT JOIN PessoaGrupoPessoa 
						ON (
							Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa 
							AND PessoaGrupoPessoa.IdLoja = HistoricoMensagem.IdLoja
						) 
					LEFT JOIN GrupoPessoa 
						ON (
							PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja 
							AND PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
						),
					TipoMensagem 
				WHERE HistoricoMensagem.IdLoja = 1 
					AND HistoricoMensagem.IdLoja = TipoMensagem.IdLoja 
					AND HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem
					$where";
		}
?>