<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Enumeration;

abstract class FileType
{
    const FILE_TYPE_ALL = 'all';
    const FILE_TYPE_VIDEO = 'video';
    const FILE_TYPE_TEXT = 'text';
    const FILE_TYPE_AUDIO = 'audio';
    const FILE_TYPE_DOC = 'doc';
    const FILE_TYPE_APP = 'app';
}