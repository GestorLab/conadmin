<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<html>
	<head>
		<title><?=$tituloBoleto?></title>
		<meta http-equiv=Content-Type content=text/html charset=ISO-8859-1>
		<meta name="Generator" content="Projeto BoletoPHP - www.boletophp.com.br - Licença GPL" />
		<style type=text/css>
			body{
				FONT: 10px Arial;
			}

			<!--.cp {  font: bold 10px Arial; color: black}
			<!--.ti {  font: 9px Arial, Helvetica, sans-serif}
			<!--.ld { font: bold 15px Arial; color: #000000}
			<!--.ct { FONT: 9px "Arial Narrow"; COLOR: #000033} 
			<!--.cn { FONT: 9px Arial; COLOR: black }
			<!--.bc { font: bold 20px Arial; color: #000000 }
			<!--.ld2 { font: bold 12px Arial; color: #000000 }
			#cabecalho, #quadro{
				width:665px; 
				border-bottom: 1px #000 solid;
			}
			#quadro{
				border-bottom: 0;
			}
			#cabecalho{
				height: 45px; 
				padding: 5px;
				text-align: right;
			}
			#quadro{
				height: 360px;
				text-align:left;
				margin-top: 10px;
				font-size: 11px;
			}
			#quadro table{
				font-size: 11px;
				margin: 0 0 15px 0;
				width:665px;
			}
			#quadro table tr th, #quadro table tr td{
				border-bottom: 1px #7C8286 solid;
			}
			#quadro p{
				margin: 0;
				font-size: 12px;
			}			
			table tr td{
				font-size: 11px;
				margin: 0 0 15px 0;				
			}
			#table_local_deposito, #table_local_deposito2{
				border: 1px #000 solid;
				border-right: 0;
			}
			#table_local_deposito tr td, #table_local_deposito2 tr td{
				padding:2px;
				border-right: 1px #000 solid;							
			}
			
			#table_local_deposito tr td .local_deposito, #table_local_deposito2 tr td .local_deposito{
				border:0; 								
			}
			#table_local_deposito tr td table, #table_local_deposito2 tr td table{
				width: 100%;				
			}
			#table_local_deposito tr td .local_deposito_cl2, #table_local_deposito2 tr td .local_deposito_cl2{
				border:0; 				
			}
			.titulo{
				text-align: left; 
				width: 665px; 
				font-size: 11px; 
				margin-bottom:3px
			}
	--></style> 
	</head>

	<!--body text=#000000 bgColor=#ffffff topMargin=0 rightMargin=0>
	<META NAME="keywords" CONTENT="Boleto, Dinamico, PHP, ASP, Itau, Bradesco, HSBC, Real, Banespa, Unibanco, Banco do Brasil, Sistemas, Sites, Cobrança Bancaria">
	<META NAME="description" CONTENT="Sistema para criação de boletos on-lines código fonte em PHP ou ASP, como usar boletos bancarios."-->

	<body text=#000000 bgColor=#ffffff topMargin=0 rightMargin=0>
		<div align=center>
			<?
				include("../cabecalho_html.php");
				include("../demonstrativo_html.php");
				
				$sql = "select
							ContaReceberDados.IdPessoa,
							ContaReceberDados.DataLancamento,
							ContaReceberDados.NumeroDocumento,
							ContaReceberDados.DataVencimento,
							ContaReceberDados.ValorFinal,
							ContaReceberDados.IdLocalCobranca,
							Pessoa.Fax
						from
							ContaReceberDados,
							(select
								IdPessoa
							from
								ContaReceber
							where
								IdLoja = $IdLoja and
								IdContaReceber = $IdContaReceber) ContaReceberPessoa,
							Pessoa								
						where
							ContaReceberDados.IdLoja = $IdLoja and
							ContaReceberDados.IdPessoa = ContaReceberPessoa.IdPessoa and							
							ContaReceberDados.IdContaReceber = $IdContaReceber and
							ContaReceberDados.IdPessoa = Pessoa.IdPessoa";
				$res = @mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);	
				
				$lin[DataLancamento] = dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
				$lin[DataVencimento] = dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
				$lin[ValorFinal] 	 = str_replace('.',',',formata_double($lin[ValorFinal]));	
				
				$cont = 0;
				$i = -1;		
			 	$sql = "select
							LocalCobrancaParametro.IdLocalCobrancaParametro,
							LocalCobrancaParametro.ValorLocalCobrancaParametro,
							LocalCobrancaLayoutParametro.ObsLocalCobrancaParametro
						from
							LocalCobrancaParametro,
							LocalCobrancaLayoutParametro														
						where
							LocalCobrancaParametro.IdLoja = $IdLoja and
							LocalCobrancaParametro.IdLocalCobranca = $lin[IdLocalCobranca] and
							LocalCobrancaParametro.IdLocalCobrancaLayout = LocalCobrancaLayoutParametro.IdLocalCobrancaLayout and
							LocalCobrancaParametro.IdLocalCobrancaParametro = LocalCobrancaLayoutParametro.IdLocalCobrancaParametro";
				$res2 = mysql_query($sql,$con);
				while($lin2 = mysql_fetch_array($res2)){
					$LocalCobrancaParametro[$lin2[IdLocalCobrancaParametro]] = $lin2[ValorLocalCobrancaParametro];					
					$j = 0;
					if($lin2[IdLocalCobrancaParametro] == "Banco1NumeroBanco"){
						if($lin2[ValorLocalCobrancaParametro] != ""){
							$aux = explode("\n",$lin2[ObsLocalCobrancaParametro]);							
							while($aux[$j] != ""){								
								$aux2 = explode("-",$aux[$j]);	
								if(trim($aux2[0]) == $lin2[ValorLocalCobrancaParametro]){
									$LocalCobrancaParametro['Banco1NomeBanco'] = substr($aux2[1],0,28);
									break;
								}								
								$j++;
							}														
							
							$lin2['Banco1NumeroBanco'] = $lin2[ObsLocalCobrancaParametro];
							$i++;
							$Banco[$i] = 1;
							$cont++;																					
						}
					}								
					if($lin2[IdLocalCobrancaParametro] == "Banco2NumeroBanco"){
						if($lin2[ValorLocalCobrancaParametro] != ""){
							$aux = explode("\n",$lin2[ObsLocalCobrancaParametro]);							
							while($aux[$j] != ""){								
								$aux2 = explode("-",$aux[$j]);	
								if(trim($aux2[0]) == $lin2[ValorLocalCobrancaParametro]){
									$LocalCobrancaParametro['Banco2NomeBanco'] = substr($aux2[1],0,32);
									break;
								}								
								$j++;
							}														
							
							$lin2['Banco2NumeroBanco'] = $lin2[ObsLocalCobrancaParametro];
							$i++;
							$Banco[$i] = 2;
							$cont++;							
						
						}
					}	
					if($lin2[IdLocalCobrancaParametro] == "Banco3NumeroBanco"){
						if($lin2[ValorLocalCobrancaParametro] != ""){
							$aux = explode("\n",$lin2[ObsLocalCobrancaParametro]);							
							while($aux[$j] != ""){								
								$aux2 = explode("-",$aux[$j]);	
								if(trim($aux2[0]) == $lin2[ValorLocalCobrancaParametro]){
									$LocalCobrancaParametro['Banco3NomeBanco'] = substr($aux2[1],0,32);
									break;
								}								
								$j++;
							}														
							
							$lin2['Banco3NumeroBanco'] = $lin2[ObsLocalCobrancaParametro];
							$i++;							
							$Banco[$i] = 3;
							$cont++;						
						}
					}	
					if($lin2[IdLocalCobrancaParametro] == "Banco4NumeroBanco"){
						if($lin2[ValorLocalCobrancaParametro] != ""){
							$aux = explode("\n",$lin2[ObsLocalCobrancaParametro]);							
							while($aux[$j] != ""){								
								$aux2 = explode("-",$aux[$j]);	
								if(trim($aux2[0]) == $lin2[ValorLocalCobrancaParametro]){
									$LocalCobrancaParametro['Banco4NomeBanco'] = substr($aux2[1],0,32);
									break;
								}								
								$j++;
							}
																					
							$lin2['Banco4NumeroBanco'] = $lin2[ObsLocalCobrancaParametro];
							$i++;
							$Banco[$i] = 4;
							$cont++;						
						}
					}	
				}
				
				if($LocalCobrancaParametro["Fax"] == ""){
					$LocalCobrancaParametro["Fax"] = $lin[Fax];	
				}		
			?>
			<br />
			<div style='text-align:left; width:665px;'>Corte na linha pontilhada.</div>
			<img height=1 src=imagens/6.gif width=665 border=0>
			<p style='font-size: 14; font-weight:bold'>Ficha para Depósito</p>
			<div class='titulo' style='margin-top:-10px'><b>Informações do Título</b></div>
			<table cellpadding='1' cellspacing='2' width='665px' style='border: 1px #000 solid'>
				<tr>
					<td><b>Nº do Conta a Receber:</b> <?=$IdContaReceber?></td>
					<td><b>Número do Documento:</b> <?=$lin[NumeroDocumento]?></td>
				</tr>
				<tr>
					<td><b>Data do Processamento: </b> <?=$lin[DataLancamento]?></td>
					<td><b>Data do Vencimento: </b> <?=$lin[DataVencimento]?></td>
				</tr>
				<tr>
					<td><b>Fax: </b><?=$LocalCobrancaParametro["Fax"]?></td>
					<td style='font-size: 14px'>Valor para Depósito: <b><?=getParametroSistema(5,1)?> <?=$lin[ValorFinal]?></b></td>
				</tr>			
			</table>
			<br />
			<div class='titulo'><b>Informações para Depósito</b></div>
			<?			
				$i = -1;
				if($cont == 1){
					$width = '332px';
				}else{
					$width = '665px';
				}
				$qtd = 1;
				
				if($cont > 0){
					echo"
					<div style='text-align:left; width: 665px'>
					<table id='table_local_deposito' cellpadding='0' cellspacing='0' width='$width'>
						<tr>
							";
					while($cont != 0){
						$i++;
						if(trim($LocalCobrancaParametro['Banco'.$Banco[$i].'NumeroBanco']) != ""){						 				
							echo"<td width='50%'>
									<table cellpadding='0' cellspacing='0'>
										<tr>								
											<td class='local_deposito'><b style='font-size: 14;'>".$LocalCobrancaParametro['Banco'.$Banco[$i].'NomeBanco']."</b></td>										
											<td class='local_deposito' style='text-align:right'><b>Nº do Banco:</b> ".$LocalCobrancaParametro['Banco'.$Banco[$i].'NumeroBanco']."</td>
										</tr>									
										<tr>
											<td class='local_deposito'><b>Nº da Agência: </b>".$LocalCobrancaParametro['Banco'.$Banco[$i].'Agencia']."</td>	
											<td class='local_deposito' style='text-align:right'><b>Nº da Conta: </b>".$LocalCobrancaParametro['Banco'.$Banco[$i].'Conta']."</td>		
										</tr>
										<tr>
											<td class='local_deposito' colspan='2'><b>CPF/CNPJ: </b>".$LocalCobrancaParametro['Banco'.$Banco[$i].'CPFCNPJ']."</td>			
										</tr>
										<tr>
											<td class='local_deposito' colspan='2'><b>Titular: </b>".$LocalCobrancaParametro['Banco'.$Banco[$i].'Titular']."</td>		
										</tr>
									</table>
								</td>";
							$cont--;
							if($qtd==2){
								break;
							}												
							$qtd++;													
						}																		
					}					
					echo"	
						</tr>
					</table>
					</div>";
				}
				$i++;
				if($cont == 1){
					$width = '332px';
				}else{
					$width = '665px';
				}
				if($cont > 0){
					echo"
					<br />
					<div style='text-align:left; width: 665px'>
					<table id='table_local_deposito' cellpadding='0' cellspacing='0' width='$width'>
						<tr>
							";					
					while($cont != 0){							
						if(trim($LocalCobrancaParametro['Banco'.$Banco[$i].'NumeroBanco']) != ""){					
							echo"<td width='50%'>
									<table cellpadding='0' cellspacing='0'>
										<tr>								
											<td class='local_deposito'><b style='font-size: 14;'>".$LocalCobrancaParametro['Banco'.$Banco[$i].'NomeBanco']."</b></td>										
											<td class='local_deposito' style='text-align:right'><b>Nº do Banco:</b> ".$LocalCobrancaParametro['Banco'.$Banco[$i].'NumeroBanco']."</td>															
										</tr>									
										<tr>
											<td class='local_deposito'><b>Nº da Agência: </b>".$LocalCobrancaParametro['Banco'.$Banco[$i].'Agencia']."</td>	
											<td class='local_deposito' style='text-align:right'><b>Nº da Conta: </b>".$LocalCobrancaParametro['Banco'.$Banco[$i].'Conta']."</td>		
										</tr>
										<tr>
											<td class='local_deposito' colspan='2'><b>CPF/CNPJ: </b>".$LocalCobrancaParametro['Banco'.$Banco[$i].'CPFCNPJ']."</td>			
										</tr>
										<tr>
											<td class='local_deposito' colspan='2'><b>Titular: </b>".$LocalCobrancaParametro['Banco'.$Banco[$i].'Titular']."</td>		
										</tr>
									</table>
								</td>";
							if($cont==4){
								break;
							}				
							$cont--;						
						}
						$i++;
					}
					echo"	
						</tr>
					</table>
					</div>";
				}								
			?>			
			
			<br />	
			<div class='titulo' style='font-size: 14; text-align:center'><b>Procedimentos e Normas</b></div>
			<div style="width:665px; text-align:justify; font-size: 11.5px">
			<? InstrucoesBoletoHTML($IdContaReceber); ?>		
			</div>
			<br />
			<div style='border: 1px #000 solid; width: 665px; height: 30px; padding-top: 5px; border-bottom: 0'>
				<p style='margin:0'>Cole aqui seu comprovante de depósito/transferência.</p> 
				<?
					if(trim($LocalCobrancaParametro["Email"]) == ""){
						echo "<p style='margin:0'>Envie esta ficha de depósito para o <b style='font-size: 14;'>Fax: ".$LocalCobrancaParametro["Fax"]."</b></p>";
					}else{
						echo "<p style='margin:0'>Envie uma cópia digitalizada para o <b style='font-size: 14;'>E-mail: ".$LocalCobrancaParametro["Email"]."</b></p>";
					}
				?>
			</div>			
		</div>
	</BODY>
</HTML>
