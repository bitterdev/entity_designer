<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Enumeration;

abstract class FieldType
{
    const FIELD_TYPE_FILE = 'file';
    const FIELD_TYPE_PAGE_SELECTOR = 'page_selector';
    const FIELD_TYPE_TEXT = 'text';
    const FIELD_TYPE_EMAIL = 'email';
    const FIELD_TYPE_TELEPHONE = 'telephone';
    const FIELD_TYPE_PASSWORD = 'password';
    const FIELD_TYPE_WEB_ADDRESS = 'url';
    const FIELD_TYPE_TEXT_AREA = 'textarea';
    const FIELD_TYPE_NUMBER = 'number';
    const FIELD_TYPE_RADIO_BUTTONS = 'radio';
    const FIELD_TYPE_CHECKBOX_LIST = 'checkbox';
    const FIELD_TYPE_SELECT_BOX = 'select';
    const FIELD_TYPE_ASSOCIATION = 'association';
}