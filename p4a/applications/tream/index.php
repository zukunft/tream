<?php
/* 

TREAM GUI start

This file is part of TREAM - Portfolio Management Software.

TREAM is free software: you can redistribute it and/or modify it
under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of
the License, or (at your option) any later version.

TREAM is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with TREAM. If not, see <http://www.gnu.org/licenses/gpl.html>.

To contact the authors write to: 
Timon Zielonka <timon@zukunft.com>

Copyright (c) 2013-2015 zukunft.com AG, Zurich
Heang Lor <heang@zukunft.com>

http://tream.biz

 * This file is based on P4A - PHP For Applications.
 *
 * To contact the authors write to:                                     
 * Fabrizio Balliano <fabrizio@fabrizioballiano.it>                    
 * Andrea Giardina <andrea.giardina@crealabs.it>
 *
 * https://github.com/fballiano/p4a

*/ 

// Select application's locale
define("P4A_LOCALE", 'en_US');

define("P4A_DSN", 'mysql://p4a:xxx@localhost/tream');

// Enable logging and profiling of all DB actions
// define("P4A_DB_PROFILE", true);

// Enable more error details
define("P4A_EXTENDED_ERRORS", true);

// Disable AJAX during the development phase, it will allows you
// a faster debug, enable it on the production server
// define("P4A_AJAX_ENABLED", false);

// Path (on server) where P4A will write all code transferred via AJAX
// define("P4A_AJAX_DEBUG", "/tmp/p4a_ajax_debug.txt");

require_once dirname(__FILE__) . '/../../p4a.php';

// Check Installation and configuration.
// This lines should be removed after the first run.
$check = p4a_check_configuration();

$log_user = get_current_user();

// Here we go
if (is_string($check)) {
	print $check;
} else {
	$p4a = p4a::singleton("Tream");
	$p4a->main();
}
