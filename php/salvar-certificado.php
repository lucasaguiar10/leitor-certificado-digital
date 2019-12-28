<?php

				
	date_default_timezone_set('Etc/GMT+3');
	setlocale(LC_ALL, "", "pt_BR.utf-8");
	header("Content-Type: application/json; charset=utf-8");
	
	//PREENCHE AS VARIÁVEIS COM OS DADOS VINDOS DOS CAMPOS DO FORMULÁRIO	
	$senha = filter_input(INPUT_POST, 'senha-certificado', FILTER_SANITIZE_STRING); 
	
	//SE HOUVER ARQUIVO, FAZ INSERÇÃO
	if(!empty($_FILES['certificado-digital']['name']))
	{
		//UPLOAD DE ARQUIVO  VINDO DO FORMULÁRIO
		$file = $_FILES["certificado-digital"]["tmp_name"];		
								
		if($_FILES["certificado-digital"]["error"][0] != 4)
		{				

			$certs = array ();
			$pkcs12 = file_get_contents($file);					
			
			if( openssl_pkcs12_read($pkcs12, $certs, $senha) )
			{				
				$dados = array ();
				$dados = openssl_x509_parse( openssl_x509_read($certs['cert']) );
				
				$privatekey = $certs['pkey'];
				
				$pub_key = openssl_pkey_get_public($certs['cert']);
				$keyData = openssl_pkey_get_details($pub_key);

				$publickey = $keyData['key'];
							
				
			  //Dados mais importantes	
			  
			   /*			   
			   echo '<br>'.'<br>'.'--- Dados do Certificado ---'.'<br>'.'<br>';
			   echo $dados['name'].'<br><br>';                           //Nome
			   echo $dados['hash'].'<br><br>';                           //hash
			   echo $dados['subject']['C'].'<br><br>';                   //País
			   echo $dados['subject']['ST'].'<br><br>';                  //Estado
			   echo $dados['subject']['L'].'<br><br>';                   //Município
			   echo $dados['subject']['CN'].'<br><br>';                  //Razão Social e CNPJ / CPF
			   echo date('d/m/Y', $dados['validTo_time_t'] ).'<br><br>'; //Validade
			   echo $dados['extensions']['subjectAltName'].'<br><br>';   //Emails Cadastrados separado por ,
			   echo $dados['extensions']['authorityKeyIdentifier'].'<br><br>'; 
			   echo $dados['issuer']['OU'].'<br><br>';                   //Emissor 
			   echo '<br>'.'<br>'.'--- Chave Pública ---'.'<br>'.'<br><br>';
			   print_r($publickey);
			   echo '<br>'.'<br>'.'--- Chave Privada ---'.'<br>'.'<br><br>';
			   print_r($privatekey);
			   */
			   
			   $retorno = []; 
			   $razao_cnpj = explode(":", $dados['subject']['CN']);
			   $retorno['razaosocial'] =  $razao_cnpj[0];
			   $retorno['cnpj'] = $razao_cnpj[1];
			   $retorno['validade'] = date('d/m/Y', $dados['validTo_time_t']);
			   
			   echo json_encode($retorno);
			   
			}
			
		}	
	}	

?>