[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]
[{assign var="where" value=$oView->getListFilter()}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<script type="text/javascript">
<!--
window.onload = function ()
{
    top.reloadEditFrame();
    [{ if $updatelist == 1}]
        top.oxid.admin.updateList('[{ $oxid }]');
    [{ /if}]
}
//-->
</script>


<div id="liste">

<form name="search" id="search" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{include file="_formparams.tpl" cl="siocronjoblist" lstrt=$lstrt actedit=$actedit oxid=$oxid fnc="" language=$actlang editlanguage=$actlang}]
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <colgroup>
        [{block name="admin_sio_cronjob_list_colgroup"}]
        <col width="3%">
        <col width="9%">
        <col width="13%">
        <col width="13%">
        <col width="13%">
        <col width="13%">
        <col width="13%">
        <col width="13%">
        <col width="10%">
        [{/block}]
    </colgroup>
<tr class="listitem">
    [{block name="admin_sio_cronjob_list_filter"}]
    <td valign="top" class="listfilter first" height="20"  colspan="9">
        <div class="r1">
            <div class="b1">
              <div class="find">
                <select name="changelang" class="editinput" onChange="Javascript:top.oxid.admin.changeLanguage();">
                  [{foreach from=$languages item=lang}]
                  <option value="[{ $lang->id }]" [{ if $lang->selected}]SELECTED[{/if}]>[{ $lang->name }]</option>
                  [{/foreach}]
                </select>
                <input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]">
              </div>

              <input class="listedit" type="text" size="50" maxlength="128" name="where[sio_cronjob][oxtitle]" value="[{ $where.sio_cronjob.oxtitle }]">
            </div>
        </div>
    </td>
    [{/block}]
</tr>
<tr>
    [{block name="admin_sio_cronjob_list_sorting"}]
    <td class="listheader first" height="15" width="30" align="center"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'sio_cronjob', 'oxactive', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_ACTIVTITLE" }]</a></td>
    <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'sio_cronjob', 'oxsort', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_SORT" }]</a></td>
    <td class="listheader"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'sio_cronjob', 'oxtitle', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_NAME" }]</a></td>
    <td class="listheader"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'sio_cronjob', 'minute', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="SIO_CRONJOB_MINUTE" }]</a></td>
    <td class="listheader"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'sio_cronjob', 'hour', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="SIO_CRONJOB_HOUR" }]</a></td>
    <td class="listheader"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'sio_cronjob', 'day', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="SIO_CRONJOB_DAY" }]</a></td>
    <td class="listheader"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'sio_cronjob', 'month', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="SIO_CRONJOB_MONTH" }]</a></td>
    <td class="listheader"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'sio_cronjob', 'weekday', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="SIO_CRONJOB_WEEKDAY" }]</a></td>
    <td class="listheader"></td>
    [{/block}]
</tr>

[{assign var="blWhite" value=""}]
[{assign var="_cnt" value=0}]
[{foreach from=$mylist item=listitem}]
    [{assign var="_cnt" value=$_cnt+1}]
    <tr id="row.[{$_cnt}]">

    [{ if $listitem->blacklist == 1}]
        [{assign var="listclass" value=listitem3 }]
    [{ else}]
        [{assign var="listclass" value=listitem$blWhite }]
    [{ /if}]
    [{ if $listitem->getId() == $oxid }]
        [{assign var="listclass" value=listitem4 }]
    [{ /if}]
    

    [{block name="admin_sio_cronjob_list_item"}]
    <td valign="top" class="[{ $listclass}][{ if $listitem->sio_cronjob__oxactive->value == 1}] active[{/if}]" height="15"><div class="listitemfloating">&nbsp</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->sio_cronjob__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->sio_cronjob__oxsort->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->sio_cronjob__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->sio_cronjob__oxtitle->value }]</a></div></td>
    
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->sio_cronjob__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->sio_cronjob__minute->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->sio_cronjob__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->sio_cronjob__hour->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->sio_cronjob__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->sio_cronjob__day->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->sio_cronjob__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->sio_cronjob__month->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->sio_cronjob__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->sio_cronjob__weekday->value }]</a></div></td>
    
    <td class="[{ $listclass}]">[{ if !$listitem->isOx() && !$readonly}]<a href="Javascript:top.oxid.admin.deleteThis('[{ $listitem->sio_cronjob__oxid->value }]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a>[{/if}]</td>
    [{/block}]
</tr>
[{if $blWhite == "2"}]
[{assign var="blWhite" value=""}]
[{else}]
[{assign var="blWhite" value="2"}]
[{/if}]
[{/foreach}]
[{include file="pagenavisnippet.tpl" colspan="6"}]
</table>
</form>
</div>

[{include file="pagetabsnippet.tpl"}]


<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="SIO_CRONJOB_LIST_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="SIO_CRONJOB_LIST_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>

