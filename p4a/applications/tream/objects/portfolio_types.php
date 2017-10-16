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
class Portfolio_types extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "exposure_target_list")
			->setTable("exposure_targets")
			->addJoinLeft("exposure_items", "exposure_targets.exposure_item_id  = exposure_items.exposure_item_id",
					  array('description'=>'exposure'))
			->addJoinLeft("account_mandates", "exposure_targets.account_mandat_id  = account_mandates.account_mandat_id",
					  array('description'=>'mandat','portfolio_type_id'=>'portfolio_type_id'))
			->addJoinLeft("currencies", "exposure_targets.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addOrder("account_mandat_id")
			->load();

		$this->setSource($p4a->portfolio_types);
		$this->setTitle("Risk profile");
		$this->firstRow();

		$this->fields->type_name->setLabel("Profile name");
		$this->fields->level->setTooltip("to enable the system to detect higher risks in a lower level");
		$this->fields->code_id->disable();
		$this->fields->comment->setWidth(400);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($p4a->portfolio_types)
			->setVisibleCols(array("type_name","level","comment"))
			->setWidth(700)
			->showNavigationBar();

		$this->build("p4a_table", "table_targets")
			->setSource($this->exposure_target_list)
			->setWidth(700)
			->setVisibleCols(array("mandat","fx","exposure","target","limit_up","limit_down","comment")) 
			->showNavigationBar(); 
		$this->exposure_target_list->addFilter("portfolio_type_id = ?", $p4a->portfolio_types->fields->portfolio_type_id); 

		$this->setRequiredField("type_name");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Risk profile details")
			->anchor($this->fields->type_name)
			->anchor($this->fields->level)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details)
 			->anchor($this->table_targets);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->type_name);
	}
}
