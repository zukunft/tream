<?php
/* 

This file is part of TREAM - Open Source Portfolio Management Software for External Asset Advisors.

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
 * @link http://tream.biz
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License

*/
class Exposure_items extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "exposure_items")
			->setTable("exposure_items")
			->addOrder("description")
			->addJoinLeft("currencies", "exposure_items.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addJoinLeft("exposure_types", "exposure_items.exposure_type_id  = exposure_types.exposure_type_id",
					  array('type_name'=>'type'))
			->addJoinLeft("v_exposure_item_parts", "exposure_items.is_part_of  = v_exposure_item_parts.exposure_item_id",
					  array('part_of'=>'part_of'))
			->setPageLimit(30)
			->load();

		$this->setSource($this->exposure_items);
		$this->setTitle("Exposure items");
		$this->firstRow();

		// Customizing fields properties
		$this->fields->exposure_type_id
			->setLabel("Type")
			->setType("select")
			->setSource(P4A::singleton()->select_exposure_types)
			->setSourceDescriptionField("type_name");

		$this->fields->currency_id
			->setLabel("Currency")
			->setTooltip("to link the currencies without usage of Security Exposures")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->security_type_id
			->setLabel("Asset class")
			->setTooltip("to link the main security types without usage of Security Exposures")
			->setType("select")
			->setSource(P4A::singleton()->select_security_types)
			->setSourceDescriptionField("description");

		$this->fields->is_part_of
			->setLabel("is part of")
			->setType("select")
			->setSource(P4A::singleton()->exposure_item_select)
			->setSourceDescriptionField("description");

		// Customizing fields properties
		$this->fields->part_weight->setLabel("with weight");
		$this->fields->comment->setWidth(400);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($this->exposure_items)
			->setVisibleCols(array("type","description","part_of","part_weight")) 
			->setWidth(700)
			->showNavigationBar();

		$this->setRequiredField("description");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Exposure type detail")
			->anchor($this->fields->exposure_type_id)
			->anchor($this->fields->description)
			->anchor($this->fields->is_part_of)
			->anchorLeft($this->fields->part_weight)
			->anchor($this->fields->currency_id)
			->anchor($this->fields->security_type_id)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->description);
	}
}
