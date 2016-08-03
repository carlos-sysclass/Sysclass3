{assign var="info" value=$T_DATA.data}
<div class="widget">
	<div class="widget-body">
		<form class="form-clientes" method="{$T_DATA.method}" action="{$T_DATA.action}">
			<h4 class="heading-arrow">Dados Gerais</h4>
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label for="firstname" class="control-label">Nome</label>
						<div class="controls"><input type="text" name="firstname" class="span12" value="{$info.firstname}"></div>
					</div>
					<div class="control-group">
						<label for="lastname" class="control-label">Sobrenome</label>
						<div class="controls"><input type="text" name="lastname" class="span12" value="{$info.lastname}"></div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group">
						<label for="username" class="control-label">CPF</label>
						<div class="controls"><input type="text" name="cpf" class="span12" rel="cpf" value="{$info.cpf}"></div>
					</div>
					<div class="control-group">
						<label for="username" class="control-label">CNPJ</label>
						<div class="controls"><input type="text" name="cnpj" class="span12" rel="cnpj" value="{$info.cnpj}"></div>
					</div>
				</div>
			</div>
			<h4 class="heading-arrow">Endereço</h4>
			<div class="row-fluid">
				<div class="span2">
					<div class="control-group">
						<label for="firstname" class="control-label">CEP</label>
						<div class="controls">
							<div class="input-append">
					  			<input type="text" name="cep" placeholder="00000000" id="appendedInputButtons" class="span12" rel="cep" value="{$info.cep}">
					  			<button type="button" name="cep-search" class="btn"><i class="icon-search"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span9">
					<div class="control-group">
						<label for="password" class="control-label">Endereço</label>
						<div class="controls"><input type="text" name="endereco" class="span12" value="{$info.endereco}"></div>
					</div>
				</div>
				<div class="span3">
					<div class="control-group">
						<label for="confirm_password" class="control-label">Número</label>
						<div class="controls"><input type="text" name="numero" class="span12" rel="number" value="{$info.numero}"></div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span4">
					<div class="control-group">
						<label for="password" class="control-label">Bairro</label>
						<div class="controls"><input type="text" name="bairro" class="span12" value="{$info.bairro}"></div>
					</div>
				</div>
				<div class="span4">
					<div class="control-group">
						<label for="confirm_password" class="control-label">Cidade</label>
						<div class="controls"><input type="text" name="cidade" class="span12" value="{$info.cidade}"></div>
					</div>
				</div>
				<div class="span4">
					<div class="control-group">
						<label for="password" class="control-label">Estado</label>
						<div class="controls"><input type="text" name="estado" class="span12" value="{$info.estado}"></div>
					</div>
				</div>
			</div>
			<h4 class="heading-arrow">Dados de Contato</h4>
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label for="username" class="control-label">Telefone</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on glyphicons phone"><i></i></span>
								<input type="text" name="telefone" class="input-large" placeholder="(xx) 9999-9999" rel="phone" value="{$info.telefone}" />
							</div>
						</div>
					</div>
					<div class="control-group">
						<label for="username" class="control-label">Email</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on glyphicons envelope"><i></i></span>
								<input type="text" name="email" class="input-large" placeholder="contato@cliente.com.br" value="{$info.email}" />
							</div>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group">
						<label for="username" class="control-label">Facebook</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on glyphicons facebook"><i></i></span>
								<input type="text" name="facebook" class="input-large" placeholder="/facebookID" value="{$info.facebook}" />
							</div>
						</div>
					</div>
					<div class="control-group">
						<label for="username" class="control-label">Skype ID</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on glyphicons skype"><i></i></span>
								<input type="text" name="skype" class="input-large" placeholder="skypeID" value="{$info.skype}" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr class="separator">

			<div class="form-actions">
				<button class="btn btn-icon btn-primary glyphicons circle_ok" type="submit"><i></i>Salvar</button>
				<!--
				<button class="btn btn-icon btn-default glyphicons circle_remove" type="button"><i></i></button>
				-->
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		var form = jQuery("form.form-clientes");

		jQuery(':input').setMask({
			attr: "rel"
		});

		jQuery("button[name='cep-search']").click(function(){
			var cep = jQuery("input[name='cep']").val();
			if(cep!="" && cep.length==9) {
				var url = "/cep/search/" + cep;
				jQuery.get(
					url,
					null,
					function(response, status) {
						if (response.resultado == 1) {
							jQuery("input[name='endereco']").val(response.tipo_logradouro + " " + response.logradouro);
							jQuery("input[name='bairro']").val(response.bairro);
							jQuery("input[name='cidade']").val(response.cidade);
							jQuery("input[name='estado']").val(response.uf);
							jQuery("input[name='numero']").focus();
						} else {
							alert(response.resultado_txt);
						}
					},
					'json'
				);
			}
		});

		form.validate({
			debug: false,
			rules : {
				'firstname'	: {
					required: true
				},
				'lastname'	: {
					required: true
				},
				'cpf'		: {
					cpf: true
				},
				'cnpj'		: {
					cnpj: true
				},
				'email'	: {
					email: true
				}
			},
			ignoreTitle : true,
			submitHandler: function(f) {
				f.submit();
			},
			showErrors: function(map, list) {
				this.currentElements.parents('label:first, .controls:first').find('.error').remove();
				this.currentElements.parents('.control-group:first').removeClass('error');
				
				$.each(list, function(index, error) 
				{
					var ee = $(error.element);
					var eep = ee.parents('label:first').length ? ee.parents('label:first') : ee.parents('.controls:first');
					
					ee.parents('.control-group:first').addClass('error');
					eep.find('.error').remove();
					eep.append('<p class="error help-block"><span class="label label-important">' + error.message + '</span></p>');
				});
				//refreshScrollers();
			}
		});
	});
</script>