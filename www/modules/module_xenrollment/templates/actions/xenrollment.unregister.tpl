{capture name="t_xenrollment_history_form"}
	{include 
		file="$T_XENROLLMENT_BASEDIR/templates/includes/xenrollment.history.form.tpl"
	}
{/capture}

{if $smarty.get.output == 'innerhtml'}
	{$smarty.capture.t_xenrollment_history_form}
{else}
	{eF_template_printBlock
		title 			= $smarty.const.__XENROLLMENT_UNREGISTER_HISTORY
		data			= $smarty.capture.t_xenrollment_history_form
		contentclass	= "block no_padding"
	}
{/if}