<?php

//error_reporting(E_ERROR | E_WARNING | E_PARSE);
function ds_debug($var)
{
    //	echo '<br/>';
    //    var_dump($var);
    //	echo '<br/>';
}

global $entryname_error;
global ${$entryname_error};
global $_POST;
function fixFilesArray(&$files)
{
    $names = array('name' => 1, 'type' => 1, 'tmp_name' => 1, 'error' => 1, 'size' => 1);
    foreach ($files as $key => $part) {
        // only deal with valid keys and multiple files
        $key = (string)$key;
        if (isset($names[$key]) && is_array($part)) {
            foreach ($part as $position => $value) {
                $files[$position][$key] = $value;
                ds_debug($value);
            }
            // remove old key reference
            unset($files[$key]);
        }
    }
}

function oddEven($place)
{
    if ($place % 2) {
        echo 'odd';
    } else {
        echo 'even';
    }
}

function string_normalise($string, $escSpace = TRUE)
{
    if ($escSpace == TRUE) {
        $return = str_replace(
            array('?', '*', '-', ' ', 'à', 'á', 'â', 'ã', 'ä', '\'', '/', '’', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý'),
            array('', '', '', '', 'a', 'a', 'a', 'a', 'a', '', '', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y'), $string);
        $return = strtolower($return);
    }
    if ($escSpace == FALSE) {
        $return = str_replace(
            array('?', '*', '-', 'à', 'á', 'â', 'ã', 'ä', '\'', '/', '’', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý'),
            array('', '', '', 'a', 'a', 'a', 'a', 'a', '', '', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y'), $string);
    }


    $return = str_replace(':', '', $return);

    return $return;
}

//ds_validate_forms($_FILES, $_POST, $formargs, $meta);
function ds_validate_forms(
    $myPost, $formargs, $meta = array(
    "message"    => 'From Message',
    //"emailto"    => 'eric.bright@designshopp.com',
    "emailto"    => 'eric.bright@designshopp.com',
    "emailfrom"  => 'eric.bright@designshopp.com',
    'id_from_db' => 0), $extraMessage = false){
    /*
      $meta['message']
      $meta['emailto']
      $meta['emailfrom']
      );
     */
    //include 'class-email.php';
    global $_POST;


    $haserror         = FALSE;
    $place            = 1;
    $entryname_sticky = '';
    $label            = '';
    $entryname        = '';
    $emaillabel       = array();
    $emailvar         = array();
    $emailTo          = $meta['emailto'];
    //$emailTo          = 'eric.bright@designshopp.com';
    $emailFrom          = $meta['emailfrom'];

/*    $config['mailtype'] = "html";
    $email              = new CI_Email($config);

    $email->to($emailTo);
    $email->from($emailFrom);*/

    foreach ($formargs as $origlabel => $info) {

        if (isset($info['mylabel']) && $info['mylabel'] !== '' && $info['mylabel'] !== '&nbsp;') {
            $label = $info['mylabel'];
        } else {
            $label = $origlabel;
        }

        /* Standardize the name */
        $entryname = 'form_' . string_normalise($origlabel);

        $entryname = 'form_' . string_normalise($label);
        if (isset($info['name']) && $info['name'] !== '') {
            $entryname = 'form_' . $info['name'];
        }

        /* Create a name for the error */
        $entryname_error    = $entryname . '_error';
        $entryname_errorMsg = $entryname . '_errorMsg';

        /* Validate if needed */

        if (isset($myPost[$entryname])) {

            if ($info['type'] === 'checkbox') {
                if (empty($myPost[$entryname])) {
                    if ($info['required'] == TRUE) {
                        $_POST['has_error']      = TRUE;
                        $_POST[$entryname_error] = TRUE;
                        ${$entryname_error}      = TRUE;
                    }
                } else {
                    $emaillabel[$entryname] = $label;
                    $has_selected = array();
                    foreach ($myPost[$entryname] as $key => $value) {
                        $has_selected[] = $value;
                    }
                    $emailvar[$entryname] = implode(', ', $has_selected);
                }
            } elseif ($info['extratype'] === 'email') {
                /* standardize and filter email */
                $myPost[$entryname] = filter_var($myPost[$entryname], FILTER_VALIDATE_EMAIL);
                //$myPost[$entryname] = strtolower($myPost[$entryname]);
                $emailregex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i';

                if (preg_match($emailregex, $myPost[$entryname]) == 0 ||
                    $myPost[$entryname] == ''
                ) {
                    if ($info['required'] == TRUE) {
                        $_POST['has_error']      = TRUE;
                        $_POST[$entryname_error] = TRUE;
                        ${$entryname_error}      = TRUE;
                    }
                } else {
                    $_POST[$entryname]      = $_POST[$entryname];
                    $emaillabel[$entryname] = $label;
                    $emailvar[$entryname]   = $_POST[$entryname];
                }
            } /* Other wise, process as string */ else {
                if ($myPost[$entryname] === $label || $myPost[$entryname] === '') {
                    unset($_POST[$entryname]);
                    if ($info['required'] == TRUE) {
                        $_POST['has_error']      = TRUE;
                        $_POST[$entryname_error] = TRUE;
                        ${$entryname_error}      = TRUE;
                    }
                } else { /* is non empty, non label string */
                    $myPost[$entryname]     = $myPost[$entryname];
                    $emaillabel[$entryname] = $label;
                    $emailvar[$entryname]   = $_POST[$entryname];
                }
            }
        }
        /* Attach Documents */
        if ($info['type'] === 'uploadmultiple') {

            $uploadPath = ABSPATH ;
            //die(var_dump($entryname, isset($_FILES[$entryname]), !empty($_FILES[$entryname])));
            if (isset($myFiles[$entryname]) && !empty($myFiles[$entryname])) {

                if (isset($myFiles[$entryname])) {

                    if ($info['type'] === 'uploadmultiple') {

                        if ($myFiles[$entryname] !== '') {

                            fixFilesArray($myFiles[$entryname]);

                            // ################# EMAIL FILES #####################


                            $attachSize = 1;
                            foreach ($myFiles[$entryname] as $position => $file) {

                                move_uploaded_file($file['tmp_name'], $uploadPath . string_normalise($file['name']));

                                $attachSize += $file['size'];

                                if (isset($file['name']) && $file['name'] !== '') {

                                    $email->attach($uploadPath . string_normalise($file['name']));

                                }
                            }

                            if ($attachSize < (24 * 1024 * 1024)) {

                            } else {
                                $_POST['has_error']      = TRUE;
                                $_POST[$entryname_error] = TRUE;
                                ${$entryname_error}      = TRUE;
                            }

                            // ################# EMAIL FILES #####################
                        }
                    }
                }
            }
        }
    }

    /* end foreach */
	date_default_timezone_set('America/New_York');
	$message = '<html>
                <head>
                    <title>' . $meta['message'] . '</title>
                </head>
                <body>
                    <table width="700" border="0" cellspacing="0" cellpadding="0">';
	$i = 0;
	if($extraMessage){
		$message .='
                    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                    <tr>
                            <td colspan="2" width="700" valign="top">' . $extraMessage . '</td>
                    </tr>';
	}
	foreach($emaillabel as $p => $label){
		$message .='
                    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                    <tr>
                            <td width="250" valign="top">' . $label . '</td>
                            <td width="450">' . nl2br($emailvar[$p]) . '</td>
                    </tr>';
		$i++;
	}



	$message .='</table>
                </body>
            </html>';

/*	$email->subject($meta['message']);
	$email->message($message);*/

	//error_log(print_r($emaillabel, true), 1, 'eric.bright@designshopp.com');
	//error_log(print_r($emailvar, true), 1, 'eric.bright@designshopp.com');

	//error_log(print_r($message, true), 1, 'eric.bright@designshopp.com');


    if (!isset($_POST['has_error'])) {
        if ($attachSize < (24 * 1024 * 1024)) {
            //$email->send();
	        add_filter( 'wp_mail_content_type', 'set_html_content_type' );
	        $_POST['emailstatus'] = wp_mail( $emailTo, $emailFrom, $message);
	        remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
	        $_POST['emailsent'] = TRUE;

        } else {
            //todo notify user that the file limit is greater than 25MB

        }

        //################ DELETE UPLOADED FILES ##################

/*        $dirHandle = opendir($uploadPath);

        while ($file = readdir($dirHandle)) {
            if (!is_dir($file)) {
                unlink("$uploadPath" . "$file"); // unlink() deletes the files
            }
        }*/

        //################ END DELETE UPLOADED FILES ##################

        /*if ($_POST['emailsent'] === TRUE && $_POST['form_has_upload'] === TRUE) {
            unlink($uploaddirectory . '/' . $zipname);
        }*/

/*        $debug_config['mailtype'] = "html";
        $debugemail               = new CI_Email($debug_config);

        $debugemail->to('eric.bright@designshopp.com');
        $debugemail->from($emailFrom);

        $debugemail->subject("DS DEBUG REPORT : GENERAL");

        $debug_msg  = $email->print_debugger();
        $debug_send = "
                <html>
                <body>
                    From IP:<br/> " . $_SERVER['REMOTE_ADDR'] . "<br/><br/>
                    Included Message:<br/><code>" . $message . "</code><br/><br/>
                    Debug Report:<br/>
                    <code>
                    " . $debug_msg . "
                    </code>
                </body></html>";
        $debugemail->message($debug_send);*/

	   // error_log(print_r($debug_send, true), 1, 'eric.bright@designshopp.com');
       //  $debugemail->send();
    } else {

    }
}

function reArrayFiles(&$file_post)
{

    $file_ary   = array();
    $file_count = count($file_post['name']);
    $file_keys  = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

function ds_form($formargs, $validation, $meta, $microcopy)
{ /* Start Form */
    $haserror         = '';
    $place            = 1;
    $entryname_sticky = '';
    $label            = '';
    $entryname        = '';
    $validationReqs   = array();
    $validationMsg    = array();
    $emaillabel       = array();
    $emailvar         = array();

    foreach ($formargs as $label => $info) {

        /* Handle the Variables */

        /* Standardize the name */
        $entryname = 'form_' . string_normalise($label);
        if (isset($info['name']) && $info['name'] !== '') {
            $entryname = 'form_' . $info['name'];
        }

        /* Create a name for the error */
        $entryname_error = $entryname . '_error';

        /* Empty 'entryname' if it is default */
        if (isset($_POST[$entryname]) && $_POST[$entryname] === $label) {
            unset($_POST[$entryname]);
        }

        /* username: {
          required: true,
          minlength: 2
          }, */
        if ($info['required'] == TRUE) {

            if (isset($info['equalTo']) && $info['extratype'] === 'email') {
                $validationReqs[] = $entryname . ': {equalTo: "#' . $info['equalTo'] . '", email: true, minlength: 2, required: true}';
            } elseif ($info['extratype'] === 'email') {
                $validationReqs[] = $entryname . ': {email: true, minlength: 2, required: true}';
            } elseif ($info['extratype'] === "postal") {
                $validationReqs[] = $entryname . ': {required: true, minlength: 3, postal_code: true }';
            } elseif ($info['type'] === "uploadmultiple") {
                $validationReqs[] = $entryname . ': {required: true, filesize: 26214400 }';
            } else {
                $validationReqs[] = $entryname . ': {required: true, minlength: 1 }';
            }
            $validationReqs[] = '';
            if (isset($info['req_message']) && $info['req_message'] !== '') {
                $validationMsg[] = $entryname . ': "' . $info['req_message'] . '"';
            } else {
                $validationMsg[] = $entryname . ': "' . $microcopy['requiredmsg'] . '"';
            }
        }
	    if ($info['type'] === 'html') {
		    if (isset($info['content']) && $info['content'] !== '') {
			    echo $info['content'];
		    }
	    }else{
        ?>
        <div class="form-row form-row-<?= $place ?> type-<?= $info['type'] ?> <?php oddEven($place) ?> <?php
        if (isset($info['wrapper'])) {
            echo $info['wrapper'];
        }
        ?>">
            <?php if ($info['hidelabelbox'] !== TRUE) { ?>
                <div class="col col-first">
                <?php if ($info['haslabel'] !== FALSE) { ?>
                    <label for="<?= $entryname ?>"><?php
                        if (isset($info['mylabel']) && $info['mylabel'] != '') {
                            echo $info['mylabel'];
                        } else {
                            echo $label;
                        }
                        ?></label>
                    <?php if (isset($info['secondlabel']) && $info['secondlabel'] !== '') { ?>
                        <span><?= $info['secondlabel']; ?></span>
                    <?php
                    }
                } else {
                    ?>&nbsp;<?php } ?>
                </div><?php } ?>
	        <div class="col col-second">
                <?php
                if ($info['type'] === 'header') {
                    echo '<span class="label">' . $label . '</span>';
                    if ($info['haslabel'] === FALSE && isset($info['secondlabel'])) {
                        echo '<span class="small-label">' . $info['secondlabel'] . '</span>';
                    }
                } elseif ($info['type'] === "hidden") {
                    echo "<input type='hidden' value='{$info['default']}' name='$entryname'/>";
                } elseif ($info['type'] === "text") {
                    $type = 'text';
                    if ($info['extratype'] === 'email') {
                        $type = 'email';
                    }
                    if ($info['extratype'] === 'phone') {
                        $type = 'phone';
                    }
                    if ($info['extratype'] === 'date') {
                        $type = 'date';
                    }
                    ?>

                    <input class="text_field <?php
                    if ($validationinfo !== '') {
                        echo $validationinfo;
                    }
                    ?>" type="<?= $type ?>" name="<?= $entryname ?>" id="<?= $entryname ?>"
                        <?php
                        if (isset($_REQUEST[$entryname])) {
                            echo 'value="' . $_REQUEST[$entryname] . '"';
                        } elseif (isset($info['default_value'])) {
                            echo 'value="' . $info['default_value'] . '"';
                        } elseif (isset($info['default'])) {
                            echo 'placeholder="' . $info['default'] . '"  ';
                        } elseif ($info['haslabel'] === FALSE) {
                            echo 'placeholder="' . $label . '"  ';
                        }
                        ?> />
                <?php
                } elseif ($info['type'] === "uploadmultiple" || $info['type'] === 'uploadsingle') {
                    if (isset($info['req_message']) && $info['req_message'] !== '') {
                        $rm = ' title="' . $info['req_message'] . '"';
                    } else {
                        $rm = '';
                    }
                    if ($info['required'] === TRUE) {
                        $cr = ' required="required"' . $rm;
                    } else {
                        $cr = '';
                    }
                    ?>
                    <input class="text_field  <?php
                    if ($validationinfo !== '') {
                        echo $validationinfo;
                    }
                    ?>" type="file"<?php echo $cr;
                    if ($info['type'] === 'uploadmultiple') {
                        echo ' accept="image/*" multiple="multiple" name="' . $entryname . '[]"';
                    } else {
                        echo 'name="' . $entryname . '"';
                    }?> id="<?= $entryname ?>" data-pair="pair_<?= $entryname ?>" <?php
                    if (isset($_POST[$entryname])) {
                        echo 'value="' . $_POST[$entryname] . '"';
                    } elseif ($info['haslabel'] === FALSE) {
                        echo 'value="' . $label . '"  ';
                    }
                    ?> />

                    <?php /*<a style="display:block;" href="#<?= $entryname; ?>" class="file-upload-replacer input-replacer"
                       data-pair="<?= $entryname; ?>">
							<span class="text-input" id="pair_<?= $entryname; ?>"><?php
                                if (isset($info['mylabel']) && $info['mylabel'] != '') {
                                    echo $info['mylabel'];
                                } else {
                                    echo $label;
                                }
                                ?></span>
                        <span class="upload-button"><?php echo $microcopy['upload']; ?></span>
                    </a>*/
                    ?>


                <?php
                } elseif ($info['type'] === "dropdown") {
                    if ($info['required'] === TRUE) {
                        $cr = ' required';
                    } else {
                        $cr = '';
                    }

                    ?>

                    <select name="<?= $entryname ?>" id="<?= $entryname ?>"<?= $cr; ?>>
                        <option disabled="disabled" selected="selected"><?= $info['default']; ?></option>
                        <?php
                        foreach ($info['values'] as $k => $v) {
                            $dropv = 'form_' . string_normalise($v);
                            if (isset($_POST[$entryname]) && $_POST[$entryname] == $v) {
                                echo '<option selected value="' . $v . '">' . $v . "</option>\n";
                            } else {
                                echo '<option value="' . $v . '">' . $v . "</option>\n";
                            }
                        }
                        ?>
                    </select>
                <?php
                } elseif ($info['type'] === "checkbox") {
                    ?>
                    <fieldset>
                        <?php
                        $p = 0;
                        foreach ($info['values'] as $k => $v) {
                            $check = 'form_' . string_normalise($k);

                            if ($p === 0 && $info['required'] === TRUE) {
                                $cr = ' required="required"';
                            } else {
                                $cr = '';
                            }
                            //echo '<input type="checkbox" class="checkbox" name="" id="" value="" />' . "\n\t" . '<label for="' . $check . '">' . $v . "</label>\n\n\t";
	                        echo '<label><input  type="checkbox"' . $cr . ' name="' . $entryname . '[]" value="' . $v . '"/>' . $v . '</label>';

                            unset($cr);
                            $p++;
                        }
                        ?>

                    </fieldset>
                <?php
                } elseif ($info['type'] === "textarea") {
                    ?>
                    <textarea class="text_area  <?php
                    if ($validationinfo !== '') {
                        echo $validationinfo;
                    }
                    ?>" id="<?= $entryname ?>" name="<?= $entryname ?>" <?php
                    /* If is set to show no label, and no post data is received, enable clearText */
                    if (isset($_REQUEST[$entryname])) { /* do nothing */
                    } elseif (isset($info['default'])) {
                        echo ' placeholder="' . $info['default'] . '"';
                    } elseif ($info['haslabel'] === FALSE) { //HAS LABEL WILL ALWAYS BE FALSE
                        echo ' placeholder="' . $label . '"';
                    }
                    ?> ><?php
                    if (isset($_REQUEST[$entryname])) {
                        echo $_REQUEST[$entryname];
                    }
                    ?></textarea><?php
                } else {
                    ?>
                    <p class="error">Please an input type</p>
                <?php } ?>
                <?php
                if ($_POST[$entryname_error] === TRUE) {
                    if (!isset($info['req_message'])) {
                        $info['req_message'] = 'This field is required';
                    }
                    ?>
                    <label for="<?= $entryname ?>"
                           class="<?= $entryname ?> error"><?= (isset($_POST[$entryname_errorMsg]) ? $_POST[$entryname_errorMsg] : $info['req_message']); ?></label>
                <? } ?>
            </div>
        </div>
        <?php /* /*<p class="<?=$entryname?> error"><?=${$entryname_error}?></p> */ ?>

        <?php
        $place++;
        /* End Form */
    }
    }?>
	<div class="form-row row-submit">
		<div class="col col-second">
		<input type="submit" name="submit-form" id="submit_form"
		       value="<?php if (isset($meta['send_button']) && $meta['send_button'] !== '') {
			       echo $meta['send_button'];
		       } else {
			       echo 'Submit';
		       } ?>"/>
		</div>
	</div>




    <script type="text/javascript">
        $(document).ready(function () {



            $.validator.addMethod("defaultInvalid", function (value, element) {
                return !(element.value == element.defaultValue);
            });

            $.validator.addMethod("postal_code", function (value, element) {
                return this.optional(element) || /\d{5}-\d{4}$|^\d{5}$|^[a-zA-Z][0-9][a-zA-Z](| )?[0-9][a-zA-Z][0-9]$/.test(value);
            }, "<?php _ex("Please enter a valid postal code.", 'Forms', 'Stack8');?>");

            <?php
            /* Start jQuery Validation */
            if($validation !== ''){
                $validationReqs = array_filter($validationReqs);
                $validationMsg = array_filter($validationMsg);


                $reqsCount = count($validationReqs);
                $msgCount = count($validationMsg);
                $i = 1;
                ?>

	        $(document).on(handleClick, '.js-submit', function (e) {
		        var $this = $(this);
		        e.preventDefault();
				$("#submit_form").click();
	        });
            $("<?= $validation ?>").validate({
                //debug: true,
                rules: {
                    <?php
                    foreach($validationReqs as $k => $v){
                        echo $v;
                        if($i !== $reqsCount){
                            echo ", \n";
                        }
                        $i++;
                    }
                    ?>
                },
                messages: {
                    <?php
                    $i = 1;
                    foreach($validationMsg as $k => $v){
                        echo $v;
                        if($i !== $reqsCount){
                            echo ", \n";
                        }
                        $i++;
                    }
                    ?>
                },
                errorPlacement: function (error, element) {
                    error.appendTo(element.parent());
                }

            });
        });


    </script>
    <?php
    /* End jQuery Validation */
}
}

/* end ds_forms(); */

?>
