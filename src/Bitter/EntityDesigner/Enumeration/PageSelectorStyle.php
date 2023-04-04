<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Enumeration;

abstract class PageSelectorStyle
{
    const PAGE_SELECTOR_STYLE_QUICK_SELECT = 'quick_select';
    const PAGE_SELECTOR_STYLE_SELECT_PAGE = 'select_page';
    const PAGE_SELECTOR_STYLE_SELECT_FROM_SITEMAP = 'select_from_sitemap';
}