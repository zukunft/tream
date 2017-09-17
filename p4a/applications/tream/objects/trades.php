<?php
/* 

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

 * This file is based on P4A - PHP For Applications.
 *
 * To contact the authors write to:                                     
 * Fabrizio Balliano <fabrizio@fabrizioballiano.it>                    
 * Andrea Giardina <andrea.giardina@crealabs.it>
 *
 * https://github.com/fballiano/p4a
 *
 * @author Timon Zielonka <timon@zukunft.com>
 * @copyright Copyright (c) 2013-2017 zukunft.com AG, Zurich

*/
class Trades extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "trades")
			->setTable("trades")
			->addOrder("creation_time")
/*			->addJoinLeft("accounts", "trades.account_id = accounts.account_id",
					  array('account_name'=>'account')) */
			->addJoinLeft("portfolios", "trades.portfolio_id = portfolios.portfolio_id",
					  array('portfolio_name'=>'portfolio'))
/*			->addJoinLeft("persons", "trades.internal_person_id  = persons.person_id",
					  array('lastname'=>'internal_person')) */
			->addJoinLeft("securities", "trades.security_id  = securities.security_id",
					  array('name'=>'security','ISIN'=>'ISIN','last_price'=>'last'))
			->addJoinLeft("v_trade_security", "trades.trade_id  = v_trade_security.trade_id",
					  array('security_name'=>'security_name')) 
			->addJoinLeft("trade_types", "trades.trade_type_id  = trade_types.trade_type_id",
					  array('description'=>'trade_type'))
			->addJoinLeft("trade_stati", "trades.trade_status_id  = trade_stati.trade_status_id",
					  array('status_text'=>'status'))
			->setWhere('trades.security_id is Not Null') 
			->setPageLimit(20)
			->load();

		$this->build("p4a_db_source", "log_trades")
			->setTable("log_data")
			->addOrder("log_time")
			->setWhere("log_data.table_name = 'trades'")
			->load();

		$this->build("p4a_db_source", "trade_payments")
			->setTable("trade_payments")
			->addOrder("amount")
			->addJoinLeft("trade_payment_types", "trade_payments.trade_payment_type_id  = trade_payment_types.trade_payment_type_id",
					  array('type_name'=>'type'))
			->load();
		
		$this->setSource($this->trades);
		
		$this->firstRow();
        
		// Customizing fields properties
		$this->fields->date_placed
			->setLabel("Placed at")
			->setTooltip("time when the order has been placed at the markets");
		$this->fields->date_client
			->setLabel("Accounting date")
			->setTooltip("at the moment only used by Bank Baer");

		$this->fields->account_id
			->setLabel("Account")
			->setType("select")
			->setSource(P4A::singleton()->select_accounts)
			->setSourceDescriptionField("account_select_name"); 

		$this->fields->portfolio_id
			->setLabel("Portfolio")
			->setType("select")
			->setSource(P4A::singleton()->select_portfolios)
			->setSourceDescriptionField("portfolio_select_name"); 

		$this->fields->internal_person_id
			->setLabel("Internal Person")
			->setType("select")
			->setSource(P4A::singleton()->internal_persons)
			->setSourceDescriptionField("select_name");

		$this->fields->currency_id
			->setLabel("Currency")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->settlement_currency_id
			->setLabel("Settlement curr")  
			->setType("select")
			->setSource(P4A::singleton()->select_settle_currencies)
