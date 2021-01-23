<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
	<?= $message ?>
</div>


