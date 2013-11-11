<?
 function getNomeMes(){
  $mesAux = array("","Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outobro","Novembro","Dezembro");
  $dia = date("d");
  $mes = $mesAux[(int) date("m")];
  $ano = date("Y");
  
  return $dia." de ".$mes." de ".$ano;
 }
 function getNomeDia(){
  $diaAux = array("Domingo","Segunda-Feira","Terça-Feira","Quarta-Feira","Quinta-Feira","Sexta-Feira","Sabado");
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
				<p style='margin-top: -9; font-size: 11.3px'><b><u>CONTRATO DE PRESTAÇÃO DE CONEXÃO E ACESSO À INTERNET</u></b></p>
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
					  <td class='td1'>&nbsp; Endereço:</td>
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
					  <td colspan='1' class='td1' style='width: 906px'> &nbsp;&nbsp;Designação nominal do plano de acesso contratado: </td>
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
					<td class='td1' colspan='1'>&nbsp; Equipamentos para ativação do serviço: </td>
					<td class='td2'></td>
				</tr>
				<tr>
					<td class='td1' colspan='1'>&nbsp; Data de Ativação: Nas 72 horas subsequentes no prazo normal.</td>
					<td class='td2'></td>
				</tr>
			</table>
			<p style='font-size:10.7px'><b>AS PARTES</b> a seguir qualificadas:</p>
			<p class='text'>I - <b>PRESTADORA</b>: DIGITAL TECH INFORMÁTICA LTDA ME, CNPJ n.º08.929.889/0001-06, 
				com sede na R. Salomão Abdala, 1020 - Jardim Itamaracá CEP 79062-220 - Campo Grande/MS, 
				representada por seu sócio, <b>Deibi Carlos Delatori</b>, brasileiro, casado, empresário,
				portador da identidade RG nº 1384453 SSP/MS e CPF nº 008.963.951-03, denominada simplesmente <b>CONTRATADA</b>,
				com outorga da <b>Anatel</b>, por meio do Ato de Autorização n.º, 1.965 de 31/03/2011 e Termo de Autorização n.º PVST/SPV 195/2011,
				conforme consta do Processo n.º 53500.019268/2010, doravante simplesmente designada “DIGITALNET” e
			</p>
			<p style='margin-top:-6px'></p>
			<p class='text'>II - ASSINANTE: Pessoa física ou jurídica, Identificada conforme TERMO DE ADESÃO, 
				parte integrante deste contrato, mediante comprovação eletrônica através do site http://www.dcdms.com.br/termodeadesao. 
				Têm entre si o presente CONTRATO DE PRESTAÇÃO DE SERVIÇOS DE COMUNICAÇÃO MULTIMÍDIA, 
				fornecidos pela PRESTADORA qualificada acima, e o ASSINANTE, identificado no TERMO DE ADESÃO, 
				o qual será regido pelas cláusulas a seguir, levando-se em consideração, ainda,
				na interpretação do contrato, as definições abaixo relacionadas, 
				utilizadas para a perfeita compreensão dos termos adotados neste ajuste:
			</p>
			<p class='text'>a) PRESTADORA: pessoa jurídica que mediante autorização presta o SCM;</p>
			<p class='text'>b) ASSINANTE: é a pessoa natural ou jurídica que possui vínculo contratual com a prestadora para fruição do SCM, segundo os termos e condições estabelecidas no presente contrato;</p>
			<p class='text'>c) INFORMAÇÕES MULTIMÍDIA: sinais de áudio, vídeo, dados, voz e outros sons e imagens, textos e outras informações de qualquer natureza;</p>
			<p class='text'>d) SERVIÇO DE COMUNICAÇÃO MULTIMÍDIA: é um serviço fixo de telecomunicações de interesse coletivo, prestado em âmbito nacional e internacional, no regime privado, 
				que possibilita a oferta de capacidade de transmissão, emissão e recepção de informações multimídia, utilizando quaisquer meios, a assinantes dentro de uma área de prestação de serviço.</p>
			<p class='text'>e) INTERCONEXÃO: ligação entre redes de telecomunicações funcionalmente compatíveis, de modo que os usuários de serviços de uma das redes possam se comunicar com usuários de serviço de outra ou acessar serviços nela disponíveis;</p>
			<p class='text'>f) MENSALIDADE: é a quantia devida pelo assinante à PRESTADORA, mensalmente, pela transmissão, emissão e recepção de informações multimídia, conforme tabela da
				PRESTADORA que variará de acordo com o pacote escolhido, e, conforme o caso, com outras modalidades de serviços solicitados pelo assinante;</p>
			<p class='text'>g) SERVIÇO DE ACESSO A INTERNET: compreende o fornecimento, instalação e manutenção dos meios de transmissão necessários para prestação do serviço de acesso à internet em banda larga, através dos provedores de acesso habilitados, desde o Ponto Principal de instalação, indicado pelo ASSINANTE, até a infra-estrutura que integra o ambiente da PRESTADORA;</p>
			<p class='text'>h) CARACTERÍSTICAS BÁSICAS DO PRODUTO: o serviço de acesso à internet em banda larga consiste no provimento de canais de transmissão de dados, áudio e vídeo, utilizando-se dos meios de acesso disponíveis: a) Acesso discado (linha telefônica); b) Acesso sem fio via rádio digital (Wi-fi 802.11x); c) Acesso via ADSL; d) Acesso via cable modem; e) Acesso via circuito dedicado de alta velocidade;</p>
			<p class='text'>I - O serviço de acesso a Internet será prestado em faixas de velocidade, conforme escolha do ASSINANTE,</p>
			<p class='text'>II - Para configurar o serviço de acesso à internet em banda larga, será atribuído pelo provedor via Rede IP, um endereço IP fixo ou dinâmico, em razão do serviço contratado.</p>
			<p class='text'style='font-size:10.7px'><b>Do Objeto: </b></p>
			<p class='text'>Cláusula 1ª - Este contrato tem por OBJETO a aquisição, pelo ASSINANTE, do direito de acesso ao Serviço de Comunicação Multimídia e outros serviços ofertados pela PRESTADORA, na localidade anteriormente indicada no TERMO DE ADESÃO onde a PRESTADORA detém a autorização e mediante o pagamento pactuado, e, adicionalmente, do pagamento das mensalidades indicadas na referida proposta, no período em que vigorar o presente contrato, pela recepção dos serviços escolhidos pelo ASSINANTE quando da formulação da proposta de adesão.</p>
			<p class='text'>Cláusula 2ª - Além do pacote de serviço escolhido, constituem MODALIDADES DO SERVIÇO DE COMUNICAÇÃO MULTIMÍDIA, e desde que disponibilizados pela PRESTADORA, poderá o assinante solicitá-los, mediante o pagamento da respectiva taxa de serviço, além do valor da mensalidade e/ou preço correspondente às modalidades solicitadas, as quais poderão ser canceladas a qualquer tempo pelo ASSINANTE, sem ônus e, em caso de re-ligação ou reabilitação do serviço, ficará o ASSINANTE responsável pelo pagamento de nova taxa de serviço/adesão: 1) a aquisição de programas pagos individualmente pelo ASSINANTE em horário previamente programado pela PRESTADORA (pay-per-view); e 2) serviços especializados de informações meteorológicas, bancárias, financeiras, culturais, de preços e outros que possam ser oferecidos aos ASSINANTES do SCM.Do pacote de serviços e suas alterações:</p>
			<p class='text'>Cláusula 3ª - A escolha do pacote de SCM e da faixa de velocidade de acesso à internet em banda larga selecionada poderá ser alterada pelo ASSINANTE a qualquer tempo, por outro pacote ou faixa de velocidade de sua escolha desde que disponível pela PRESTADORA à época da substituição, e, nesse caso, ficará o ASSINANTE responsável pelo pagamento da taxa de serviço respectiva, de acordo com a tabela de preços vigente à época, adequando-se, ainda, o preço da mensalidade respectiva, para, reduzi-lo ou aumentá-lo, conforme a nova opção do pacote ou velocidade de acesso escolhidos. As condições desta nova opção serão fixadas através de nova relação contratual, nos termos das condições gerais vigentes à época da alteração.</p>
			<p class='text' style='font-size:10.7px'><b>Dos parâmetros de qualidade</b></p>
			<p class='text'>Cláusula 4ª - São parâmetros de qualidade para o SCM, sem prejuízo de outros que venham a ser definidos pela ANATEL:</p>
			<p class='text'>I – Fornecimento de sinais respeitando as características estabelecidas na regulamentação;</p>
			<p class='text'>II – disponibilidade dos serviços nos índices contratados;</p>
			<p class='text'>III – emissão de sinais eletromagnéticos nos níveis estabelecidos em regulamentação;</p>
			<p class='text'>IV – divulgação de informação aos seus assinantes, de forma inequívoca, ampla e com antecedência razoável, quanto a alterações de preços e condições de fruição do serviço;</p>
			<p class='text'>V – rapidez no atendimento às solicitações e reclamações dos assinantes;</p>
			<p class='text'>VI – número de reclamações contra a prestadora;</p>
			<p class='text'>VII – fornecimento das informações necessárias à obtenção dos indicadores de qualidade do serviço, de planta, bem como os econômico-financeiros, de forma a possibilitar a avaliação da qualidade na prestação do serviço.</p>
			<p style='font-size:10.7px;margin-top:-5px'><b>Dos Direitos e Obrigações da prestadora</b></p>
			<p style='font-size:10.7px;margin-top:-5px'>Cláusula 5ª - Constituem direitos da prestadora, além dos previstos na Lei 9.472/97, na regulamentação pertinente e os discriminados no termo de autorização para prestação do serviço:</p>
			<p class='text'>I – empregar equipamentos e infra-estrutura que não lhe pertençam;</p>
			<p class='text'>II – contratar com terceiros o desenvolvimento de atividades inerentes, acessórias ou complementares ao serviço.</p>
			<p class='text'>Parágrafo primeiro: A prestadora, em qualquer caso, continuará responsável perante a ANATEL e os assinantes pela prestação e execução dos serviços.</p>
			<p class='text'>Cláusula 6ª - Face as reclamações e dúvidas dos assinantes a prestadora deve fornecer imediato esclarecimento e sanar o problema com a maior brevidade possível;</p>
			<p class='text'>Cláusula 7ª - Em caso de interrupção ou degradação da qualidade do serviço, a prestadora deve descontar da assinatura o valor proporcional ao número de horas ou fração superior a trinta minutos.
							Parágrafo Primeiro – A necessidade de interrupção ou degradação do serviço por motivo de manutenção, ampliação da rede ou similares deverá ser amplamente comunicada aos assinantes que serão afetados, com antecedência mínima de uma semana, devendo os mesmos terem um desconto na assinatura à razão de 1/30 (um trinta avos) por dia ou fração superior a quatro horas.</p>
			<p class='text'>Parágrafo Segundo – A prestadora não será obrigada a efetuar o desconto se a interrupção ou degradação do serviço ocorrer por motivos de caso fortuito ou força maior, cabendo-lhe o ônus da prova.</p>
			<p class='text'>Cláusula 8ª - Sem prejuízo no disposto na legislação aplicável, as prestadoras de SCM têm a obrigação de:</p>
			<p class='text'>I – não recusar o atendimento a pessoas cujas dependências estejam localizadas na área de prestação do serviço, nem impor condições discriminatórias, salvo nos casos em que a pessoa se encontrar em área geográfica ainda não atendida pela rede;</p>
			<p class='text'>II – tornar disponíveis ao assinante, com antecedência razoável, informações relativas a preços, condições de fruição dos serviços, bem como suas alterações;</p>
			<p class='text'>III – descontar da assinatura o equivalente ao número de horas ou fração superior a trinta minutos de serviço interrompido ou degradado com relação ao total médio de horas de capacidade contratada;</p>
			<p class='text'>IV – prestar esclarecimentos ao assinante, de pronto e livre de ônus, face as suas reclamações relativas à fruição dos serviços;</p>
			<p class='text'>IV – observar os parâmetros de qualidade estabelecidos na regulamentação e no contrato celebrado com o assinante, pertinentes à prestação do serviço.</p>
			<p class='text'>Cláusula 9ª - A prestadora observará o dever de zelar estritamente pelo sigilo inerente aos serviços de telecomunicações e pela confidencialidade quanto aos dados e informações do assinante, empregando todos os meios e tecnologias necessárias para assegurar este direito dos usuários;
				Parágrafo único – A prestadora tornarão disponíveis os dados referentes à suspensão de sigilo de telecomunicações para a autoridade judiciária ou legalmente investida desses poderes que determinar a suspensão do sigilo.</p>
			<p style='font-size:10.7px;margin-top:-5px'><b>Dos direitos e deveres do assinante</b></p>
			<p style='margin-top:-6px'></p>
			<p class='text'>Cláusula 10ª - O assinante do SCM tem direito, sem prejuízo do disposto na legislação aplicável:</p>
			<p class='text'>I – de acesso ao serviço, mediante contratação junto a uma prestadora;</p>
			<p class='text'>II – à liberdade de escolha da prestadora;</p>
			<p class='text'>III – ao tratamento não discriminatório quanto às condições de acesso e fruição do serviço;</p>
			<p class='text'>IV – à informação adequada sobre condições de prestação do serviço;</p>
			<p class='text'>V – ao conhecimento prévio das condições de suspensão dos serviços, exceto quando independer da vontade da prestadora;</p>
			<p class='text'>VI – ao recebimento do documento de cobrança com discriminação dos valores cobrados;</p>
			<p class='text'>VII - comunicar à prestadora, através da Central de Atendimento, toda e qualquer irregularidade ou mau funcionamento do Serviço ou fato nocivo à segurança, relacionado à prestação do Serviço, visando possibilitar a adequada assistência e/ou orientação pela prestadora;</p>
			<p class='text'>VIII - Manter atualizados os seus dados cadastrais com a prestadora, informando-a sobre toda e qualquer modificação, seja de endereço, administrador do contrato, controle societário, dentre outros.</p>
			<p class='text'>Cláusula 11ª - É proibido ao assinante ceder, transferir ou disponibilizar a prestação de serviço de comunicação multimídia – SCM, contratado com a PRESTADORA a terceiros, quer seja por cabo, rádio ou qualquer outro meio de transmissão, sob pena de rescisão do presente contrato, bem como, a obrigação do assinante de ressarcir à PRESTADORA os serviços não tarifados, as perdas e danos e os lucros cessantes.</p>
			<p style='font-size:10.7px;margin-top:-5px'><b>Dos Preços de Adesão, Mensalidades e Reajustes:</b></p>
			<p style='margin-top:-6px'></p>
			<p class='text'>Cláusula 12ª - Pelo direito de acesso ao SCM e da faixa de velocidade de acesso à internet em banda larga, o ASSINANTE pagará à PRESTADORA, O PREÇO PREVIAMENTE AJUSTADO, nas condições nela indicadas. O ASSINANTE deverá efetuar os pagamentos das mensalidades através de documento de cobrança emitido pela PRESTADORA, em estabelecimento bancário ou outra instituição autorizada prévia e expressamente por esta última.</p>
			<p class='text'>Cláusula 13ª - Pelo pacote de serviços e pela faixa de velocidade de acesso à internet em banda larga escolhidos, o ASSINANTE pagará à PRESTADORA a MENSALIDADE estipulada na proposta de adesão, mediante documento de cobrança emitido mensalmente pela PRESTADORA e remetido ao ASSINANTE. Os valores referentes à mensalidade são pré-estabelecidos, não sendo aceito qualquer outro valor que não o impresso na tabela de preços da PRESTADORA.</p>
			<p class='text'>Cláusula 14ª - AS MENSALIDADES DEVERÃO SER PAGAS nas datas de vencimento indicadas na proposta de adesão, sendo o pagamento sempre referente ao mês já utilizado, independente da efetiva utilização do serviço, ou seja, independente do número de horas utilizadas, salvo quando enquadrado na Cláusula 7ª Parágrafo 1º.</p>
			<p class='text'>Cláusula 15ª - O VALOR da mensalidade será REAJUSTADO, após doze meses contados da data da assinatura da proposta de adesão, com base na variação do Índice Geral de Preços - IGP-M, divulgado pela Fundação Getúlio Vargas, ou, no caso de sua extinção ou da inexistência de sua divulgação, por outro índice que melhor reflita a perda do poder aquisitivo da moeda nacional ocorrida no período. Outrossim, será lícito à PRESTADORA REAJUSTAR A MENSALIDADE EM DECORRÊNCIA DE FATOS OU CIRCUNSTÂNCIAS IMPREVISÍVEIS ou alheias à sua vontade, e que importem em variação de seus custos operacionais, de modo a tornar este contrato excessivamente oneroso ou que resultem em desequilíbrio contratual à PRESTADORA, como, por exemplo, o disposto na cláusula 22ª deste instrumento.</p>
			<p class='text'>Cláusula 16ª - O ATRASO NO PAGAMENTO ou o não-pagamento de qualquer das parcelas do preço da adesão e/ou mensalidades em seu respectivo vencimento acarretará a incidência de multa de 2% (dois por cento) e de juros de mora praticados no mercado. A eventual tolerância da PRESTADORA com relação à dilação do prazo para pagamento não será interpretada como novação contratual. A alegação de não recebimento, pelo assinante, do documento de cobrança não o eximirá da obrigação de proceder ao pagamento na data de vencimento estabelecida e o atraso implicará na aplicação das penalidades previstas neste instrumento.</p>
			<p class='text'>Cláusula 17ª - Em caso de INADIMPLEMENTO, pelo não pagamento de qualquer parcela do preço da adesão e/ou mensalidade na data de seu respectivo vencimento, o assinante será considerado inadimplente, podendo neste caso a PRESTADORA optar: (a) pela INTERRUPÇÃO imediata do serviço até a efetiva quitação do(s) débito(s), acrescidos dos encargos legais e contratualmente previstos; (b) pelo DESLIGAMENTO imediato do ponto de conexão até a efetiva quitação do(s) débito(s) em atraso, acrescido(s) dos encargos legais e contratualmente previsto, cabendo ainda ao assinante o pagamento da taxa de serviço vigente à época de seu re-ligamento, na hipótese de liquidação do débito. Em qualquer das hipóteses,será facultado à PRESTADORA proceder à SUSPENSÃO DA PRESTAÇÃO DE SERVIÇOS ACESSÓRIOS (assistência técnica, etc.) até efetiva quitação do(s) débito(s) em atraso.</p>
			<p style='font-size:10.7px;margin-top:-5px'><b>Condições gerais da contratação:</b></p>
			<p class='text'>Cláusula 18ª - Reconhecendo que a PRESTADORA somente oferece os meios de transmissão, emissão e recepção de informações multimídia, o ASSINANTE a isenta de qualquer responsabilidade na hipótese de interrupção de suas atividades em decorrência de FATO DE TERCEIRO, CASO FORTUITO OU FORÇA MAIOR, inclusive restrições ou limitações que lhe sejam impostas pelo Poder Públicas, seja em caráter eventual ou definitivo, ou, ainda, faltas ou quedas bruscas de energia; danos involuntários que exijam o desligamento temporário do sistema em razão de reparos ou manutenção de equipamentos; a interrupção de sinais pelas fornecedoras de acesso à rede mundial; características técnicas dos aparelhos receptores do ASSINANTE que prejudiquem a recepção do sinal; e outros tipos de limitações técnicas ou intercorrências alheias à vontade da PRESTADORA.</p>
			<p class='text'>Cláusula 19ª - O PRAZO DE INSTALAÇÃO do SCM pela PRESTADORA é até 10 dias, CONTADO da data em que o ASSINANTE DISPONIBILIZAR AS CONDIÇÕES FÍSICAS DO IMÓVEL para a instalação do SCM, além de, sempre que necessário for, providenciar a autorização do síndico do condomínio ou dos demais condôminos para ligação do mencionado sistema. Não sendo necessária a referida autorização nem a realização de obras, o prazo para a instalação começará a fluir a partir da data da ciência, pela PRESTADORA, da adesão firmada pelo ASSINANTE à proposta de serviços.</p>
			<p class='text'>Cláusula 20ª - É DE RESPONSABILIDADE DE O ASSINANTE providenciar todas as obras necessárias à disponibilização das condições físicas do imóvel à instalação do SCM, arcando com todos os custos dela decorrentes, cabendo ao ASSINANTE, outrossim, obter do síndico do condomínio ou dos demais condôminos, sempre que necessário for, a autorização para ligação dos sinais e para realização das obras referidas.</p>
			<p class='text'>Parágrafo 1º - Os meios de transmissão e equipamentos colocados à disposição do ASSINANTE para acesso à internet devem ser utilizados exclusivamente para os fins e nos endereços para os quais foram solicitados, não sendo permitido utilizá-los para fins diversos ou cedê-los a terceiros.</p>
			<p class='text'>Cláusula 21ª - Em caso de problemas no sistema de acesso à internet em banda larga, a responsabilidade da PRESTADORA pela MANUTENÇÃO e FUNCIONAMENTO estará limitada aos casos de acesso ao Serviço de Comunicação Multimídia, uso regular dos aparelhos instalados, ficando, destarte, expressamente excluídos de tal garantia quaisquer serviços ou reparos que se façam necessários em razão de má ou inadequada utilização dos equipamentos do sistema.</p>
			<p class='text'>Cláusula 22ª - Os serviços de assistência técnica serão realizados com EXCLUSIVIDADE pela PRESTADORA ou por assistência técnica por ela autorizada, ficando EXPRESSAMENTE VEDADO ao ASSINANTE: (I) proceder qualquer alteração na rede externa de distribuição dos sinais, ou nos pontos de sua conexão ao(s) aparelho(s) retransmissor (es); (II) permitir que qualquer pessoa não autorizada pela PRESTADORA manipule a rede externa, ou qualquer outro equipamento que as componha; (III) acoplar equipamento ao sistema de conexão do SCM que permita a recepção de serviço não contratado pelo ASSINANTE com a PRESTADORA; (IV) disponibilizar através do serviço de acesso à internet em banda larga contratado, servidores Web, e outros à terceiros, sem a anuência da PRESTADORA. A PRESTADORA está autorizada a efetuar, periodicamente, vistoria nos equipamentos, visando a sua manutenção e funcionamento ideais;</p>
			<p class='text'>Parágrafo único: Quando efetuada a solicitação de conserto pelo ASSINANTE, e as falhas não forem atribuíveis à PRESTADORA, tal solicitação acarretará cobrança do valor referente à visita ocorrida, cabendo aqueles certificarem-se previamente do valor praticado, à época, pela PRESTADORA.</p>
			<p class='text'>Cláusula 23ª - A PRESTADORA terá garantido o ACESSO e TRÂNSITO, a qualquer tempo, nas dependências do assinante onde esteja instalado o sistema do SCM, como forma de preservação das condições contratuais e da qualidade da prestação do SCM. Na hipótese de impedimento do exercício deste direito, a PRESTADORA poderá proceder a suspensão imediata da prestação dos serviços ou ainda a rescisão do contrato, independentemente de qualquer procedimento judicial e sem prejuízo da cobrança dos serviços prestados.</p>
			<p class='text'>Cláusula 24ª - A PRESTADORA não fornecerá nenhum tipo de material/equipamento para o ASSINANTE tanto em caráter de comodato quanto de venda. O ASSINANTE deve possuir todo material necessário para prestação do SERVIÇO, sendo de sua total responsabilidade quanto ao mal uso e/ou mal funcionamento dos equipamentos, devendo procurar seu fornecedor para eventuais trocas ou reparos.</p>
			<p class='text'>Cláusula 25ª - A(s) inclusão (es) de outro(s) serviço(s) disponibilizado(s) pela prestadora poderá (ão) ser solicitado(s) pelo ASSINANTE junto à PRESTADORA, a qualquer tempo, pelo que pagará a respectiva taxa de serviço, relativa à sua instalação, e ser-lhe-á adicionado à mensalidade o valor correspondente ao ponto ou pontos adicionais, em conformidade com a tabela vigente à época em que for (em) pleiteado(s).</p>
			<p class='text'>Cláusula 26ª - Ocorrendo fatos imprevisíveis os quais acarretem ELEVAÇÃO DOS CUSTOS OPERACIONAIS dos serviços prestados pela PRESTADORA, como por exemplo, de aumento real no preço dos acessos à rede mundial, a instituição de tributos, contribuições ou outros encargos de qualquer natureza, que incidam ou venham a incidir sobre o objeto deste contrato, ou mesmo alterações em suas alíquotas, além de outros fatos equivalentes que importem no desequilíbrio econômico financeiro deste contrato, a PRESTADORA poderá aumentar a mensalidade paga pelo ASSINANTE em razão dos custos adicionais ora mencionada. Caso o aumento dos custos, por onerosidade excessiva, torne inviável a prestação dos serviços, e não permitindo a legislação vigente à época o referido aumento, fica assegurado à PRESTADORA a rescisão do presente contrato, sem quaisquer ônus para a PRESTADORA, mediante prévio aviso de 30 (trinta) dias.</p>
			<p class='text'>Cláusula 27ª - O ASSINANTE, após a quitação do preço da adesão e estando em dia com as mensalidades, terá a faculdade de solicitar, por escrito, a ALTERAÇÃO DE ENDEREÇO para a transferência do local da adesão para outro endereço NA MESMA CIDADE, desde que haja possibilidade técnica de instalação, especialmente de disponibilidade do serviço no novo bairro indicado pelo ASSINANTE, onde se promoverá a nova instalação do sistema, respeitada às velocidades de acesso à internet em banda larga disponíveis, além dos prazos de instalação então fixados pela PRESTADORA, mediante o pagamento da taxa de serviço vigente na data do pedido de transferência.</p>
			<p class='text'>Cláusula 28ª - Desde que o ASSINANTE esteja em dia com suas obrigações contratuais, a PRESTADORA, ou quem esta indicar, prestará ao ASSINANTE os serviços de ASSISTÊNCIA TÉCNICA por ele solicitados, neste instrumento entendida como os serviços especializados para atendimento auxiliar ao ASSINANTE, obedecida a tabela de preços praticada à época pela PRESTADORA. O ASSINANTE terá sempre acesso à tabela de preços em vigor.</p>
			<p class='text'>Cláusula 29ª - O presente contrato vigorará por 12 (doze) meses, a contar da data do ingresso do ASSINANTE no sistema.</p>
			<p class='text'>Cláusula 30ª - O presente contrato ficará RESCINDIDO DE PLENO DIREITO caso: a) seja CANCELADA A AUTORIZAÇÃO do SCM CONCEDIDA à PRESTADORA pelo órgão Federal competente, hipótese em que a PRESTADORA ficará isenta de qualquer ônus; b) por MANIFESTAÇÃO ESCRITA do ASSINANTE que não tenha mais interesse na continuidade da assinatura, comunique à PRESTADORA sua decisão, a qualquer tempo, devendo, cumprir integralmente com as obrigações estabelecidas neste contrato, não acarretando, nesse caso, quaisquer ônus adicionais ao ASSINANTE; c) em razão da suspensão do serviço do assinante inadimplente, hipótese em que o referido ASSINANTE NÃO TERÁ DIREITO A RESTITUIÇÃO de qualquer quantia até então paga, permanecendo responsável pelo pagamento dos valores em atraso, acrescidos dos encargos legais e contratuais aqui fixados; d) o endereço indicado pelo assinante na proposta de adesão para a instalação do sistema NÃO APRESENTE CONDIÇÕES TÉCNICAS para conexão do SCM operado pela PRESTADORA, hipótese em que esta RESTITUIRÁ ao ASSINANTE as quantias eventualmente pagas pelo preço de adesão, com correção monetária pelos mesmos índices adotados neste contrato, não acarretando à PRESTADORA quaisquer outros ônus adicionais;</p>
			<p class='text'>e) FALTA DE AUTORIZAÇÃO pelo síndico do condomínio em que será instalado o SCM, ou os demais condôminos, para a instalação do referido sistema no endereço indicado, hipótese em que a PRESTADORA DEVOLVERÁ ao ASSINANTE os valores do preço de adesão, devidamente atualizados, pelo mesmo índice de atualização previsto neste instrumento, não acarretando à PRESTADORA quaisquer outros ônus adicionais; f) se o ASSINANTE, em face deste contrato, por AÇÃO OU OMISSÃO, COMPROMETER A IMAGEM PÚBLICA DA PRESTADORA; g) POR DETERMINAÇÃO LEGAL, OU POR ORDEM EMANADA DA AUTORIDADE COMPETENTE que determine a suspensão ou supressão da prestação dos serviços objeto deste contrato, ou por pedido ou decretação de concordata ou falência do ASSINANTE; h) se o ASSINANTE UTILIZAR DE PRÁTICAS QUE DESRESPEITEM A LEI, A MORAL, OS BONS COSTUMES, AINDA, CONTRÁRIAS AOS USOS E COSTUMES CONSIDERADOS RAZOÁVEIS E NORMALMENTE ACEITOS NO AMBIENTE DA INTERNET, tais como: INVADIR A PRIVACIDADE OU PREJUDICAR OUTROS MEMBROS DA COMUNIDADE INTERNET, tentar obter acesso ilegal a banco de dados da PRESTADORA e/ou de terceiros, alterar e/ou copiar arquivos ou, ainda, obter senhas e dados de terceiros sem prévia autorização, enviar mensagens coletivas de e-mail (spam e-mails) a grupos de usuários, ofertando produtos ou serviços de qualquer natureza, que não sejam de interesse dos destinatários ou que não tenham consentimento expresso deste; i) se o desrespeitar as leis de direitos autorais e de propriedade intelectual;</p>
			<p class='text'>Cláusula 31ª - EM QUALQUER DAS HIPÓTESES DE RESCISÃO CONTRATUAL, o assinante deverá RESTITUIR à PRESTADORA, em sua sede, OS EQUIPAMENTOS e bens que lhe haviam sido entregues em regime de comodato, no prazo máximo de 15 dias, contados da data da rescisão. Caso não o faça, será o assinante constituído em mora, devendo responder por ela, além da obrigação de pagar a mensalidade durante o tempo de atraso no cumprimento da obrigação prevista nesta cláusula.</p>
			<p class='text'>Cláusula 32ª - A não utilização dos direitos e prerrogativas previstos neste contrato por qualquer das partes NÃO IMPORTARÁ EM NOVAÇÃO CONTRATUAL OU RENÚNCIA DE DIREITOS nele estabelecidos, podendo a parte interessada, a qualquer tempo, e a seu critério exercê-los. </p>
			<p class='text'>Cláusula 33ª - A PRESTADORA poderá ampliar agregar outros serviços e introduzir MODIFICAÇÕES NO PRESENTE CONTRATO, mediante registro em Cartório ou de Aditivo contratual e no sistema operacional, com comunicação escrita ou mensagens lançadas no documento de cobrança mensal, o que será dado como recebido e aceito pelo assinante pela simples prática posterior de atos ou ocorrências de fatos configurativos de sua adesão ou permanência no SCM, sendo ainda aplicáveis, automaticamente, a todas as disposições deste contrato, todos os atos do poder concernente publicados na imprensa oficial e que digam respeito aos serviços ofertados no presente contrato.</p>
			<p class='text'>Cláusula 34ª - O presente contrato OBRIGA AS PARTES e seus SUCESSORES, os quais devem cumprir fiel e integralmente dos termos da avença, pelo prazo em que estiver em vigor, permanecendo em vigor, outrossim, todas as cláusulas e obrigações firmadas entre as partes, reservando-se ainda a PRESTADORA o direito de ceder e transferir a terceiros, total ou parcialmente, independentemente de notificação prévia, os direitos e obrigações assumidas através deste instrumento.</p>
			<p class='text'>Cláusula 35ª - A PRESTADORA indica ao assinante o endereço da Agência Nacional de Telecomunicações - Anatel, cuja sede encontra-se em Brasília-DF, SAUS Quadra 06 Blocos E e H, CEP 70.070-940, bem como, o endereço eletrônico www.anatel.gov.br. A biblioteca da Anatel localiza-se na sede, em Brasília, no Bloco F - Térreo, onde os assinantes poderão encontrar cópia do regulamento do Serviço de Comunicação Multimídia. Informa ainda, o telefone da central de atendimento da Anatel 133, bem como seu telefone da central de atendimento ao cliente 0800 603 5025 e seu endereço na internet www.connectms.com.br</p>
			<p class='text'>Cláusula 36ª - As partes elegem o FORO da comarca de Campo Grande/MS, para dirimir as controvérsias porventura oriundas deste contrato, com expressa renúncia a qualquer outro, por mais privilegiado que seja.</p>
			<p class='text'>Campo Grande/MS, 25 de JULHO de 2012.</p>
			<br />
			<p>_________________________________________________</p>
			<p style='font-size:10.5px;margin-top:-14px'>Deibi Carlos Delatori<p>
			<p style='font-size:10.5px;margin-top:-14px'>DIGITAL TECH INFORMATICÁ LTDA ME</p>
			
		</div>
	</body>
</html>