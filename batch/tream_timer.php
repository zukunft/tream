<?php 
 
/* 

tream_timer.php - call the scheduled jobs 

TO DO: 

automatically create a 15 min crontab entry by install.php if not existing


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

Copyright (c) 2013-2017 zukunft.com AG, Zurich
Heang Lor <heang@zukunft.com>

http://tream.biz
*/ 

// todo:add this to the crontab by install.php

include_once './tream_db_adapter.php';
include_once './tream_db.php';
include_once './tream_get_yahoo.php';
include_once './tream_portfolio_check.php';
include_once './tream_messages.php';
include_once './tream_check_securities.php';

// save prices from yahoo
tream_save_yahoo(FALSE);

// add birthdays
//tream_check_events();

// check asset allocation
tream_check_all_portfolios(FALSE);

// check general limits for single securities
tream_check_portfolio_security_moves(FALSE);

// send out trade confirmations
msg_confirm_trades(FALSE);

// check security triggers
echo msg_sec_triggers($debug);

echo 'TREAM timed jobs done.';
?> 

