<?php
/**
 * This file is part of P4A - PHP For Applications.
 *
 * P4A is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 * 
 */

/**
 * @author Timon Zielonka <timon@zukunft.com>
 * @copyright Copyright (c) 2010-2013 Timon Zielonka 
 */
class Trade_cash extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

/*		if ($_SESSION['portfolio_id'] > 0) {
			$this->setTitle("Cash in- and outflows (for ".$_SESSION['portfolio_id'].")");
		} else { */
			$this->setTitle("Cash in- and outflows");
/* 		} */

		$this->build("p4a_db_source", "trades")
			->setTable("trades")
			->addOrder("creation_time")
			->addJoinLeft("portfolios", "trades.portfolio_id = portfolios.portfolio_id",
					  array('portfolio_name'=>'portfolio'))
			->addJoinLeft("currencies", "trades.currency_id  = currencies.currency_id",
					  array('symbol'=>'ccy'))
			->addJoinLeft("v_securities", "trades.security_id  = v_securities.security_id",
					  array('name'=>'security','last_price'=>'last','type_code_id'=>'sec_type_id'))
			->addJoinLeft("trade_types", "trades.trade_type_id  = trade_types.trade_type_id",
					  array('description'=>'trade_type'))
			->addJoinLeft("trade_stati", "trades.trade_status_id  = trade_stati.trade_status_id",
					  array('status_text'=>'status'))
			->setPageLimit(20)
			->load();

/*		if ($_SESSION['portfolio_id'] > 0) {
			$this->trades->setWhere("trades.security_id is Not Null AND v_securities.type_code_id = 'cash' AND trades.portfolio_id = ".$_SESSION['portfolio_id']);
		} else { */
			$this->trades->setWhere("trades.security_id is Not Null AND v_securities.type_code_id = 'cash'");
/* 		} */

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
			->setTable("v_securities")
			->load();

		$this->build("p4a_db_source", "portfolio_data")
			->setTable("portfolios")
			->load();

		$this->build("p4a_db_source", "status_data")
			->setTable("trade_stati")
			->load();

		$this->setSource($this->trades);
		$this->firstRow();

		// Customizing fields properties
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

		$this->fields->trade_type_id
			->setLabel("Trade type")
			->setType("select")
			->setSource(P4A::singleton()->select_trade_types_cash)
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

		$this->fields->trade_confirmation_person->setLabel("Bank Contact");
		$this->fields->confirmation_time->setLabel("time placed at bank");

		$this->fields->comment->setWidth(500);

		$this->fields->creation_time->enable(false);

		$this->build("p4a_Label", "info_client")->setLabel("Client contact");
		$this->build("p4a_Label", "info_optional")->setLabel("Optional parameter");
		$this->build("p4a_Label", "info_process")->setLabel("Trade processing");

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
			->setVisibleCols(array("trade_date","portfolio","trade_type","ccy","size","status","checked"))
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
			->anchorLeft($this->fields->currency_id)
			->anchorLeft($this->fields->size)

			/* client communication */
			->anchor($this->info_client)
			->anchor($this->fields->contact_type_id)
			->anchorLeft($this->fields->internal_person_id) /* automatically filed */
			->anchor($this->fields->comment)

			/* additional parameters for the trade with automatically set fields that can be overwritten*/
			->anchor($this->info_optional)
			->anchor($this->fields->trade_date)
			->anchorLeft($this->fields->fees_internal)

			/* fields to track the processing */
			->anchor($this->info_process)
			->anchor($this->fields->trade_status_id)
			->anchor($this->fields->confirmation_time) 
			->anchorLeft($this->fields->trade_confirmation_type_id)
			->anchorLeft($this->fields->trade_confirmation_person)
			//->anchorLeft($this->fields->settlement_date)
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
			$this->trades->setWhere('trades.security_id is Null AND trades.settlement_currency_id is Null');
			$this->trades->firstRow();
		} else {	
			$this->trades
				->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('currencies.symbol', "%{$value}%"))
				->firstRow();

			if (!$this->trades->getNumRows()) {
				$this->warning("No results were found");
				$this->trades->setWhere('trades.security_id is Null AND trades.settlement_currency_id is Null');
				$this->trades->firstRow();
			}
		}
	} 	

	function saveRow()
	{
		// set the internal person
		$pers_id = $this->fields->internal_person_id->getNewValue();
		if ($pers_id < 1) {
			$this->user_data
				->setWhere("username = '".$_SESSION['log_user']."'")
				->firstRow();
			$pers_id = $this->user_data->fields->internal_person_id->getValue();
			$this->fields->internal_person_id->setNewValue($pers_id);
		} 
		// set the portfolio related values
		$portfolio_id = $this->fields->portfolio_id->getNewValue();
		if ($portfolio_id < 1 AND $_SESSION['portfolio_id'] > 0) {
			$portfolio_id = $_SESSION['portfolio_id'];
			$this->fields->portfolio_id->setNewValue($portfolio_id);
		} 
		if ($portfolio_id < 1) {
			$p4a->messageError("Portfolio missing.");
		} 
		// set the settlement currency based on the portfolio selected if not yet set
		$curr_id = $this->fields->currency_id->getNewValue();
		if ($curr_id < 1) {
			$this->portfolio_data
				->setWhere("portfolio_id = ".$portfolio_id)
				->firstRow(); 
			$curr_id = $this->portfolio_data->fields->currency_id->getValue();
			$this->fields->currency_id->setNewValue($curr_id);
		} 
		// always set the security based on the currency selected; this is needed to bring all trades in line and set a valid security for all trades
		$this->sec_data
			->setWhere("currency_id = ".$curr_id." AND type_code_id = 'cash'")
			->firstRow();
		$sec_id = $this->sec_data->fields->security_id->getValue();
		$this->fields->security_id->setNewValue($sec_id);
		// the trade price for cash trades is always 1; this setting is needed for faster PnL calculation
		$this->fields->price->setNewValue(1);

		// set the defaut trade date
		$date_trade = $this->fields->trade_date->getNewValue();
		if ($date_trade == "") {
			$date_trade = date('Y-m-d H:i:s');
			$this->fields->trade_date->setNewValue($date_trade);
		} 
		// set the defaut settlement date
		$date_settle = $this->fields->settlement_date->getNewValue();
		if ($date_settle == "") {
			$date_trade = $this->fields->trade_date->getNewValue();
			$date_settle = date('Y-m-d', strtotime(date('Y-m-d',$date_trade) . ' +3 Weekday'));
			$this->fields->settlement_date->setNewValue($date_settle);
		} 

		// set the default trade status if not set by the user
		$status_id = $this->fields->trade_status_id->getNewValue();
		if ($status_id < 1) {
			$this->status_data
				->setWhere("code_id = 'executed'")
				->firstRow();
			$status_id = $this->status_data->fields->trade_status_id->getValue();
			$this->fields->trade_status_id->setNewValue($status_id);
		} 

		// save the new values
		parent::saveRow();
	} 
}
