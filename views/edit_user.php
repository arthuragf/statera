<?php 
    /** @var Application::$clsApp->oUser statera\models\User */

use statera\core\Application;

/** @var $this statera\core\View */
    $this->sTitle = 'Edit User';
?>

<h1>Create an account</h1>
<?php $oForm = statera\core\form\Form::begin('/edit_user', 'post'); ?>
    <div class="row">
        <div class="col">
            <?= $oForm->InputField(Application::$clsApp->oUser, 'firstname'); ?>
        </div>
        <div class="col">
            <?= $oForm->InputField(Application::$clsApp->oUser, 'lastname'); ?>
        </div>
    </div>
    <?= $oForm->InputField(Application::$clsApp->oUser, 'email'); ?>
    <?= $oForm->InputField(Application::$clsApp->oUser, 'password')->passwordField(); ?>
    <?= $oForm->InputField(Application::$clsApp->oUser, 'confirmPassword')->passwordField(); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?= statera\core\form\Form::end(); ?>