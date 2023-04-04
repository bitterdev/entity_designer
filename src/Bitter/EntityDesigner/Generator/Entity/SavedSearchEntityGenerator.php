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

class SavedSearchEntityGenerator extends Generator implements GeneratorInterface
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
        $fileName = $this->getSrcPath() . "/Entity/Search/" . "Saved" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "Search.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . "\\Entity\\Search;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Entity\Search\SavedSearch;\n";

        if ($this->hasFullDoctrineSupport()) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\Mapping as ORM;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Entity\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Table(name=\"`" .  "Saved" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "SearchQueries" . "`\")\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class Saved" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "Search extends SavedSearch\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @var integer\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Id\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "GeneratedValue(strategy=\"AUTO\")\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* " . ($this->hasFullDoctrineSupport() ? "@ORM\\" : "@") . "Column(name=\"`id`\", type=\"integer\", nullable=true)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$id;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        $table = "Saved" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "SearchQueries";

        return [new GeneratorItem($fileName, $code, $table)];
    }
}