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
class Security_exposures extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "security_exposures")
			->setTable("security_exposures")
			->addJoinLeft("securities", "security_exposures.security_id  = securities.security_id",
					  array('name'=>'security'))
			->addJoinLeft("v_exposure_items", "security_exposures.exposure_item_id  = v_exposure_items.exposure_item_id",
					  array('description'=>'exposure', 'type_name'=>'type_name'))
			->addOrder("exposure_item_id")
			->load();

		$this->setSource($this->security_exposures);
		$this->firstRow();

		// Customizing fields properties
		$this->fields->exposure_item_id
			->setLabel("Exposure")
			->setType("select")
			->setSource(P4A::singleton()->exposure_item_select)
			->setSourceDescriptionField("description");

		$this->fields->security_id
			->setLabel("Security")
			->setType("select")
			->setSource(P4A::singleton()->select_securities)
			->setSourceDescriptionField("select_name");

		$this->fields->security_exposure_status_id
			->setLabel("Status")
			->setType("select")
			->setSource(P4A::singleton()->security_exposure_stati_select)
			->setSourceDescriptionField("status_text");

		// Search Fieldset
		$this->build("p4a_field", "sec_search")
			->setLabel("Security name")
			->implement("onreturnpress", $this, "search");
		$this->build("p4a_field", "type_search")
			->setLabel("or type name")
			->implement("onreturnpress", $this, "search");
		$this->build("p4a_field", "expo_search")
			->setLabel("or exposure")
			->implement("onreturnpress", $this, "search");
		$this->build("p4a_button", "cmd_search")
			->setLabel("Go")
			->implement("onclick", $this, "search");
		$this->build("p4a_fieldset", "fs_search")
			->setLabel("Search")
			->anchor($this->sec_search)
			->anchor($this->type_search)
			->anchor($this->expo_search)
			->anchorLeft($this->cmd_search);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($this->security_exposures)
			->setVisibleCols(array("security","type_name","exposure","exposure_in_pct","comment")) 
			->setWidth(500)
			->showNavigationBar();

		//$this->setRequiredField("description");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Security exposure detail")
			->anchor($this->fields->security_id)
			->anchor($this->fields->exposure_item_id)
			->anchor($this->fields->exposure_in_pct)
			->anchor($this->fields->security_exposure_status_id)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->fs_search)
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->exposure_in_pct);
	}

	public function search()
	{
		$value = $this->sec_search->getSQLNewValue();
		if ($value <> "") {
			$this->security_exposures
				->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL("`securities`.`name`", "%{$value}%"))
				->firstRow();

			if (!$this->security_exposures->getNumRows()) {
				$this->warning("No results with security ".$value." found");
				$this->security_exposures->setWhere(null);
				$this->security_exposures->firstRow();
			}
		}
        
		if ($value == "") {
			$value = $this->type_search->getSQLNewValue();
			if ($value <> "") {
				$this->security_exposures
					->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL("`v_exposure_items`.`type_name`", "%{$value}%"))
					->firstRow();

				if (!$this->security_exposures->getNumRows()) {
					$this->warning("No results with type ".$value." found");
					$this->security_exposures->setWhere(null);
					$this->security_exposures->firstRow();
				}
			}
		}
        
		if ($value == "") {
			$value = $this->expo_search->getSQLNewValue();
			if ($value <> "") {
				$this->security_exposures
					->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL("`v_exposure_items`.`description`", "%{$value}%"))
					->firstRow();

				if (!$this->security_exposures->getNumRows()) {
					$this->warning("No results with exposure ".$value." found");
					$this->security_exposures->setWhere(null);
					$this->security_exposures->firstRow();
				}
			}
		}
		
		if ($value == "") {
			$this->security_exposures->setWhere(null);
			$this->security_exposures->firstRow();
		}
	} 	

	
	
}
