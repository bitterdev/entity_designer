<?php
/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');

use Bitter\EntityDesigner\Entity\Entity;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\View\View;

/** @var Entity[] $entities */
/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/help', null, 'entity_designer');
/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/reminder', ["packageHandle" => "entity_designer", "rateUrl" => "https://www.concrete5.org/marketplace/addons/entity-designer/reviews"], 'entity_designer');
/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/license_check', ["packageHandle" => "entity_designer"], 'entity_designer');

$app = Application::getFacadeApplication();

?>
<?php if (empty($entities)): ?>
    <div class="alert alert-warning">
        <?php echo t("Currently there are no entities. Click the »Add Entity« button in the header to create one."); ?>
    </div>
<?php else: ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>
                <?php echo t("Name"); ?>
            </th>

            <th>
                &nbsp;
            </th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($entities as $entity): ?>
            <tr>
                <td>
                    <?php echo $entity->getName(); ?>
                </td>

                <td>
                    <div class="float-end">
                        <a href="<?php echo h((string)Url::to("/dashboard/entity_designer/edit", $entity->getId())); ?>"
                           class="btn btn-default">
                            <i class="fa fa-pencil"></i> <?php echo t("Edit"); ?>
                        </a>

                        <a href="<?php echo h((string)Url::to("/dashboard/entity_designer/remove", $entity->getId())); ?>"
                           class="btn btn-danger">
                            <i class="fa fa-trash"></i> <?php echo t("Remove"); ?>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!--suppress JSUnresolvedVariable -->
    <script>
        (function ($) {
            $(function () {
                $(".btn-danger").bind("click", function (e) {
                    e.preventDefault();

                    let deleteUrl = $(this).attr("href");

                    ConcreteAlert.confirm(
                        "<?php echo h(t('Are you sure?')); ?>",
                        function () {
                            window.location.href = deleteUrl;
                        },
                        'btn-danger',
                        "<?php echo h(t('Confirm')); ?>"
                    );

                    return false;
                });
            });
        })(jQuery);
    </script>
<?php endif; ?>

<?php
/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/did_you_know', ["packageHandle" => "entity_designer"], 'entity_designer');
?>