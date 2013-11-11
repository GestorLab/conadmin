			<div id='quadroBuscaPessoa' style='width:533px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Pessoa<div class='fecha' onClick="vi_id('quadroBuscaPessoa', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpadding='0'>
					<tr>
						<td class='tit'>Busca Pessoa</td>
						<td class='fecha' onClick="vi_id('quadroBuscaPessoa', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioPessoa' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Pessoa/Razão Social</td>
								<td class='descCampo'>CPF/CNPJ</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:365px' maxlength='100' onkeyup="busca_pessoa_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<input type='text' name='CPF_CNPJ' value='' autocomplete="off" style='width:128px' maxlength='18' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeyup="busca_pessoa_lista()">
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='descCampo'>País</td>
								<td class='descCampo'>Estado</td>
								<td class='descCampo'>Nome Cidade</td>
							</tr>
							<tr>
								<td>
									<select name='IdPais'  style='width:113px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_pessoa_lista(); verifica_estado(this.value,document.formularioPessoa.IdEstado)">
										<option value=''>Todos</option>
										<?
											$sql = "select IdPais, NomePais from Pais order by NomePais";
											$res = mysql_query($sql,$con);
											while($lin = mysql_fetch_array($res)){
												echo "<option value='$lin[IdPais]'>$lin[NomePais]</option>";
											}
										?>
									</select>
								</td>
								<td>
									<select name='IdEstado' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:161px'  onChange="busca_pessoa_lista()">
										<option value=''>Todos</option>
									</select>
								</td>
								<td class='campo'>
									<input type='text' name='NomeCidade' value='' style='width:223px' maxlength='100' onkeyup="busca_pessoa_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 200px;'>
						<div id='listaDadosQuadroPessoa'>&nbsp;</div>
					</div>
					<form name='BuscaPessoa' method='post' onSubmit='return validar_busca_pessoa()'>
						<input type='hidden' name='IdPessoa' value=''>
						<table>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaPessoa', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaPessoa');
					
						var valorCampoPessoa = '';
						function validar_busca_pessoa(){
							if(valorCampoPessoa !=''){
								switch(document.formulario.Local.value){
									case 'AgenteAutorizado':
										busca_agente(valorCampoPessoa,'false',document.formulario.Local.value);
										break;
									case 'Carteira':
										busca_carteira(document.formulario.IdAgenteAutorizado.value,valorCampoPessoa,'false',document.formulario.Local.value);
										break;
									case 'Fornecedor':
										busca_fornecedor(valorCampoPessoa,'false',document.formulario.Local.value);
										break;
									case 'Terceiro':
										busca_terceiro(valorCampoPessoa,'false',document.formulario.Local.value);
										break;
									default:
										busca_pessoa(valorCampoPessoa,'false',document.formulario.Local.value);
										break;
								}
							}
							return false;
						}
						function busca_pessoa_lista(){
							var Nome 		= document.formularioPessoa.Nome.value;
							var IdPais 		= document.formularioPessoa.IdPais.value;
							var IdEstado	= document.formularioPessoa.IdEstado.value;
							var NomeCidade	= document.formularioPessoa.NomeCidade.value;
							var CPF_CNPJ	= document.formularioPessoa.CPF_CNPJ.value;
							var Local		= document.formulario.Local.value;
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
						    
						    if(IdEstado == 0) 	IdEstado = '';
						    
						    if(Nome == '' && IdPais == '' && IdEstado == '' && NomeCidade == '' && CPF_CNPJ==''){
						    	url = "../administrativo/xml/pessoa.php?Limit="+Limit;
							}else{							
								url = "../administrativo/xml/pessoa.php?Nome="+Nome+"&IdPais="+IdPais+"&IdEstado="+IdEstado+"&NomeCidade="+NomeCidade+"&CPF_CNPJ="+CPF_CNPJ;
							}
							
							switch(Local){
								case 'AgenteAutorizado':
									url	+=	'&TipoAgenteAutorizado=1';	
									break;
								case 'Carteira':
									url	+=	'&TipoVendedor=1';	
									break;
								case 'Fornecedor':
									url	+=	'&TipoFornecedor=1';	
									break;
								case 'Usuario':
									url	+=	'&TipoUsuario=1&Busca=Busca';	
									break;
								case 'DeclaracaoPagamento':
										'&AnoDeclaracaoPagamento='+document.formulario.AnoReferencia.value;
									break;
							}
							
							url	+= "&rand"+Math.random();
							
							xmlhttp.open("GET", url,true);
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroPessoa').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 510px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Pessoa</td>\n<td class='listaDados_titulo'>Nome</td>\n<td class='listaDados_titulo'>Razão Social</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPessoa").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdPessoa = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Nome = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var RazaoSocial = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Cor = nameTextNode.nodeValue;
												
												if(Cor=="")	Cor = "#FFF";
												
												Nome 		= Nome.substring(0,30);
												RazaoSocial = RazaoSocial.substring(0,30);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdPessoa+"' onClick='aciona_busca_pessoa(this,"+IdPessoa+")'>";
												dados += 	"\n<td style='background-color:"+Cor+"'>"+IdPessoa+"</td><td style='background-color:"+Cor+"'>"+Nome+"</td><td style='background-color:"+Cor+"'>"+RazaoSocial+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroPessoa').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_pessoa(campo,valor){
							if(valorCampoPessoa!=''){
								document.getElementById('listaDados_td_'+valorCampoPessoa).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoPessoa == valor){
								switch(document.formulario.Local.value){
									case 'AgenteAutorizado':
										busca_agente(valor);
										break;
									case 'Carteira':
										busca_carteira(document.formulario.IdAgenteAutorizado.value,valor);
										break;
									case 'Fornecedor':
										busca_fornecedor(valor);
										break;
									case 'Terceiro':
										busca_terceiro(valor);
										break;
									default:
										busca_pessoa(valor,false,document.formulario.Local.value);
										break;
								}
							}
							valorCampoPessoa = valor;
							document.BuscaPessoa.IdPessoa.value = valorCampoPessoa;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_pessoa(){
							document.formularioPessoa.Nome.value			=	"";
							document.formularioPessoa.NomeCidade.value		=	"";
							document.formularioPessoa.CPF_CNPJ.value		=	"";
							document.formularioPessoa.IdPais[0].selected	=	true;
							
							while(document.formularioPessoa.IdEstado.options.length > 1){
								document.formularioPessoa.IdEstado.options[1] = null;
							}
							valorCampoPessoa = '';
						}
						enterAsTab(document.forms.formularioPessoa);
					</script>
				</div>
			</div>
