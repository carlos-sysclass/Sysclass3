{extends file="layout/default-noauth.tpl"}
{block name="menu"}{/block}
{block name="menu-closing"}{/block}
{block name="content"}
	<div id="login">
		<form class="form-signin" method="post" action="/painel/login/">
			<h3 class="glyphicons unlock form-signin-heading"><i></i> Acesso ao Painel</h3>
			<div class="uniformjs">
				<div class="control-group">
					<div class="controls"><input name="email" type="text" class="input-block-level" placeholder="Email"></div>
				</div>	
				<div class="control-group">
					<div class="controls"><input name="password" type="password" class="input-block-level" placeholder="Senha"></div>
				</div>
				<div class="uniformjs control-group">
					<label class="checkbox">
					<div class="controls"><input name="remember" type="checkbox" value="1">Lembrar meu acesso</label></div>
				</div>
			</div>
			<button class="btn btn-large btn-primary" type="submit">Entrar</button>
		</form>
	</div>

	<script type="text/javascript">
		$(function() {
			var form = jQuery("form.form-signin");
			form.validate({
				debug: false,
				rules : {
					'email'	: {
						required: true,
						email: true
					},
					'password'		: {
						required: true
					}
				},
				ignoreTitle : true,
				submitHandler: function() {
					console.log(form);
					jQuery.ajax({
						url: form.attr("action"), 
						type: 'post',
						data: form.serialize(),
						dataType: 'json',
						success: function(json) {
							if (json['error']) {
								if (json['error']['warning']) {
								}
							} else {
								if (json['redirect']) {
									location = json['redirect'];
								}
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
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
{/block}