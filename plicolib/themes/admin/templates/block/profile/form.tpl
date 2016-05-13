{assign var="info" value=$T_DATA.data}
<div class="widget widget-tabs border-bottom-none">
	<div class="widget-head">
		<ul>
			<li class="active"><a class="glyphicons edit" href="#account-details" data-toggle="tab"><i></i>Account details</a></li>
			<li><a class="glyphicons settings" href="#account-settings" data-toggle="tab"><i></i>Account settings</a></li>
		</ul>
	</div>
	<div class="widget-body">
		<form class="form-profile" method="{$T_DATA.method}" action="{$T_DATA.action}" style="margin: 0;">
		<div class="tab-content" style="padding: 0;">
			<div class="tab-pane active" id="account-details">
			
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group">
							<label class="control-label">Nome</label>
							<div class="controls">
								<input type="text" name="nome" value="{$info.nome}" class="span10" />
								<span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="First name is mandatory"><i></i></span>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Sobrenome</label>
							<div class="controls">
								<input type="text" name="sobrenome" value="{$info.sobrenome}" class="span10" />
								<span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="Last name is mandatory"><i></i></span>
							</div>
						</div>
						
					</div>
					<div class="span6">
						<div class="control-group">
							<label class="control-label">Sexo</label>
							<div class="controls">
								<select name="sex" class="span12">
									<option value="0" {if $info.sex == 0}selected="selected"{/if}>Selecione...</option>
									<option value="1" {if $info.sex == 1}selected="selected"{/if}>Masculino</option>
									<option value="2" {if $info.sex == 2}selected="selected"{/if}>Feminino</option>
								</select>
							</div>
						</div>
						<!--
						<div class="control-group">
							<label class="control-label">Data de Nascimento</label>
							<div class="controls">
								<div class="input-append">
									<input type="text" id="datepicker" class="span12" value="13/06/1988" />
									<span class="add-on glyphicons calendar"><i></i></span>
								</div>
							</div>
						</div>
						-->
					</div>
				</div>
				<div class="separator line bottom"></div>
				<div class="control-group row-fluid">
					<label class="control-label">Sobre você</label>
					<div class="controls">
						<textarea id="mustHaveId" name="about_me" class="wysihtml5 span12" rows="5"></textarea>
					</div>
				</div>
				<div class="form-actions" style="margin: 0;">
					<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Salvar</button>
				</div>
			</div>
			<div class="tab-pane" id="account-settings">
				<div class="row-fluid">
					<div class="span3">
						<strong>Alterar Senha</strong>
						<p class="muted">Caso deseje alterar sua senha, digite os campos .</p>
					</div>
					<div class="span9">
						<label for="inputUsername">Email</label>
						<input type="text" name="login" id="inputUsername" class="span10" value="{$info.login}" disabled="disabled" />
						<span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="O e-mail usado para login não pode ser alterado"><i></i></span>
						
								
						<label for="inputPasswordOld">Senha atual</label>
						<input type="password" name="password[old]" id="inputPasswordOld" class="span10" value="" placeholder="Deixe em branco para não alterar" />
						<span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="Deixe em branco caso não deseje alterar a sua senha."><i></i></span>
						
						<label for="inputPasswordNew">Nova senha</label>
						<input type="password" name="password[new]" id="inputPasswordNew" class="span10" value="" placeholder="Deixe em branco para não alterar" />
						
						
						<label for="inputPasswordNew2">Confirme</label>
						<input type="password" name="password[confirm]" id="inputPasswordNew2" class="span10" value="" placeholder="Deixe em branco para não alterar" />
						
					</div>
				</div>
				<div class="separator line bottom"></div>
				<div class="row-fluid">
					<div class="span3">
						<strong>Detalhes de Contato</strong>
						<p class="muted">Deixe aqui as suas informações de contato. Todas essas informações são opcionais.</p>
					</div>
					<div class="span9">
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label for="telefone" class="control-label">Telefone</label>
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
								<div class="control-group">
									<label for="website" class="control-label">Website</label>
									<div class="controls">
										<div class="input-prepend">
											<span class="add-on glyphicons envelope"><i></i></span>
											<input type="text" name="website" class="input-large" placeholder="www.cliente.com.br" value="{$info.website}" />
										</div>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<label for="facebook" class="control-label">Facebook</label>
									<div class="controls">
										<div class="input-prepend">
											<span class="add-on glyphicons facebook"><i></i></span>
											<input type="text" name="facebook" class="input-large" placeholder="/facebookID" value="{$info.facebook}" />
										</div>
									</div>
								</div>
								<div class="control-group">
									<label for="twitter" class="control-label">Twitter</label>
									<div class="controls">
										<div class="input-prepend">
											<span class="add-on glyphicons twitter"><i></i></span>
											<input type="text" name="twitter" class="input-large" placeholder="/twitterID" value="{$info.twitter}" />
										</div>
									</div>
								</div>
								<div class="control-group">
									<label for="skype" class="control-label">Skype ID</label>
									<div class="controls">
										<div class="input-prepend">
											<span class="add-on glyphicons skype"><i></i></span>
											<input type="text" name="skype" class="input-large" placeholder="skypeID" value="{$info.skype}" />
										</div>
									</div>
								</div>
								<div class="control-group">
									<label for="yahoo" class="control-label">Yahoo ID</label>
									<div class="controls">
										<div class="input-prepend">
											<span class="add-on glyphicons yahoo"><i></i></span>
											<input type="text" name="yahoo" class="input-large" placeholder="yahooID" value="{$info.yahoo}" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions" style="margin: 0;">
					<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Salvar</button>
				</div>
			</div>
		</div>
		</form>
		
	</div>
</div>
</div>		

<script type="text/javascript">
	$(function() {
		var form = jQuery("form.form-profile");

		jQuery(':input').setMask({
			attr: "rel"
		});

		form.validate({
			debug: false,
			rules : {
				'nome'	: {
					required: true
				},
				'sobrenome'	: {
					required: true
				},
				'email'	: {
					email: true
				},

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
