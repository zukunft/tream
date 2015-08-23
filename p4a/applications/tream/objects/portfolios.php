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
 *
 * @author Timon Zielonka <timon@zukunft.com>
 * @copyright Copyright (c) 2013-2015 zukunft.com AG, Zurich

*/
class Portfolios extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "portfolios")
			->setTable("portfolios")
			->addOrder("portfolio_name")
			->addJoinLeft("currencies", "portfolios.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addJoinLeft("banks", "portfolios.bank_id = banks.bank_id",
					  array('bank_name'=>'bank'))
			->load();

		$this->build("p4a_db_source", "trades")
			->setTable("trades")
			->addOrder("creation_time")
			->addJoin("accounts", "trades.account_id = accounts.account_id",
					  array('account_name'=>'account'))
			->addJoinLeft("persons", "trades.internal_person_id  = persons.person_id",
					  array('lastname'=>'client'))
			->addJoinLeft("securities", "trades.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addJoinLeft("trade_types", "trades.trade_type_id  = trade_types.trade_type_id",
					  array('description'=>'trade_type'))
			->addJoinLeft("currencies", "trades.currency_id  = currencies.currency_id",
					  array('symbol'=>'curr'))
			->setPageLimit(20)
			->load();

		$this->build("p4a_db_source", "portfolio_pos")
			->setTable("v_portfolio_allocation")
			->addOrder("portfolio_id")
			->setPageLimit(20)
			->load();

		$this->build("p4a_db_source", "portfolio_pos_closed")
			->setTable("v_portfolio_pos_closed")
			->addOrder("portfolio_id")
			->setPageLimit(5)
			->load();

		$this->build("p4a_db_source", "portfolio_pnl")
			->setTable("v_portfolio_pnl")
			->addOrder("portfolio_id")
			->load();

		$this->build("p4a_db_source", "exposure_target_values")
			->setTable("v_exposure_target_values")
			->addOrder("portfolio_id")
			->load();

		$this->setSource($this->portfolios);
		$this->firstRow();

		// Customizing fields properties
		$this->fields->account_id
			->setLabel("Account")
			->setType("select")
			->setSource(P4A::singleton()->select_accounts)
			->setSourceDescriptionField("account_select_name");

		$this->fields->currency_id
			->setLabel("Ref. curr")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->bank_id
			->setLabel("Bank")
			->setType("select")
			->setSource(P4A::singleton()->select_banks)
			->setSourceDescriptionField("bank_name");

		$this->fields->portfolio_type_id
			->setLabel("Type")
			->setType("select")
			->setSource(P4A::singleton()->select_portfolio_types)
			->setSourceDescriptionField("type_name");

		$this->fields->is_part_of
			->setLabel("Part of")
			->setType("select")
			->setSource(P4A::singleton()->select_portfolios)
			->setSourceDescriptionField("portfolio_select_name"); 

		$this->fields->currency_id->setTooltip("for cash portfolios that are part of a main portfolio, this field is the account currency");
		$this->fields->monitoring_security_limit->setTooltip("if one security of this portfolio moves more than x percent a message is send to the portfolio manager");

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($this->portfolios)
			->setWidth(500)
			->setVisibleCols(array("portfolio_name","fx","bank"))
			->showNavigationBar();

		$this->build("p4a_table", "table_trades")
			->setSource($this->trades)
			->setWidth(600)
			->setVisibleCols(array("trade_date","trade_type","size","security","price","curr")) 
			->showNavigationBar();
		$this->trades->addFilter("portfolio_id = ?", $this->portfolios->fields->portfolio_id); 

		$this->build("p4a_table", "table_portfolio_pos")
			->setSource($this->portfolio_pos)
			->setWidth(900)
			->setVisibleCols(array("asset_class","security_name","position","trade_curr","buy_price","sec_curr","bid","ask","last","pos_value_ref","pnl_ref","pnl_pct","pnl_market","pnl_fx","aum_pct"))
			->showNavigationBar();
		$this->portfolio_pos->addFilter("portfolio_id = ?", $this->portfolios->fields->portfolio_id); 

		$this->build("p4a_table", "table_portfolio_pos_closed")
			->setSource($this->portfolio_pos_closed)
			->setWidth(600)
			->setVisibleCols(array("security_name","trade_curr","avg_buy","avg_sell","pnl_total"))
			->showNavigationBar();
		$this->portfolio_pos_closed->addFilter("portfolio_id = ?", $this->portfolios->fields->portfolio_id); 

		$this->build("p4a_table", "table_portfolio_pnl")
			->setSource($this->portfolio_pnl)
			->setWidth(600)
			->setVisibleCols(array("update_time","aum","pnl"))
			->showNavigationBar();
		$this->portfolio_pnl->addFilter("portfolio_id = ?", $this->portfolios->fields->portfolio_id); 

		$this->build("p4a_table", "table_target_values")
			->setSource($this->exposure_target_values)
			->setWidth(600)
			->setVisibleCols(array("item_name","neutral","calc_value","diff_neutral")) 
			->showNavigationBar();
		$this->exposure_target_values->addFilter("portfolio_id = ?", $this->portfolios->fields->portfolio_id);  

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Account detail")
			->anchor($this->fields->account_id)
			->anchor($this->fields->portfolio_name)
			->anchor($this->fields->portfolio_type_id)
			->anchor($this->fields->is_part_of)
			->anchor($this->fields->currency_id)
			->anchor($this->fields->bank_id)
			->anchor($this->fields->bank_portfolio_id)
			->anchor($this->fields->monitoring)
			->anchorLeft($this->fields->monitoring_security_limit)
			->anchor($this->fields->inactive)
			->anchor($this->fields->confirm_to_bank)
			->anchorLeft($this->fields->confirm_to_client);
		
		$this->frame
			->anchor($this->table)
 			->anchorLeft($this->fs_details)
 			->anchorLeft($this->table_portfolio_pnl)
 			->anchor($this->table_trades)
 			->anchorLeft($this->table_portfolio_pos)
 			->anchor($this->table_portfolio_pos_closed)
 			->anchorLeft($this->table_target_values); 

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->person_id);
	}
}
