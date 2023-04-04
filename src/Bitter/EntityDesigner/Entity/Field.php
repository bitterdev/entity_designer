<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Entity;

use Bitter\EntityDesigner\Enumeration\FieldType;
use Bitter\EntityDesigner\Enumeration\FieldValidation;
use Bitter\EntityDesigner\Enumeration\FileType;
use Bitter\EntityDesigner\Enumeration\PageSelectorStyle;
use Bitter\EntityDesigner\Enumeration\AssociationType;
use Concrete\Core\Support\Facade\Application;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity
 */
class Field implements JsonSerializable
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    protected $isPrimaryKey = false;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $handle = '';

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $prefix = '';

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $label = '';

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $suffix = '';

    /**
     * @var bool
     * @ORM\Column(type="boolean") *
     */
    protected $disabled = false;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $helpText = '';

    /**
     * @var int
     * @ORM\Column(type="integer") *
     */
    protected $maxLength = 255;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $value = '';

    /**
     * @ORM\OneToMany(targetEntity="Bitter\EntityDesigner\Entity\FieldOption", mappedBy="field", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     *
     * @var FieldOption[]
     */
    protected $options;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $placeholder = '';

    /**
     * @var int
     * @ORM\Column(type="integer") *
     */
    protected $min = 0;

    /**
     * @var int
     * @ORM\Column(type="integer") *
     */
    protected $max = 0;

    /**
     * @var int
     * @ORM\Column(type="integer") *
     */
    protected $step = 1;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $validation = FieldValidation::FIELD_VALIDATION_NONE;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $type = FieldType::FIELD_TYPE_TEXT;

    /**
     * @var bool
     * @ORM\Column(type="boolean") *
     */
    protected $displayInListView = true;

    /**
     * @var Entity
     * @ORM\ManyToOne(targetEntity="Bitter\EntityDesigner\Entity\Entity")
     * @ORM\JoinColumn(name="entityId", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $entity;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $allowedFileType = FileType::FILE_TYPE_ALL;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $pageSelectorStyle = PageSelectorStyle::PAGE_SELECTOR_STYLE_SELECT_PAGE;

    /**
     * @var bool
     * @ORM\Column(type="boolean") *
     */
    protected $isTemp = true;

    /**
     * @var Entity
     * @ORM\ManyToOne(targetEntity="Bitter\EntityDesigner\Entity\Entity")
     * @ORM\JoinColumn(name="associatedEntityId", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $associatedEntity;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $associationType = AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * @noinspection PhpUnused
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     * @return Field
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @noinspection PhpUnused
     * @return bool
     */
    public function isTemp()
    {
        return $this->isTemp;
    }

    /**
     * @param bool $isTemp
     * @return Field
     */
    public function setIsTemp($isTemp)
    {
        $this->isTemp = $isTemp;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Field
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAllowedFileType()
    {
        return $this->allowedFileType;
    }

    /**
     * @param string $allowedFileType
     * @return Field
     */
    public function setAllowedFileType($allowedFileType)
    {
        $this->allowedFileType = $allowedFileType;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageSelectorStyle()
    {
        return $this->pageSelectorStyle;
    }

    /**
     * @param string $pageSelectorStyle
     * @return Field
     */
    public function setPageSelectorStyle($pageSelectorStyle)
    {
        $this->pageSelectorStyle = $pageSelectorStyle;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        return $this->isPrimaryKey;
    }

    /**
     * @param bool $isPrimaryKey
     * @return Field
     */
    public function setIsPrimaryKey($isPrimaryKey)
    {
        $this->isPrimaryKey = $isPrimaryKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param string $handle
     * @return Field
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @return Field
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
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
     * @return Field
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param string $suffix
     * @return Field
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return Field
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasHelpText()
    {
        return strlen($this->getHelpText()) > 0;
    }

    /**
     * @return string
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * @param string $helpText
     * @return Field
     */
    public function setHelpText($helpText)
    {
        $this->helpText = $helpText;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * @param int $maxLength
     * @return Field
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Field
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return FieldOption[]
     */
    public function getOptions()
    {
        $app = Application::getFacadeApplication();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $app->make(EntityManagerInterface::class);
        return $entityManager->getRepository(FieldOption::class)->findBy(["field" => $this]);
    }

    /**
     * @param FieldOption[] $options
     * @return Field
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     * @return Field
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param int $min
     * @return Field
     */
    public function setMin($min)
    {
        $this->min = $min;
        return $this;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param int $max
     * @return Field
     */
    public function setMax($max)
    {
        $this->max = $max;
        return $this;
    }

    /**
     * @return int
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param int $step
     * @return Field
     */
    public function setStep($step)
    {
        $this->step = $step;
        return $this;
    }

    /**
     * @return string
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @param string $validation
     * @return Field
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Field
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisplayInListView()
    {
        return $this->displayInListView;
    }

    /**
     * @param bool $displayInListView
     * @return Field
     */
    public function setDisplayInListView($displayInListView)
    {
        $this->displayInListView = $displayInListView;
        return $this;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     * @return Field
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return Entity
     */
    public function getAssociatedEntity()
    {
        return $this->associatedEntity;
    }

    /**
     * @return int
     */
    public function getAssociatedEntityId()
    {
        if ($this->getAssociatedEntity() instanceof Entity) {
            return $this->getAssociatedEntity()->getId();
        } else {
            return null;
        }
    }

    /**
     * @param Entity|null $associatedEntity
     * @return Field
     */
    public function setAssociatedEntity($associatedEntity)
    {
        $this->associatedEntity = $associatedEntity;
        return $this;
    }

    /**
     * @return string
     */
    public function getAssociationType()
    {
        return $this->associationType;
    }

    /**
     * @param string $associationType
     * @return Field
     */
    public function setAssociationType($associationType)
    {
        $this->associationType = $associationType;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "handle" => $this->getHandle(),
            "prefix" => $this->getPrefix(),
            "label" => $this->getLabel(),
            "suffix" => $this->getSuffix(),
            "isDisabled" => $this->isDisabled(),
            "helpText" => $this->getHelpText(),
            "maxLength" => $this->getMaxLength(),
            "value" => $this->getValue(),
            "options" => $this->getOptions(),
            "placeholder" => $this->getPlaceholder(),
            "min" => $this->getMin(),
            "max" => $this->getMax(),
            "step" => $this->getStep(),
            "validation" => $this->getValidation(),
            "type" => $this->getType(),
            "isDisplayInListView" => $this->isDisplayInListView()
        ];
    }


}
