<?
	set_time_limit(0);
	ini_set("memory_limit",getParametroSistema(138,1));

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;

	include("nota_fiscal/$local_IdNotaFiscalLayout/funcoes.php");

	$local_PeriodoApuracao = dataConv($local_MesReferencia, 'm/Y','Y-m');
	$PatchSistema		   = getParametroSistema(6,1);

	// Dados do Processo
	$sqlProcesso = "select
						NomeArquivoMestre,
						NomeArquivoItem,
						NomeArquivoDestinatario,
						LogProcessamento,
						StatusArquivoMestre
					from
						NotaFiscal2ViaEletronicaArquivo
					where
						IdLoja = $local_IdLoja  and
						IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
						MesReferencia = '$local_MesReferencia' and
						Status = '$local_IdStatusArquivoMestre'";
	$resProcesso = mysql_query($sqlProcesso,$con);
	$Processo = mysql_fetch_array($resProcesso);
	
	// Cria a pasta da loja
	$PatchFile = $PatchSistema;
	$PatchFile .= "modulos/administrativo/remessa/nota_fiscal/$local_IdLoja";
	@mkdir($PatchFile, 0770);

	// Cria a pasta da Periodo de Apuraчуo
	$PatchFile .= "/".$local_PeriodoApuracao;
	@mkdir($PatchFile, 0770);

	// Cria a pasta do status
	$PatchFile .= "/".$Processo[StatusArquivoMestre];
	@mkdir($PatchFile, 0770);

	@mysql_close($con);
	include ('../../files/conecta.php');

	// Gera o arquivo ITEM
	include("confirmar_nf_2_via_eletronica_remessa_item.php");

	@mysql_close($con);
	include ('../../files/conecta.php');

	// Gera o arquivo MESTRE
	include("confirmar_nf_2_via_eletronica_remessa_mestre.php");

	@mysql_close($con);
	include ('../../files/conecta.php');

	// Gera o arquivo DESTINATARIO
	include("confirmar_nf_2_via_eletronica_remessa_destinatario.php");

	@mysql_close($con);
	include ('../../files/conecta.php');
	
	// Gera o arquivo EXTRA (estadual)
	$sqlContribuinte = "select
							Estado.SiglaEstado
						from
							NotaFiscalTipo,
							Pessoa,
							PessoaEndereco,
							Estado,
							Cidade
						where
							NotaFiscalTipo.IdLoja = $local_IdLoja and
							NotaFiscalTipo.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
							Pessoa.IdPessoa = NotaFiscalTipo.IdPessoa and
							Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
							Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
							PessoaEndereco.IdPais = Estado.IdPais and
							PessoaEndereco.IdPais = Cidade.IdPais and
							PessoaEndereco.IdEstado = Estado.IdEstado and
							PessoaEndereco.IdEstado = Cidade.IdEstado and
							PessoaEndereco.IdCidade = Cidade.IdCidade";
	$resContribuinte = mysql_query($sqlContribuinte,$con);
	$Contribuinte = mysql_fetch_array($resContribuinte);

	$Contribuinte[SiglaEstado] = strtolower($Contribuinte[SiglaEstado]);
	$remessaExtra = "confirmar_nf_2_via_eletronica_remessa_$Contribuinte[SiglaEstado].php";

	if(file_exists("rotinas/".$remessaExtra)){
		include($remessaExtra);
	}

	@mysql_close($con);
	include ('../../files/conecta.php');

	$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Processo confirmado com sucesso.\n".$LogProcessamento;

	$sql = "update NotaFiscal2ViaEletronicaArquivo set 
					LogProcessamento = concat('$LogProcessamento','\n',LogProcessamento),
					LoginConfirmacao='$local_login',
					IdStatus = 3,
					DataConfirmacao=concat(curdate(),' ',curtime())
				where 
					IdLoja='$local_IdLoja' and 
					IdNotaFiscalLayout='$local_IdNotaFiscalLayout' and 
					MesReferencia='$local_MesReferencia' and
					Status = '$local_IdStatusArquivoMestre'";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;
		
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;
		}
	}

	if($local_transaction == true){
		$sql = "COMMIT;";
		$local_Erro = 174;
	}else{
		$sql = "ROLLBACK;";
		$local_Erro = 175;
	}
	mysql_query($sql,$con);
?>