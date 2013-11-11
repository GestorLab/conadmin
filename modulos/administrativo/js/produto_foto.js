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
								url = 'cadastro_produto_foto.php?IdProduto='+IdProduto+'&Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							
							if(numMsg == 91){
								var aux = 0, medio=0, ultima=0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdProduto+"_"+IdProdutoFoto == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar');
										break;
									}
								}
								document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
								
								//url = 'listar_produto_foto.php?IdProduto='+IdProduto+'&Erro='+document.formulario.Erro.value;
								window.location.reload( true );
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
		if(document.formulario.IdProduto.value == ''){
			mensagens(1);
			document.formulario.IdProduto.focus();
			return false;
		}
		if(document.formulario.NomeArquivo.value == ''){
			mensagens(1);
			document.formulario.NomeArquivo.focus();
			return false;
		}
		var endFoto = document.formulario.EndFoto.value;
		var tipo    = endFoto.substring(endFoto.length-4,endFoto.length);
			
		tipo = tipo.toLowerCase();
		if(tipo == ''){
			mensagens(1);
			document.formulario.Foto.focus();
			return false;
		}else{
			if(tipo == ".jpg" || tipo == ".jpeg" || tipo == ".png" || tipo == ".gif"){
				if(document.formulario.Redimensionar.value==0){
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
	function ativa_imagem(endFoto){
		if(endFoto == '' || endFoto == undefined){
						
			document.getElementById('quadroFotoFoto').src			 = "../../img/estrutura_sistema/sem_foto.gif";
			document.formulario.EndFoto.value 						 = "";
			return false;
		}else{
			var tipo = endFoto.substring(endFoto.length-4,endFoto.length);
			
			tipo = tipo.toLowerCase();
			if(tipo == ".jpg" || tipo == ".jpeg" || tipo == ".png" || tipo == ".gif"){
				document.getElementById('quadroFotoFoto').src			  = endFoto;
				document.formulario.EndFoto.value 						  = endFoto;
			}
		}
		return true;
	}
	function inicia(){
		document.formulario.IdProduto.focus();
	}
