			<div id='quadroBuscaEstado' style='width:365px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca Estado<div class='fecha' onClick="vi_id('quadroBuscaEstado', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Estado</td>
						<td class='fecha' onClick="vi_id('quadroBuscaEstado', false);">X</td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioEstado' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Estado</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='NomeEstado' value='' style='width:334px' maxlength='100' onkeyup="busca_estado_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroEstado'>&nbsp;</div>
					</div>
					<form name='BuscaEstado' method='post' onSubmit='return validar_busca_estado()'>
						<input type='hidden' name='IdEstado' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaEstado', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>	
					<script language='JavaScript' type='text/javascript'>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaEstado');
						
						var valorCampoEstado = '';
						function validar_busca_estado(){
							if(valorCampoEstado !=''){
								busca_estado(document.formulario.IdPais.value,valorCampoEstado);
							}
							return false;
						}
						
						function busca_estado_lista(){
							var IdPais 	  	= document.formulario.IdPais.value;
							var NomeEstado	= document.formularioEstado.NomeEstado.value;
							var Limit	  	= <?=getCodigoInterno(7,4)?>;
							var nameNode, nameTextNode, url;
							
							if(IdPais == ''){
								vi_id('quadroBuscaEstado', false)						
							}
							
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
						    
						    if(NomeEstado != ''){
								url = "xml/estado.php?IdPais="+IdPais+"&NomeEstado="+NomeEstado;
							}else{
						    	url = "xml/estado.php?IdPais="+IdPais+"&Limit="+Limit;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroEstado').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Estado</td>\n<td class='listaDados_titulo'>Nome Estado</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPais").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdEstado = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var NomeEstado = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdEstado+"' onClick='aciona_busca_estado(this,"+IdEstado+")'>";
												dados += 	"\n<td>"+IdEstado+"</td><td>"+NomeEstado+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroEstado').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						
						function aciona_busca_estado(campo,valor){	
							var IdPais = document.formulario.IdPais.value;
							if(valorCampoEstado!=''){
								document.getElementById('listaDados_td_'+valorCampoEstado).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoEstado == valor){
								busca_estado(IdPais,valor,true);
							}
							valorCampoEstado = valor;
							document.BuscaEstado.IdEstado.value = valorCampoEstado;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';							
						}
						
						function limpa_form_estado(){
							document.formularioEstado.NomeEstado.value	=	"";
							
							valorCampoEstado = "";
						}
						enterAsTab(document.forms.formularioEstado);	
					</script>
				</div>
			</div>
