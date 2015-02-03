<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<title>Relatório</title>
{literal}
<style type="text/css">
    * { font-weight: normal; font-size: 1em; font-family: Arial, Verdana, sans-serif;}
    h1 { font-size: 1.3em; }
    h2 { font-size: 1.15em; }
</style>
{/literal}
</head>
<body>
<ul style="list-style-type: none; padding-bottom: 10px;">
{foreach from=$T_AREA51_REPORTS key=COURSE item=LESSONS}
    <li><h1>{$COURSE}</h1>
    <ol style="list-style-type: upper-roman;padding-left: 20px; padding-bottom: 10px;">
    {foreach from=$LESSONS key=LESSON item=QUESTIONS}
        <li><h2>{$LESSON}</h2>
        <ol style="list-style-type: decimal; padding-left: 20px; padding-bottom: 10px;">
        {foreach from=$QUESTIONS key=QUESTION item=ANSWERS}
            <li>{$QUESTION}
            <ol style="list-style-type: lower-alpha; padding-left: 20px; padding-bottom: 10px;">
            {foreach from=$ANSWERS key=ANSWER item=VALUE}
                <li>{if not is_numeric($ANSWER)}{$ANSWER} - {/if}{$VALUE}</li>
            {/foreach}
            </ol>
            </li>
        {/foreach}
        </ol>
        </li>
    {/foreach}
    </ol>
    </li>
{/foreach}
</ul>

</body>
</html>
