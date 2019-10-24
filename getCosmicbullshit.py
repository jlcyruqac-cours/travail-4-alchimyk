#!/usr/bin/python
# -*- coding: latin-1 -*-

# getCosmicbullshit.py by Jean-Sébastien St-Pierre STPJ15018206, October 2019
# This is the logic implementation for ze REST request

import sys
import re, json, datetime
from datetime import *
import requests

php_sign = sys.argv[1]

# As we learn to be good programmers, we should not reinvent the wheel.
def getAztro(asign):
	params = (
		('sign', asign),
		('day', 'today'),
		)

	cosmicFart = requests.post('https://aztro.sameerkumar.website/', params = params) 
	print(format(cosmicFart.json()["description"]))

#if __name__ == '__main__':
getAztro(php_sign)
	
