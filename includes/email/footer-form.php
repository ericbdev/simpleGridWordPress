<?php
/*   * ***************************************************************************** */



$microcopy = array(
    'upload'      => _x('Choose a file', 'Contact Forms', 'Stack8'),
    'requiredmsg' => 'This field is required',


);
$sendInfo = array(
	'send_button' => _x('Submit', 'Contact Form', 'Stack8'),
	"message"     => _x('Contact request from Stack8', 'Contact Form', 'Stack8'),
	//"emailto"     => 'consulting@Stack8.ca',
	"emailto"     => 'info@Stack8.ca',
	"emailfrom"     => 'info@Stack8.ca'
	//'id_from_db' => $id
);


//"type" => "text", 'textarea', 'checkbox', 'dropdown', 'uploadmultiple'
//'default' => 'inside label' /* custom text to display inside content, in drop downs is the default selected text*/
//'name' => 'form_name' to overide
//'extratype'=> 'date','email','phone'
//'values' => array('item1','item2','item3','etc'); /*Text for dropdowns, checkboxes, radios*/
//"mylabel" => "My First Name",  /*If unspecified, label is taken from key*/
//'secondlabel' => 'other text to show below label'
//"wrapper" => "my wrapper class",  /*/*assigns a specific class to the wrapping element*/
//"required"    => true, /*defaults to false
//"haslabel" => false  /*Defaults to true, if is false, have inline label*/,
//"hidelabelbox" => true  /*determines if the bounding box around the label is hidden, defaults to false*/
//"isemail" => true,  //Defaults to false, if its true, gets procesed as email*/
//"required"    => true,
//"req_message" => "Please enter a valid e-mail"
$footerArgs = array(
    //(ICL_LANGUAGE_CODE == 'fr' ? '' : '')
    _x('Name', 'Contact Form', 'Stack8') => array(
	    "type"         => "text",
	    "haslabel"     => TRUE,
	    "hidelabelbox" => FALSE,
	    "required"     => TRUE,
	    'name'         => 'footer_full_name',
	    "req_message"  => _x('Please enter your full name', 'Contact Form', 'Stack8'),
    ),
    _x('Email', 'Contact Form', 'Stack8') => array(
	    "type"         => "text",
	    'extratype'    => 'email',
	    "required"     => true, /* defaults to false */
	    "req_message"  => _x('Please enter a valid e-mail address', 'Contact Form', 'Stack8'),
	    "haslabel"     => TRUE,
	    "hidelabelbox" => FALSE,
	    'name'         => 'footer_email'
    ),
    _x('Message', 'Contact Form', 'Stack8') => array(
	    "type"         => "textarea",
	    "haslabel"     => TRUE,
	    "hidelabelbox" => FALSE,
	    "required"     => true,
	    "req_message"  => _x('Please enter a message', 'Contact Form', 'Stack8'),
	    'name'         => 'footer_message',
    ),

);

if (isset($_REQUEST['submit-form']) && isset($_REQUEST['form_footer_full_name'])) {
	ds_validate_forms($_POST, $footerArgs, $sendInfo);
}

if (isset($_POST['emailsent']) && $_POST['emailsent'] == TRUE && isset($_REQUEST['form_footer_full_name'])) {
    ?>
	<p><?php _ex('Thank you for your contact request, we will reply as soon as possible', 'Contact Form', 'Stack8');?></p>
<?php
} else {
    ?>
    <form id="footer-form" name="footer-form" class="footer-form" method="post">
        <?php ds_form($footerArgs, '#footer-form', $sendInfo, $microcopy); ?>
    </form>
<?php } ?>
