{foreach key="group_key" item="contact_list" from=$T_QUICK_MAILS_CONTACT_LIST}
	{include 
		file="$T_QUICK_MAILS_BASEDIR/templates/includes/quick_mails.list.tpl"
		T_QUICK_MAILS_CONTACT_LIST=$contact_list
		T_QUICK_MAILS_CONTACT_CLASS="quick_mails-contact-list quick_mails-contact-list-$group_key"
	}
{/foreach}