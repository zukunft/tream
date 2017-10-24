<html>
 <head>
  <title>TREAM install wizard</title>
 </head>
 <body>
 <?php 
 
/*

Install wizard for TREAM

general concepts used at creation of TREAM
link between code and db is done only using the fields code_id

Script naming convention:
-------------------------

tream_*.php (t_)         - backend code that cannot be called from the GUI (with the funtion prefix in the brackets)


The php modules of TREAM are:

main (can be called by the GUI)
----

install.php                    - called usually just once to create the database, setup the basic configuration e.g. by adding code linked database rows and calls the setup wizard
reconciliation.php             - to start the reconciliation from the GUI
event_exe.php                  - called from the p4a user interface to execute one single event
tream_timer.php                - called by the linux server crontab: just the starting point for all periodic tasks; should be as small as possible


asset allocation monitoring
---------------------------

portfolio_overview.php         - wrapper to shows the details of one portfolio for testing using the internal TREAM functions
check_exposure_tree.php        - check the exposure tree consistency and create warning messages if needed mainly for testing, but can also be called from the GUI
tream_portfolio_check.php      - functions to check one portfolio; Calculate the portfolio exposures and create warning messages if needed; is called also by check.php
tream_allocation_optimizer.php - calculates an optimal asset allocation


database connection
-------------------

tream_db.php (sql)             - functions for tream specific databse requests. If possible all db table names and field names are here, so that if a database 
tream_db_adapter.php           - functions the link to the database; only this module should contain direct SQL code; parts maybe replaced by zend sql link 


user interaction
----------------

tream_message.php (msg)        - functions for sending emails, creating events and debugging
tream_check_securities.php     - TREAM Check securities
tream_check_events.php         - functions that checks if new events must be created or others can be closed 


reconciliation and portfolio data im-/export
--------------------------------------------

tream_recon_file.php           - called to start the reconciliation for one specific file; the module tream_timer calls this for all files needed
tream_link_cs.php (cs)         - import positions from the Credit Suisse EAM link
tream_link_mm.php (mm)         - to load background data from MarketMap like the Market Map symbols selected by the ISIN
tream_link_eurex.php           - to get quotes from the eurex webside
tream_files.php (file)         - Link to saved files
tream_files_xml.php (xml)      - Link to XML files


price feeds and security data im-/export
----------------------------------------

tream_get_yahoo.php (gyp)      - Yahoo link (get prices)
tream_get_yahoo_sec.php (gys)  - Yahoo link (get security data and maybe called from the GUI)


Charts (can also be called from the GUI)
-----

tream_chart.php                - called from the p4a user interface and returns a picture; link to the library pChart
tream_chart_pie.php            - create a pie chart with TREAM settings
tream_chart_xy.php             - create a XY chart with TREAM settings
tream_chart_exposure_type.php  - show the risk reward chart (These scripts can be called from the GUI but needs to be review, because they have there own db call)


general
-----

tream_library.php              - general php functions, If possible the functions should be replaced be zend functions and this file is empty


testing
-------

tream_files_test.php           - Link to test files


old modules
-----------

tream_jb_link.php              - to be moved to the reconciliation configuration
check.php                      - Consistency check script that fixes automatically some issues or creates a message for the user; to be splitted to tream_timer, tream_messages and other
tream_charts.php               - maybe not needed any more


*/
 
// main batch job 
echo 'TREAM install and setup wizard<br>';

include_once './tream_db_adapter.php';
include_once './tream_db.php';

// init
$link = sql_open();

/*
 To Do: 
 
 create a fresh database if needed
 
 check database records that are linked to code 

 create missing records and load the IDs into an array
 used the field code_id to identify the correct records

 defined in tream_db_adapter.php

*/

echo 'TREAM install wizard - create default settings<br>';
sql_code_link(EVENT_STATUS_CREATED, "status_text", "created");
sql_code_link(EVENT_STATUS_WORKING, "status_text", "in progress");
sql_code_link(EVENT_STATUS_DONE,    "status_text", "done");
sql_code_link(EVENT_STATUS_CLOSED,  "status_text", "closed");

sql_code_link(EVENT_TYPE_TRADE_MISSING,  "type_name", "Trade missing");
sql_code_link(EVENT_TYPE_SQL_ERROR_ID,   "type_name", "SQL Error");
sql_code_link(EVENT_TYPE_SYSTEM_EVENT,   "type_name", "System Event");
sql_code_link(EVENT_TYPE_USER_DAILY,     "type_name", "Daily check");
sql_code_link(EVENT_TYPE_EXPOSURE_LIMIT, "type_name", "Portfolio exposure limit");

sql_code_link(SYSTEM_MM_FEED,    "system_name", "Price Feed Market Map");
sql_code_link(SYSTEM_YAHOO_FEED, "system_name", "Price Feed Yahoo");

sql_code_link(FILE_TYPE_FIXED, "type_name", "fixed leght");
sql_code_link(FILE_TYPE_XML,   "type_name", "xml");

sql_code_link(ACCOUNT_PERSON_TYPE_PM,      "type_name", "Portfolio Manager");
sql_code_link(ACCOUNT_PERSON_TYPE_DPM,     "type_name", "Deputy Portfolio Manager 	");
sql_code_link(ACCOUNT_PERSON_TYPE_BANK_RM, "type_name", "Bank RM");

sql_code_link(MSG_TRADE_CONF_CLIENT, "type_name", "Trade confirmation client");
sql_code_link(MSG_TRADE_CONF_BANK,   "type_name", "Trade confirmation bank");

sql_code_link(SEC_TRIGGER_TAKE_PROFIT, "type_name", "send an email message if the price raises above the defined level");
sql_code_link(SEC_TRIGGER_STOP_LOSS,   "type_name", "send a message if the price falls below a defined level");

sql_code_link(SEC_TRIGGER_STATUS_NEW,       "status_text", "created");
sql_code_link(SEC_TRIGGER_STATUS_ACTIVE,    "status_text", "monitoring");
sql_code_link(SEC_TRIGGER_STATUS_TRIGGERED, "status_text", "event created");
sql_code_link(SEC_TRIGGER_STATUS_CLOSED,    "status_text", "closed");

echo 'standard configuration created';

// Person Type: intern, Prospect
// Trade type: Buy @ market (factor 1), Sell @ market (factor -1)
// Trade status: new, placed at bank, executed

// optional: Rational needed should be default be switched off

//sql_code_link(EVENT_TYPE_SYSTEM_EVENT,  "type_name", "System Event");
// 

// add non linked default values
// account_type: Prospect
// account_mandate: Discretionary Growth
// currency: CHF, EUR, USD
// banks: JB
// contact type: telefon call
// Securities: ABB

// create or / and adjust crontab job

echo 'create users';

//sql_code_link(USER_SYSTEM,       "username", "system");
//sql_code_link(USED_CHECK_SCRIPT, "username", "check script");

// request the user name and password and add these to the persons as an admin
// request the firm name and create a person with this name; this person is used in the scripts
// create a account for the firm plus an portfolio

sql_close($link);

echo 'TREAM install finished succesfull<br>';

 ?> 
 </body>
</html> 
