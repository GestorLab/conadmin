			<div id='quadroBuscaContrato' style='width:651px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Contrato<div class='fecha' onClick="vi_id('quadroBuscaContrato', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Contrato</td>
						<td class='fecha' onClick="vi_id('quadroBuscaContrato', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioContrato' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Serviço</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoServico' autocomplete="off" value='' style='width:618px' maxlength='100' onkeyup="busca_contrato_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height:245px;'>
						<div id='listaDadosQuadroContrato'>&nbsp;</div>
					</div>
					<form name='BuscaContrato' method='post' onSubmit='return validar_busca_contrato()'>
						<input type='hidden' name='IdContrato' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaContrato', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaContrato');
						
						var valorCampoContrato = '';
						function validar_busca_contrato(){
							if(valorCampoContrato !=''){
								busca_contrato(valorCampoContrato,'false',document.formulario.Local.value);
							}
							return false;
						}
						function busca_contrato_lista(){
							var DescricaoServico	= document.formularioContrato.DescricaoServico.value;
							var Local				= document.formulario.Local.value;
							var Limit	  			= 12;
							var nameNode, nameTextNode, url;
							
							if(Local == 'OrdemServico' || Local == 'Protocolo'){
								var IdPessoa	= document.formulario.IdPessoa.value;
								
								if(IdPessoa == ""){
									IdPessoa	=	document.formulario.IdPessoaF.value;
								}
							}
							
							var url = "xml/contrato.php";
							
						    if(DescricaoServico == ''){
						    	url += "?Limit="+Limit;
							}else{
								url += "?DescricaoServico="+DescricaoServico;
							}
							
							if(Local == 'OrdemServico' || Local == 'Protocolo'){
								url	+= "&IdStatusExc=1&IdPessoa="+IdPessoa;
							}
							
							if(Local == 'Protocolo'){
								url += "&IdOrdemServico="+document.formulario.IdOrdemServico.value+"&IdContaEventual="+document.formulario.IdContaEventual.value+"&IdContaReceber="+document.formulario.IdContaReceber.value;
							}
							
							call_ajax(url,function (xmlhttp){
								if(xmlhttp.responseText == 'false'){
									document.getElementById('listaDadosQuadroContrato').innerHTML = "";
								} else{
									var dados = "<table id='listaDados' style='width: 628px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Contrato</td>\n<td class='listaDados_titulo'>Nome Serviço</td>\n<td class='listaDados_titulo'>Data Início</td>\n<td class='listaDados_titulo'>Status</td>\n</tr>";
									
									for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdContrato").length; i++){
										var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[i]; 
										var nameTextNode = nameNode.childNodes[0];
										var IdContrato = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var DescricaoServico = verifica_dado(nameTextNode.nodeValue);
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var DataInicio = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var Cor = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var Status = nameTextNode.nodeValue;
										
										DescricaoServico = DescricaoServico.substring(0,40);
										
										if(Cor == ""){
											Cor = "#FFF";
										}
										
										dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdContrato+"' onClick='aciona_busca_contrato(this,"+IdContrato+")'>";
										dados += 	"\n<td style='background-color:"+Cor+"'>"+IdContrato+"</td><td style='background-color:"+Cor+"'>"+DescricaoServico+"</td><td style='background-color:"+Cor+"'>"+dateFormat(DataInicio)+"</td><td style='background-color:"+Cor+"'>"+Status+"</td>";
										dados += "\n</tr>";
									}
									
									dados += "\n</table>";
									document.getElementById('listaDadosQuadroContrato').innerHTML = dados;
								}
							});
						}
						function aciona_busca_contrato(campo,valor){
							if(valorCampoContrato!=''){
								//alert('listaDados_td_'+valorCampoContrato);
								//document.getElementById('listaDados_td_'+valorCampoContrato).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoContrato == valor){
								busca_contrato(valor,false,document.formulario.Local.value);
							}
							valorCampoContrato = valor;
							document.BuscaContrato.IdContrato.value = valorCampoContrato;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioContrato);	
					</script>
				</div>
			</div>
