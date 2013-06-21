{* template functions for inner table *}
{capture name = 't_inner_table_code}
    <table>
        {section name = 'links_list' loop = $T_LINKS_INNERTABLE max = $T_LINKS_MAX_LINKS}
            <tr><td>{$smarty.section.links_list.iteration}. 
                <a href = "{$T_LINKS_INNERTABLE[links_list].link}">{$T_LINKS_INNERTABLE[links_list].display}</a>
                </td>
            </tr>
        {sectionelse}
            <tr><td class = "emptyCategory">{$smarty.const._LINKS_NOLINKFOUND}</td></tr>
        {/section}
        {if $T_LINKS_INNERTABLE_COUNT > $T_LINKS_MAX_LINKS}
        	<tr><td>&nbsp;</td></tr>
        	<tr><td>{$T_LINKSCOUNTMESSAGE} {$smarty.const._LINKS_CLICK} <a href="{$T_LINKS_BASEURL}">{$smarty.const._LINKS_HERE}</a> {$smarty.const._LINKS_TOSHOW}</td></tr>
        {/if}
    </table>
{/capture}

{eF_template_printBlock 
	title = $smarty.const._LINKS_LINKSPAGE 
	data = $smarty.capture.t_inner_table_code 

	image = $T_LINKS_BASELINK|cat:'images/link.png' 
	options = $T_LINKS_INNERTABLE_OPTIONS
	contentclass="module-links-innerhtml blockContents"
}