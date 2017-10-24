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
*/

/** This file is based on P4A - PHP For Applications.
 *
 * To contact the authors write to:                                     
 * Fabrizio Balliano <fabrizio@fabrizioballiano.it>                    
 * Andrea Giardina <andrea.giardina@crealabs.it>
 *
 * https://github.com/fballiano/p4a
 *
 * @author Timon Zielonka <timon@zukunft.com>
 * @copyright Copyright (c) 2013-2017 zukunft.com AG, Zurich
 * @link http://tream.biz
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License

*/
class Trade_fx_swap extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setTitle("FX swap trades");

		$this->build("p4a_db_source", "trades")
			->setTable("trades")
			->addOrder("creation_time")
			->addJoinLeft("portfolios", "trades.portfolio_id = portfolios.portfolio_id",
					  array('portfolio_name'=>'portfolio'))
			->addJoinLeft("v_securities", "trades.security_id  = v_securities.security_id",
					  array('name'=>'security','ISIN'=>'ISIN','last_price'=>'last','type_code_id'=>'sec_type_id'))
			->addJoinLeft("currencies", "trades.currency_id  = currencies.currency_id",
					  array('symbol'=>'ccy'))
			->addJoinLeft("v_currencies_2", "trades.settlement_currency_id  = v_currencies_2.currency_id",
					  array('v_currencies_2.symbol'=>'to_ccy'))
			->addJoinLeft("trade_types", "trades.trade_type_id  = trade_types.trade_type_id",
					  array('description'=>'trade_type','use_fx_swap'=>'use'))
			->addJoinLeft("trade_stati", "trades.trade_status_id  = trade_stati.trade_status_id",
					  array('status_text'=>'status'))
			->setWhere("trades.security_id is Not Null AND v_securities.type_code_id = 'FX_swap'") 
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

		// data sources to calculate the trade values
		$this->build("p4a_db_source", "user_data")
			->setTable("log_users")
			->load();

		$this->build("p4a_db_source", "sec_data")
			->setTable("securities")
			->load();

		$this->build("p4a_db_source", "portfolio_data")
			->setTable("portfolios")
			->load();

		$this->build("p4a_db_source", "fx_data")
			->setTable("v_currency_price_feed")
			->load();

		$this->build("p4a_db_source", "status_data")
			->setTable("trade_stati")
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
			->setLabel("vs curr")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->trade_type_id
			->setLabel("Trade type")
			->setType("select")
			->setSource(P4A::singleton()->select_trade_types_fx_swap)
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
		
		$this->fields->fx_rate->setLabel("short FX rate");
		$this->fields->price->setLabel("long FX rate");
		$this->fields->trade_confirmation_person->setLabel("Bank Contact");
		$this->fields->confirmation_time->setLabel("time placed at bank");
		$this->fields->premium_sett->setLabel("Premium in settlement currency");
		$this->fields->premium_sett_netto->setLabel("Netto premium in settlement currency");

		$this->setRequiredField("rational");

		$this->fields->rational->setWidth(500);
		$this->fields->comment->setWidth(500);

		//$this->build("p4a_field", "loguser")->setLabel($p4a->menu->items->loguser->getLabel());

		$this->fields->creation_time->enable(false);

		$this->build("p4a_Label", "info_client")->setLabel("Client contact");
		$this->build("p4a_Label", "info_optional")->setLabel("Optional parameter");
		$this->build("p4a_Label", "info_process")->setLabel("Trade processing");
		$this->build("p4a_Label", "info_overwrite")->setLabel("Automatic (can overwrite)");

		// set default values
		$this->trades->fields->valid_until->setDefaultValue(date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"))));
/*		$this->fields->internal_person_id
			->setLabel("TP Person"); */

		// Search Fieldset
		$this->build("p4a_field", "txt_search")
			->setLabel("Currency symbol")
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
			->setVisibleCols(array("trade_date","portfolio","trade_type","size","price","ccy","status","checked"))
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
			/* the main fields to enter the trade */
			->anchor($this->fields->portfolio_id)   /* preselect based on menu selection */
			->anchor($this->fields->trade_type_id)
			->anchor($this->fields->currency_id)
			->anchorLeft($this->fields->settlement_currency_id)
			->anchor($this->fields->size)
			->anchor($this->fields->fx_rate)
			->anchorLeft($this->fields->premium)
			->anchor($this->fields->price)
			->anchorLeft($this->fields->premium_sett)
			->anchor($this->fields->rational)

			/* client communication */
			->anchor($this->info_client)
			->anchor($this->fields->contact_type_id)
			->anchorLeft($this->fields->internal_person_id) /* automatically filed */
			->anchor($this->fields->comment)

			/* additional parameters for the trade with automatically set fields that can be overwritten*/
			->anchor($this->info_optional)
			->anchor($this->fields->trade_date)
			->anchor($this->fields->valid_until)
			->anchorLeft($this->fields->fees_internal)

			/* fields to track the processing */
			->anchor($this->info_process)
			->anchor($this->fields->trade_status_id)
			->anchor($this->fields->confirmation_time) 
			->anchorLeft($this->fields->trade_confirmation_type_id)
			->anchorLeft($this->fields->trade_confirmation_person)
			//->anchorLeft($this->fields->settlement_date)

			//->anchor($this->fields->trade_type_id)
			;
		
		$this->frame
			->anchor($this->fs_search)
			->anchor($this->table)
 			->anchor($this->fs_details)
 			->anchorLeft($this->table_trade_payments);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->rational);
	}
	public function search()
	{
		$value = $this->txt_search->getSQLNewValue();
		if ($value == '') {
			$this->trades->setWhere('trade_types.use_fx_swap = 1');
			$this->trades->firstRow();
		} else {	
			$this->trades
				->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('currencies.symbol', "%{$value}%"))
				->firstRow();

			if (!$this->trades->getNumRows()) {
				$this->warning("No results were found");
				$this->trades->setWhere('trade_types.use_fx_swap = 1');
				$this->trades->firstRow();
			}
		}
	} 	

	function saveRow()
	{
		// set the default trade status if not set by the user
		$status_id = $this->fields->trade_status_id->getNewValue();
		if ($status_id < 1) {
			$this->status_data
				->setWhere("code_id = 'executed'")
				->firstRow();
			$status_id = $this->status_data->fields->trade_status_id->getValue();
			$this->fields->trade_status_id->setNewValue($status_id);
		} 

		// save the new value
		$this->fields->bo_status->setNewValue(1);
		//$local_log_user_mask = $p4a->menu->items->loguser->getLabel();
		parent::saveRow();
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
	} 
}
