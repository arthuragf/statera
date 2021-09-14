<?php 
    /** @var Application::$clsApp->oUser statera\models\User */

use statera\core\Application;
use statera\models\Custom;

/** @var $this statera\core\View */
    $this->sTitle = 'Edit User';
    $this->aRequiredAssets = ['js' => ['edit_user']];

    $clsCustom = new Custom(
        ['changePassword' => 'on']
        , ['changePassword' => 'Change password']
    );
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
    <div class="row">
        <div class="col">
            <?= $oForm->InputField(Application::$clsApp->oUser, 'email'); ?>
        </div>
        <div class="col">
            <?= $oForm->SelectField(Application::$clsApp->oUser, 'status'); ?>        
        </div>
    </div>
    <?= $oForm->InputField($clsCustom, 'changePassword', ['aAttributes' => ['onclick="enablePasswordFields([\'password\',\'confirmPassword\'])"'], 'aClasses' => ['classteste', 'classteste2']])->checkboxField(); ?>
    <?= $oForm->InputField(Application::$clsApp->oUser, 'password', ['aAttributes' => ['disabled']])->passwordField(); ?>
    <?= $oForm->InputField(Application::$clsApp->oUser, 'confirmPassword',['aAttributes' => ['disabled']])->passwordField(); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?= statera\core\form\Form::end(); ?>