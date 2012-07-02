{capture name="webtutoria_question"}
	<ul class="xwebtutoria-list">		
		<li class="lista grid_24">
			<div class="foto grid_4 alpha">
				<img width="47" height="47" src="" alt="">
				<div>{$T_XWEBTUTORIA_QUESTION.username}</div>
			</div>
			<div class="pergunta grid_20 omega">
				<div class="texto">
					<p>
					{$T_XWEBTUTORIA_QUESTION.body}
					</p>
				</div>
			</div>
		</li>
	</ul>
	<div class="clear"></div>
{/capture}

{capture name="t_xwebtutoria_register_form"}
	{$T_XWEBTUTORIA_REGISTER_FORM.javascript}
	<form {$T_XWEBTUTORIA_REGISTER_FORM.attributes}>
		{$T_XWEBTUTORIA_REGISTER_FORM.hidden}
		
		<label>{$T_XWEBTUTORIA_REGISTER_FORM.classe_id.label}</label>
		{$T_XWEBTUTORIA_REGISTER_FORM.classe_id.html}
		<div class="clear"></div>
		
		{$T_XWEBTUTORIA_REGISTER_FORM.body.html}
		
		
		<div class="clear"></div>
		
		<div class="grid_24" style="margin-top: 20px; margin-bottom: 20px;">
			<button class="button_colour round_all" type="submit" name="{$T_XWEBTUTORIA_REGISTER_FORM.submit_xwebtutoria.name}" value="{$T_XWEBTUTORIA_REGISTER_FORM.submit_xwebtutoria.value}">
				<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
				<span>{$T_XWEBTUTORIA_REGISTER_FORM.submit_xwebtutoria.value}</span>
			</button>
		</div>		
		
	</form>
{/capture}


{if $smarty.get.output == 'innerhtml'}
	{$smarty.capture.t_xwebtutoria_register_form}
{else}
	{if isset($T_XWEBTUTORIA_QUESTION)}
		{eF_template_printBlock
			title 			= $smarty.const.__XWEBTUTORIA_QUESTION_TO_ANSWER
			data			= $smarty.capture.webtutoria_question
			contentclass	= "xwebtutoria-list"
			class			= ""
			options			= ""
			absoluteImagePath	= true
		}
	{/if}

	{eF_template_printBlock
		title 			= $T_XWEBTUTORIA_BODY_TITLE
		data			= $smarty.capture.t_xwebtutoria_register_form
	}
{/if}