/*			->setSource(P4A::singleton()->select_settle_currencies) */
			->setSourceDescriptionField("symbol");

		$this->fields->security_id
			->setLabel("Security")
			->setType("select")
			->setSource(P4A::singleton()->select_securities)
			->setSourceDescriptionField("select_name");

		$this->fields->trade_type_id
			->setLabel("Trade type")
			->setType("select")
			->setSource(P4A::singleton()->select_trade_types)
			->setSourceDescriptionField("description"); 

		$this->fields->trade_status_id
			->setLabel("Trade status")
			->setType("select")
			->setSource(P4A::singleton()->select_trade_stati)
			->setSourceDescriptionField("status_text"); 

		$this->fields->trade_confirmation_type_id
			->setLabel("Bank Contact type")
			->setType("select")
			->setSource(P4A::singleton()->select_trade_confirmation_types)
			->setSourceDescriptionField("type_name"); 

		$this->fields->contact_type_id
			->setLabel("Client contact type")
			->setType("select")
			->setSource(P4A::singleton()->select_contact_types)
			->setSourceDescriptionField("type_name");

		$this->fields->related_trade_id
			->setLabel("Releated trade")
			->setType("select")
			->setSource(P4A::singleton()->trade_select)
			->setSourceDescriptionField("trade_key");

		$this->fields->scanned_bank_confirmation->setType("file");
		
		$this->fields->price->setLabel("Trade price");
		$this->fields->trade_confirmation_person->setLabel("Bank Contact");
		$this->fields->confirmation_time->setLabel("time placed at bank");
		$this->fields->premium_sett->setLabel("Premium in settlement currency");
		$this->fields->premium_sett_netto->setLabel("Netto premium in settlement currency");

		$this->setRequiredField("rational");

		$this->fields->rational->setWidth(500);
		$this->fields->comment->setWidth(500);

