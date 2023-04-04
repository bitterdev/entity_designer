<?php
/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die("Access Denied.");

/** @var string $faIconClass */
/** @var string $label */
?>

<div class="btn-group">
    <a href="<?php echo $url; ?>" class="btn btn-success">
        <i class="fa fa-<?php echo $faIconClass; ?>"></i> <?php echo $label; ?>
    </a>
</div>