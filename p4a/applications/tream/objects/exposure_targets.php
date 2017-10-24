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
class Exposure_targets extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "exposure_targets")
			->setTable("exposure_targets")
			->addJoinLeft("exposure_items", "exposure_targets.exposure_item_id  = exposure_items.exposure_item_id",
					  array('description'=>'exposure'))
			->addJoinLeft("account_mandates", "exposure_targets.account_mandat_id  = account_mandates.account_mandat_id",
					  array('description'=>'mandat'))
			->addJoinLeft("currencies", "exposure_targets.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addOrder("account_mandat_id")
			->load();

		$this->build("p4a_db_source", "log_exposure_targets")
			->setTable("log_data")
			->addOrder("log_time")
			->setWhere("log_data.table_name = 'exposure_targets'")
			->load();

		$this->build("p4a_db_source", "exposure_target_values")
			->setTable("v_exposure_target_values")
			->addOrder("portfolio_id")
			->load();

		$this->setSource($this->exposure_targets);
		$this->firstRow();

		// Customizing fields properties
		$this->fields->account_mandat_id
			->setLabel("Mandat")
			->setType("select")
			->setSource(P4A::singleton()->select_account_mandates)
			->setSourceDescriptionField("description");

		$this->fields->exposure_item_id
			->setLabel("Exposure")
			->setType("select")
			->setSource(P4A::singleton()->select_exposure_items)
			->setSourceDescriptionField("description");

		$this->fields->currency_id
			->setLabel("Ref. currency")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->target->setLabel("Neutral");

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($this->exposure_targets)
			->setVisibleCols(array("mandat","fx","exposure","target","limit_up","limit_down","comment")) 
			->setWidth(500)
			->showNavigationBar();

		//$this->setRequiredField("description");

		$this->build("p4a_table", "table_target_values")
			->setSource($this->exposure_target_values)
			->setWidth(500)
			->setVisibleCols(array("portfolio_name","calc_value","optimized","diff","neutral","exception","diff_neutral")) 
			->showNavigationBar(); 
		$this->exposure_target_values->addFilter("exposure_target_id = ?", $this->exposure_targets->fields->exposure_target_id); 

		$this->build("p4a_table", "table_log")
			->setSource($this->log_exposure_targets)
			->setWidth(500)
			->setVisibleCols(array("log_time","user_name","field_name","old_value","new_value"))
			->showNavigationBar(); 
		$this->log_exposure_targets->addFilter("row_id = ?", $this->exposure_targets->fields->exposure_target_id); 

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Exposure target detail")
			->anchor($this->fields->account_mandat_id)
			->anchor($this->fields->currency_id)
			->anchor($this->fields->exposure_item_id)
			->anchor($this->fields->target)
			->anchor($this->fields->limit_up)
			->anchor($this->fields->limit_down)
			->anchor($this->fields->optimized)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details)
 			->anchorLeft($this->table_target_values)
 			->anchorLeft($this->table_log);


		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->exposure_item_id);
	}
}
