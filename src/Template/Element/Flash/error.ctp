<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div class="message error" role="alert">
	<?= $message ?>
</div>



