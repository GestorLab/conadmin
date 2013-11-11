			<div id='quadroBuscaContratoTipoVigencia' style='width:365px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca Tipo Vigência Contrato<div class='fecha' onClick="vi_id('quadroBuscaContratoTipoVigencia', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Tipo Vigência Contrato</td>
						<td class='fecha' onClick="vi_id('quadroBuscaContratoTipoVigencia', false);">X</div></td>
					</tr>
				</table>
				<div id='filtro_busca'>
					<form name='formularioContratoTipoVigencia'method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Tipo Vigência Contrato</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoContratoTipoVigencia' value='' autocomplete="off" style='width:334px' maxlength='100' onkeyup="busca_tipo_vigencia_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroContratoTipoVigencia'>&nbsp;</div>
					</div>
					<form name='BuscaContratoTipoVigencia' method='post' onSubmit='return validar_busca_contrato_tipo_vigencia()'>
						<input type='hidden' name='IdContratoTipoVigencia' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaContratoTipoVigencia', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaContratoTipoVigencia');
						
						var valorCampoContratoTipoVigencia = '';
						function validar_busca_contrato_tipo_vigencia(){
							if(valorCampoContratoTipoVigencia !=''){
								busca_tipo_vigencia(valorCampoContratoTipoVigencia);
							}
							return false;
						}
						function busca_tipo_vigencia_lista(){
							var DescricaoContratoTipoVigencia = document.formularioContratoTipoVigencia.DescricaoContratoTipoVigencia.value;
							var Limit						  = <?=getCodigoInterno(7,4)?>;
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
						    
						    if(DescricaoContratoTipoVigencia == ''){
						    	url = "xml/tipo_vigencia.php?Limit="+Limit;
							}else{
								url = "xml/tipo_vigencia.php?DescricaoContratoTipoVigencia="+DescricaoContratoTipoVigencia;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroContratoTipoVigencia').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Tipo Vig.</td>\n<td class='listaDados_titulo'>Nome</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContratoTipoVigencia").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoTipoVigencia")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdContratoTipoVigencia = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContratoTipoVigencia")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoContratoTipoVigencia = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdContratoTipoVigencia+"' onClick='aciona_contrato_tipo_vigencia(this,"+IdContratoTipoVigencia+")'>";
												dados += 	"\n<td>"+IdContratoTipoVigencia+"</td>\n<td>"+DescricaoContratoTipoVigencia+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroContratoTipoVigencia').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_contrato_tipo_vigencia(campo,valor){
							if(valorCampoContratoTipoVigencia!=''){
								document.getElementById('listaDados_td_'+valorCampoContratoTipoVigencia).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoContratoTipoVigencia == valor){
								busca_tipo_vigencia(valor,false,document.formulario.Local.value);
							}
							valorCampoContratoTipoVigencia = valor;
							document.BuscaContratoTipoVigencia.IdContratoTipoVigencia.value = valorCampoContratoTipoVigencia;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioContratoTipoVigencia);	
					</script>
				</div>
			</div>
