<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 6/23/15
 * Time: 1:01 PM
 */
?>

<div id="innehall" class="i">

    <div id="system-message-container">
    </div>
    <form method="post" enctype="multipart/form-data" class="form-form validate-form container">
        <div class="centerer">
            <input name="option" value="com_form" type="hidden">
            <input name="view" value="saveform" type="hidden">
            <input name="id" value="1" type="hidden">
            <a data-target="index.php?option=com_form&amp;view=listfield&amp;form=1" data-function="ASK" class="edit-icon"></a>

            <div>
                <p>Do you have questions about Mustard Seed and our events? Please get in touch with us.</p>
            </div>
            <div>
                <input name="field_10" value="" form-require="require" form-validate="COM_FORM_VALIDATE_1" class="form-validation" placeholder="Name" type="text"> </div>
            <div>
                <input name="field_11" value="" form-require="" form-validate="COM_FORM_VALIDATE_1" class="form-validation" placeholder="Company" type="text"> </div>
            <div>
                <input name="field_12" value="" form-require="require" form-validate="COM_FORM_VALIDATE_4" class="form-validation" placeholder="Email" type="text"> </div>
            <div>
                <input name="field_13" value="" form-require="" form-validate="COM_FORM_VALIDATE_5" class="form-validation" placeholder="Telephone" type="text"> </div>
            <div>
                <textarea name="field_14" form-require="" form-validate="minlength" form-minlength="0" class="form-validation" cols="35" rows="5" placeholder="Message"></textarea>
            </div>
            <div>
                <input value="GET IN TOUCH" type="submit">
            </div>
        </div>
    </form>
</div>