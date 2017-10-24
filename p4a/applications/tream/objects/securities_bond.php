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
class Securities_bond extends P4A_Base_Mask
{
	public $fs_search = null;
	public $txt_search = null;
	public $cmd_search = null;
	
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setTitle("Bonds");
		
		$this->build("p4a_db_source", "securities")
			->setTable("securities")
			->addOrder("name")
			->addJoinLeft("currencies", "securities.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addJoinLeft("v_security_types", "securities.security_type_id  = v_security_types.security_type_id",
					  array('description'=>'type'))
			->addJoinLeft("security_quote_types", "securities.security_quote_type_id  = security_quote_types.security_quote_type_id",
					  array('type_name'=>'quote_type')) 
			->addJoinLeft("security_issuers", "securities.security_issuer_id  = security_issuers.security_issuer_id",
					  array('issuer_name'=>'issuer')) 
			->setWhere("v_security_types.code_id = 'bond'") 
			->setPageLimit(30)
			->load();

		$this->build("p4a_db_source", "trades")
			->setTable("trades")
			->addOrder("creation_time")
			->addJoin("accounts", "trades.account_id = accounts.account_id",
					  array('account_name'=>'account'))
			->addJoin("persons", "trades.internal_person_id  = persons.person_id",
					  array('lastname'=>'client'))
			->addJoin("trade_types", "trades.trade_type_id  = trade_types.trade_type_id",
					  array('description'=>'trade_type'))
			->load();

		$this->build("p4a_db_source", "v_portfolio_pos_named")
			->setTable("v_portfolio_pos_named")
			->addOrder("portfolio")
			->load();

		// data sources to set the default values
		$this->build("p4a_db_source", "type_data")
			->setTable("security_types")
			->load();

		$this->setSource($this->securities);
		$this->firstRow();

		$this->fields->currency_id
			->setLabel("Currency")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->security_type_id
			->setLabel("Security type")
			->setTooltip("to define the trade mask used")
			->setType("select")
			->setSource(P4A::singleton()->select_security_types)
			->setSourceDescriptionField("description");

		$this->fields->security_exchange_id
			->setLabel("Main exchange")
			->setType("select")
			->setSource(P4A::singleton()->select_security_exchanges)
			->setSourceDescriptionField("exchange_name");

		$this->fields->exposure_item_asset_class_id
			->setLabel("Asset Class")
			->setTooltip("to ancher the security in the asset allocation tree. If the security cannot be linked to one asset class, use Security exposures")
			->setType("select")
			->setSource(P4A::singleton()->select_asset_class)
			->setSourceDescriptionField("description");

		$this->fields->price_feed_type_id
			->setLabel("Price feed")
			->setType("select")
			->setSource(P4A::singleton()->select_security_price_feed_types)
			->setSourceDescriptionField("type_name");

		$this->fields->security_quote_type_id
			->setLabel("Quote type")
			->setType("select")
			->setSource(P4A::singleton()->select_security_quote_types)
			->setSourceDescriptionField("type_name"); 

		$this->fields->security_exposure_status_id
			->setLabel("Exposure parameters")
			->setType("select")
			->setSource(P4A::singleton()->security_exposure_stati_select)
			->setSourceDescriptionField("status_text");

		$this->fields->security_issuer_id
			->setLabel("Issuer")
			->setType("select")
			->setSource(P4A::singleton()->security_issuer_select)
			->setSourceDescriptionField("issuer_name");

		$this->fields->start_date->setLabel("Issue date");
		$this->fields->end_date->setLabel("Maturity");
		
		$this->fields->update_time->disable();
		$this->fields->hist_update_time->disable();

		// Search Fieldset
		$this->build("p4a_field", "txt_search")
			->setLabel("Issuer or ISIN")
			->implement("onreturnpress", $this, "search");
		$this->build("p4a_field", "yield_min")
			->setLabel("Yield from")
			->implement("onreturnpress", $this, "search");
		$this->build("p4a_field", "yield_max")
			->setLabel("to")
			->implement("onreturnpress", $this, "search");
		$this->build("p4a_button", "cmd_search")
			->setLabel("Go")
			->implement("onclick", $this, "search");
		$this->build("p4a_fieldset", "fs_search")
			->setLabel("Search")
			->anchor($this->txt_search)
			->anchorLeft($this->yield_min)
			->anchorLeft($this->yield_max)
			->anchorLeft($this->cmd_search);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($this->securities)
			->setVisibleCols(array("issuer","yield","end_date","ISIN","valor","symbol_yahoo","fx","last_price","update_time"))
			->setWidth(900)
			->showNavigationBar();

