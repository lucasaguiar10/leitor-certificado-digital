$(document).ready(function()
{


	$('#formulario-certificado-digital').submit(function(e)
	{			
		e.preventDefault();
		var formulario = $(this);
		let retorno_transferir = salvarCertificado();	
		
	});



	function salvarCertificado()
	{	

		let formulario = $('#formulario-certificado-digital')[0];
		let data = new FormData(formulario);

		$.ajax
		({		
			url:"php/salvar-certificado.php",		
			type:"POST",
			data: data,
			processData: false,
			contentType: false	
			
		}).done(function(data)
		{	
			console.log(data);			
			alert(data.razaosocial);
			alert(data.cnpj);
			alert(data.validade);
		
			$('#senha-certificado').val("");
			$('#certificado-digital').val("");
		
		}).fail(function()
		{
			
		}).always(function()
		{

		});		
		
	}



});
	
