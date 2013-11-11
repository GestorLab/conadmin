			<div id='quadroBuscaTipoMensagem' style='width:375px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Tipo E-mail<div class='fecha' onClick="vi_id('quadroBuscaTipoEmail', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Tipo Mensagem</td>
						<td class='fecha' onClick="vi_id('quadroBuscaTipoMensagem', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioTipoMensagem' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Descrição Tipo Mensagem</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:334px' maxlength='100' onkeyup="busca_tipo_mensagem_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroTipoMensagem'>&nbsp;</div>
					</div>
					<form name='BuscaTipoMensagem' method='post' onSubmit='return validar_busca_tipo_mensagem()'>
						<input type='hidden' name='IdTipoMensagem' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaTipoMensagem', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaTipoMensagem');
						
						var valorCampoTipoMensagem = '';
						function validar_busca_tipo_mensagem(){
							if(valorCampoTipoMensagem !=''){
								busca_tipo_mensagem(valorCampoTipoMensagem,'false',document.formulario.Local.value);
							}
							return false;
						}
						function busca_tipo_mensagem_lista(){
							var Nome 		= document.formularioTipoMensagem.Nome.value;
							var Limit	  	= <?=getCodigoInterno(7,4)?>;
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
						    
						    if(Nome == ''){
						    	url = "../administrativo/xml/tipo_mensagem.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/tipo_mensagem.php?Titulo="+Nome;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroTipoMensagem').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Tipo Mensagem</td>\n<td class='listaDados_titulo'>Nome Tipo Mensagem</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdTipoMensagem").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMensagem")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdTipoMensagem = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Titulo")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Titulo = nameTextNode.nodeValue;
												
												Titulo = Titulo.substr(0,40);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdTipoMensagem+"' onClick='aciona_busca_tipo_mensagem(this,"+IdTipoMensagem+")'>";
												dados += "\n<td>"+IdTipoMensagem+"</td><td>"+Titulo+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroTipoMensagem').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_tipo_mensagem(campo,valor){
							if(valorCampoTipoMensagem!=''){
								document.getElementById('listaDados_td_'+valorCampoTipoMensagem).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoTipoMensagem == valor){
								busca_tipo_mensagem(valor,false,document.formulario.Local.value);
							}
							valorCampoTipoMensagem = valor;
							document.BuscaTipoMensagem.IdTipoMensagem.value = valorCampoTipoMensagem;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';							
						}
						function limpa_form_tipo_mensagem(){
							document.BuscaTipoMensagem.IdTipoMensagem.value	=	"";
							document.formularioTipoMensagem.Nome.value		=	"";
							
							valorCampoTipoMensagem = "";
						}
						enterAsTab(document.forms.formularioTipoMensagem);
					</script>
				</div>
			</div>