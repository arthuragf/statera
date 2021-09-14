<?php 
    /** @var $clsRecoverPass statera\models\RecoverPass */

use statera\core\Application;

/** @var $this statera\core\View */
    $this->sTitle = 'Pass Recovery';
?>

<h1>Password Recovery</h1>
<?php $oForm = statera\core\form\Form::begin('/pass_recovery', 'post'); ?>
    <div class="row">
        <div class="col">
            <?= $oForm->InputField($clsRecoverPass, 'email'); ?>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Recover my Password</button>
<?= statera\core\form\Form::end(); ?>