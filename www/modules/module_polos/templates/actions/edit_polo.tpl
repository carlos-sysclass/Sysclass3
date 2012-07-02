{capture name="t_edit_polos_form"}
	{include file="$T_MODULE_POLOS_BASEDIR/templates/includes/polo_form.tpl"}
{/capture}

{eF_template_printBlock 
	title=$smarty.const._MODULE_POLOS_EDITPOLO
	data=$smarty.capture.t_edit_polos_form
	contentclass=""
}