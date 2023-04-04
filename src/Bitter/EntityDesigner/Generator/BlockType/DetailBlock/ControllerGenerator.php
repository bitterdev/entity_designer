<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\BlockType\DetailBlock;

use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class ControllerGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/blocks/" . $this->entity->getHandle() . "_detail/controller.php";

        $code = "<?php\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " *\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * " . t("This file was build with the Entity Designer add-on.") . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " *\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * https://www.concrete5.org/marketplace/addons/entity-designer\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " *\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection DuplicatedCode */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getNamespace() . "\Block\\" . $this->textService->camelcase($this->entity->getHandle()) . "Detail;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Block\BlockController;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Http\ResponseFactory;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\EntityManagerInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class Controller extends BlockController\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$btTable = \"bt" . $this->textService->camelcase($this->entity->getHandle()) . "Detail\";\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var int */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$specificEntryId;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var string */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$entryMode;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var EntityManagerInterface */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$entityManager;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var ResponseFactory */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$responseFactory;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "const ENTRY_MODE_ANOTHER_PAGE = \"P\";\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "const ENTRY_MODE_SPECIFIC_ENTRY = \"S\";\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function on_start()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "parent::on_start();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->responseFactory = \$this->app->make(ResponseFactory::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->entityManager = \$this->app->make(EntityManagerInterface::class);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getBlockTypeDescription()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('Add an entry detail display to a page.');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getBlockTypeName()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('" . h($this->entity->getName()) . " Entry Detail');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getBlockTypeInSetName()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('Details');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @noinspection PhpUnused */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function action_display_entry(\$entryId = null)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$this->entryMode === self::ENTRY_MODE_ANOTHER_PAGE) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$entry = \$this->entityManager->getRepository(" . $this->textService->camelcase($this->entity->getHandle()) . "::class)->findOneBy([\"id\" => \$entryId]);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (\$entry instanceof " . $this->textService->camelcase($this->entity->getHandle()) . ") {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->set(\"entry\", \$entry);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->responseFactory->notFound(null)->send();\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->app->shutdown();\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->responseFactory->forbidden(null)->send();\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->app->shutdown();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "private function setDefaults()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entries = [];\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "foreach (\$this->entityManager->getRepository(" . $this->textService->camelcase($this->entity->getHandle()) . "::class)->findAll() as \$entry) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "/** @var " . $this->textService->camelcase($this->entity->getHandle()) . " \$entry */\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$entries[\$entry->getId()] = \$entry->getDisplayValue();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entryModes = [\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "self::ENTRY_MODE_ANOTHER_PAGE => t('Get entry from list block on another page'),\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "self::ENTRY_MODE_SPECIFIC_ENTRY => t('Display specific entry')\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "];\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"entries\", \$entries);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"entryModes\", \$entryModes);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function add()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->setDefaults();\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function edit()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->setDefaults();\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function view()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$this->entryMode === self::ENTRY_MODE_SPECIFIC_ENTRY) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$entry = \$this->entityManager->getRepository(" . $this->textService->camelcase($this->entity->getHandle()) . "::class)->findOneBy([\"id\" => \$this->specificEntryId]);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (\$entry instanceof " . $this->textService->camelcase($this->entity->getHandle()) . ") {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->set(\"entry\", \$entry);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}