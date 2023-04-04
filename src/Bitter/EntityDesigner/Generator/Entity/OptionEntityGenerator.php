<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\Entity;

use Bitter\EntityDesigner\Entity\Field;
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class OptionEntityGenerator extends Generator implements GeneratorInterface
{
    /** @var Field */
    protected $field;

    /**
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param Field $field
     * @return OptionEntityGenerator
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getSrcPath() . "/Entity/" . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($this->field->getHandle()) . "Options.php";

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
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Entity\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Table(name=\"`" .  $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($this->field->getHandle()) . "Options" . "`\")\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class " . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($this->field->getHandle()) . "Options\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @var integer\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Id\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "GeneratedValue(strategy=\"AUTO\")\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Column(name=\"`id`\", type=\"integer\", nullable=true)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$id;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @var \\" . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "ManyToOne(targetEntity=\"\\" . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . "\")\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "JoinColumn(name=\"entryId\", referencedColumnName=\"id\", onDelete=\"CASCADE\", nullable=true)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$entry;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @var string\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Column(name=\"`value`\", type=\"string\", nullable=true)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$value = '';\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @return int\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getId()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this->id;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @param int \$id\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @return " . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($this->field->getHandle()) . "Options\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function setId(\$id)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->id = \$id;\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @return " . $this->textService->camelcase($this->entity->getHandle()) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getEntry()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this->entry;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @param " . $this->textService->camelcase($this->entity->getHandle()) . " \$entry\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @return " . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($this->field->getHandle()) . "Options\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function setEntry(\$entry)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->entry = \$entry;\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @return string\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getValue()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this->value;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @param string \$value\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @return " . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($this->field->getHandle()) . "Options\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function setValue(\$value)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->value = \$value;\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getDisplayValue()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this->value;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";

        $table = $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($this->field->getHandle()) . "Options";

        return [new GeneratorItem($fileName, $code, $table)];
    }
}