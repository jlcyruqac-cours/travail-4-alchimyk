<?php
/* 
	Title: SmallCMS
	Descr: Mini gestionnaire de contenu base sur flasklike
	Author: Jean-Luc Cyr
	Modified: Jean-Sebastien St-Pierre
	Date: 2019/10/23
*/
require('flasklike.php');

# Index page
$route_defs['/']['GET'] = 
function(){
	$params = ['titre'=>'Les astres chuchottent à votre oreille!',
				];
	fl_render_template('templates/index.html', $params);
};

# Data processing from POST request
$route_defs['/horoscope']['POST'] = 
function(){

	# Extract POST data items
	$name = $_POST['inputName'];
	$fstName = $_POST['inputFstName'];
	$bthDate = $_POST['inputDOB'];

	# Launch input validation
	$validationErrors = validateInput($name, $fstName, $bthDate);
	$mySign = getSign($bthDate);
	$zeHoroscope = getAztro($mySign);

	# Pre-format for json-like array
	$preJsonified = array('name' => $name, 'fstName' => $fstName, 'bthDate' => $bthDate, 'ErrorCodes' => $validationErrors
		, 'sign' => $mySign, 'horoscope' => $zeHoroscope);
	echo json_encode($preJsonified);
};


function validateInput($name, $fname, $bdate)
{
	# Regex server-side validation for user input
	$namePattern = '/(^[a-zA-Z\-]+)$/';
	$bdatePattern = "~^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$~";
	$validationError = [];

	# Validation for null/forbidden inputs
	if ($name == "")
		array_push($validationError,"Le champ 'nom' est vide!\n");
	else
	{
		if (!preg_match($namePattern, $name))
			array_push($validationError, "Le nom " . $name . " est invalide!\n");
	}
	
	if ($fname == "")
		array_push($validationError, "Le champ 'prénom' est vide!\n");
	else
	{
		if (!preg_match($namePattern, $fname))
			array_push($validationError, "Le prénom " . $fname . " est invalide!\n");
	}

	if ($bdate == "")
		array_push($validationError, "Le champ 'date de naissance' est vide!\n");
	else
	{
		if ($bdate > date('Y-m-d'))
			array_push($validationError, "Vous ne pouvez pas être né dans le futur!\n");
		else
		{
			if (!preg_match($bdatePattern, $bdate))
				array_push($validationError, "La date " . $bdate . " est invalide!\n");
		}
	}
	return $validationError;
};

function getSign($bdate) 
{
	# Parsing/casting date components
	$dateParts = explode('-', $bdate);
	$month = intval($dateParts[1]);
	$day = intval($dateParts[2]);
	# Get Zodiac sign
	$zodiac = array('', 'Capricorn', 'Aquarius', 'Pisces', 'Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius', 'Capricorn');
	$last_day = array('', 19, 18, 20, 20, 21, 21, 22, 22, 21, 22, 21, 20, 19);
	return ($day > $last_day[$month]) ? $zodiac[$month + 1] : $zodiac[$month];
	 
};

function getAztro($asign)
{
	# Since what we learn in Jean-Luc's courses shall serve some purpose, I had to make
	# at least a minor call to a Python script.
	exec("python getCosmicBullshit.py $asign", $horoscope);
	return $horoscope;
};
	
////////////////////////////////////////////////////////////////
// and after all definitions should call flasklike_run()
fl_run();
