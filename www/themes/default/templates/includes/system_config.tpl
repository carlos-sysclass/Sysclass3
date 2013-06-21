{capture name = "view_config"}
	{if !$smarty.get.op || $smarty.get.op == 'general'}
		{capture name="general_security"}
			{sC_template_printForm form=$T_GENERAL_SECURITY_FORM}
		{/capture}
		{capture name = "general_locale"}
			{sC_template_printForm form=$T_GENERAL_LOCALE_FORM}
		{/capture}
		{capture name = "general_smtp"}
			{sC_template_printForm form=$T_GENERAL_SMTP_FORM}
		{/capture}
		{capture name = "external_php"}
			{sC_template_printForm form=$T_GENERAL_PHP_FORM}
		{/capture}

		<div class="tabber">
			{sC_template_printBlock tabber = "main" title=$smarty.const._GENERALSETTINGS data=$smarty.capture.general_main image='32x32/settings.png'}
			{sC_template_printBlock tabber = "security" title=$smarty.const._SECURITYSETTINGS data=$smarty.capture.general_security image='32x32/generic.png'}
			{sC_template_printBlock tabber = "locale" title=$smarty.const._LOCALE data=$smarty.capture.general_locale image='32x32/languages.png'}
			{sC_template_printBlock tabber = "smtp" title=$smarty.const._EMAILSETTINGS data=$smarty.capture.general_smtp image='32x32/mail.png'}
			{sC_template_printBlock tabber = "php" title=$smarty.const._PHP data=$smarty.capture.external_php image='32x32/php.png'}
		</div>

	{elseif $smarty.get.op == 'user'}
		{capture name = "user_main"}
			{sC_template_printForm form=$T_USER_MAIN_FORM}
		{/capture}
		{capture name = "user_multiple_logins"}
			{sC_template_printForm form=$T_USER_MULTIPLE_LOGINS_FORM}
		{/capture}
		{capture name = "user_webserver_authentication"}
			{sC_template_printForm form=$T_USER_WEBSERVER_AUTHENTICATION_FORM}
		{/capture}

		<div class="tabber">
			{sC_template_printBlock tabber = "main" title=$smarty.const._USERACTIVATIONSETTINGS data=$smarty.capture.user_main image='32x32/user.png'}
			{sC_template_printBlock tabber = "multiple_logins" title=$smarty.const._MULTIPLELOGINS data=$smarty.capture.user_multiple_logins image='32x32/users.png'}
			{sC_template_printBlock tabber = "webserver_authentication" title=$smarty.const._WEBSERVERAUTHENTICATION data=$smarty.capture.user_webserver_authentication image='32x32/generic.png'}
		</div>
	{elseif $smarty.get.op == 'appearance'}
		{capture name = "appearance_main"}
			{sC_template_printForm form=$T_APPEARANCE_MAIN_FORM}
		{/capture}
		{capture name = "appearance_logo"}
			{sC_template_printForm form=$T_APPEARANCE_LOGO_FORM}
		{/capture}
		{capture name = "appearance_favicon"}
			{sC_template_printForm form=$T_APPEARANCE_FAVICON_FORM}
		{/capture}

		<div class="tabber">
			{sC_template_printBlock tabber = "main" title=$smarty.const._APPEARANCE data=$smarty.capture.appearance_main image='32x32/layout.png'}
			{sC_template_printBlock tabber = "logo" title=$smarty.const._LOGO data=$smarty.capture.appearance_logo image='32x32/themes.png'}
			{sC_template_printBlock tabber = "favicon" title=$smarty.const._FAVICON data=$smarty.capture.appearance_favicon image='32x32/themes.png'}
		</div>
	{elseif $smarty.get.op == 'external'}
		{capture name = "external_main"}
			{sC_template_printForm form=$T_EXTERNAL_MAIN_FORM}
		{/capture}
		{capture name = "external_math"}
			{sC_template_printForm form=$T_EXTERNAL_MATH_FORM}
		{/capture}
		{capture name = "external_livedocx"}
			{sC_template_printForm form=$T_EXTERNAL_LIVEDOCX_FORM}
		{/capture}
		<div class="tabber">
			{sC_template_printBlock tabber = "options" title=$smarty.const._EXTERNALTOOLS data=$smarty.capture.external_main image='32x32/generic.png'}
			{sC_template_printBlock tabber = "math" title=$smarty.const._MATHSETTINGS data=$smarty.capture.external_math image='32x32/generic.png'}
			{sC_template_printBlock tabber = "livedocx" title=$smarty.const._PHPLIVEDOCX data=$smarty.capture.external_livedocx image='32x32/generic.png'}
			{sC_template_printBlock tabber = "ldap" title=$smarty.const._LDAP data=$smarty.capture.external_ldap image='32x32/generic.png'}
		</div>
	{elseif $smarty.get.op == 'customization'}
		{capture name = "customization_disable"}
			{sC_template_printForm form=$T_CUSTOMIZATION_DISABLE_FORM}
		{/capture}
		<div class="tabber">
			{sC_template_printBlock tabber = "disable" title=$smarty.const._DISABLEOPTIONS data=$smarty.capture.customization_disable image='32x32/generic.png'}
			{sC_template_printBlock tabber = "social" title=$smarty.const._SOCIALOPTIONS data=$smarty.capture.customization_social image='32x32/social.png'}
			{sC_template_printBlock tabber = "enterprise" title=$smarty.const._ENTERPRISEOPTIONS data=$smarty.capture.customization_enterprise image='32x32/enterprise.png'}
		</div>
	{/if}
{/capture}
{*moduleConfig: The configuration settings page*}
{capture name = "moduleConfig"}
	<tr><td class="moduleCell">
			{sC_template_printBlock title = $smarty.const._CONFIGURATIONVARIABLES data = $smarty.capture.view_config image='32x32/tools.png' help = 'System_settings' main_options = $T_TABLE_OPTIONS options = $T_THEMES_LINK}
		</td></tr>
	{/capture}
