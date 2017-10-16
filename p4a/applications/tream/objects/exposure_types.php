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
class Exposure_types extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "exposure_types")
			->setTable("exposure_types")
			->addOrder("type_name")
			->load();
		//$this->setSource($p4a->exposure_types);
		$this->setSource($this->exposure_types);

		$this->firstRow();

		// Customizing fields properties
		$this->fields->code_id->disable(); // because code_id is used in the code and should never be changed; it is just show to have an indication for the naming
		$this->fields->description->setWidth(400);
		$this->fields->comment->setWidth(400);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($this->exposure_types)
			->setVisibleCols(array("type_name","code_id","description","comment"))
			->setWidth(500)
			->showNavigationBar();

		$this->setRequiredField("type_name");

		//$this->build("p4a_image", "chart","/batch/tream_chart_xy.php?title=Assets&xaxis=risk&yaxis=return&labels=CHF,EUR,USD&xvalues=40,30,10&yvalues=2,3,4");

		$this->build("p4a_image", "chart","chart");
		$this->build('p4a_button','show_chart_btn')
			->implement("onclick", $this, "show_chart");;
		$this->show_chart_btn->setLabel('Update chart');		
		
		// add this change to documentaion!!!
		$this->build("p4a_field", "ref_fx")
			->setLabel("Ref. currency")
			->setType("select")
			->setValue(3) // the default system currency
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");
		//$this->chart->setIcon("chart");
		$this->chart->setIcon("/batch/tream_chart_exposure_type.php?type_id=".$this->exposure_types->fields->exposure_type_id->getValue()."&ref_fx=".$this->ref_fx->getNewValue()."");
		
		//$this->build("p4a_label", "testl","type_id=".$this->exposure_types->fields->exposure_type_id->getValue()."");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Exposure type detail")
			->anchor($this->fields->type_name)
			->anchor($this->fields->description)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details)
			//->anchor($this->show_chart_btn)
			->anchor($this->ref_fx)
 			->anchorLeft($this->show_chart_btn)
 			->anchor($this->chart);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->type_name);
	}
	public function show_chart()
	{
		$this->chart->setIcon("/batch/tream_chart_exposure_type.php?type_id=".$this->exposure_types->fields->exposure_type_id->getValue()."&ref_fx=".$this->ref_fx->getNewValue()."");
	}
	function main()
	{
		parent::main();
		$this->chart->setIcon("/batch/tream_chart_exposure_type.php?type_id=".$this->exposure_types->fields->exposure_type_id->getValue()."&ref_fx=".$this->ref_fx->getNewValue()."");
		
	}
/*	function prevRow()
	{
		parent::prevRow();
	} 
	function nextRow()
	{
		parent::nextRow();
	} */
/*
	function newRow()
	{
		parent::newRow();
	} */
}
