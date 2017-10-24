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

class Exposure_references extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "exposure_references")
			->setTable("exposure_references")
			->addJoinLeft("securities", "exposure_references.sec_ref_neutral_id  = securities.security_id",
					  array('name'=>'neutral_reference'))
			->addJoinLeft("account_mandates", "exposure_references.account_mandate_id  = account_mandates.account_mandat_id",
					  array('description'=>'mandate'))
			->addJoinLeft("currencies", "exposure_references.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addOrder("account_mandat_id")
			->load();

		$this->build("p4a_db_source", "log_exposure_references")
			->setTable("log_data")
			->addOrder("log_time")
			->setWhere("log_data.table_name = 'exposure_references'")
			->load();

		$this->setSource($this->exposure_references);
		$this->firstRow();

		// Customizing fields properties
		$this->fields->account_mandate_id
			->setLabel("Mandate")
			->setType("select")
			->setSource(P4A::singleton()->select_account_mandates)
			->setSourceDescriptionField("description");

		$this->fields->sec_ref_neutral_id
			->setLabel("Neutral Reference")
			->setType("select")
			->setSource(P4A::singleton()->select_securities)
			->setSourceDescriptionField("select_name");

		$this->fields->sec_ref_taa_id
			->setLabel("Tactical Adjustment")
			->setType("select")
			->setSource(P4A::singleton()->select_securities_2)
			->setSourceDescriptionField("select_name");

		$this->fields->sec_benchmark_id
			->setLabel("Benchmark")
			->setType("select")
			->setSource(P4A::singleton()->select_securities_3)
			->setSourceDescriptionField("select_name");

		$this->fields->currency_id
			->setLabel("Currency")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($this->exposure_references)
			->setVisibleCols(array("mandate","fx","neutral_reference","comment")) 
			->setWidth(500)
			->showNavigationBar();

		//$this->setRequiredField("description");

		$this->build("p4a_table", "table_log")
			->setSource($this->log_exposure_references)
			->setWidth(500)
			->setVisibleCols(array("log_time","user_name","field_name","old_value","new_value"))
			->showNavigationBar(); 
		$this->log_exposure_references->addFilter("row_id = ?", $this->exposure_references->fields->exposure_reference_id); 

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Exposure reference detail")
			->anchor($this->fields->account_mandate_id)
			->anchor($this->fields->currency_id)
			->anchor($this->fields->sec_ref_neutral_id)
			->anchor($this->fields->sec_ref_taa_id)
			->anchor($this->fields->sec_benchmark_id)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details)
 			->anchorLeft($this->table_log);


		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->sec_ref_neutral_id);
	}
}
