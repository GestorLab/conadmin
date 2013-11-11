<?php
	require_once("WSBilling.php");
	// Inicia a classe WSBilling
	$WSBilling = new WSBilling();

	// Cria o cabeçalho SOAP
	$xmlObj = $WSBilling->add_node("","soap-env:Envelope");
	$WSBilling->add_attributes($xmlObj, array("xmlns:soap-env" => "http://schemas.xmlsoap.org/soap/envelope/") );
	$xmlObj = $WSBilling->add_node($xmlObj,"soap-env:Body");

	// Cria  o elemento m:F2bCobranca
	$xmlObjF2bCobranca = $WSBilling->add_node($xmlObj,"m:F2bCobranca");
	$WSBilling->add_attributes($xmlObjF2bCobranca, array("xmlns:m" => "http://www.f2b.com.br/soap/wsbilling.xsd") );

	// Cria o elemento mensagem
	$xmlObj = $WSBilling->add_node($xmlObjF2bCobranca,"mensagem");
	$WSBilling->add_attributes($xmlObj, array("data" => date("Y-m-d"), 
											  "numero" => date("His"),
											  "tipo_ws" => "WebService"));

	// Cria o elemento sacador
	$xmlObj = $WSBilling->add_node($xmlObjF2bCobranca,"sacador");
	$WSBilling->add_attributes($xmlObj, array("conta" => $LocalCobrancaParametro[Conta], 
											  "senha" => $LocalCobrancaParametro[Senha]));
	$WSBilling->add_content($xmlObj,$linPessoaLoja[Nome]);

	// Cria o elemento cobranca
	$xmlObjCobranca = $WSBilling->add_node($xmlObjF2bCobranca,"cobranca");
	$WSBilling->add_attributes($xmlObjCobranca, array("valor" => $linContaReceber[ValorFinal], 
													  "tipo_cobranca" => $LocalCobrancaParametro[TipoCobranca],
													  "num_document" => $linContaReceber[CodigoContaReceber],
													  "cod_banco" => ""));
	// Se tipo_taxa = 0 a taxa será cobrada em reais (R$), se tipo_taxa = 1 a taxa será cobrada em porcentagem (%)

	// Tipo de cobrança:
	// B - Boleto; C - Cartão de crédito; D - Cartão de débito; T - Transferência On-line
	// Caso queira permitir cobrança por mais de um tipo, enviar as letras juntas. Ex.: "BCD" (Aceitar Boleto, Crédito e Débito)

	// num_document:
	// serve para enviar à F2b um número de controle próprio, facilitando a busca na administração

	// Cria os elementos demonstrativos (Até 10 linhas com 80 caracteres cada)
	$xmlObj = $WSBilling->add_node($xmlObjCobranca,"demonstrativo");
	$WSBilling->add_content($xmlObj,"Referente ao título de número ".$linContaReceber[NumeroDocumento]);
	/*$xmlObj = $WSBilling->add_node($xmlObjCobranca,"demonstrativo");
	$WSBilling->add_content($xmlObj,"Pague em qualquer banco");*/

	// Cria o elemento desconto
	if($linContaReceber[ValorDescontoAConceber] > 0){
		$xmlObj = $WSBilling->add_node($xmlObjCobranca,"desconto");
		$WSBilling->add_attributes($xmlObj, array("valor" => $linContaReceber[ValorDescontoAConceber], "tipo_desconto" => "0", 
												  "antecedencia" => "0"));
	}

	// Cria o elemento multa
	$xmlObj = $WSBilling->add_node($xmlObjCobranca,"multa");
	$WSBilling->add_attributes($xmlObj, array("valor" => "0.00",  "tipo_multa" => "0", 
											  "valor_dia" => "0.00", "tipo_multa_dia" => "0", 
											  "atraso" => "0"));

	//Cria o elemento agendamento
	$xmlObj = $WSBilling->add_node($xmlObjF2bCobranca,"agendamento");
	$WSBilling->add_attributes($xmlObj, array("vencimento" => $linContaReceber[DataVencimento], 
	//  Descomentar os atributos abaixo caso queria realizar cobranças com Agendamento //////
	//                                          "ultimo_dia" => "n",
	//                                          "antecedencia" => 10,
	//                                          "periodicidade" => "1",
	//                                          "periodos" => "12",
											  "sem_vencimento" => "n"));
	$WSBilling->add_content($xmlObj,"Pagamento a vista");

	// Cria o elemento sacado
	$xmlObjSacado = $WSBilling->add_node($xmlObjF2bCobranca,"sacado");
	$WSBilling->add_attributes($xmlObjSacado, array("grupo" => $linPessoa[IdGrupoPessoa], 
													"codigo" => $linPessoa[IdPessoa], 
													"envio" => "n"));
	// Cria o elemento nome
	$xmlObj = $WSBilling->add_node($xmlObjSacado,"nome");
	$WSBilling->add_content($xmlObj,$linPessoa[Nome]);
	// Cria o elemento email
	$xmlObj = $WSBilling->add_node($xmlObjSacado,"email");
	$WSBilling->add_content($xmlObj,$linPessoa[Email][0]);
	$xmlObj = $WSBilling->add_node($xmlObjSacado,"email");
	$WSBilling->add_content($xmlObj,$linPessoa[Email][1]);
	// Cria o elemento endereco
	$xmlObj = $WSBilling->add_node($xmlObjSacado,"endereco");
	$WSBilling->add_attributes($xmlObj, array("logradouro" => $linPessoa[Endereco],
											  "numero" => $linPessoa[Numero],
											  "complemento" => $linPessoa[Complemento],
											  "bairro" => $linPessoa[Bairro],
											  "cidade" => $linPessoa[NomeCidade],
											  "estado" => $linPessoa[SiglaEstado],
											  "cep" => $linPessoa[Cep]));
	/*// Cria o elemento telefone
	$xmlObj = $WSBilling->add_node($xmlObjSacado,"telefone");
	$WSBilling->add_attributes($xmlObj, array("ddd" => "",
											  "numero" => ""));

	// Cria o elemento telefone comercial
	$xmlObj = $WSBilling->add_node($xmlObjSacado,"telefone_com");
	$WSBilling->add_attributes($xmlObj, array("ddd_com" => "",
											  "numero_com" => ""));

	// Cria o elemento telefone celular
	$xmlObj = $WSBilling->add_node($xmlObjSacado,"telefone_cel");
	$WSBilling->add_attributes($xmlObj, array("ddd_cel" => "",
											  "numero_cel" => ""));*/

	// Cria o elemento cpf
	if($linPessoa[CPF] != ''){
		$xmlObj = $WSBilling->add_node($xmlObjSacado,"cpf");
		$WSBilling->add_content($xmlObj,$linPessoa[CPF]);
	}

	// Cria o elemento cnpj
	if($linPessoa[CNPJ] != ''){
		$xmlObj = $WSBilling->add_node($xmlObjSacado,"cnpj");
		$WSBilling->add_content($xmlObj,$linPessoa[CNPJ]);
	}

	// **** É possível registrar a mesma cobrança para vários sacados ao mesmo tempo ****
	/*
	// Cria um novo elemento sacado
	$xmlObjSacado = $WSBilling->add_node($xmlObjF2bCobranca,"sacado");
	// Cria o elemento nome
	$xmlObj = $WSBilling->add_node($xmlObjSacado,"nome");
	$WSBilling->add_content($xmlObj,"Maria Oliveira");
	// Cria o elemento email
	$xmlObj = $WSBilling->add_node($xmlObjSacado,"email");
	$WSBilling->add_content($xmlObj,"mariaoliveira@f2b.com.br");

	// Cria um novo elemento sacado
	$xmlObjSacado = $WSBilling->add_node($xmlObjF2bCobranca,"sacado");
	// Cria o elemento nome
	$xmlObj = $WSBilling->add_node($xmlObjSacado,"nome");
	$WSBilling->add_content($xmlObj,"Pedro Oliveira");
	// Cria o elemento email
	$xmlObj = $WSBilling->add_node($xmlObjSacado,"email");
	$WSBilling->add_content($xmlObj,"pedrooliveira@f2b.com.br");
	*/

	// envia dados
	$WSBilling->send($WSBilling->getXML());
	$resposta = $WSBilling->resposta;
	//$resposta = implode("",file("WSBillingResposta.xml"));
	if(strlen($resposta) > 0){
		// Reinicia a classe WSBlling, agora com uma string XML
		$WSBilling = new WSBilling($resposta);

		// LOG 
		$log = $WSBilling->pegaLog();
		echo "<html>		
				<head>
					<meta http-equiv='content-type' content='text/html; charset=ANSI' />
					<title>".getParametroSistema(4,1)."</title>
					<link rel = 'stylesheet' type = 'text/css' href = '../css/index.css' />
					<link rel = 'stylesheet' type = 'text/css' href = '../css/default.css' />
					<link REL='SHORTCUT ICON' HREF='../../../img/estrutura_sistema/favicon.ico'>
				</head>
				<body>
					<div id='quadro'>";
		if($log["texto"] == "OK"){

			// **** Para abrir a cobrança em uma nova janela ****
			$cobranca	= $WSBilling->pegaCobranca();
			$urlBoleto	= $cobranca[0]["url"];
			$numero_f2b = $cobranca[0]["numero"];

			// Parametros de Recebimento
			$sql = "insert into ContaReceberRecebimentoParametro set 
						IdLoja = $local_IdLoja,
						IdContaReceber = $linContaReceber[IdContaReceber],
						IdContaReceberRecebimento = $local_IdContaReceberRecebimento,
						IdLocalCobranca = $IdLocalCobranca,
						IdParametroRecebimento = 'NumeroF2B',
						ValorParametro = '$numero_f2b'";
			mysql_query($sql,$con);

			$sql = "insert into ContaReceberRecebimentoParametro set 
						IdLoja = $local_IdLoja,
						IdContaReceber = $linContaReceber[IdContaReceber],
						IdContaReceberRecebimento = $local_IdContaReceberRecebimento,
						IdLocalCobranca = $IdLocalCobranca,
						IdParametroRecebimento = 'Processado',
						ValorParametro = ''";
			mysql_query($sql,$con);

			$_SESSION['url_cobranca'] = $urlBoleto;

			echo "<script>
					var aberta = 0;
					var abrir = window.open ('" . $urlBoleto . "','jan','toolbar=no,location=no,menubar=no,resizable=no,scrollbars=yes,width=830,height=500');
					
					abrir.onfocus = function (){
										aberta = 1;
									}
					
					abrir.focus();
					
					function verifica(){
						if(aberta == 1){
							window.location.href = '../menu.php?ctt=pagamento_online_aguarde.php&IdParametroSistema=10';
						}else{
							document.getElementById('quadro').innerHTML = '<BR><BR><p style=\'text-align: center; color: #FFF;\' class=\'descCampo\'><B>Seu navegador bloqueou a janela Pop-up.</B><br>Clique no botão abaixo para continuar...<br><br><INPUT type=\'button\' onclick=\"javascript:direciona()\" value=\'Continuar...\' /></p>';
						}
					}

					function direciona(){
						window.open ('$urlBoleto','jan','toolbar=no,location=no,menubar=no,resizable=no,scrollbars=yes,width=830,height=500');
						aberta = 1;
						verifica();
					}
					
					setTimeout('verifica()',1000);
			</script>";

			// **** Recebendo todos os dados para tratamento do retorno conforme necessidade do cliente ****
			/*
			// AGENDAMENTO
			$agendamento = $WSBilling->pegaAgendamento();
			// COBRANCAS
			$cobranca = $WSBilling->pegaCobranca();
			// SACADOS 
			$sacado = $WSBilling->pegaSacado();

			echo "<table border=1><tr><td colspan='2' bgcolor='gray'><b>Log</b></td></tr>";
			foreach($log as $key => $value){
				echo '<tr><td>$log["'.$key.'"]</td><td>'.$value.'</td></tr>';
			}
			echo "<tr><td colspan='2' bgcolor='gray'><b>Agendamento</b></td></tr>";
			foreach($agendamento as $key => $value){
				echo '<tr><td>$agendamento["'.$key.'"]</td><td>'.$value.'</td></tr>';
			}
			echo "<tr><td colspan='2' bgcolor='gray'><b>Cobranca</b></td></tr>";
			foreach($cobranca as $key => $value){
				foreach($cobranca[$key] as $key2 => $value2){
					echo '<tr><td>$cobranca['.$key.']["'.$key2.'"]</td><td>'.$value2.'</td></tr>';
				}
			}
			echo "<tr><td colspan='2' bgcolor='gray'><b>Sacado</b></td></tr>";
			foreach($sacado as $key => $value){
				foreach($sacado[$key] as $key2 => $value2){
					echo '<tr><td>$sacado['.$key.']["'.$key2.'"]</td><td>'.$value2.'</td></tr>';
				}
			}
			echo "</table>";
		   */
		   
		} else {
			echo "<table border=1><tr><td colspan='2' bgcolor='gray'><b>Log</b></td></tr>";
			foreach($log as $key => $value){
				echo '<tr><td>$log["'.$key.'"]</td><td><font color="red">'.$value.'</font></td></tr>';
			}
			echo "</table>";
		}
		echo "</div></body></html>";
	} else {
		echo '<font color="red">Sem resposta</font>';
	}
?>