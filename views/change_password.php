<?php 
    /** @var $clsRecoverPass statera\models\RecoverPass */
    /** @var $this statera\core\View */
    $this->sTitle = 'Change Password';
?>

<h1>Change Password</h1>
<?php $oForm = statera\core\form\Form::begin('/change_password', 'post'); ?>
    <?= $oForm->InputField($clsRecoverPass, 'password')->passwordField(); ?>
    <?= $oForm->InputField($clsRecoverPass, 'confirmPassword')->passwordField(); ?>
    <?= $oForm->InputField($clsRecoverPass, 'token')->hiddenField(); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?= statera\core\form\Form::end(); ?>