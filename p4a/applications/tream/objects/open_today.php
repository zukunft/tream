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
class Open_today extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "events")
			->setTable("events")
			->addOrder("event_date")
			->setWhere("events.event_status_id < 3") 
			->load();

		$this->build("p4a_db_source", "trades")
			->setTable("trades")
			->addOrder("creation_time")
			->addJoin("accounts", "trades.account_id = accounts.account_id",
					  array('account_name'=>'account'))
			->addJoin("securities", "trades.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addJoin("trade_types", "trades.trade_type_id  = trade_types.trade_type_id",
					  array('description'=>'trade_type'))
			->setWhere("trades.trade_status_id <> 4 and trades.trade_status_id <> 5")
			->load();

		$this->build("p4a_db_source", "derivate")
			->setTable("v_portfolio_pos_derivate")
			->addOrder("open_value")
			->load();

		$this->setSource($this->trades);
		$this->firstRow();

		// Customizing fields properties
		$this->fields->account_id
			->setLabel("Account")
			->setType("select")
			->setSource(P4A::singleton()->accounts)
			->setSourceDescriptionField("account_name"); 

		$this->fields->portfolio_id
			->setLabel("Portfolio")
			->setType("select")
			->setSource(P4A::singleton()->portfolios)
			->setSourceDescriptionField("portfolio_name"); 

		$this->fields->internal_person_id
			->setLabel("TP Person")
			->setType("select")
			->setSource(P4A::singleton()->internal_persons)
			->setSourceDescriptionField("lastname");

		$this->fields->currency_id
			->setLabel("Currency")
			->setType("select")
			->setSource(P4A::singleton()->currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->security_id
			->setLabel("Security")
			->setType("select")
			->setSource(P4A::singleton()->securities)
			->setSourceDescriptionField("name");

		$this->fields->trade_type_id
			->setLabel("Trade type")
			->setType("select")
			->setSource(P4A::singleton()->trade_types)
			->setSourceDescriptionField("description"); 

		$this->fields->trade_status_id
			->setLabel("Trade status")
			->setType("select")
			->setSource(P4A::singleton()->trade_stati)
			->setSourceDescriptionField("status_text"); 

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->build("p4a_table", "event_table")
			->setLabel("Open events")
			->setSource($this->events)
			->setVisibleCols(array("event_date","description","solution1_description","solution2_description"))
			->setWidth(1200)
			->showNavigationBar();

		$this->build("p4a_table", "table")
			->setLabel("Open trades")
			->setSource($this->trades)
			->setVisibleCols(array("trade_date","account","trade_type","size","security","price"))
			->setWidth(1200)
			->showNavigationBar();

		$this->build("p4a_table", "derivate_table")
			->setLabel("Open derivatives")
			->setSource($this->derivate)
			->setVisibleCols(array("security_name","open_value","bid","ask","last","pos_value"))
			->setWidth(1200)
			->showNavigationBar();

		
		$this->frame
			->anchor($this->event_table)
			->anchor($this->table)
			->anchor($this->derivate_table);
 		
 		/* add an account selector */
 		/* add an bank selector */

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->trade_status_id);
	}
}
