<p><?php
	if (get_lang_active('fr')):
		echo "Désolé, nous n'avons pas pu trouver la page que vous cherchiez.";
	else:
		_ex( "Sorry, but you've found a page that does not exist, please use the above navigation to find your way back.",
			'Error 404', theme_domain());
	endif;
	?></p>