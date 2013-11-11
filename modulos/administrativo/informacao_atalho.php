<?
	$localModulo		=	1;
	$localOperacao		=	16;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/informativo.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>

		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Informações de Atalhos')">		
		<div id='carregando'>carregando</div>
		<div id='conteudo'>	
			<div class='quadro_info' style='width:600px;'>			
				<p class='titulo'>Segue abaixo os comandos que podem ser utilizados de forma rápida e prática. </p>
				<table cellpadding='0' cellspacing='0' border='0' width='600px'>
					<tr>
						<td valign='top' width='48%' style='padding-right: 5px'>
							<table cellpadding='0' cellspacing='0' border='0' width='324px'>
								<tr>
									<td width='40px'><B>Sigla</B></td>
									<td><B>Operação</B></td>
								</tr>
								<tr>
									<td style='padding-top: 5px'><a onClick="carregaOperacao('RE')">'RE'</a></td>								
									<td style='padding-top: 5px'><a onClick="carregaOperacao('RE')">Arquivo de Remessa</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('AR')">'AR'</a></td>
									
									<td><a onClick="carregaOperacao('AR')">Arquivo de Retorno</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('PR')">'PR'</a></td>								
									<td><a onClick="carregaOperacao('PR')">Cadastro de Produto</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('CX')">'CX'</a></td>								
									<td><a onClick="carregaOperacao('CX')">Caixa</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('CC')">'CC'</a></td>								
									<td><a onClick="carregaOperacao('CC')">Centro de Custo</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('EV')">'EV'</a></td>								
									<td><a onClick="carregaOperacao('EV')">Conta Eventual</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('CR')">'CR'</a></td>								
									<td><a onClick="carregaOperacao('CR')">Conta Receber</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('ND')">'ND'</a></td>								
									<td><a onClick="carregaOperacao('ND')">Conta Receber (Busca por número do documento)</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('CO')">'CO'</a></td>								
									<td><a onClick="carregaOperacao('CO')">Contrato</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('EE')">'EE'</a></td>								
									<td><a onClick="carregaOperacao('EE')">E-mail Enviado (E-mail´s antigos)</a></td>
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('LF')">'LF'</a></td>
									
									<td><a onClick="carregaOperacao('LF')">Lançamento Financeiro</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('LC')">'LC'</a></td>								
									<td><a onClick="carregaOperacao('LC')">Local de Cobrança</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('LR')">'LR'</a></td>								
									<td><a onClick="carregaOperacao('LR')">Lote Repasse</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('MD')">'MD'</a></td>
									
									<td><a onClick="carregaOperacao('MD')">Mala Direta</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('ME')">'ME'</a></td>
									
									<td><a onClick="carregaOperacao('ME')">Mensagem Enviada</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('NF')">'NF'</a></td>
									
									<td><a onClick="carregaOperacao('NF')">Nota Fiscal</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('NN')">'NN'</a></td>
									
									<td><a onClick="carregaOperacao('NN')">Nosso Número</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('OS')">'OS'</a></td>								
									<td><a onClick="carregaOperacao('OS')">Ordem Serviço</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('PE')">'PE'</a></td>								
									<td><a onClick="carregaOperacao('PE')">Pessoa</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('PC')">'PC'</a></td>								
									<td><a onClick="carregaOperacao('PC')">Plano de Conta</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('PF')">'PF'</a></td>								
									<td><a onClick="carregaOperacao('PF')">Processo Financeiro</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('PT')">'PT'</a></td>								
									<td><a onClick="carregaOperacao('PT')">Protocolo</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('RC')">'RC'</a></td>								
									<td><a onClick="carregaOperacao('RC')">Recibo</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('SE')">'SE'</a></td>								
									<td><a onClick="carregaOperacao('SE')">Serviço</a></td>								
								</tr>
								<tr>
									<td><a onClick="carregaOperacao('TV')">'TV'</a></td>								
									<td><a onClick="carregaOperacao('TV')">Tipo Vigencia</a></td>								
								</tr>													
							</table>							
							<p class='texto' style='margin-top:8px'>Obs: Este mesmo campo serve para apresentar o valor do código de barra, que o scaner leu.</p>
						</td>
						<td valign='top' width='50%' style='padding-left: 5px'>
							<p class='texto'>Exemplo: Você deve digitar a sigla da operação que deseja buscar junto com o código da operação.</p>
							<p class='texto'>Obs: Você pode digitar a sigla maiúscula ou minúscula e também pode colocar um espaço entre a sigla e o código. Segue os exemplos abaixo.</p>					
							<img src='../../img/estrutura_sistema/info_atalho_ex_1.jpg' /> 
							<p style='margin: 5px 0 10px 60px'>ou</p>
							<img src='../../img/estrutura_sistema/info_atalho_ex_2.jpg' /> 
							<p class='texto' style='margin-top: 8px'>Logo abaixo você pode observar como são usados os comandos da lista à esquerda, ao clicar em uma sigla ou operação de sua escolha.</p>														
							<input type='text' id='codigo_barra' style='width:135px' readOnly/>							
						</td>
					</tr>
				</table>
			</div>
		</div>
	</body>	
</html>

<script>
	function carregaOperacao(id){
		var valor = parseInt(Math.random() * 20);
		if(valor < 10){
			if(valor < 5){				
				document.getElementById('codigo_barra').value = id.toLowerCase()+parseInt(Math.random() * 500);	
			}else{			
				document.getElementById('codigo_barra').value = id.toUpperCase()+parseInt(Math.random() * 500);
			}
		}else{
			if(valor < 15){
				document.getElementById('codigo_barra').value = id.toLowerCase()+" "+parseInt(Math.random() * 500);		
			}else{
				document.getElementById('codigo_barra').value = id.toUpperCase()+" "+parseInt(Math.random() * 500);		
			}
		}	
	}
</script>

