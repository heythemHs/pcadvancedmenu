{*
* PC Advanced Menu Template
*}

{if Configuration::get('PC_ADVANCED_MENU_ENABLED')}
<nav class="pc-advanced-menu" data-position="{$menu_position|escape:'htmlall':'UTF-8'}">
  <ul class="pushNav js-topPushNav">
    <li class="closeLevel js-closeLevelTop hdg">
      <i class="fa fa-close"></i>
      {l s='Close' mod='pcadvancedmenu'}
    </li>

    {foreach from=$menu_items item=item}
      {if $item.item_type == 'tab'}
        {* Tab with submenu *}
        <li class="menu-item-tab">
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

          {if isset($item.children) && count($item.children) > 0}
            <ul class="pushNav pushNav_level js-pushNavLevel">
              <li class="closeLevel js-closeLevel hdg">
                <i class="fa fa-chevron-left"></i>
                {l s='Go Back' mod='pcadvancedmenu'}
              </li>
              {foreach from=$item.children item=child}
                {include file="module:pcadvancedmenu/views/templates/hook/_menu_item.tpl" item=$child}
              {/foreach}
            </ul>
          {/if}
        </li>
      {else}
        {* Regular menu item *}
        {include file="module:pcadvancedmenu/views/templates/hook/_menu_item.tpl" item=$item}
      {/if}
    {/foreach}

  </ul>
</nav>

<div class="burger js-menuToggle">
  <i class="fa fa-navicon"></i>
</div>

<span class="screen"></span>
{/if}
