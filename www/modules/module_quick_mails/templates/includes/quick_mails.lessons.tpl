{capture name="quick_mail_lesson_contacts"}
	{include 
		file="$T_QUICK_MAILS_BASEDIR/templates/includes/quick_mails.list.tpl"
		T_QUICK_MAILS_CONTACT_LIST=$T_QUICK_MAILS_CONTACT_LIST
	}
{/capture}
{eF_template_printBlock
	title 				= $smarty.const.__QUICK_MAILS_CONTACTS
	data				= $smarty.capture.quick_mail_lesson_contacts
	contentclass		= "blockContents"
	class				= ""
}