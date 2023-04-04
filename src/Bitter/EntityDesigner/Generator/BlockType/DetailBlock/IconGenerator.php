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

class IconGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/blocks/" . $this->entity->getHandle() . "_detail/icon.png";

        $fileContents = $this->fileSystem->getContents($this->pkg->getPackagePath() . "/resources/detail_icon.png");

        return [new GeneratorItem($fileName, $fileContents)];
    }
}