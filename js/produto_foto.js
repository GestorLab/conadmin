	function excluir(IdProduto,IdProdutoFoto){
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == 'inserir'){
					return false;
				}
			}
			var xmlhttp   = false;
			if (window.XMLHttpRequest) { // Mozilla, Safari,...
    			xmlhttp = new XMLHttpRequest();
		        if(xmlhttp.overrideMimeType){
		    //    	xmlhttp.overrideMimeType('text/xml');
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
    
   			url = "files/excluir/excluir_produto_foto.php?IdProduto="+IdProduto+"&IdProdutoFoto="+IdProdutoFoto;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(document.formulario != undefined){
							document.formulario.Erro.value = xmlhttp.responseText;
							if(parseInt(xmlhttp.responseText) == 91){
								document.formulario.Acao.value 	= 'inserir';
								url = 'adicionar_foto.php?Erro='+document.formulario.Erro.value+"&IdProduto="+IdProduto;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}
					}
				}
				return true;
			}
			xmlhttp.send(null);
		}
	} 
	function validar(){
		if(document.formulario.Acao.value == 'inserir'){
			var endFoto = document.formulario.Foto.value;
			var tipo    = endFoto.substring(endFoto.length-4,endFoto.length);
			
			tipo = tipo.toLowerCase();
			if(tipo == ".jpg" || tipo == ".jpeg" || tipo == ".png" || tipo == ".gif"){
				if(document.formulario.Redimensionar.value==0 || document.formulario.Redimensionar.value==''){
					mensagens(1);
					document.formulario.Redimensionar.focus();
					return false;
				}
			}else{
				mensagens(10);
				document.formulario.Foto.focus();
				return false;
			}
		}
		return true;
	}
	function tabela_nova(){
		
		var tam = document.getElementById('tabela_foto').rows.length;
		var l1	= document.getElementById('tabela_foto').insertRow(tam);
				
		var c0	= l1.insertCell(0);
		var c1	= l1.insertCell(1);
		var c2	= l1.insertCell(2);
		
		if(document.formulario.IdProdutoFoto.value != ''){
			disabled	=	'disabled';
		}else{
			disabled	=	'';
		}
		
		c0.innerHTML	= "<input type='hidden' name='EndFoto'/><input type='file' name='Foto' value='' style='width:369px' maxlength='100' onChange=\"document.formulario.EndFoto.value=document.formulario.Foto.value; ativa_imagem(document.formulario.EndFoto.value)\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" "+disabled+" tabindex='1'>";
		c1.innerHTML	= "&nbsp;";
		c2.innerHTML	= "<select name='Redimensionar' style='width:120px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2' "+disabled+"><option value='0'></option></select>";
		
		buscaRedimensionar('');
		window.opener.busca_produto_foto(document.formulario.IdProduto.value);
	}
	function ativa_imagem(endFoto){
		if(endFoto == '' || endFoto == undefined){
			document.getElementById('foto_G').src			 = "../../img/estrutura_sistema/indisponivel.jpg";
			document.formulario.DescricaoFoto.value			 = "";
			document.formulario.EndFoto.value 				 = "";
			
			document.getElementById('tabela_foto').deleteRow(1);
			tabela_nova();
			
			return false;
		}else{
			var tipo = endFoto.substring(endFoto.length-4,endFoto.length);
			
			tipo = tipo.toLowerCase();
			if(tipo == ".jpg" || tipo == ".jpeg" || tipo == ".png" || tipo == ".gif"){
			
				var img = new Image();	
				img.src = endFoto;
				if(img.width < 100){
					document.getElementById('foto_G').style.width  = img.width+'px';
				}else{
					document.getElementById('foto_G').style.width = '500px';
				}
				
				document.getElementById('foto_G').src		  = endFoto;
				document.formulario.EndFoto.value 			  = endFoto;
				document.formulario.Redimensionar[0].selected = true;
				
			}else{
				document.getElementById('foto_G').src			 = "../../img/estrutura_sistema/indisponivel.jpg";
				document.formulario.DescricaoFoto.value			 = "";
				document.formulario.EndFoto.value 				 = "";
			
				mensagens(10);
				return false;	
			}
		}
		return true;
	}
	function buscaRedimensionar(){
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	    //    	xmlhttp.overrideMimeType('text/xml');
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
		
		url = "xml/parametro_sistema.php?IdGrupoParametroSistema=60";
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){		
						while(document.formulario.Redimensionar.options.length > 0){
							document.formulario.Redimensionar.options[0] = null;
						}
					}else{	
						var nameNode, nameTextNode;					
						while(document.formulario.Redimensionar.options.length > 0){
							document.formulario.Redimensionar.options[0] = null;
						}
						
						addOption(document.formulario.Redimensionar,'',0);
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroSistema = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorParametroSistema = nameTextNode.nodeValue;
							
							addOption(document.formulario.Redimensionar,ValorParametroSistema,IdParametroSistema);
						}
						document.formulario.Redimensionar[0].selected = true;
					}
				}
			}
		}
		xmlhttp.send(null);	
	}
	
	var fotos 	= new  Array();
	var desc 	= new  Array();
	var produto	= new  Array();
	
	function visualizarFoto(pos){
		mensagens(0);
		
		document.getElementById('foto_G').src 	= prefixo + fotos[pos];
		document.formulario.DescricaoFoto.value	= desc[pos]; 	
		document.formulario.IdProdutoFoto.value	= produto[pos]; 	
	}
	
	function addFoto(){
		document.getElementById('foto_G').src 		= '../../img/estrutura_sistema/indisponivel.jpg';
		document.formulario.DescricaoFoto.value		= ""; 	
		document.formulario.IdProdutoFoto.value		= ""; 
		document.formulario.Acao.value				= 'inserir';	
		
		document.getElementById('tabela_foto').deleteRow(1);
		tabela_nova();
		
		verificaAcao();
	}
