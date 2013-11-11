	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == 'inserir'){
				document.formulario.bt_inserir.disabled	= false;
				document.formulario.bt_alterar.disabled	= true;
				document.formulario.bt_excluir.disabled = true;
			} else{
				document.formulario.bt_inserir.disabled	= true;
				document.formulario.bt_alterar.disabled	= false;
				document.formulario.bt_excluir.disabled = false;
			}
		}
	}
	function validar(){
		if(document.formulario.DescricaoTemplate.value == ''){
			mensagens(1);
			document.formulario.DescricaoTemplate.focus();
			return false;
		}
		
		return true;
	}
	function excluir(IdTemplate,listar){
		if(IdTemplate == '' || IdTemplate == undefined){
			IdTemplate = document.formulario.IdTemplate.value;
		}
		
		if(excluir_registro()){
			if(document.formulario.Acao.value == "inserir" && listar != "listar"){
				return;
			}
			
			var url = "./files/excluir/excluir_template_mensagem.php?IdTemplate="+IdTemplate;
			
			call_ajax(url,function (xmlhttp){
				var numMsg = parseInt(xmlhttp.responseText);
				
				if(listar != "listar"){
					document.formulario.Erro.value = numMsg;
					
					if(numMsg == 7){
						document.formulario.Acao.value = "inserir";
						url = "cadastro_template_mensagem.php?Erro=" + document.formulario.Erro.value;
						window.location.replace(url);
					} else{
						verificaErro();
					}
				} else{
					mensagens(0);
					mensagens2(numMsg);
					
					if(numMsg == 7){
						for(var i = 0; i < document.getElementById("tabelaTemplateMensagem").rows.length; i++){
							if(IdTemplate == document.getElementById("tabelaTemplateMensagem").rows[i].accessKey){
								document.getElementById("tabelaTemplateMensagem").deleteRow(i);
								tableMultColor("tabelaTemplateMensagem", document.filtro.corRegRand.value);
								document.getElementById("tabelaTemplateMensagemTotal").innerHTML = "Total: "+(document.getElementById("tabelaTemplateMensagem").rows.length-2);
								break;
							}
						}
					}
				}
			});
		}
	}
	function mensagens2(n,Local){
		var msg = '', prioridade = '';
		
		if(Local == '' || Local == undefined){
			Local = '';
		}
		
		if(n == 0){
			return help2(msg,prioridade);
		}
		
		var url = "../../xml/mensagens.xml";
		
		call_ajax(url,function (xmlhttp){
			var nameNode = xmlhttp.responseXML.getElementsByTagName("msg"+n)[0], nameTextNode;
			
			if(nameNode != null){
				nameTextNode = nameNode.childNodes[0];
				msg = nameTextNode.nodeValue;
			} else{
				msg = '';
			}
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("pri"+n)[0]; 
			
			if(nameNode != null){
				nameTextNode = nameNode.childNodes[0];
				prioridade = nameTextNode.nodeValue;
			} else{
				prioridade = '';
			}
			
			return help2(msg,prioridade);
		});
	}
	function verificaErro2(){
		var nerro = parseInt(document.formulario.Erro.value);
		mensagens2(nerro,document.formulario.Local.value);
	}
	function help2(msg,prioridade){
		if(msg != ''){
			scrollWindow("bottom");
		}
		
		document.getElementById('helpText2').innerHTML = msg;
		document.getElementById('helpText2').style.display = "block";
		
		switch(prioridade){
			case 'atencao':
				document.getElementById('helpText2').style.color = "#C10000";
				return true;
			default:
				document.getElementById('helpText2').style.color = "#004975";
				return true;
		}
	}
	function listar_template_mensagem(){
		while(document.getElementById("tabelaTemplateMensagem").rows.length > 2){
			document.getElementById("tabelaTemplateMensagem").deleteRow(1);
		}
		
	   	var url = "xml/template_mensagem.php";
		
		mensagens2(0);
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == "false"){
				document.getElementById("tabelaTemplateMensagemTotal").innerHTML = "Total: 0";
			} else{
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdTemplate").length; i++) {
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdTemplate")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdTemplate = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTemplate")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoTemplate = nameTextNode.nodeValue.substr(0,77);
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataAlteracao = nameTextNode.nodeValue;
					
					var tam 	= document.getElementById("tabelaTemplateMensagem").rows.length;
					var linha	= document.getElementById("tabelaTemplateMensagem").insertRow(tam-1);
					
					if(i%2 != 0){
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					linha.accessKey	= IdTemplate;
					
					var c0 = linha.insertCell(0);	
					var c1 = linha.insertCell(1);	
					var c2 = linha.insertCell(2);	
					var c3 = linha.insertCell(3);
					var c4 = linha.insertCell(4);
					
					var linkIni = "<a onClick=\"busca_template_mensagem("+IdTemplate+",true,'"+document.formulario.Local.value+"')\">";
					var linkFim = "</a>";
					
					c0.innerHTML = linkIni + IdTemplate + linkFim;
					c0.style.cursor  = "pointer";
					c0.style.padding =	"0 0 0 5px";
					
					c1.innerHTML = linkIni + DescricaoTemplate + linkFim;
					c1.style.cursor = "pointer";
					
					c2.innerHTML = linkIni + dateFormat(DataCriacao) + linkFim;
					c2.style.cursor = "pointer";
					
					c3.innerHTML = linkIni + dateFormat(DataAlteracao) + linkFim;
					c3.style.cursor = "pointer";
					
					c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdTemplate+",'listar')\">";
					c4.style.textAlign = "center";
					c4.style.cursor = "pointer";
				}
				
				document.getElementById('tabelaTemplateMensagemTotal').innerHTML = "Total: "+i;
			}
		});
	}