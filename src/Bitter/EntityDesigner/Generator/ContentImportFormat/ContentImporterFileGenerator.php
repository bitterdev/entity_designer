<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\ContentImportFormat;

use Bitter\EntityDesigner\Entity\Entity;
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class ContentImporterFileGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        if ($this->entity->hasValidPackageHandle()) {
            /** @var Entity[] $entities */
            $entities = $this->entityManager->getRepository(Entity::class)->findBy(["packageHandle" => $this->entity->getPackageHandle(), "isTemp" => false]);

            if (!empty($entities)) {
                $fileName = $this->getPath() . DIRECTORY_SEPARATOR . "install_" . $this->identifierService->getString() . ".xml";

                $code = "";
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?xml version=\"1.0\"?>\n";
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<concrete5-cif version=\"1.0\">\n";

                /*
                 * Block types
                 */

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<blocktypes>\n";

                foreach ($entities as $entity) {
                    foreach (["detail", "list", "form"] as $blockTypeHandle) {
                        $blockTypeHandle = $entity->getHandle() . "_" . $blockTypeHandle;

                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<blocktype handle=\"" . $blockTypeHandle . "\" package=\"" . $entity->getPackageHandle() . "\"/>\n";
                    }
                }

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</blocktypes>\n";

                /*
                 * Block type sets
                 */

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<blocktypesets>\n";

                foreach ($entities as $entity) {
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<blocktypeset handle=\"" . $entity->getHandle() . "\" name=\"" . h(htmlspecialchars($entity->getName())) . "\" package=\"" . $entity->getPackageHandle() . "\">\n";

                    foreach (["detail", "list", "form"] as $blockTypeHandle) {
                        $blockTypeHandle = $entity->getHandle() . "_" . $blockTypeHandle;

                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<blocktype handle=\"" . $blockTypeHandle . "\" package=\"" . $entity->getPackageHandle() . "\"/>\n";
                    }

                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</blocktypeset>\n";
                }

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</blocktypesets>\n";

                /*
                 * Single pages
                 */

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<singlepages>\n";

                foreach ($entities as $entity) {
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<page name=\"" . h(htmlspecialchars($entity->getName())) . "\" path=\"" . $entity->getPath() . "\" filename=\"" . $entity->getPath() . "/view.php\" package=\"" . $entity->getPackageHandle() . "\" />\n";
                }

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</singlepages>\n";

                /*
                 * Permissions keys
                 */

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<permissionkeys>\n";

                foreach ($entities as $entity) {
                    foreach(["create", "read", "update", "delete"] as $action) {
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<permissionkey handle=\"" . $action . "_" . $entity->getHandle() . "_entries" . "\" name=\"" . ucfirst($action) . " " . $entity->getName() . " Entries" . "\" description=\"\" package=\"" . $entity->getPackageHandle(). "\" category=\"admin\">\n";

                        if ($action !== "read") {
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<access>\n";
                            $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<group name=\"Administrators\" description=\"\"/>\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "</access>\n";
                        }

                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</permissionkey>\n";
                    }
                }

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</permissionkeys>\n";

                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</concrete5-cif>\n";

                return [new GeneratorItem($fileName, $code)];
            }
        }

        return [];
    }
}