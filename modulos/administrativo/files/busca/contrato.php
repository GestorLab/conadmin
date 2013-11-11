			<div id='quadroBuscaContrato' style='width:765px;' class='quadroFlutuante'>	
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
								<td class='descCampo'>Nome Pessoa/Razão Social</td>
								<td class='descCampo'>CPF/CNPJ</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:574px' maxlength='100' onkeyup="busca_contrato_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<input type='text' name='CPF_CNPJ' value='' autocomplete="off" style='width:150px' maxlength='18' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeyup="busca_contrato_lista()">
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='descCampo'>Nome Serviço</td>
								<td class='descCampo'>Periodicidade</td>
								<td class='descCampo'>Status</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoServico' autocomplete="off" value='' style='width:338px' maxlength='100' onkeyup="busca_contrato_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<select name='IdPeriodicidade' style='width:140px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_contrato_lista()">
										<option value=''>Todos</option>
										<?
											$sql = "select IdPeriodicidade, DescricaoPeriodicidade from Periodicidade where Ativo = 1 order by IdPeriodicidade";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdPeriodicidade]'>$lin[DescricaoPeriodicidade]</option>";
											}
										?>
									</select>
								</td>
								<td class='campo'>
									<select name='IdStatus' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_contrato_lista()">
										<option value=''>Todos</option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 245px;'>
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
							var Nome 				= document.formularioContrato.Nome.value;
							var CPF_CNPJ			= document.formularioContrato.CPF_CNPJ.value;
							var DescricaoServico	= document.formularioContrato.DescricaoServico.value;
							var IdPeriodicidade		= document.formularioContrato.IdPeriodicidade.value;
							var IdStatus			= document.formularioContrato.IdStatus.value;
							var Local				= document.formulario.Local.value;
							var Limit	  			= <?=getCodigoInterno(7,4)?>;
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
						    
						    if(Nome == '' && DescricaoServico == '' && IdPeriodicidade == '' && CPF_CNPJ=='' && IdStatus==''){
						    	url = "../administrativo/xml/contrato.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/contrato.php?Nome="+Nome+"&DescricaoServico="+DescricaoServico+"&IdPeriodicidade="+IdPeriodicidade+"&CPF_CNPJ="+CPF_CNPJ+"&IdStatus="+IdStatus;
							}
							if(Local == 'OrdemServico'){
								url	+= "&IdPessoa="+IdPessoa;
							}
							
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroContrato').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 742px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Contrato</td>\n<td class='listaDados_titulo'>Nome Pessoa</td>\n<td class='listaDados_titulo'>Nome Serviço</td>\n<td class='listaDados_titulo'>Data Início</td>\n<td class='listaDados_titulo'>Periodicidade</td>\n<td class='listaDados_titulo'>Valor (<?=getParametroSistema(5,1)?>)</td>\n<td class='listaDados_titulo'>Status</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContrato").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdContrato = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Nome = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var RazaoSocial = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoServico = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DataInicio = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Cor = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Valor = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescPeriodicidade = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Status = nameTextNode.nodeValue;
																		
												Nome 		= Nome.substring(0,15);
												RazaoSocial = RazaoSocial.substring(0,15);
												var DescricaoServico = DescricaoServico.substring(0,20);
												
												if(RazaoSocial != '') Nome = RazaoSocial;
												
												if(Cor=="")	Cor = "#FFF";
												if(Valor=="") 	Valor = 0;
												
												Valor	=	formata_float(Arredonda(Valor,2),2).replace(".",",");
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdContrato+"' onClick='aciona_busca_contrato(this,"+IdContrato+")'>";
												dados += 	"\n<td style='background-color:"+Cor+"'>"+IdContrato+"</td><td style='background-color:"+Cor+"'>"+Nome+"</td><td style='background-color:"+Cor+"'>"+DescricaoServico+"</td><td style='background-color:"+Cor+"'>"+dateFormat(DataInicio)+"</td><td style='background-color:"+Cor+"'>"+DescPeriodicidade+"</td><td style='background-color:"+Cor+"'>"+Valor+"</td><td style='background-color:"+Cor+"'>"+Status+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroContrato').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_contrato(campo,valor){
							if(valorCampoContrato!=''){
								document.getElementById('listaDados_td_'+valorCampoContrato).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoContrato == valor){
								busca_contrato(valor,false,document.formulario.Local.value);
							}
							valorCampoContrato = valor;
							document.BuscaContrato.IdContrato.value = valorCampoContrato;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_contrato(){
							document.formularioContrato.Nome.value				=	"";
							document.formularioContrato.CPF_CNPJ.value			=	"";
							document.formularioContrato.DescricaoServico.value	=	"";
							document.formularioContrato.IdPeriodicidade.value	=	"";
							document.formularioContrato.IdStatus.value			=	"";
							
							valorCampoContrato = "";
						}
						enterAsTab(document.forms.formularioContrato);	
					</script>
				</div>
			</div>