		$this->build("p4a_table", "table_trades")
			->setSource($this->trades)
			->setWidth(600)
			->setVisibleCols(array("trade_date","account","trade_type","size","price")) 
			->showNavigationBar();
		$this->trades->addFilter("security_id = ?", $this->securities->fields->security_id);  

		$this->build("p4a_table", "table_pos")
			->setSource($this->v_portfolio_pos_named)
			->setWidth(600)
			->setVisibleCols(array("portfolio","position","buy_price","trade_curr")) 
			->showNavigationBar();
		$this->v_portfolio_pos_named->addFilter("security_id = ?", $this->securities->fields->security_id);  

		$this->setRequiredField("name");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Security detail")
			->anchor($this->fields->security_issuer_id)
			->anchor($this->fields->currency_id)
			->anchor($this->fields->yield)
			->anchor($this->fields->start_date)
			->anchor($this->fields->end_date)
			->anchor($this->fields->name)
			->anchor($this->fields->ISIN)
			->anchor($this->fields->valor)
			//->anchor($this->fields->security_type_id)
			->anchor($this->fields->exposure_item_asset_class_id)
			->anchor($this->fields->symbol_wikidata)
			->anchor($this->fields->symbol_bloomberg)
			->anchor($this->fields->symbol_reuters)
			->anchor($this->fields->symbol_market_map)
			->anchor($this->fields->symbol_yahoo)
			->anchor($this->fields->bid)
			->anchor($this->fields->ask)
			->anchor($this->fields->last_price)
			->anchor($this->fields->update_time)
			->anchor($this->fields->hist_update_time)
			->anchor($this->fields->security_quote_type_id)
			->anchor($this->fields->price_feed_type_id)
			->anchor($this->fields->security_exposure_status_id)
			->anchor($this->fields->monitoring_security_limit)
			->anchor($this->fields->archiv);
			
		
		$this->build("p4a_button", "btn_update_yahoo") 
			->setLabel("Yahoo update") 
			->implement("onclick", $this,"_btn_update_yahoo");

		$this->build("p4a_label", "yahoo_result"," ");

		$this->frame
			->anchor($this->fs_search)
			->anchorLeft($this->btn_update_yahoo)
			->anchorLeft($this->yahoo_result)
			->anchor($this->table)
 			->anchorLeft($this->fs_details)
 			->anchor($this->table_trades)
 			->anchorLeft($this->table_pos); 

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->name);
	}

	public function search()
	{
		$value = $this->txt_search->getSQLNewValue();
		$this->securities
			->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('ISIN', "%{$value}%"))
			->firstRow();

		if (!$this->securities->getNumRows()) {
			$this->securities
				->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('name', "%{$value}%"))
				->firstRow();

			if (!$this->securities->getNumRows()) {
				$this->warning("No results were found");
				$this->securities
					->setWhere(null)
					->firstRow();
			}
		}
	} 	

	function saveRow()
	{
		// set the security type values
		$this->type_data
			->setWhere("code_id = 'bond'")
			->firstRow();
		$type_id = $this->fields->security_type_id->getNewValue();
		if ($type_id < 1) {
			$type_id = $this->type_data->fields->security_type_id->getValue();
			$this->fields->security_type_id->setNewValue($type_id);
		} 
		$quote_type_id = $this->fields->security_quote_type_id->getNewValue();
		if ($quote_type_id < 1) {
			$quote_type_id = $this->type_data->fields->security_quote_type_id->getValue();
			$this->fields->security_quote_type_id->setNewValue($quote_type_id);
		} 
		
		parent::saveRow(); 
	}
	
	public function _btn_update_yahoo()
	{
		// update prices
		//$this->yahoo_result->setLabel(http_parse_message(http_get("https://tream.biz/batch/tream_get_security_from_yahoo.php"))->body); 
		$sec_id = $this->securities->fields->security_id->getValue();
		if ($sec_id > 0) {
                        $yahoo_result = file_get_contents("https://tream.biz/batch/tream_get_security_from_yahoo.php?id=".$sec_id);
		} else {
                        $yahoo_result = file_get_contents("https://tream.biz/batch/tream_get_security_from_yahoo.php");
		}
		$this->yahoo_result
                        ->setWidth(600)
                        ->setLabel($yahoo_result); 
	}

}
