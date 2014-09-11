<?php
/*   * ***************************************************************************** */



$microcopy = array(
    'upload'      => _x('Choose a file', 'Forms', themeDomain()),
    'requiredmsg' => 'This field is required',


);
$sendInfo = array(
	'send_button' => _x('Submit', 'Contact Form', themeDomain()),
	"message"     => _x('Contact request from Website', 'Contact Form', themeDomain()),
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
$quoteArgs =  array(
	//(ICL_LANGUAGE_CODE == 'fr' ? '' : '')
	_x('*Name', 'Contact Form', themeDomain()) => array(
		"type"         => "text",
		"hidelabelbox" => true,
		"required"     => true,
		"haslabel"     => false,
		'name'         => 'contact_full_name',
		"req_message"  => _x('Please enter your full name', 'Contact Form', themeDomain()),
	),
	_x('*Email', 'Contact Form', themeDomain()) => array(
		"type"         => "text",
		'extratype'    => 'email',
		"required"     => true, /* defaults to false */
		"req_message"  => _x('Please enter a valid e-mail address', 'Contact Form', themeDomain()),
		"hidelabelbox" => true,
		"haslabel"     => false,
		"required"     => true,
		'name'         => 'contact_email'
	),
	_x('*Message', 'Contact Form', themeDomain()) => array(
		"type"         => "textarea",
		"haslabel"     => false,
		"hidelabelbox" => true,
		"required"     => true,
		"req_message"  => _x('Please enter a message', 'Contact Form', themeDomain()),
		'name'         => 'contact_message',
	),

);

if (isset($_REQUEST['submit-form']) && isset($_REQUEST['form_contact_full_name'])) {
	ds_validate_forms($_POST, $quoteArgs, $sendInfo);
}

if (isset($_POST['emailsent']) && $_POST['emailsent'] == TRUE) {
    ?>
	<div class="wrapper">
		<div class="row">
			<div class="columns small-12">
				<p><?php _ex('Thank you for your contact request, you will be replied to shortly', 'Contact Form', themeDomain());?></p>
			</div>
		</div>
	</div>
<?php
} else {
    ?>
    <form id="contact-form" name="contact-form" class="contact-form" method="post">
        <?php ds_form($quoteArgs, '#contact-form', $sendInfo, $microcopy); ?>
    </form>
<?php } ?>
