<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\Entity;

use Bitter\EntityDesigner\Enumeration\AssociationType;
use Bitter\EntityDesigner\Enumeration\FieldType;
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Utility\Service\Text;

class EntityGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $hasAssociations = false;

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                switch ($fieldEntity->getAssociationType()) {
                    case AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY:
                    case AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY:
                        $hasAssociations = true;
                        break;
                }
            }
        }

        if (!$hasAssociations) {
            foreach ($this->entity->getAssociatedFields() as $fieldEntity) {
                if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                    switch ($fieldEntity->getAssociationType()) {
                        case AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY:
                            $hasAssociations = true;
                            break;
                    }
                }
            }
        }

        $fileName = $this->getSrcPath() . "/Entity/" . $this->textService->camelcase($this->entity->getHandle()) . ".php";

        // for php type conversion
        $typeMapping = [
            FieldType::FIELD_TYPE_FILE => "\Concrete\Core\Entity\File\File",
            FieldType::FIELD_TYPE_PAGE_SELECTOR => "integer",
            FieldType::FIELD_TYPE_TEXT => "string",
            FieldType::FIELD_TYPE_EMAIL => "string",
            FieldType::FIELD_TYPE_TELEPHONE => "string",
            FieldType::FIELD_TYPE_PASSWORD => "string",
            FieldType::FIELD_TYPE_WEB_ADDRESS => "string",
            FieldType::FIELD_TYPE_TEXT_AREA => "string",
            FieldType::FIELD_TYPE_NUMBER => "integer",
            FieldType::FIELD_TYPE_RADIO_BUTTONS => "string",
            FieldType::FIELD_TYPE_CHECKBOX_LIST => "\\Doctrine\\Common\\Collections\\Collection",
            FieldType::FIELD_TYPE_SELECT_BOX => "string"
        ];

        $code = "<?php\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "/**\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " *\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " * " . t("This file was build with the Entity Designer add-on.") . "\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " *\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " * https://www.concrete5.org/marketplace/addons/entity-designer\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " *\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection DuplicatedCode */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection PhpFullyQualifiedNameUsageInspection */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . "\\Entity;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";

        if ($this->hasFullDoctrineSupport()) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\Mapping as ORM;\n";
        }

        if ($hasAssociations) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\Common\Collections\ArrayCollection;\n";

            foreach ($this->entity->getFields() as $fieldEntity) {
                if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                    $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace($fieldEntity->getAssociatedEntity()) . "\\Entity\\" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . ";\n";
                }
            }
        }

        foreach ($this->entity->getAssociatedFields() as $fieldEntity) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace($fieldEntity->getEntity()) . "\\Entity\\" . $this->textService->camelcase($fieldEntity->getEntity()->getHandle()) . ";\n";
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Entity\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Table(name=\"`" . $this->textService->camelcase($this->entity->getHandle()) . "`\")\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class " . $this->textService->camelcase($this->entity->getHandle()) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";


        /*
         * write the properties
         */

        foreach ($this->entity->getFields(true) as $field) {
            $defaultValue = "";

            if ($field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                if ($field->getAssociationType() === AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE) {
                    $type = $this->textService->camelcase($field->getAssociatedEntity()->getHandle());
                } else {
                    $type = "ArrayCollection|" . $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . "[]";
                }

                switch ($field->getAssociationType()) {
                    case AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE:
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @var " . $type . "\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "OneToOne(targetEntity=\"" . $this->getSrcNamespace($field->getAssociatedEntity()) . "\\Entity\\" . $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . "\", mappedBy=\"" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . "\")\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$" . lcfirst($this->textService->camelcase($field->getHandle())) . ";\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
                        break;

                    case AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY:
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @var " . $type . "\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "OneToMany(targetEntity=\"" . $this->getSrcNamespace($field->getAssociatedEntity()) . "\\Entity\\" . $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . "\", mappedBy=\"" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . "\", cascade={\"persist\", \"remove\"})\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$" . lcfirst($this->textService->camelcase($field->getHandle())) . ";\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
                        break;

                    case AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY:
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @var " . $type . "\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "ManyToMany(targetEntity=\"" . $this->getSrcNamespace($field->getAssociatedEntity()) . "\\Entity\\" . $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . "\", inversedBy=\"" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . "\")\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "JoinTable(name=\"" . $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . $this->textService->camelcase($this->entity->getHandle()) . "\")\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$" . lcfirst($this->textService->camelcase($field->getHandle())) . ";\n";
                        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
                        break;
                }

            } else {
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @var " . $typeMapping[$field->getType()] . "\n";

                if ($field->isPrimaryKey()) {
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Id\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "GeneratedValue(strategy=\"AUTO\")\n";
                }

                if ($field->getType() === FieldType::FIELD_TYPE_FILE) {
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "ManyToOne(targetEntity=\"\Concrete\Core\Entity\File\File\")\n";
                    // concrete5 file entity doesn't allow multiple file links
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "JoinColumn(name=\"fID\", referencedColumnName=\"fID\", onDelete=\"CASCADE\", nullable=true)\n";
                } else if ($field->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "OneToMany(targetEntity=\"\\" . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($field->getHandle()) . "Options\", mappedBy=\"entry\", cascade={\"persist\", \"remove\", \"merge\"}, orphanRemoval=true)\n";
                } else {
                    $doctrineType = "";

                    switch ($field->getType()) {
                        case FieldType::FIELD_TYPE_TEXT:
                        case FieldType::FIELD_TYPE_EMAIL:
                        case FieldType::FIELD_TYPE_TELEPHONE:
                        case FieldType::FIELD_TYPE_PASSWORD:
                        case FieldType::FIELD_TYPE_WEB_ADDRESS:
                        case FieldType::FIELD_TYPE_RADIO_BUTTONS:
                        case FieldType::FIELD_TYPE_SELECT_BOX:
                            $doctrineType = "string";
                            $defaultValue = "'" . t($field->getValue()) . "'";
                            break;

                        case FieldType::FIELD_TYPE_TEXT_AREA:
                            $doctrineType = "text";
                            $defaultValue = "'" . t($field->getValue()) . "'";
                            break;

                        case FieldType::FIELD_TYPE_NUMBER:
                        case FieldType::FIELD_TYPE_PAGE_SELECTOR:
                            $doctrineType = "integer";

                            if (strlen($field->getValue()) > 0) {
                                $defaultValue = (int)$field->getValue();
                            }

                            break;
                    }

                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Column(name=\"`" . lcfirst($this->textService->camelcase($field->getHandle())) . "`\", type=\"" . $doctrineType . "\", nullable=true)\n";
                }

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";

                if (strlen($defaultValue) > 0) {
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected $" . lcfirst($this->textService->camelcase($field->getHandle())) . " = " . $defaultValue . ";\n";
                } else {
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected $" . lcfirst($this->textService->camelcase($field->getHandle())) . ";\n";
                }

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
            }
        }

        foreach ($this->entity->getAssociatedFields() as $fieldEntity) {
            if ($fieldEntity->getAssociationType() === AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY) {
                $type = "ArrayCollection|" . $this->textService->camelcase($fieldEntity->getEntity()->getHandle()) . "[]";
            } else {
                $type = $this->textService->camelcase($fieldEntity->getEntity()->getHandle());
            }

            switch ($fieldEntity->getAssociationType()) {
                case AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE:
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @var " . $type . "\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "OneToOne(targetEntity=\"" . $this->getSrcNamespace($fieldEntity->getEntity()) . "\\Entity\\" . $this->textService->camelcase($fieldEntity->getEntity()->getHandle()) . "\", inversedBy=\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\")\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "JoinColumn(name=\"" . lcfirst($this->textService->camelcase($fieldEntity->getEntity()->getHandle())) . "Id\", referencedColumnName=\"id\", onDelete=\"SET NULL\")\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$" . lcfirst($this->textService->camelcase($fieldEntity->getEntity()->getHandle())) . ";\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
                    break;

                case AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY:
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @var " . $type . "\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "ManyToOne(targetEntity=\"" . $this->getSrcNamespace($fieldEntity->getEntity()) . "\\Entity\\" . $this->textService->camelcase($fieldEntity->getEntity()->getHandle()) . "\", inversedBy=\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\")\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "JoinColumn(name=\"" . lcfirst($this->textService->camelcase($fieldEntity->getEntity()->getHandle())) . "Id\", referencedColumnName=\"id\", nullable=true, onDelete=\"SET NULL\")\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$" . lcfirst($this->textService->camelcase($fieldEntity->getEntity()->getHandle())) . ";\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
                    break;

                case AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY:
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @var " . $type . "\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "ManyToMany(targetEntity=\"" . $this->getSrcNamespace($fieldEntity->getEntity()) . "\\Entity\\" . $this->textService->camelcase($fieldEntity->getEntity()->getHandle()) . "\", mappedBy=\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\")\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$" . lcfirst($this->textService->camelcase($fieldEntity->getEntity()->getHandle())) . ";\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
                    break;
            }
        }

        /*
         * Write the constructor
         */

        if ($hasAssociations) {
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function __construct()\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";

            foreach ($this->entity->getFields() as $fieldEntity) {
                if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                    switch ($fieldEntity->getAssociationType()) {
                        case AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY:
                        case AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . " = new ArrayCollection();\n";
                            break;
                    }
                }
            }

            foreach ($this->entity->getAssociatedFields() as $fieldEntity) {
                if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                    switch ($fieldEntity->getAssociationType()) {
                        case AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . " = new ArrayCollection();\n";
                            break;
                    }
                }
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        }


        /*
         * Write the get methods
         */

        foreach ($this->entity->getFields(true) as $field) {
            if ($field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                if ($field->getAssociationType() === AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE) {
                    $type = $this->textService->camelcase($field->getAssociatedEntity()->getHandle());
                } else {
                    $type = "ArrayCollection|" . $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . "[]";
                }
            } else {
                $type = $typeMapping[$field->getType()];
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return " . $type . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function get" . $this->textService->camelcase($field->getHandle()) . "()\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize + $indentSize) . "return \$this->" . lcfirst($this->textService->camelcase($field->getHandle())) . ";" . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        }

        foreach ($this->entity->getAssociatedFields() as $field) {
            if ($field->getAssociationType() === AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY) {
                $type = "ArrayCollection|" . $this->textService->camelcase($field->getEntity()->getHandle()) . "[]";
            } else {
                $type = $this->textService->camelcase($field->getEntity()->getHandle());
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return " . $type . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function get" . $this->textService->camelcase($field->getEntity()->getHandle()) . "()\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize + $indentSize) . "return \$this->" . lcfirst($this->textService->camelcase($field->getEntity()->getHandle())) . ";" . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        }

        /*
         * Write the set methods
         */

        foreach ($this->entity->getFields(true) as $field) {
            if ($field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                if ($field->getAssociationType() === AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE) {
                    $type = $this->textService->camelcase($field->getAssociatedEntity()->getHandle());
                } else {
                    $type = "ArrayCollection|" . $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . "[]";
                }
            } else {
                $type = $typeMapping[$field->getType()];
            }

            if ($type === FieldType::FIELD_TYPE_ASSOCIATION) {
                $type = $this->textService->camelcase($field->getAssociatedEntity()->getHandle());
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param " . $type . " $" . lcfirst($this->textService->camelcase($field->getHandle())) . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return " . $this->textService->camelcase($field->getEntity()->getHandle()) . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function set" . $this->textService->camelcase($field->getHandle()) . "(\$" . lcfirst($this->textService->camelcase($field->getHandle())) . ")\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize + $indentSize) . "\$this->" . lcfirst($this->textService->camelcase($field->getHandle())) . " = \$" . lcfirst($this->textService->camelcase($field->getHandle())) . ";\n";
            $code .= str_repeat(" ", ($indent + 1) + $indentSize + $indentSize) . "return \$this;\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        }

        foreach ($this->entity->getAssociatedFields() as $field) {
            if ($field->getAssociationType() === AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY) {
                $type = "ArrayCollection|" . $this->textService->camelcase($field->getEntity()->getHandle()) . "[]";
            } else {
                $type = $this->textService->camelcase($field->getEntity()->getHandle());
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param " . $type . " $" . lcfirst($this->textService->camelcase($field->getEntity()->getHandle())) . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return " . $this->textService->camelcase($this->getEntity()->getHandle()) . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function set" . $this->textService->camelcase($field->getEntity()->getHandle()) . "(\$" . lcfirst($this->textService->camelcase($field->getEntity()->getHandle())) . ")\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize + $indentSize) . "\$this->" . lcfirst($this->textService->camelcase($field->getEntity()->getHandle())) . " = \$" . lcfirst($this->textService->camelcase($field->getEntity()->getHandle())) . ";\n";
            $code .= str_repeat(" ", ($indent + 1) + $indentSize + $indentSize) . "return \$this;\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        }

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getDisplayValue()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return " .
            // format the display value
            sprintf(
                "%s;",
                trim(
                    str_replace(
                        [". \"\"", "\"\" ."],
                        "",
                        sprintf(
                            "\"%s\"",
                            preg_replace_callback(
                                '/{(.*?)}/',
                                function ($aa) {
                                    $app = Application::getFacadeApplication();
                                    /** @var Text $textHelper */
                                    $textHelper = $app->make(Text::class);
                                    return "\" . \$this->get" . $textHelper->camelcase($aa[1]) . "() . \"";
                                },
                                $this->getEntity()->getDisplayValue()
                            )
                        )
                    )
                )
            ) . "\n";

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        /*
         * Generate the option values entities for checkboxes
         */

        $table = $this->textService->camelcase($this->entity->getHandle());

        $generatorItems = [new GeneratorItem($fileName, $code, $table)];

        /** @var OptionEntityGenerator $optionEntityGenerator */
        $optionEntityGenerator = $this->app->make(OptionEntityGenerator::class);

        foreach ($this->entity->getFields() as $field) {
            if ($field->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {
                $optionEntityGenerator->setEntity($this->entity);
                $optionEntityGenerator->setField($field);
                $generatorItems = array_merge($generatorItems, $optionEntityGenerator->build($indent, $indentSize));

            } elseif ($field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                if ($field->getAssociationType() === AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY) {
                    $tableName = $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . $this->textService->camelcase($this->entity->getHandle());
                    $generatorItems[] = new GeneratorItem(null, null, $tableName);
                }
            }
        }

        return $generatorItems;
    }
}