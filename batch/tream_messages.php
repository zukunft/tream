<?php   

/* 

tream_messages.php - TREAM messages for sending out email and other messages 

TO DO: 

...

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
*/ 


// maybe move these parameters to the parameter table later 
// don't send out old trade confirmations, because most likely they are allready confirmed
define("MSG_TRADE_CONF_MAX_DAYS", 2);

define("MSG_VAR_START", "$$");
define("MSG_VAR_END", "$$");


// used for debugging to be able to switch on an off the output
function tream_debug($msg, $debug) {
  if ($debug == TRUE) {
    echo $msg.'<br>';
  }
}

// replace vars in a trade related message text
function msg_text_trade($msg_text, $trade_row) {
  $result = $msg_text;
  $result = str_replace(MSG_VAR_START.'portfolio_id'.          MSG_VAR_END, $trade_row['portfolio_id'],               $result);
  $result = str_replace(MSG_VAR_START.'portfolio_name'.        MSG_VAR_END, $trade_row['portfolio_name'],               $result);
  $result = str_replace(MSG_VAR_START.'trade_id'.              MSG_VAR_END, $trade_row['trade_id'],               $result);
  $result = str_replace(MSG_VAR_START.'trade_type'.            MSG_VAR_END, $trade_row['trade_type'],               $result);
  $result = str_replace(MSG_VAR_START.'size'.                  MSG_VAR_END, $trade_row['size'],                   $result);
  $result = str_replace(MSG_VAR_START.'security_name'.         MSG_VAR_END, $trade_row['security_name'],          $result);
  $result = str_replace(MSG_VAR_START.'price'.                 MSG_VAR_END, $trade_row['price'],                  $result);
  $result = str_replace(MSG_VAR_START.'valid_until'.           MSG_VAR_END, $trade_row['valid_until'],            $result);
  $result = str_replace(MSG_VAR_START.'creation_time'.         MSG_VAR_END, $trade_row['creation_time'],          $result);
  $result = str_replace(MSG_VAR_START.'confirmation_time_bank'.MSG_VAR_END, $trade_row['confirmation_time_bank'], $result);
  $result = str_replace(MSG_VAR_START.'person_name'.           MSG_VAR_END, $trade_row['person_name'],            $result);
  $result = str_replace(MSG_VAR_START.'portfolio_manager'.     MSG_VAR_END, $trade_row['portfolio_manager'],           $result);
  return $result;
}

// send out a email via the tream server
function msg_email($mail_to, $mail_subject, $mail_message) {
  $result = '';
  // don't send messages to nobody, but do not create a warning, because if for example the deputy portfolio manager is not set this function may nevertheless be called
  if ($mail_to <> '') {
    $mail_header =  'From:     tream@trinklerpartners.com' . "\r\n" .
		    'Reply-To: tream@trinklerpartners.com' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
    //$result .= 'send mail '.$mail_subject.' ('.$mail_message.') to '.$mail_to.' from '.$portfolio_manager.' ';
    $result .= mail($mail_to, $mail_subject, $mail_message, $mail_header).'<br>';
  }
  return $result;
}
 
// trade confirmations: send out a trade confirmation for double check
// at the moment just send a email, maybe a later other channels
function msg_confirm_trade_client($trade_row, $msg_subject, $msg_text) {
  $result = '';
  // for security reasons at the moment to a fixed email
  //$client            = sql_get("SELECT p.contact_number FROM portfolios pf, accounts a, v_persons p WHERE pf.portfolio_id = ".$trade_row['portfolio_id']." AND pf.account_id = a.account_id AND a.person_id = p.person_id;");
  $client            = 'timon@zukunft.com';
  $portfolio_manager = sql_get("SELECT contact_number FROM v_portfolio_persons WHERE portfolio_id = ".$trade_row['portfolio_id']." AND portfolio_function_code_id = '".ACCOUNT_PERSON_TYPE_PM."';");
  if ($portfolio_manager == '') { $portfolio_manager = 'info@trinklerpartners.com'; };

  $mail_to = $client;
  $mail_subject = msg_text_trade($msg_subject, $trade_row);
  $mail_message = msg_text_trade($msg_text,    $trade_row);
  $result .= msg_email($mail_to, $mail_subject, $mail_message);
  $result .= sql_set("trades", "trade_id", $trade_row['trade_id'], "confirmation_time_client", date('Y-m-d H:i:s'), '');

  return $result;
}