/*		$this->build("p4a_field", "loguser")->setLabel($p4a->menu->items->loguser->getLabel()); */

		$this->fields->creation_time->enable(false);

		// set default values
		$this->trades->fields->valid_until->setDefaultValue(date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"))));
/*		$this->fields->internal_person_id
			->setLabel("TP Person"); */

		// Search Fieldset
		$this->build("p4a_field", "txt_search")
			->setLabel("Security name")
			->implement("onreturnpress", $this, "search");
		$this->build("p4a_button", "cmd_search")
			->setLabel("Go")
			->implement("onclick", $this, "search");
		$this->build("p4a_fieldset", "fs_search")
			->setLabel("Search")
			->anchor($this->txt_search)
			->anchorLeft($this->cmd_search);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($this->trades)
			->setVisibleCols(array("trade_date","portfolio","trade_type","size","security_name","ISIN","price","last","status","checked")) 
			->setWidth(1200)
			->showNavigationBar();
    
		$this->build("p4a_table", "table_log")
			->setSource($this->log_trades)
			->setWidth(500)
			->setVisibleCols(array("log_time","user_name","field_name","old_value","new_value"))
			->showNavigationBar(); 
		$this->log_trades->addFilter("row_id = ?", $this->trades->fields->trade_id); 

		$this->build("p4a_table", "table_trade_payments")
			->setSource($this->trade_payments)
			->setWidth(500)
			->setVisibleCols(array("amount","type"))
			->showNavigationBar(); 
		$this->trade_payments->addFilter("trade_id = ?", $this->trades->fields->trade_id); 

		$this->build("p4a_fieldset", "fs_details") /* simular in open today, so please copy updates */
			->setLabel("Trade detail") 
			->anchor($this->fields->portfolio_id)   /* preselect based on account */
/*			->anchorLeft($this->fields->account_id) */  /* maybe not needed because already defined over portfolio id */
			->anchor($this->fields->trade_date)
/*			->anchorLeft($this->fields->date_placed) */ /* removed not to confuse; maybe show later as read only with a details button */
			->anchor($this->fields->valid_until)
/*			->anchorLeft($this->fields->date_client) switched off not the confuse; is only fiiled by bank Baer*/
			->anchor($this->fields->confirmation_time) 
			->anchorLeft($this->fields->trade_confirmation_type_id)
			->anchorLeft($this->fields->trade_confirmation_person)
			->anchor($this->fields->internal_person_id)
			->anchorLeft($this->fields->contact_type_id)
			->anchor($this->fields->security_id)
			->anchor($this->fields->currency_id)
			->anchorLeft($this->fields->fx_rate)
			->anchorLeft($this->fields->settlement_currency_id)
			->anchor($this->fields->trade_type_id)
			->anchorLeft($this->fields->trade_status_id)
			->anchor($this->fields->size)
			->anchorLeft($this->fields->price)
			->anchorLeft($this->fields->premium)
			->anchorLeft($this->fields->premium_sett)
			->anchor($this->fields->fees)
			->anchorLeft($this->fields->fees_internal)
			->anchorLeft($this->fields->fee_text)
			->anchor($this->fields->fees_bank)
			->anchorLeft($this->fields->fees_extern)
			->anchorLeft($this->fields->premium_netto)
			->anchorLeft($this->fields->premium_sett_netto)
			->anchor($this->fields->rational)
			->anchor($this->fields->settlement_date)
			->anchorLeft($this->fields->creation_time)
			->anchor($this->fields->bank_ref_id)
			->anchorLeft($this->fields->counterparty_ref_id)
			->anchor($this->fields->bank_text_ins)
			->anchorLeft($this->fields->bank_text_cash)
			->anchor($this->fields->checked)
			->anchorLeft($this->fields->related_trade_id)
			->anchor($this->fields->scanned_bank_confirmation)
			//->anchor($this->fields->bo_status)
			->anchor($this->fields->comment);
/*
			$this->table
				->addActionCol('copy');
			$this->table->cols->copy
				->setWidth(50)			// set column width to 50
				->setLabel('copy')		// set column content 
*/				
		$this->frame
			->anchor($this->fs_search) 
			->anchor($this->table)
 			->anchor($this->fs_details)
 			->anchorLeft($this->table_trade_payments)
 			->anchorLeft($this->table_log);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->rational);
	}
	public function search()
	{
		$value = $this->txt_search->getSQLNewValue();
		if ($value == '') {
			$this->trades->setWhere(null);
			$this->trades->firstRow();
		} else {	
			$this->trades
				->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('securities.ISIN', "%{$value}%"))
				->firstRow();

			if (!$this->trades->getNumRows()) {
				$this->trades
					->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('securities.name', "%{$value}%"))
					->firstRow();

				if (!$this->trades->getNumRows()) {
					$this->warning("No results were found");
					$this->trades->setWhere(null);
					$this->trades->firstRow();
				}
			}
		}
	} 	

	function saveRow()
	{
/*
		// calc the new portfolio including the trade
		$sql_sec_value = "SELECT pos.pos_value_ref FROM v_portfolio_pos pos WHERE pos.security_id = ".$this->fields->security_id->getNewValue()." AND pos.portfolio_id = ".$this->fields->portfolio_id->getNewValue().";";
		$sql_result = mysql_query($query) or die('Query failed: ' . mysql_error() . ', when executing the query ' . $query . '.');
		$sql_array = mysql_fetch_array($sql_result, MYSQL_NUM);
		if (is_null($sql_array['pos_value_ref'])) {
		  $sec_value = 0;
		} else {  
		  $sec_value = $sql_array['pos_value_ref'];
		}
		
		// reset the portfolio value
		if ($sec_value <> 0) {
		    $sql_update = "UPDATE portfolio_security_fixings SET fixed_price = '".$sec_value."' WHERE portfolio_id = ".$this->fields->portfolio_id->getNewValue()." AND security_id = ".$this->fields->security_id->getNewValue().";";
		    mysql_query($sql_update);
		} */
		
		
		// save the new value
		$this->fields->bo_status->setNewValue(1);
		//$local_log_user_mask = $p4a->menu->items->loguser->getLabel();
/*		if(isset($_POST['param4']) && $_POST['param4']=='copy') { 
		  $feilds = array('copy'=>'yes');	
		} else {
		  $feilds = array();	
		} 

		parent::saveRow($feilds); 
*/
		parent::saveRow(); 

	}
	
   	
}
?>
