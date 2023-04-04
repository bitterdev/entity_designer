<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\EntityDesigner\Controller\Dialog\EntityDesigner;

use Bitter\EntityDesigner\Generator\GeneratorService;
use Bitter\EntityDesigner\Validator\HandleValidator;
use Concrete\Controller\Backend\UserInterface;
use Concrete\Core\Entity\Package;
use Concrete\Core\Http\Request;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\File\EditResponse;
use Concrete\Core\Permission\Key\Key;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\CSRF\Token;
use Doctrine\ORM\EntityManagerInterface;

class AddPackage extends UserInterface
{
    protected $viewPath = '/dialogs/entity_designer/add_package';
    /** @var Request */
    protected $request;
    /** @var ResponseFactory */
    protected $responseFactory;
    /** @var HandleValidator */
    protected $handleValidator;
    /** @var Token */
    protected $token;
    /** @var EntityManagerInterface */
    protected $entityManager;
    /** @var GeneratorService */
    protected $generatorService;

    public function __construct()
    {
        parent::__construct();

        if (is_null($this->app)) {
            $this->app = Application::getFacadeApplication();
        }

        $this->request = $this->app->make(Request::class);
        $this->responseFactory = $this->app->make(ResponseFactory::class);
        $this->entityManager = $this->app->make(EntityManagerInterface::class);
        $this->handleValidator = $this->app->make(HandleValidator::class);
        $this->generatorService = $this->app->make(GeneratorService::class);
        $this->token = $this->app->make(Token::class);
    }

    public function add()
    {
        // Do Nothing
    }

    public function submit()
    {
        if ($this->token->validate("save_package")) {
            $response = new EditResponse();

            $data = $this->request->request->all();

            if (!isset($data["name"]) || strlen($data["name"]) === 0) {
                $this->error->add(t("You need to enter a name."));
            }

            if (!isset($data["handle"]) || strlen($data["handle"]) === 0) {
                $this->error->add(t("You need to enter a handle."));
            }

            if ($this->entityManager->getRepository(Package::class)->findOneBy(["pkgHandle" => $data["handle"]]) instanceof Package) {
                $this->error->add(t("The given handle is already in use."));
            }

            $this->handleValidator->isValid($data["handle"], $this->error);

            if (!isset($data["description"]) || strlen($data["description"]) === 0) {
                $this->error->add(t("You need to enter a description."));
            }

            if (!$this->error->has()) {
                $this->generatorService->createPackage(
                    $data["name"],
                    $data["handle"],
                    $data["description"]
                );

                $response->setMessage(t('Package created successfully.'));
            } else {
                $response->setError($this->error);
            }

            $this->responseFactory->json($response)->send();
            $this->app->shutdown();
        } else {
            $this->responseFactory->notFound(null)->send();
            $this->app->shutdown();
        }
    }

    public function canAccess()
    {
        return Key::getByHandle("add_packages")->validate();
    }
}