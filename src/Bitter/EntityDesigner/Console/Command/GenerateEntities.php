<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Console\Command;

use Bitter\EntityDesigner\Generator\GeneratorService;
use Concrete\Core\Support\Facade\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Exception;

class GenerateEntities extends Command
{
    protected function configure()
    {
        $this
            ->setName('entity-designer:generate-entities')
            ->setDescription(t('Generate the entities.'));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = Application::getFacadeApplication();

        /** @var GeneratorService $generator */
        $generator = $app->make(GeneratorService::class);

        $io = new SymfonyStyle($input, $output);

        try {
            $generator->install();

            $io->success(t("All entities has been created successfully."));
        } catch (Exception $error) {
            $io->error($error->getMessage());
        }
    }
}
