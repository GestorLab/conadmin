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
			height: 25px;
		   }
		   #conteiner .td2{
			border-bottom: solid 1px;
			border-right: none;
			height: 25px;
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
		   
		   font-size:11px;
		   text-align:left;
		   margin-top:-8px;
		   
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
			 <p style='font-size:15px;text-align:center'>
				<b>Digital Net - Internet Banda Larga | Internet Via Rádio - em Campo Grande MS</b>
			</p>
			<img src='imagens/image.jpg'>
			<p style='font-size:13px;text-align:Right;margin-top:-3px'>
				<b>Digital Net - Internet Banda Larga | Internet Via Rádio - em Campo Grande MS</b>
			</p>
			<p style='font-size:11px;text-align:Right;margin-top:-12px'>R. Salomão Abdala, 1020 - Jd. Itamaracá</p>
			<p style='font-size:11px;text-align:Right;margin-top:-10px'>Fone: (67)3043 -6580/9239-6580 - Cep: 79062-700 - Campo Grande / M S</p>
			<p style='font-size:13px;text-align:Right'><font color='blue'><b>Termo de Cancelamento de Contrato</b></font></p>
			<hr size=1 width='100%'>
			<p class='text'><b>DADOS DO CLIENTE:</b></p>
			<p class='text'>Cliente: <b>ABASTECEDORA DE COMBUSTIVEL AMERICA LTDA</b></p>
			<p class='text'>CPF/CNPJ: <b>07.741.437/0001-25</b></p>
			<p class='text'>RG /IE :</p>
			<p class='text'>Endereço: <b>RODOVIA MINI ANEL RODOVIARIO,2488</b></p>
			<p class='text'>Bairro: <b>JD ITAMARACA</b></p>
			<p class='text'>Complemento:<b> KM 8,8</b></p>
			<p class='text'>CEP :</p>
			<p class='text'>Cidade: <b>Campo Grande</b></p>
			<p class='text'>Estado:<b> MS</b></p>
			<p class='text'>Telefone Residencial: <b>3387-1003</b></p>
			<p class='text'>Telefone Comercial:</p>
			<p class='text'>Telefone Celular:</p>
			<p style='font-size:11px;text-align:left'><b>TERMO DE CANCELAMENTO:</b></p>
						
			<p style='font-size:11px'>Conforme solicitado pelo cliente acima menc ionado, este termo tem como objetivo o cancelamento do serviço de acesso à Internet
				fornecido ao cliente pela DIGITAL NET - INTERNET BANDA LARGA |INTERNET VIA RáDIO - EM CAMPO GRANDE MS.
			</p>
			<p style='font-size:11px'>(Este Termo não isentao cliente da responsabilidade pela quitação de débitos pendentes )
				Campo Grande/MS, 08 de Agosto de 2013.
			<p>
			
	<div style='margin-left:30px;font-size:10px'>		
		<table style='text-align:center' cellspacing=-11>
			<tr>
				<td style='width:550px;text-align:center'>______________________________________	</td>
				<td style='width:100px'></td>
				<td style='width:550'>______________________________________ </td>
			</tr>
			<tr>
				<td style='text-align:left;font-size:10px'>DIGITAL NET - INTERNET BANDA LARGA | INTERNET VIA RÁDIO</td>
				<td></td>
				<td style='text-align:center;font-size:10px'>CLIENTE</td>
			</tr>
			<tr>
				<td style='text-align:center;font-size:10px'><b>-EM CAMPO GRANDE MS</b></td>
				<td></td>
				<td style='text-align:center;font-size:10px'>ABASTECEDORA DE COMBUSTIVEL AMERICA LTDA</td>
			</tr>
			<tr>
				<td style='text-align:center;font-size:10px'>DEIBI CARLOS DE LATORI
				<td></td>
				<td></td>
			</tr>
		</table>
	</div>
			
			<div style='text-align: center'>
			
				<input type='button' onclick='window.print()' value='Imprimir Contrato' />
			</div>
		</div>
	</body>
</html>