function msg_confirm_trade_bank($trade_row, $msg_subject, $msg_text) {
  $result = '';
  $bank_rm           = sql_get("SELECT contact_number FROM v_portfolio_persons WHERE portfolio_id = ".$trade_row['portfolio_id']." AND portfolio_function_code_id = '".ACCOUNT_PERSON_TYPE_BANK_RM."';");
  $portfolio_manager = sql_get("SELECT contact_number FROM v_portfolio_persons WHERE portfolio_id = ".$trade_row['portfolio_id']." AND portfolio_function_code_id = '".ACCOUNT_PERSON_TYPE_PM."';");
  if ($portfolio_manager == '') { $portfolio_manager = 'info@trinklerpartners.com'; };

  $mail_to = $bank_rm;
  $mail_subject = msg_text_trade($msg_subject, $trade_row);
  $mail_message = msg_text_trade($msg_text,    $trade_row);
  $result .= msg_email($mail_to, $mail_subject, $mail_message);
  $result .= sql_set("trades", "trade_id", $trade_row['trade_id'], "confirmation_time_bank", date('Y-m-d H:i:s'), '');

  return $result;
}

// send a trade confirmation for all trades that have not yet been sent
function msg_confirm_trades() {
  $link = sql_open();

  $result = '';
  $date_back_until =  '-'.MSG_TRADE_CONF_MAX_DAYS.' days' ;

  // loop over the trades that have not yet been confirmed to the client
  $sql_result  = sql_result("SELECT * FROM v_trades_confirm_to_client WHERE creation_time > '".date('Y-m-d H:i:s', strtotime( $date_back_until ) )."';");
  $msg_subject = sql_get("SELECT subject FROM messages m, message_types t WHERE m.message_type_id = t.message_type_id AND t.code_id = '".MSG_TRADE_CONF_CLIENT."';");
  $msg_text    = sql_get("SELECT body    FROM messages m, message_types t WHERE m.message_type_id = t.message_type_id AND t.code_id = '".MSG_TRADE_CONF_CLIENT."';");
  while ($trade_row = mysql_fetch_assoc($sql_result)) {
    $result .= msg_confirm_trade_client($trade_row, $msg_subject, $msg_text);
  }
  
  // loop over the trades that have not yet been confirmed to the bank
  $sql_result  = sql_result("SELECT * FROM v_trades_confirm_to_bank WHERE creation_time > '".date('Y-m-d H:i:s', strtotime( $date_back_until ) )."';");
  $msg_subject = sql_get("SELECT subject FROM messages m, message_types t WHERE m.message_type_id = t.message_type_id AND t.code_id = '".MSG_TRADE_CONF_BANK."';");
  $msg_text    = sql_get("SELECT body    FROM messages m, message_types t WHERE m.message_type_id = t.message_type_id AND t.code_id = '".MSG_TRADE_CONF_BANK."';");
  while ($trade_row = mysql_fetch_assoc($sql_result)) {
    $result .= msg_confirm_trade_bank($trade_row, $msg_subject, $msg_text);
  }
  
  sql_close($link);

  return $result;
}

// send a trade confirmation for all trades that have not yet been sent
/* please use tream_debug of tream_db_adapter instead
function msg_log($msg_text, $debug) {
  if ($debug == TRUE) {
    echo msg_text;
  }
}
*/
?>
