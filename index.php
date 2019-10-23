<?php
/* 
	Title: SmallCMS
	Descr: Mini gestionnaire de contenu base sur flasklike
	Author: Jean-Luc Cyr
	Modified: Jean-Sebastien St-Pierre
	Date: 2019/10/23
*/
require('flasklike.php');

////////////////////////////////////////////////////////////////
// Page d'accueil
$route_defs['/']['GET'] = 
function(){
	$params = ['titre'=>'Les astres chuchottent à votre oreille!',
				];
	fl_render_template('templates/index.html', $params);
};

////////////////////////////////////////////////////////////////
// Tentative de connexion
$route_defs['/horoscope']['POST'] = 
function(){
	
	$name = $_POST['inputName'];
	$fstName = $_POST['inputFstName'];
	$bthDate = $_POST['inputDOB'];

	/*<script type='text/javascript'>
	window.alert($name & $fstName & $bthDate);
	</script>*/
	
	exec("python app.py $name $fstName $bthDate", $output);
	foreach ($output as $key)
	{
		echo $key ."\n";
	}
	
	//echo $output;
	#echo "<script type='text/javascript'>alert('$output');</script>";
};

////////////////////////////////////////////////////////////////
// Menu principal
$route_defs['/horoscope']['GET'] = 
function(){
	if (!fl_auth()) {
		echo "bleep";
		$params = ['message'=>'Veuillez vous identifier',
					];
		fl_render_template('index.html', $params);		
	} else {
		fl_render_template('menu.html', $params);				
	}
};


////////////////////////////////////////////////////////////////
// and after all definitions should call flasklike_run()
fl_run();
