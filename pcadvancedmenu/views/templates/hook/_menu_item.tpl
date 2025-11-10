{*
* Menu Item Partial Template
*}

{if isset($item.children) && count($item.children) > 0}
  {* Item with submenu *}
  <li class="menu-item menu-item-{$item.item_type|escape:'htmlall':'UTF-8'}">
    <div class="openLevel js-openLevel">
      {if $item.icon}
        <i class="{$item.icon|escape:'htmlall':'UTF-8'}"></i>
      {/if}
      {if $item.image_url}
        <img src="{$item.image_url|escape:'htmlall':'UTF-8'}" alt="{$item.title|escape:'htmlall':'UTF-8'}" class="menu-item-image">
      {/if}
      {$item.title|escape:'htmlall':'UTF-8'}
      <i class="fa fa-chevron-right pull-right"></i>
    </div>

    <ul class="pushNav pushNav_level js-pushNavLevel">
      <li class="closeLevel js-closeLevel hdg">
        <i class="fa fa-chevron-left"></i>
        {l s='Go Back' mod='pcadvancedmenu'}
      </li>
      {foreach from=$item.children item=child}
        {include file="module:pcadvancedmenu/views/templates/hook/_menu_item.tpl" item=$child}
      {/foreach}
    </ul>
  </li>
{else}
  {* Simple link item *}
  <li class="menu-item menu-item-{$item.item_type|escape:'htmlall':'UTF-8'}">
    <a href="{$item.link|escape:'htmlall':'UTF-8'}">
      {if $item.icon}
        <i class="{$item.icon|escape:'htmlall':'UTF-8'}"></i>
      {/if}
      {if $item.image_url}
        <img src="{$item.image_url|escape:'htmlall':'UTF-8'}" alt="{$item.title|escape:'htmlall':'UTF-8'}" class="menu-item-image">
      {/if}
      {$item.title|escape:'htmlall':'UTF-8'}
    </a>
  </li>
{/if}
