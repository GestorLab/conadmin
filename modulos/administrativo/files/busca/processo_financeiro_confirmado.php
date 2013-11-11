			<div id='quadroBuscaProcessoFinanceiro' style='width:365px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Processo Financeiro<div class='fecha' onClick="vi_id('quadroBuscaProcessoFinanceiro', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Processo Financeiro</td>
						<td class='fecha' onClick="vi_id('quadroBuscaProcessoFinanceiro', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioProcessoFinanceiro' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Mês Referência</td>
								<td class='descCampo'>Menor Vencimento</td>							
								<td class='descCampo'>Status</td>	
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='MesReferencia' autocomplete="off" value='' style='width:93px' maxlength='7' onChange="busca_processo_financeiro_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'mes')" tabindex='1'>
								</td>
								<td class='campo'>	
									<input type='text' name='MenorVencimento' autocomplete="off" value='' style='width:105px' maxlength='7' onkeyup="busca_processo_financeiro_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'mes')" tabindex='2'>
								</td>
								<td  class='campo'>
								<select name='IdStatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:125px' tabindex='3' disabled>
									<option value=''>Confirmado</option>									
								</select>								
							</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroProcessoFinanceiro'>&nbsp;</div>
					</div>
					<form name='BuscaProcessoFinanceiro' method='post' onSubmit='return validar_busca_processo_financeiro()'>
						<input type='hidden' name='IdProcessoFinanceiro' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaProcessoFinanceiro', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						new Draggable('quadroBuscaProcessoFinanceiro');
						
						var valorCampoProcessoFinanceiro = '';
						function validar_busca_processo_financeiro(){
							if(valorCampoProcessoFinanceiro !=''){
								busca_processo_financeiro(valorCampoProcessoFinanceiro);	
							}
							return false;
						}
						function busca_processo_financeiro_lista(){
						
							var MesReferencia	= document.formularioProcessoFinanceiro.MesReferencia.value;
							var MenorVencimento	= document.formularioProcessoFinanceiro.MenorVencimento.value;							
							var Limit	  		= <?=getCodigoInterno(7,4)?>;
							var nameNode, nameTextNode, url;
							
							var xmlhttp   = false;
							if (window.XMLHttpRequest) { // Mozilla, Safari,...
						    	xmlhttp = new XMLHttpRequest();
						        if(xmlhttp.overrideMimeType){
						        	xmlhttp.overrideMimeType('text/xml');
								}
							}else if (window.ActiveXObject){ // IE
								try{
									xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
								}catch(e){
									try{
										xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
						            } catch (e) {}
						        }
						    }
						    
						    if(MesReferencia == '' && MenorVencimento==''){						
						    	url = "xml/processo_financeiro.php?Limit="+Limit+"&IdStatus="+3;
							}else{
								url = "xml/processo_financeiro.php?MesReferencia="+MesReferencia+"&MenorVencimento="+MenorVencimento+"&IdStatus="+3;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroProcessoFinanceiro').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 342px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Processo</td>\n<td class='listaDados_titulo'>Mês Ref.</td>\n<td class='listaDados_titulo'>Menor Venc.</td>\n<td class='listaDados_titulo'>Local Cobrança</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdProcessoFinanceiro = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("MesReferencia")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var MesReferencia = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("MenorVencimento")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var MenorVencimento = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescFiltro_IdLocalCobranca")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescFiltro_IdLocalCobranca = nameTextNode.nodeValue;
												
												DescFiltro_IdLocalCobranca = DescFiltro_IdLocalCobranca.substr(0,20);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdProcessoFinanceiro+"' onClick='aciona_busca_processo_financeiro(this,"+IdProcessoFinanceiro+")'>";
												dados += 	"\n<td>"+IdProcessoFinanceiro+"</td><td>"+MesReferencia+"</td><td>"+MenorVencimento+"</td><td>"+DescFiltro_IdLocalCobranca+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroProcessoFinanceiro').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_processo_financeiro(campo,valor){
							if(valorCampoProcessoFinanceiro!=''){
								document.getElementById('listaDados_td_'+valorCampoProcessoFinanceiro).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoProcessoFinanceiro == valor){
								busca_processo_financeiro(valor,true);
							}
							valorCampoProcessoFinanceiro = valor;
							document.BuscaProcessoFinanceiro.IdProcessoFinanceiro.value = valorCampoProcessoFinanceiro;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_processo_financeiro(){
							document.formularioProcessoFinanceiro.MesReferencia.value	=	"";
							document.formularioProcessoFinanceiro.MenorVencimento.value	=	"";
							//document.formularioProcessoFinanceiro.IdStatus[0].selected	=	true;
							
							valorCampoProcessoFinanceiro = '';
						}						
						enterAsTab(document.forms.formularioProcessoFinanceiro);
					</script>
				</div>
			</div>
