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
 * @copyright Copyright (c) 2013-2017 zukunft.com AG, Zurich

*/
class Events extends P4A_Base_Mask
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
			->addOrder("event_date","DESC")
			->addJoinLeft("accounts", "events.account_id = accounts.account_id",
					  array('account_name'=>'account'))
			->addJoinLeft("event_types", "events.event_type_id  = event_types.event_type_id",
					  array('type_name'=>'event_type'))
			->addJoinLeft("event_stati", "events.event_status_id  = event_stati.event_status_id",
					  array('status_text'=>'status'))
			->setWhere("events.event_status_id < 3 or events.event_status_id IS NULL") 
			->setPageLimit(16)
			->load();

		$this->build("p4a_db_source", "log_events")
			->setTable("log_data")
			->addOrder("log_time")
			->setWhere("log_data.table_name = 'events'")
			->setPageLimit(6)
			->load();

		$this->setSource($this->events);
		$this->firstRow();

		// Customizing fields properties
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

		$this->fields->event_type_id
			->setLabel("Event type")
			->setType("select")
			->setSource(P4A::singleton()->select_event_types)
			->setSourceDescriptionField("type_name"); 

		$this->fields->event_status_id
			->setLabel("Event status")
			->setType("select")
			->setSource(P4A::singleton()->select_event_stati)
			->setSourceDescriptionField("status_text"); 

		$solutions_values = array(); 
		$solutions_values[] = array("id" => "0", "desc" => " do nothing");
		$solutions_values[] = array("id" => "1", "desc" => "used soultion 1");
		$solutions_values[] = array("id" => "2", "desc" => "used soultion 2");
		$this->build("p4a_array_source", "solution_selector_source")
			->load($solutions_values)
			->setPk("id"); 
		$this->fields->solution_selected
			->setLabel("Select a solution")
			->setType("select")
			->setSource($this->solution_selector_source)
			->setSourceDescriptionField("desc"); 



		$this->fields->description->setWidth(600);
		$this->fields->solution1_description
			->setWidth(600)
			->setLabel("Solution 1");
		$this->fields->solution1_sql->setWidth(600);
		$this->fields->solution2_description
			->setWidth(600)
			->setLabel("Solution 2");
		$this->fields->solution2_sql->setWidth(600);
		$this->fields->comment->setWidth(600);

		$this->fields->created->enable(false);
		$this->fields->solution1_sql->enable(false);
		$this->fields->solution2_sql->enable(false);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->build("p4a_table", "table")
			->setSource($this->events)
			->setVisibleCols(array("event_date","description","solution1_description","solution2_description","comment","solution_selected"))
			->setWidth(1200)
			->showNavigationBar();

		$this->build("p4a_table", "table_log")
			->setSource($this->log_events)
			->setWidth(500)
			->setVisibleCols(array("log_time","user_name","field_name","old_value","new_value"))
			->showNavigationBar(); 
		$this->log_events->addFilter("row_id = ?", $this->events->fields->event_id); 

		$this->build("p4a_fieldset", "fs_details") /* simular in open today, so please copy updates */
			->setLabel("Event detail")
			->anchor($this->fields->account_id)   /* maybe not needed because already defined over portfolio id */
			->anchor($this->fields->portfolio_id)   /* preselect based on account */
			->anchor($this->fields->event_date)
			->anchor($this->fields->description)
			->anchor($this->fields->event_type_id)
			->anchor($this->fields->event_status_id)

			->anchor($this->fields->created)
			->anchor($this->fields->updated)
			->anchor($this->fields->closed)
			->anchor($this->fields->solution1_description)
			->anchor($this->fields->solution1_sql)
			->anchor($this->fields->solution2_description)
			->anchor($this->fields->solution2_sql)
			->anchor($this->fields->solution_selected)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details)
 			->anchorLeft($this->table_log);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->solution_selected);
	}
/*
public function search()
	{
		$value = $this->txt_search->getSQLNewValue();
		$this->events
			->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('securities.ISIN', "%{$value}%"))
			->firstRow();

		if (!$this->events->getNumRows()) {
			$this->events
				->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('securities.name', "%{$value}%"))
				->firstRow();

			if (!$this->events->getNumRows()) {
				$this->warning("No results were found");
				$this->events->setWhere(null);
				$this->events->firstRow();
			}
		}
	} 	
*/
}
