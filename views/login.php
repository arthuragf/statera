<?php 
    /** @var $clsUser \statera\models\User */

use statera\core\Application;

/** @var $this \statera\core\View */
    $this->sTitle = 'Login';
?>

<h1>Login</h1>

<?php $oForm = \statera\core\form\Form::begin('/login', 'post'); ?>
    <?= $oForm->InputField($clsModel, 'email'); ?>
    <?= $oForm->InputField($clsModel, 'password')->passwordField(); ?>
    <div class="row">
        <div class="col">
        <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-primary" 
                onclick="window.location='<?= 
                    Application::$COMMON_URL . DIRECTORY_SEPARATOR . "register"; 
                ?>'"
            >
                    Create an Account
            </button>
        </div>
        <div class="col text-end">
            <a href="<?= Application::$COMMON_URL . DIRECTORY_SEPARATOR . "pass_recovery";?>">
                Forgot your password?
            </a>    
        </div>
    </div>
<?= \statera\core\form\Form::end(); ?>