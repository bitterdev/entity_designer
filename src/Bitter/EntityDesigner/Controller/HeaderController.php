<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Controller;

use Concrete\Core\Controller\ElementController;

class HeaderController extends ElementController
{
    protected $pkgHandle = 'entity_designer';
    protected $actions = [];
    protected $url = 'javascript:void(0);';
    protected $faIconClass = 'plus';
    protected $label = '';

    /**
     * @noinspection PhpUnused
     * @return string
     */
    public function getPkgHandle()
    {
        return $this->pkgHandle;
    }

    /**
     * @noinspection PhpUnused
     * @param string $pkgHandle
     * @return HeaderController
     */
    public function setPkgHandle($pkgHandle)
    {
        $this->pkgHandle = $pkgHandle;
        return $this;
    }

    /**
     * @noinspection PhpUnused
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @noinspection PhpUnused
     * @param array $actions
     * @return HeaderController
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return HeaderController
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @noinspection PhpUnused
     * @return string
     */
    public function getFaIconClass()
    {
        return $this->faIconClass;
    }

    /**
     * @noinspection PhpUnused
     * @param string $faIconClass
     * @return HeaderController
     */
    public function setFaIconClass($faIconClass)
    {
        $this->faIconClass = $faIconClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return HeaderController
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }


    public function getElement()
    {
        return 'dashboard/header';
    }

    public function view()
    {
        $this->set("url", $this->url);
        $this->set("faIconClass", $this->faIconClass);

        if ($this->label == "") {
            $this->set("label", t("Add"));
        } else {
            $this->set("label", $this->label);
        }

    }

}