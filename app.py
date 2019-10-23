#!/usr/bin/python
# -*- coding: latin-1 -*-

# app.py by Jean-Sébastien St-Pierre STPJ15018206, October 2019
# This is the logic implementation for the basic flask server

import sys
import re, json, datetime
from datetime import *
import requests

php_name = sys.argv[1]
php_fstName = sys.argv[2]
php_bthDate = sys.argv[3]


def validateInput(name, fname, bdate):
	#Regex server-side validation for alphanumeric-only user input
	namePattern = "[a-zA-Z- ]+$"
	bdatePattern = "^((0|1)\\d{1})/((0|1|2|3)\\d{1})/((19|20)\\d{2})"
	validationError = []

	if not name:
		validationError.append("Le champ 'nom' est vide!\n")
	else:
		if not re.match(namePattern, name):
			validationError.append(name + " est un nom invalide!\n")

	if not fname:
		validationError.append("Le champ 'prénom' est vide!\n")
	else:
		if not re.match(namePattern, fname):
			validationError.append(fname + " est un nom invalide!\n")

	if not bdate:
		validationError.append("Le champ 'date de naissance' est vide!\n")
	else:
		m, d, y = bdate.split('/')
		dateObj = date(int(y), int(m), int(d))
		if dateObj > date.today():
			validationError.append("Vous ne pouvez pas être né dans le futur\n")

		elif not re.match(bdatePattern, bdate):
			validationError.append(bdate + " est une date de naissance invalide!\n")

	return validationError

def getSign(date):
	month, d, y = date.split('/')
	day = int(d)
	if month == '12': 
		astro_sign = 'Sagittarius' if (day < 22) else 'capricorn'

	elif month == '01': 
		astro_sign = 'Capricorn' if (day < 20) else 'aquarius'

	elif month == '02': 
		astro_sign = 'Aquarius' if (day < 19) else 'pisces'

	elif month == '03': 
		astro_sign = 'Pisces' if (day < 21) else 'aries'

	elif month == '04': 
		astro_sign = 'Aries' if (day < 20) else 'taurus'

	elif month == '05': 
		astro_sign = 'Taurus' if (day < 21) else 'gemini'

	elif month == '06': 
		astro_sign = 'Gemini' if (day < 21) else 'cancer'

	elif month == '07': 
		astro_sign = 'Cancer' if (day < 23) else 'leo'

	elif month == '08': 
		astro_sign = 'Leo' if (day < 23) else 'virgo'

	elif month == '09': 
		astro_sign = 'Virgo' if (day < 23) else 'libra'

	elif month == '10': 
		astro_sign = 'Libra' if (day < 23) else 'scorpio'

	elif month == '11': 
		astro_sign = 'scorpio' if (day < 22) else 'sagittarius'

	return astro_sign

def getAztro(asign):
	params = (
		('sign', asign),
		('day', 'today'),
		)

	return requests.post('https://aztro.sameerkumar.website/', params=params)

def justGo(name, fstname, bdate):
	validated = (validateInput(name, fstname, bdate))
	mysign = getSign(bdate)
	requestHoroscope = getAztro(mysign)
	myHoroscope = format(requestHoroscope.json()["description"])			
	print(json.dumps({'name':name, 'fstName': fstname, 'bthDate': bdate,
		'ErrorCodes': validated, 'sign': mysign, 'horoscope': myHoroscope}))

if __name__ == '__main__':
	justGo(php_name, php_fstName, php_bthDate)
	
