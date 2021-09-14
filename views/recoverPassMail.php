<?php

use statera\core\Application;
?>
<form action="<?= Application::$COMMON_URL; ?>/pass_recovery" 
    target="blank" method="post"
>
    <input type="hidden" value="<?= $this->token; ?>" />
    <button type="submit">Recover Password</button>
</form>