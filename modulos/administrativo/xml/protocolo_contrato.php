<?
	$localModulo	= 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_Protocolo(){
		global $con;
		global $_GET;
		
		$local_IdLoja		= $_SESSION['IdLoja'];
		$local_IdProtocolo	= $_GET['IdProtocolo'];
		$local_IdContrato	= $_GET['IdContrato'];
		$local_IdPessoa		= $_GET['IdPessoa'];
		$where				= '';
		
		if($local_IdProtocolo != ''){
			$where .= " and Protocolo.IdProtocolo = $local_IdProtocolo";
		}
		if($local_IdContrato != ''){
			$where .= " and Protocolo.IdContrato = $local_IdContrato";
		}
		if($local_IdPessoa != ''){
			$where .= " and Protocolo.IdPessoa = $local_IdPessoa";
		}
		
		$sql = "select 
					Protocolo.IdProtocolo, 
					Protocolo.LocalAbertura, 
					Protocolo.IdProtocoloTipo,
					Protocolo.Assunto,
					Protocolo.IdPessoa,
					Protocolo.IdContrato,
					Protocolo.IdContaEventual,
					Protocolo.IdContaReceber,
					Protocolo.IdOrdemServico,
					Protocolo.CPF_CNPJ,
					Protocolo.Nome,
					Protocolo.Telefone1,
					Protocolo.Telefone2,
					Protocolo.Telefone3,
					Protocolo.Celular,
					Protocolo.Email,
					Protocolo.IdStatus,
					Protocolo.IdGrupoUsuario,
					Protocolo.LoginResponsavel,
					Protocolo.PrevisaoEtapa,
					Protocolo.LoginCriacao, 
					date_format(Protocolo.DataCriacao,'%d/%m/%Y %H:%i:%s') DataCriacao,
					Protocolo.LoginAlteracao, 
					Protocolo.DataAlteracao,
					Protocolo.LoginConclusao,
					Protocolo.DataConclusao,
					Pessoa.TipoPessoa,
					NULL Data,
					NULL Hora
				from 
					Protocolo,
					Pessoa
				where 
					Protocolo.IdLoja = '$local_IdLoja' and
					Pessoa.IdPessoa = Protocolo.IdPessoa and
					Protocolo.IdStatus = 100
					$where;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$lin[IdLocalAbertura] = $lin[LocalAbertura];
				$lin[LocalAbertura] = getParametroSistema(205,$lin[IdLocalAbertura]);
				$lin[Status] = getParametroSistema(239,$lin[IdStatus]);
				list($lin[CorStatus]) = explode("\n", getCodigoInterno(49,substr($lin[IdStatus],0,1)));
				$lin[CorStatus] = trim(str_replace("\r", "", $lin[CorStatus]));
				
				$res_tipoProtocolo = mysql_query("Select DescricaoProtocoloTipo from ProtocoloTipo where IdProtocoloTipo = '$lin[IdProtocoloTipo]';");
				$lin_tipoProtocolo = mysql_fetch_array($res_tipoProtocolo);
				
				$sql_temp = "select ValorCodigoInterno from CodigoInterno where IdGrupoCodigoInterno = '53' and IdCodigoInterno = '".$lin[IdStatus]."';";
				$res_temp = mysql_query($sql_temp, $con);
				$lin_temp = mysql_fetch_array($res_temp);
				list($temp) = explode("\n", $lin_temp[ValorCodigoInterno]);
				
				if(!empty($temp)){
					$lin[CorStatus] = str_replace("\r", "", $temp);
				}
				
				if(!empty($lin["PrevisaoEtapa"])) {
					list($lin["Data"], $lin["Hora"]) = explode(" ", dataConv($lin["PrevisaoEtapa"], "Y-m-d H:i:s", "d/m/Y H:i:s"));
					$lin["Hora"] = substr($lin["Hora"], 0, 5);
				}
				
				$dados .= "\n<IdProtocolo>$lin[IdProtocolo]</IdProtocolo>";
				$dados .= "\n<IdLocalAbertura><![CDATA[$lin[IdLocalAbertura]]]></IdLocalAbertura>";
				$dados .= "\n<LocalAbertura><![CDATA[$lin[LocalAbertura]]]></LocalAbertura>";
				$dados .= "\n<IdProtocoloTipo><![CDATA[$lin[IdProtocoloTipo]]]></IdProtocoloTipo>";
				$dados .= "\n<DescricaoProtocoloTipo><![CDATA[$lin_tipoProtocolo[DescricaoProtocoloTipo]]]></DescricaoProtocoloTipo>";
				$dados .= "\n<Assunto><![CDATA[$lin[Assunto]]]></Assunto>";
				$dados .= "\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
				$dados .= "\n<IdContrato><![CDATA[$lin[IdContrato]]]></IdContrato>";
				$dados .= "\n<IdContaEventual><![CDATA[$lin[IdContaEventual]]]></IdContaEventual>";
				$dados .= "\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
				$dados .= "\n<IdOrdemServico><![CDATA[$lin[IdOrdemServico]]]></IdOrdemServico>";
				$dados .= "\n<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
				$dados .= "\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
				$dados .= "\n<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
				$dados .= "\n<Telefone2><![CDATA[$lin[Telefone2]]]></Telefone2>";
				$dados .= "\n<Telefone3><![CDATA[$lin[Telefone3]]]></Telefone3>";
				$dados .= "\n<Celular><![CDATA[$lin[Celular]]]></Celular>";
				$dados .= "\n<Email><![CDATA[$lin[Email]]]></Email>";
				$dados .= "\n<IdGrupoUsuario><![CDATA[$lin[IdGrupoUsuario]]]></IdGrupoUsuario>";
				$dados .= "\n<LoginResponsavel><![CDATA[$lin[LoginResponsavel]]]></LoginResponsavel>";
				$dados .= "\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados .= "\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados .= "\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados .= "\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados .= "\n<LoginConclusao><![CDATA[$lin[LoginConclusao]]]></LoginConclusao>";
				$dados .= "\n<DataConclusao><![CDATA[$lin[DataConclusao]]]></DataConclusao>";
				$dados .= "\n<TipoPessoa><![CDATA[$lin[TipoPessoa]]]></TipoPessoa>";
				$dados .= "\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados .= "\n<Status><![CDATA[$lin[Status]]]></Status>";
				$dados .= "\n<CorStatus><![CDATA[$lin[CorStatus]]]></CorStatus>";
				$dados .= "\n<Data><![CDATA[$lin[Data]]]></Data>";
				$dados .= "\n<Hora><![CDATA[$lin[Hora]]]></Hora>";
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_Protocolo();
?>