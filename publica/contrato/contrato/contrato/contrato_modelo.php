<?
 function getNomeMes(){
  $mesAux = array("","Janeiro","Fevereiro","Mar�o","Abril","Maio","Junho","Julho","Agosto","Setembro","Outobro","Novembro","Dezembro");
  $dia = date("d");
  $mes = $mesAux[(int) date("m")];
  $ano = date("Y");
  
  return $dia." de ".$mes." de ".$ano;
 }
 function getNomeDia(){
  $diaAux = array("Domingo","Segunda-Feira","Ter�a-Feira","Quarta-Feira","Quinta-Feira","Sexta-Feira","Sabado");
  $dia = $diaAux[(int) date("w")];
  return $dia."";
 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<style type='text/css'>
		   #conteiner {
			font-family: "Verdana";
			font-size: 10pt;
			text-align: justify;
			margin-top: 0px;
		   }
		   #conteiner #bloco_logo{
			text-align: center;
		   }
		   #conteiner #bloco_cadastro{
			
		   }
		    #conteiner .td1{
				border-left: none;
				border-bottom: solid 1px;
				border-right: solid 1px;
				height: 10px;
				font-size:11.5px;
		   }
		   #conteiner .td2{
				border-bottom: solid 1px;
				border-right: none;
				height: 10px;
				font-size:11.5px;
		   }
		   #conteiner .clausulas b{
			margin-left: 48px;
		   }
		   #conteiner .clausulas .sub{
			margin-left: 48px;
		   }
		   #conteiner .clausulas p{
			margin-top: -20px;
		   }
		   #conteiner .clausulas ul{
			list-style: none;
		   }
		   #conteiner #localdata{
			margin-left: 360px;
			margin-top: 20px;
		   }
		   #conteiner #finaldocumento{
			margin-left: 0px;
		   }
		   #conteiner #finaldocumento ul{
			list-style: none;
			margin-left: -40px;
			margin-top: 80px;
		   }
		   
		   #conteiner #texto{
			font-size: 10pt;
		   } 
	
		   .bloco{
			background-color:#000;
			   color:#FFF;
			   font-size:14px;		   
			   height:22px;
			   font-weight:bold;
			   line-height: 22px;		   
		   }
		   .text{
		   
		   font-size:10.4px;
		   text-align:left;
		   margin-top:-9.6px;
		   
		   }
		   #blocoInfo{
			width:100%;
		   
			margin-top:10px;
		   }
		   
		   #conteiner #texto p{
				margin-top: -10pt;
				font-size: 11pt;
		   }
		   #conteiner #texto .pe{
				margin-top: 14pt;
		   }
		   #separador{
				width: 30px;
		   }
		   #conteiner #butao_imprimir{
				text-align: center;
				margin-top: 10px;
		   }
		   #conteiner .marcador{
				background-color: #FFFF00;
		   }
		   @media print{
				#conteiner #butao{
				  display: none;
				}
				#conteiner #linha{
				  display: none;
				}
				.bloco{
						color:#000;
				}
			}   
		</style>
	</head>
	<body>
		<div id='conteiner'>
			<div id='bloco_logo'>
				<p style='margin-top: -9; font-size: 11.3px'><b><u>CONTRATO DE PRESTA��O DE CONEX�O E ACESSO � INTERNET</u></b></p>
			</div>		
			<table cellpadding='0' cellspacing='0' style='width: 1024px;margin-top: -14px'>
				 <tr>
					  <td class='td2' colspan='3'/>
					  <td />
					  <td />
				 </tr>
				 <tr>
					  <td class='td1' style='width: 220px;'>&nbsp; Nome do Assinante:</td>
					  <td colspan='2' class='td2' style='padding-left:7px'></td>
					  <td />
				 </tr>
				 <tr>
					  <td class='td1'>&nbsp; Endere�o:</td>
					  <td colspan='2' class='td2' style='padding-left:7px'></td>
					  <td />
				 </tr>
				 <tr>
					  <td class='td1'>&nbsp; Nome Fantasia:</td>
					  <td colspan='2' class='td2' style='padding-left:7px'></td>
					  <td />
				 </tr>
				 <tr>
					  <td class='td1'>&nbsp; Cidade / UF:</td>
					  <td class='td1' style='width: 450px; padding-left:7px'></td>
					  <td class='td1' style='width: 450px;'>&nbsp; E-mail: </td>
				 </tr>
				 <tr>
					  <td class='td1'>&nbsp; CPF / C.N.P.J: </td>
					  <td class='td1' style='width: 450px; padding-left:7px' ></td>
					  <td class='td1' style='width: 450px;'>&nbsp; RG / IE: </td>
				 </tr>
				 <tr>
					  <td class='td1'>&nbsp; Telefone:</td>
					  <td class='td1' style='width: 376px; padding-left:7px'></td>
					  <td class='td1' style='width: 376px;'>&nbsp; Telefone Celular: </td>
				 </tr>
			</table> 
			<table cellpadding='0' cellspacing='0' style='width: 1024px'> 
				<tr>
					  <td colspan='1' class='td1' style='width: 906px'> &nbsp;&nbsp;Designa��o nominal do plano de acesso contratado: </td>
					  <td class='td2' colspan='2' style='padding-left:7px'></td>
					  <td/>
				</tr>
				 <tr>
					<td ></td>
					<td style='width: 592px'></td>
				 </tr>
				 <tr>
					<td class='td1' >&nbsp; Valor da mensalidade:</td>
					<td class='td1'>&nbsp; Vencimento: </td>
				 </tr>
				<tr>
					<td class='td1'>&nbsp;Local e Data:</td>
					<td class='td1'> &nbsp;&nbsp; (&nbsp;&nbsp;) Comodato   (&nbsp;&nbsp;) Do Contratante</td>
				</tr>
				<tr>
					<td class='td1' colspan='1'>&nbsp; Equipamentos para ativa��o do servi�o: </td>
					<td class='td2'></td>
				</tr>
				<tr>
					<td class='td1' colspan='1'>&nbsp; Data de Ativa��o: Nas 72 horas subsequentes no prazo normal.</td>
					<td class='td2'></td>
				</tr>
			</table>
			<p style='font-size:10.7px'><b>AS PARTES</b> a seguir qualificadas:</p>
			<p class='text'>I - <b>PRESTADORA</b>: DIGITAL TECH INFORM�TICA LTDA ME, CNPJ n.�08.929.889/0001-06, 
				com sede na R. Salom�o Abdala, 1020 - Jardim Itamarac� CEP 79062-220 - Campo Grande/MS, 
				representada por seu s�cio, <b>Deibi Carlos Delatori</b>, brasileiro, casado, empres�rio,
				portador da identidade RG n� 1384453 SSP/MS e CPF n� 008.963.951-03, denominada simplesmente <b>CONTRATADA</b>,
				com outorga da <b>Anatel</b>, por meio do Ato de Autoriza��o n.�, 1.965 de 31/03/2011 e Termo de Autoriza��o n.� PVST/SPV 195/2011,
				conforme consta do Processo n.� 53500.019268/2010, doravante simplesmente designada �DIGITALNET� e
			</p>
			<p style='margin-top:-6px'></p>
			<p class='text'>II - ASSINANTE: Pessoa f�sica ou jur�dica, Identificada conforme TERMO DE ADES�O, 
				parte integrante deste contrato, mediante comprova��o eletr�nica atrav�s do site http://www.dcdms.com.br/termodeadesao. 
				T�m entre si o presente CONTRATO DE PRESTA��O DE SERVI�OS DE COMUNICA��O MULTIM�DIA, 
				fornecidos pela PRESTADORA qualificada acima, e o ASSINANTE, identificado no TERMO DE ADES�O, 
				o qual ser� regido pelas cl�usulas a seguir, levando-se em considera��o, ainda,
				na interpreta��o do contrato, as defini��es abaixo relacionadas, 
				utilizadas para a perfeita compreens�o dos termos adotados neste ajuste:
			</p>
			<p class='text'>a) PRESTADORA: pessoa jur�dica que mediante autoriza��o presta o SCM;</p>
			<p class='text'>b) ASSINANTE: � a pessoa natural ou jur�dica que possui v�nculo contratual com a prestadora para frui��o do SCM, segundo os termos e condi��es estabelecidas no presente contrato;</p>
			<p class='text'>c) INFORMA��ES MULTIM�DIA: sinais de �udio, v�deo, dados, voz e outros sons e imagens, textos e outras informa��es de qualquer natureza;</p>
			<p class='text'>d) SERVI�O DE COMUNICA��O MULTIM�DIA: � um servi�o fixo de telecomunica��es de interesse coletivo, prestado em �mbito nacional e internacional, no regime privado, 
				que possibilita a oferta de capacidade de transmiss�o, emiss�o e recep��o de informa��es multim�dia, utilizando quaisquer meios, a assinantes dentro de uma �rea de presta��o de servi�o.</p>
			<p class='text'>e) INTERCONEX�O: liga��o entre redes de telecomunica��es funcionalmente compat�veis, de modo que os usu�rios de servi�os de uma das redes possam se comunicar com usu�rios de servi�o de outra ou acessar servi�os nela dispon�veis;</p>
			<p class='text'>f) MENSALIDADE: � a quantia devida pelo assinante � PRESTADORA, mensalmente, pela transmiss�o, emiss�o e recep��o de informa��es multim�dia, conforme tabela da
				PRESTADORA que variar� de acordo com o pacote escolhido, e, conforme o caso, com outras modalidades de servi�os solicitados pelo assinante;</p>
			<p class='text'>g) SERVI�O DE ACESSO A INTERNET: compreende o fornecimento, instala��o e manuten��o dos meios de transmiss�o necess�rios para presta��o do servi�o de acesso � internet em banda larga, atrav�s dos provedores de acesso habilitados, desde o Ponto Principal de instala��o, indicado pelo ASSINANTE, at� a infra-estrutura que integra o ambiente da PRESTADORA;</p>
			<p class='text'>h) CARACTER�STICAS B�SICAS DO PRODUTO: o servi�o de acesso � internet em banda larga consiste no provimento de canais de transmiss�o de dados, �udio e v�deo, utilizando-se dos meios de acesso dispon�veis: a) Acesso discado (linha telef�nica); b) Acesso sem fio via r�dio digital (Wi-fi 802.11x); c) Acesso via ADSL; d) Acesso via cable modem; e) Acesso via circuito dedicado de alta velocidade;</p>
			<p class='text'>I - O servi�o de acesso a Internet ser� prestado em faixas de velocidade, conforme escolha do ASSINANTE,</p>
			<p class='text'>II - Para configurar o servi�o de acesso � internet em banda larga, ser� atribu�do pelo provedor via Rede IP, um endere�o IP fixo ou din�mico, em raz�o do servi�o contratado.</p>
			<p class='text'style='font-size:10.7px'><b>Do Objeto: </b></p>
			<p class='text'>Cl�usula 1� - Este contrato tem por OBJETO a aquisi��o, pelo ASSINANTE, do direito de acesso ao Servi�o de Comunica��o Multim�dia e outros servi�os ofertados pela PRESTADORA, na localidade anteriormente indicada no TERMO DE ADES�O onde a PRESTADORA det�m a autoriza��o e mediante o pagamento pactuado, e, adicionalmente, do pagamento das mensalidades indicadas na referida proposta, no per�odo em que vigorar o presente contrato, pela recep��o dos servi�os escolhidos pelo ASSINANTE quando da formula��o da proposta de ades�o.</p>
			<p class='text'>Cl�usula 2� - Al�m do pacote de servi�o escolhido, constituem MODALIDADES DO SERVI�O DE COMUNICA��O MULTIM�DIA, e desde que disponibilizados pela PRESTADORA, poder� o assinante solicit�-los, mediante o pagamento da respectiva taxa de servi�o, al�m do valor da mensalidade e/ou pre�o correspondente �s modalidades solicitadas, as quais poder�o ser canceladas a qualquer tempo pelo ASSINANTE, sem �nus e, em caso de re-liga��o ou reabilita��o do servi�o, ficar� o ASSINANTE respons�vel pelo pagamento de nova taxa de servi�o/ades�o: 1) a aquisi��o de programas pagos individualmente pelo ASSINANTE em hor�rio previamente programado pela PRESTADORA (pay-per-view); e 2) servi�os especializados de informa��es meteorol�gicas, banc�rias, financeiras, culturais, de pre�os e outros que possam ser oferecidos aos ASSINANTES do SCM.Do pacote de servi�os e suas altera��es:</p>
			<p class='text'>Cl�usula 3� - A escolha do pacote de SCM e da faixa de velocidade de acesso � internet em banda larga selecionada poder� ser alterada pelo ASSINANTE a qualquer tempo, por outro pacote ou faixa de velocidade de sua escolha desde que dispon�vel pela PRESTADORA � �poca da substitui��o, e, nesse caso, ficar� o ASSINANTE respons�vel pelo pagamento da taxa de servi�o respectiva, de acordo com a tabela de pre�os vigente � �poca, adequando-se, ainda, o pre�o da mensalidade respectiva, para, reduzi-lo ou aument�-lo, conforme a nova op��o do pacote ou velocidade de acesso escolhidos. As condi��es desta nova op��o ser�o fixadas atrav�s de nova rela��o contratual, nos termos das condi��es gerais vigentes � �poca da altera��o.</p>
			<p class='text' style='font-size:10.7px'><b>Dos par�metros de qualidade</b></p>
			<p class='text'>Cl�usula 4� - S�o par�metros de qualidade para o SCM, sem preju�zo de outros que venham a ser definidos pela ANATEL:</p>
			<p class='text'>I � Fornecimento de sinais respeitando as caracter�sticas estabelecidas na regulamenta��o;</p>
			<p class='text'>II � disponibilidade dos servi�os nos �ndices contratados;</p>
			<p class='text'>III � emiss�o de sinais eletromagn�ticos nos n�veis estabelecidos em regulamenta��o;</p>
			<p class='text'>IV � divulga��o de informa��o aos seus assinantes, de forma inequ�voca, ampla e com anteced�ncia razo�vel, quanto a altera��es de pre�os e condi��es de frui��o do servi�o;</p>
			<p class='text'>V � rapidez no atendimento �s solicita��es e reclama��es dos assinantes;</p>
			<p class='text'>VI � n�mero de reclama��es contra a prestadora;</p>
			<p class='text'>VII � fornecimento das informa��es necess�rias � obten��o dos indicadores de qualidade do servi�o, de planta, bem como os econ�mico-financeiros, de forma a possibilitar a avalia��o da qualidade na presta��o do servi�o.</p>
			<p style='font-size:10.7px;margin-top:-5px'><b>Dos Direitos e Obriga��es da prestadora</b></p>
			<p style='font-size:10.7px;margin-top:-5px'>Cl�usula 5� - Constituem direitos da prestadora, al�m dos previstos na Lei 9.472/97, na regulamenta��o pertinente e os discriminados no termo de autoriza��o para presta��o do servi�o:</p>
			<p class='text'>I � empregar equipamentos e infra-estrutura que n�o lhe perten�am;</p>
			<p class='text'>II � contratar com terceiros o desenvolvimento de atividades inerentes, acess�rias ou complementares ao servi�o.</p>
			<p class='text'>Par�grafo primeiro: A prestadora, em qualquer caso, continuar� respons�vel perante a ANATEL e os assinantes pela presta��o e execu��o dos servi�os.</p>
			<p class='text'>Cl�usula 6� - Face as reclama��es e d�vidas dos assinantes a prestadora deve fornecer imediato esclarecimento e sanar o problema com a maior brevidade poss�vel;</p>
			<p class='text'>Cl�usula 7� - Em caso de interrup��o ou degrada��o da qualidade do servi�o, a prestadora deve descontar da assinatura o valor proporcional ao n�mero de horas ou fra��o superior a trinta minutos.
							Par�grafo Primeiro � A necessidade de interrup��o ou degrada��o do servi�o por motivo de manuten��o, amplia��o da rede ou similares dever� ser amplamente comunicada aos assinantes que ser�o afetados, com anteced�ncia m�nima de uma semana, devendo os mesmos terem um desconto na assinatura � raz�o de 1/30 (um trinta avos) por dia ou fra��o superior a quatro horas.</p>
			<p class='text'>Par�grafo Segundo � A prestadora n�o ser� obrigada a efetuar o desconto se a interrup��o ou degrada��o do servi�o ocorrer por motivos de caso fortuito ou for�a maior, cabendo-lhe o �nus da prova.</p>
			<p class='text'>Cl�usula 8� - Sem preju�zo no disposto na legisla��o aplic�vel, as prestadoras de SCM t�m a obriga��o de:</p>
			<p class='text'>I � n�o recusar o atendimento a pessoas cujas depend�ncias estejam localizadas na �rea de presta��o do servi�o, nem impor condi��es discriminat�rias, salvo nos casos em que a pessoa se encontrar em �rea geogr�fica ainda n�o atendida pela rede;</p>
			<p class='text'>II � tornar dispon�veis ao assinante, com anteced�ncia razo�vel, informa��es relativas a pre�os, condi��es de frui��o dos servi�os, bem como suas altera��es;</p>
			<p class='text'>III � descontar da assinatura o equivalente ao n�mero de horas ou fra��o superior a trinta minutos de servi�o interrompido ou degradado com rela��o ao total m�dio de horas de capacidade contratada;</p>
			<p class='text'>IV � prestar esclarecimentos ao assinante, de pronto e livre de �nus, face as suas reclama��es relativas � frui��o dos servi�os;</p>
			<p class='text'>IV � observar os par�metros de qualidade estabelecidos na regulamenta��o e no contrato celebrado com o assinante, pertinentes � presta��o do servi�o.</p>
			<p class='text'>Cl�usula 9� - A prestadora observar� o dever de zelar estritamente pelo sigilo inerente aos servi�os de telecomunica��es e pela confidencialidade quanto aos dados e informa��es do assinante, empregando todos os meios e tecnologias necess�rias para assegurar este direito dos usu�rios;
				Par�grafo �nico � A prestadora tornar�o dispon�veis os dados referentes � suspens�o de sigilo de telecomunica��es para a autoridade judici�ria ou legalmente investida desses poderes que determinar a suspens�o do sigilo.</p>
			<p style='font-size:10.7px;margin-top:-5px'><b>Dos direitos e deveres do assinante</b></p>
			<p style='margin-top:-6px'></p>
			<p class='text'>Cl�usula 10� - O assinante do SCM tem direito, sem preju�zo do disposto na legisla��o aplic�vel:</p>
			<p class='text'>I � de acesso ao servi�o, mediante contrata��o junto a uma prestadora;</p>
			<p class='text'>II � � liberdade de escolha da prestadora;</p>
			<p class='text'>III � ao tratamento n�o discriminat�rio quanto �s condi��es de acesso e frui��o do servi�o;</p>
			<p class='text'>IV � � informa��o adequada sobre condi��es de presta��o do servi�o;</p>
			<p class='text'>V � ao conhecimento pr�vio das condi��es de suspens�o dos servi�os, exceto quando independer da vontade da prestadora;</p>
			<p class='text'>VI � ao recebimento do documento de cobran�a com discrimina��o dos valores cobrados;</p>
			<p class='text'>VII - comunicar � prestadora, atrav�s da Central de Atendimento, toda e qualquer irregularidade ou mau funcionamento do Servi�o ou fato nocivo � seguran�a, relacionado � presta��o do Servi�o, visando possibilitar a adequada assist�ncia e/ou orienta��o pela prestadora;</p>
			<p class='text'>VIII - Manter atualizados os seus dados cadastrais com a prestadora, informando-a sobre toda e qualquer modifica��o, seja de endere�o, administrador do contrato, controle societ�rio, dentre outros.</p>
			<p class='text'>Cl�usula 11� - � proibido ao assinante ceder, transferir ou disponibilizar a presta��o de servi�o de comunica��o multim�dia � SCM, contratado com a PRESTADORA a terceiros, quer seja por cabo, r�dio ou qualquer outro meio de transmiss�o, sob pena de rescis�o do presente contrato, bem como, a obriga��o do assinante de ressarcir � PRESTADORA os servi�os n�o tarifados, as perdas e danos e os lucros cessantes.</p>
			<p style='font-size:10.7px;margin-top:-5px'><b>Dos Pre�os de Ades�o, Mensalidades e Reajustes:</b></p>
			<p style='margin-top:-6px'></p>
			<p class='text'>Cl�usula 12� - Pelo direito de acesso ao SCM e da faixa de velocidade de acesso � internet em banda larga, o ASSINANTE pagar� � PRESTADORA, O PRE�O PREVIAMENTE AJUSTADO, nas condi��es nela indicadas. O ASSINANTE dever� efetuar os pagamentos das mensalidades atrav�s de documento de cobran�a emitido pela PRESTADORA, em estabelecimento banc�rio ou outra institui��o autorizada pr�via e expressamente por esta �ltima.</p>
			<p class='text'>Cl�usula 13� - Pelo pacote de servi�os e pela faixa de velocidade de acesso � internet em banda larga escolhidos, o ASSINANTE pagar� � PRESTADORA a MENSALIDADE estipulada na proposta de ades�o, mediante documento de cobran�a emitido mensalmente pela PRESTADORA e remetido ao ASSINANTE. Os valores referentes � mensalidade s�o pr�-estabelecidos, n�o sendo aceito qualquer outro valor que n�o o impresso na tabela de pre�os da PRESTADORA.</p>
			<p class='text'>Cl�usula 14� - AS MENSALIDADES DEVER�O SER PAGAS nas datas de vencimento indicadas na proposta de ades�o, sendo o pagamento sempre referente ao m�s j� utilizado, independente da efetiva utiliza��o do servi�o, ou seja, independente do n�mero de horas utilizadas, salvo quando enquadrado na Cl�usula 7� Par�grafo 1�.</p>
			<p class='text'>Cl�usula 15� - O VALOR da mensalidade ser� REAJUSTADO, ap�s doze meses contados da data da assinatura da proposta de ades�o, com base na varia��o do �ndice Geral de Pre�os - IGP-M, divulgado pela Funda��o Get�lio Vargas, ou, no caso de sua extin��o ou da inexist�ncia de sua divulga��o, por outro �ndice que melhor reflita a perda do poder aquisitivo da moeda nacional ocorrida no per�odo. Outrossim, ser� l�cito � PRESTADORA REAJUSTAR A MENSALIDADE EM DECORR�NCIA DE FATOS OU CIRCUNST�NCIAS IMPREVIS�VEIS ou alheias � sua vontade, e que importem em varia��o de seus custos operacionais, de modo a tornar este contrato excessivamente oneroso ou que resultem em desequil�brio contratual � PRESTADORA, como, por exemplo, o disposto na cl�usula 22� deste instrumento.</p>
			<p class='text'>Cl�usula 16� - O ATRASO NO PAGAMENTO ou o n�o-pagamento de qualquer das parcelas do pre�o da ades�o e/ou mensalidades em seu respectivo vencimento acarretar� a incid�ncia de multa de 2% (dois por cento) e de juros de mora praticados no mercado. A eventual toler�ncia da PRESTADORA com rela��o � dila��o do prazo para pagamento n�o ser� interpretada como nova��o contratual. A alega��o de n�o recebimento, pelo assinante, do documento de cobran�a n�o o eximir� da obriga��o de proceder ao pagamento na data de vencimento estabelecida e o atraso implicar� na aplica��o das penalidades previstas neste instrumento.</p>
			<p class='text'>Cl�usula 17� - Em caso de INADIMPLEMENTO, pelo n�o pagamento de qualquer parcela do pre�o da ades�o e/ou mensalidade na data de seu respectivo vencimento, o assinante ser� considerado inadimplente, podendo neste caso a PRESTADORA optar: (a) pela INTERRUP��O imediata do servi�o at� a efetiva quita��o do(s) d�bito(s), acrescidos dos encargos legais e contratualmente previstos; (b) pelo DESLIGAMENTO imediato do ponto de conex�o at� a efetiva quita��o do(s) d�bito(s) em atraso, acrescido(s) dos encargos legais e contratualmente previsto, cabendo ainda ao assinante o pagamento da taxa de servi�o vigente � �poca de seu re-ligamento, na hip�tese de liquida��o do d�bito. Em qualquer das hip�teses,ser� facultado � PRESTADORA proceder � SUSPENS�O DA PRESTA��O DE SERVI�OS ACESS�RIOS (assist�ncia t�cnica, etc.) at� efetiva quita��o do(s) d�bito(s) em atraso.</p>
			<p style='font-size:10.7px;margin-top:-5px'><b>Condi��es gerais da contrata��o:</b></p>
			<p class='text'>Cl�usula 18� - Reconhecendo que a PRESTADORA somente oferece os meios de transmiss�o, emiss�o e recep��o de informa��es multim�dia, o ASSINANTE a isenta de qualquer responsabilidade na hip�tese de interrup��o de suas atividades em decorr�ncia de FATO DE TERCEIRO, CASO FORTUITO OU FOR�A MAIOR, inclusive restri��es ou limita��es que lhe sejam impostas pelo Poder P�blicas, seja em car�ter eventual ou definitivo, ou, ainda, faltas ou quedas bruscas de energia; danos involunt�rios que exijam o desligamento tempor�rio do sistema em raz�o de reparos ou manuten��o de equipamentos; a interrup��o de sinais pelas fornecedoras de acesso � rede mundial; caracter�sticas t�cnicas dos aparelhos receptores do ASSINANTE que prejudiquem a recep��o do sinal; e outros tipos de limita��es t�cnicas ou intercorr�ncias alheias � vontade da PRESTADORA.</p>
			<p class='text'>Cl�usula 19� - O PRAZO DE INSTALA��O do SCM pela PRESTADORA � at� 10 dias, CONTADO da data em que o ASSINANTE DISPONIBILIZAR AS CONDI��ES F�SICAS DO IM�VEL para a instala��o do SCM, al�m de, sempre que necess�rio for, providenciar a autoriza��o do s�ndico do condom�nio ou dos demais cond�minos para liga��o do mencionado sistema. N�o sendo necess�ria a referida autoriza��o nem a realiza��o de obras, o prazo para a instala��o come�ar� a fluir a partir da data da ci�ncia, pela PRESTADORA, da ades�o firmada pelo ASSINANTE � proposta de servi�os.</p>
			<p class='text'>Cl�usula 20� - � DE RESPONSABILIDADE DE O ASSINANTE providenciar todas as obras necess�rias � disponibiliza��o das condi��es f�sicas do im�vel � instala��o do SCM, arcando com todos os custos dela decorrentes, cabendo ao ASSINANTE, outrossim, obter do s�ndico do condom�nio ou dos demais cond�minos, sempre que necess�rio for, a autoriza��o para liga��o dos sinais e para realiza��o das obras referidas.</p>
			<p class='text'>Par�grafo 1� - Os meios de transmiss�o e equipamentos colocados � disposi��o do ASSINANTE para acesso � internet devem ser utilizados exclusivamente para os fins e nos endere�os para os quais foram solicitados, n�o sendo permitido utiliz�-los para fins diversos ou ced�-los a terceiros.</p>
			<p class='text'>Cl�usula 21� - Em caso de problemas no sistema de acesso � internet em banda larga, a responsabilidade da PRESTADORA pela MANUTEN��O e FUNCIONAMENTO estar� limitada aos casos de acesso ao Servi�o de Comunica��o Multim�dia, uso regular dos aparelhos instalados, ficando, destarte, expressamente exclu�dos de tal garantia quaisquer servi�os ou reparos que se fa�am necess�rios em raz�o de m� ou inadequada utiliza��o dos equipamentos do sistema.</p>
			<p class='text'>Cl�usula 22� - Os servi�os de assist�ncia t�cnica ser�o realizados com EXCLUSIVIDADE pela PRESTADORA ou por assist�ncia t�cnica por ela autorizada, ficando EXPRESSAMENTE VEDADO ao ASSINANTE: (I) proceder qualquer altera��o na rede externa de distribui��o dos sinais, ou nos pontos de sua conex�o ao(s) aparelho(s) retransmissor (es); (II) permitir que qualquer pessoa n�o autorizada pela PRESTADORA manipule a rede externa, ou qualquer outro equipamento que as componha; (III) acoplar equipamento ao sistema de conex�o do SCM que permita a recep��o de servi�o n�o contratado pelo ASSINANTE com a PRESTADORA; (IV) disponibilizar atrav�s do servi�o de acesso � internet em banda larga contratado, servidores Web, e outros � terceiros, sem a anu�ncia da PRESTADORA. A PRESTADORA est� autorizada a efetuar, periodicamente, vistoria nos equipamentos, visando a sua manuten��o e funcionamento ideais;</p>
			<p class='text'>Par�grafo �nico: Quando efetuada a solicita��o de conserto pelo ASSINANTE, e as falhas n�o forem atribu�veis � PRESTADORA, tal solicita��o acarretar� cobran�a do valor referente � visita ocorrida, cabendo aqueles certificarem-se previamente do valor praticado, � �poca, pela PRESTADORA.</p>
			<p class='text'>Cl�usula 23� - A PRESTADORA ter� garantido o ACESSO e TR�NSITO, a qualquer tempo, nas depend�ncias do assinante onde esteja instalado o sistema do SCM, como forma de preserva��o das condi��es contratuais e da qualidade da presta��o do SCM. Na hip�tese de impedimento do exerc�cio deste direito, a PRESTADORA poder� proceder a suspens�o imediata da presta��o dos servi�os ou ainda a rescis�o do contrato, independentemente de qualquer procedimento judicial e sem preju�zo da cobran�a dos servi�os prestados.</p>
			<p class='text'>Cl�usula 24� - A PRESTADORA n�o fornecer� nenhum tipo de material/equipamento para o ASSINANTE tanto em car�ter de comodato quanto de venda. O ASSINANTE deve possuir todo material necess�rio para presta��o do SERVI�O, sendo de sua total responsabilidade quanto ao mal uso e/ou mal funcionamento dos equipamentos, devendo procurar seu fornecedor para eventuais trocas ou reparos.</p>
			<p class='text'>Cl�usula 25� - A(s) inclus�o (es) de outro(s) servi�o(s) disponibilizado(s) pela prestadora poder� (�o) ser solicitado(s) pelo ASSINANTE junto � PRESTADORA, a qualquer tempo, pelo que pagar� a respectiva taxa de servi�o, relativa � sua instala��o, e ser-lhe-� adicionado � mensalidade o valor correspondente ao ponto ou pontos adicionais, em conformidade com a tabela vigente � �poca em que for (em) pleiteado(s).</p>
			<p class='text'>Cl�usula 26� - Ocorrendo fatos imprevis�veis os quais acarretem ELEVA��O DOS CUSTOS OPERACIONAIS dos servi�os prestados pela PRESTADORA, como por exemplo, de aumento real no pre�o dos acessos � rede mundial, a institui��o de tributos, contribui��es ou outros encargos de qualquer natureza, que incidam ou venham a incidir sobre o objeto deste contrato, ou mesmo altera��es em suas al�quotas, al�m de outros fatos equivalentes que importem no desequil�brio econ�mico financeiro deste contrato, a PRESTADORA poder� aumentar a mensalidade paga pelo ASSINANTE em raz�o dos custos adicionais ora mencionada. Caso o aumento dos custos, por onerosidade excessiva, torne invi�vel a presta��o dos servi�os, e n�o permitindo a legisla��o vigente � �poca o referido aumento, fica assegurado � PRESTADORA a rescis�o do presente contrato, sem quaisquer �nus para a PRESTADORA, mediante pr�vio aviso de 30 (trinta) dias.</p>
			<p class='text'>Cl�usula 27� - O ASSINANTE, ap�s a quita��o do pre�o da ades�o e estando em dia com as mensalidades, ter� a faculdade de solicitar, por escrito, a ALTERA��O DE ENDERE�O para a transfer�ncia do local da ades�o para outro endere�o NA MESMA CIDADE, desde que haja possibilidade t�cnica de instala��o, especialmente de disponibilidade do servi�o no novo bairro indicado pelo ASSINANTE, onde se promover� a nova instala��o do sistema, respeitada �s velocidades de acesso � internet em banda larga dispon�veis, al�m dos prazos de instala��o ent�o fixados pela PRESTADORA, mediante o pagamento da taxa de servi�o vigente na data do pedido de transfer�ncia.</p>
			<p class='text'>Cl�usula 28� - Desde que o ASSINANTE esteja em dia com suas obriga��es contratuais, a PRESTADORA, ou quem esta indicar, prestar� ao ASSINANTE os servi�os de ASSIST�NCIA T�CNICA por ele solicitados, neste instrumento entendida como os servi�os especializados para atendimento auxiliar ao ASSINANTE, obedecida a tabela de pre�os praticada � �poca pela PRESTADORA. O ASSINANTE ter� sempre acesso � tabela de pre�os em vigor.</p>
			<p class='text'>Cl�usula 29� - O presente contrato vigorar� por 12 (doze) meses, a contar da data do ingresso do ASSINANTE no sistema.</p>
			<p class='text'>Cl�usula 30� - O presente contrato ficar� RESCINDIDO DE PLENO DIREITO caso: a) seja CANCELADA A AUTORIZA��O do SCM CONCEDIDA � PRESTADORA pelo �rg�o Federal competente, hip�tese em que a PRESTADORA ficar� isenta de qualquer �nus; b) por MANIFESTA��O ESCRITA do ASSINANTE que n�o tenha mais interesse na continuidade da assinatura, comunique � PRESTADORA sua decis�o, a qualquer tempo, devendo, cumprir integralmente com as obriga��es estabelecidas neste contrato, n�o acarretando, nesse caso, quaisquer �nus adicionais ao ASSINANTE; c) em raz�o da suspens�o do servi�o do assinante inadimplente, hip�tese em que o referido ASSINANTE N�O TER� DIREITO A RESTITUI��O de qualquer quantia at� ent�o paga, permanecendo respons�vel pelo pagamento dos valores em atraso, acrescidos dos encargos legais e contratuais aqui fixados; d) o endere�o indicado pelo assinante na proposta de ades�o para a instala��o do sistema N�O APRESENTE CONDI��ES T�CNICAS para conex�o do SCM operado pela PRESTADORA, hip�tese em que esta RESTITUIR� ao ASSINANTE as quantias eventualmente pagas pelo pre�o de ades�o, com corre��o monet�ria pelos mesmos �ndices adotados neste contrato, n�o acarretando � PRESTADORA quaisquer outros �nus adicionais;</p>
			<p class='text'>e) FALTA DE AUTORIZA��O pelo s�ndico do condom�nio em que ser� instalado o SCM, ou os demais cond�minos, para a instala��o do referido sistema no endere�o indicado, hip�tese em que a PRESTADORA DEVOLVER� ao ASSINANTE os valores do pre�o de ades�o, devidamente atualizados, pelo mesmo �ndice de atualiza��o previsto neste instrumento, n�o acarretando � PRESTADORA quaisquer outros �nus adicionais; f) se o ASSINANTE, em face deste contrato, por A��O OU OMISS�O, COMPROMETER A IMAGEM P�BLICA DA PRESTADORA; g) POR DETERMINA��O LEGAL, OU POR ORDEM EMANADA DA AUTORIDADE COMPETENTE que determine a suspens�o ou supress�o da presta��o dos servi�os objeto deste contrato, ou por pedido ou decreta��o de concordata ou fal�ncia do ASSINANTE; h) se o ASSINANTE UTILIZAR DE PR�TICAS QUE DESRESPEITEM A LEI, A MORAL, OS BONS COSTUMES, AINDA, CONTR�RIAS AOS USOS E COSTUMES CONSIDERADOS RAZO�VEIS E NORMALMENTE ACEITOS NO AMBIENTE DA INTERNET, tais como: INVADIR A PRIVACIDADE OU PREJUDICAR OUTROS MEMBROS DA COMUNIDADE INTERNET, tentar obter acesso ilegal a banco de dados da PRESTADORA e/ou de terceiros, alterar e/ou copiar arquivos ou, ainda, obter senhas e dados de terceiros sem pr�via autoriza��o, enviar mensagens coletivas de e-mail (spam e-mails) a grupos de usu�rios, ofertando produtos ou servi�os de qualquer natureza, que n�o sejam de interesse dos destinat�rios ou que n�o tenham consentimento expresso deste; i) se o desrespeitar as leis de direitos autorais e de propriedade intelectual;</p>
			<p class='text'>Cl�usula 31� - EM QUALQUER DAS HIP�TESES DE RESCIS�O CONTRATUAL, o assinante dever� RESTITUIR � PRESTADORA, em sua sede, OS EQUIPAMENTOS e bens que lhe haviam sido entregues em regime de comodato, no prazo m�ximo de 15 dias, contados da data da rescis�o. Caso n�o o fa�a, ser� o assinante constitu�do em mora, devendo responder por ela, al�m da obriga��o de pagar a mensalidade durante o tempo de atraso no cumprimento da obriga��o prevista nesta cl�usula.</p>
			<p class='text'>Cl�usula 32� - A n�o utiliza��o dos direitos e prerrogativas previstos neste contrato por qualquer das partes N�O IMPORTAR� EM NOVA��O CONTRATUAL OU REN�NCIA DE DIREITOS nele estabelecidos, podendo a parte interessada, a qualquer tempo, e a seu crit�rio exerc�-los. </p>
			<p class='text'>Cl�usula 33� - A PRESTADORA poder� ampliar agregar outros servi�os e introduzir MODIFICA��ES NO PRESENTE CONTRATO, mediante registro em Cart�rio ou de Aditivo contratual e no sistema operacional, com comunica��o escrita ou mensagens lan�adas no documento de cobran�a mensal, o que ser� dado como recebido e aceito pelo assinante pela simples pr�tica posterior de atos ou ocorr�ncias de fatos configurativos de sua ades�o ou perman�ncia no SCM, sendo ainda aplic�veis, automaticamente, a todas as disposi��es deste contrato, todos os atos do poder concernente publicados na imprensa oficial e que digam respeito aos servi�os ofertados no presente contrato.</p>
			<p class='text'>Cl�usula 34� - O presente contrato OBRIGA AS PARTES e seus SUCESSORES, os quais devem cumprir fiel e integralmente dos termos da aven�a, pelo prazo em que estiver em vigor, permanecendo em vigor, outrossim, todas as cl�usulas e obriga��es firmadas entre as partes, reservando-se ainda a PRESTADORA o direito de ceder e transferir a terceiros, total ou parcialmente, independentemente de notifica��o pr�via, os direitos e obriga��es assumidas atrav�s deste instrumento.</p>
			<p class='text'>Cl�usula 35� - A PRESTADORA indica ao assinante o endere�o da Ag�ncia Nacional de Telecomunica��es - Anatel, cuja sede encontra-se em Bras�lia-DF, SAUS Quadra 06 Blocos E e H, CEP 70.070-940, bem como, o endere�o eletr�nico www.anatel.gov.br. A biblioteca da Anatel localiza-se na sede, em Bras�lia, no Bloco F - T�rreo, onde os assinantes poder�o encontrar c�pia do regulamento do Servi�o de Comunica��o Multim�dia. Informa ainda, o telefone da central de atendimento da Anatel 133, bem como seu telefone da central de atendimento ao cliente 0800 603 5025 e seu endere�o na internet www.connectms.com.br</p>
			<p class='text'>Cl�usula 36� - As partes elegem o FORO da comarca de Campo Grande/MS, para dirimir as controv�rsias porventura oriundas deste contrato, com expressa ren�ncia a qualquer outro, por mais privilegiado que seja.</p>
			<p class='text'>Campo Grande/MS, 25 de JULHO de 2012.</p>
			<br />
			<p>_________________________________________________</p>
			<p style='font-size:10.5px;margin-top:-14px'>Deibi Carlos Delatori<p>
			<p style='font-size:10.5px;margin-top:-14px'>DIGITAL TECH INFORMATIC� LTDA ME</p>
			
		</div>
	</body>
</html>