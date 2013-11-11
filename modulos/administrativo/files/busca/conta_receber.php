			<div id='quadroBuscaContaReceber' style='width:660px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca Contas a Receber<div class='fecha' onClick="vi_id('quadroBuscaContaReceber', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Contas a Receber</td>
						<td class='fecha' onClick="vi_id('quadroBuscaContaReceber', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioContaReceber' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Pessoa/Razão Social</td>
								<td class='descCampo'>Nº Documento</td>
								<td class='descCampo'>Data Lançamento</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:425px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyUp='busca_conta_receber_lista()'>
								</td>
								<td class='campo'>
									<input type='text' name='NumeroDocumento' value='' autocomplete="off" style='width:100px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onChange='busca_conta_receber_lista()'>
								</td>
								<td class='campo'>
									<input type='text' name='DataLancamento' value='' style='width:100px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onkeypress="mascara(this,event,'date')" onChange='busca_conta_receber_lista()'> 
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='descCampo'>Local de Cobrança</td>
								<td class='descCampo'>Data Vencimento</td>
								<td class='descCampo'>Data Pagamento</td>
							</tr>
							<tr>
								<td class='campo'>
									<select name='IdLocalCobranca' onFocus="Foco(this,'in')"  style='width:431px' onBlur="Foco(this,'out');" tabindex='5' onChange='busca_conta_receber_lista()'>
										<option value=''>Todos</option>
										<?
											$sql = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobranca where IdLoja=$local_IdLoja order by DescricaoLocalCobranca";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdLocalCobranca]'>$lin[DescricaoLocalCobranca]</option>";
											}
										?>
									</select>
								</td>
								<td class='campo'>
									<input type='text' name='DataVencimento' value='' style='width:100px' maxlength='10'  onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange='busca_conta_receber_lista()'>
								</td>
								<td class='campo'>
									<input type='text' name='DataPagamento' value='' style='width:100px' maxlength='10'  onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange='busca_conta_receber_lista()'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 200px;'>
						<div id='listaDadosQuadroContaReceber'>&nbsp;</div>
					</div>
					<form name='BuscaContaReceber' method='post' action='busca_conta_receber.php' onSubmit='return validar_busca_conta_receber()'>
						<input type='hidden' name='IdContaReceber' value=''>
						<table>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaContaReceber', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script type='text/javascript'>
						new Draggable('quadroBuscaContaReceber');
						
						var valorCampoContaReceber = '';
						function validar_busca_conta_receber(){
							if(valorCampoContaReceber !=''){
								busca_conta_receber(valorCampoContaReceber,'false',document.formulario.Local.value);
							}
							return false;
						}
						function busca_conta_receber_lista(){
							var Nome 					= document.formularioContaReceber.Nome.value;
							var NumeroDocumento 		= document.formularioContaReceber.NumeroDocumento.value;
							var DataVencimento			= document.formularioContaReceber.DataVencimento.value;
							var DataLancamento			= document.formularioContaReceber.DataLancamento.value;
							var IdLocalCobranca			= document.formularioContaReceber.IdLocalCobranca.value;
							var DataPagamento			= document.formularioContaReceber.DataPagamento.value;
							var Local					= document.formulario.Local.value;
							var Limit	  				= <?=getCodigoInterno(7,4)?>;
							var url_temp				= "";
							var IdStatus				= "";
							
							if(Local == "Movimentacao"){
								var url = "xml/conta_receber_caixa_movimentacao.php";
								url_temp += "&ContasReceber="+document.formulario.ContasReceber.value;
								
								if(document.formulario.TipoMovimentacao.value == 4){
									url_temp += "&IdStatus=2";
								}
							} else{
								var url = "xml/conta_receber_busca.php";
								
								if(Local == 'ContaReceberAtivar'){
									url_temp += '&IdStatusAtivacaoContaReceber=1';
								}
								
								if(Local == 'NotaFiscalSaida'){
									url_temp += '&IdStatusValido=1';
								}
								
								if(Local == "AgruparContaReceber"){
									url_temp += "&IdPessoa="+document.formulario.IdPessoa.value+"&IdStatus=1";
								}
								
								if(Local == "Protocolo"){
									var IdPessoa = document.formulario.IdPessoa.value;
									
									if(IdPessoa == ""){
										IdPessoa = document.formulario.IdPessoaF.value;
									}
									
									url_temp += "&IdPessoa="+IdPessoa+"&IdContrato="+document.formulario.IdContrato.value+"&IdContaEventual="+document.formulario.IdContaEventual.value+"&IdOrdemServico="+document.formulario.IdOrdemServico.value;
								}
							}
							
							if(Nome == '' && NumeroDocumento == '' && DataVencimento == '' && DataLancamento == '' && IdLocalCobranca=='' && DataPagamento==''){
								url += "?Limit="+Limit;
							} else{
								url += "?Nome="+Nome+"&NumeroDocumento="+NumeroDocumento+"&DataVencimento="+DataVencimento+"&DataLancamento="+DataLancamento+"&IdLocalCobranca="+IdLocalCobranca+"&DataPagamento="+DataPagamento;
							}
							
							url += url_temp;
							
							call_ajax(url,function (xmlhttp) {
								if(xmlhttp.responseText == 'false'){
									document.getElementById('listaDadosQuadroContaReceber').innerHTML = "";
								} else{
									var nameNode, nameTextNode, dados = "<table id='listaDados' style='width: 100%'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Conta Rec.</td>\n<td class='listaDados_titulo'>Nome Pessoa</td>\n<td class='listaDados_titulo'>Nº Doc.</td>\n<td class='listaDados_titulo'>Data Lanc.</td>\n<td class='listaDados_titulo'>Data Venc.</td>\n<td class='listaDados_titulo'>Valor</td>\n</tr>";
									
									for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; i++){
										nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var IdContaReceber = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var Nome = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var NumeroDocumento = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var DataLancamento = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var DataVencimento = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var Valor = nameTextNode.nodeValue;
										
										Nome = Nome.substring(0,30);
										
										dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdContaReceber+"' onClick='aciona_busca_conta_receber(this,"+IdContaReceber+")'>";
										dados += "\n<td>"+IdContaReceber+"</td><td>"+Nome+"</td><td>"+NumeroDocumento+"</td><td>"+dateFormat(DataLancamento)+"</td><td>"+dateFormat(DataVencimento)+"</td><td style='text-align: right'>"+Valor+"</td>";
										dados += "\n</tr>";
									}
									
									dados += "\n</table>";
									document.getElementById('listaDadosQuadroContaReceber').innerHTML = dados;
								}
							},false);
						}
						function aciona_busca_conta_receber(campo,valor){
							if(valorCampoContaReceber!=''){
								eval("document.getElementById('listaDados_td_"+valorCampoContaReceber+"').style.backgroundColor = '#FFFFFF'");
							}
							
							if(valorCampoContaReceber == valor){
								if(document.formulario.TipoMovimentacao != undefined){
									if(document.formulario.TipoMovimentacao.value == 4){
										busca_conta_receber(valor,false,document.formulario.Local.value,undefined,2);
									}else{
										busca_conta_receber(valor,false,document.formulario.Local.value);
									}
								}else{
									busca_conta_receber(valor,false,document.formulario.Local.value);
								}
							}
							
							valorCampoContaReceber = valor;
							document.BuscaContaReceber.IdContaReceber.value = valorCampoContaReceber;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_conta_receber(){
							document.formularioContaReceber.Nome.value				=	"";
							document.formularioContaReceber.NumeroDocumento.value	=	"";
							document.formularioContaReceber.DataVencimento.value	=	"";
							document.formularioContaReceber.DataLancamento.value	=	"";
							document.formularioContaReceber.IdLocalCobranca.value	=	"";
							document.formularioContaReceber.DataPagamento.value		=	"";
							
							valorCampoContaReceber = "";
						}
						
						enterAsTab(document.forms.formularioContaReceber);	
					</script>
				</div>
			</div>
