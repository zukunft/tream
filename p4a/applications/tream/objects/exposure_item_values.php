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
class Exposure_item_values extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->exposure_item_values);
		$this->firstRow();

		// Customizing fields properties
		$this->fields->exposure_item_id
			->setLabel("Exposure item")
			->setType("select")
			->setSource(P4A::singleton()->exposure_item_select)
			->setSourceDescriptionField("description");

		$this->fields->ref_currency_id
			->setLabel("Ref. currency")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->ref_security_id
			->setLabel("Ref. security")
			->setType("select")
			->setSource(P4A::singleton()->select_securities)
			->setSourceDescriptionField("select_name");

		// Customizing fields properties
		$this->fields->hist_return->setTooltip("automatically calculated by zukunft.com based on market prices");
		$this->fields->market_return->setTooltip("automatically calculated by zukunft.com based on market estimates");
		$this->fields->expected_return->setTooltip("can be set to overwrite the market return");
		$this->fields->hist_volatility->setTooltip("automatically calculated by zukunft.com based on market prices");
		$this->fields->implied_volatility->setTooltip("automatically calculated by zukunft.com based on option prices");
		$this->fields->expected_volatility->setTooltip("can be set to overwrite the implied volatility");

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($p4a->exposure_item_values)
			->setVisibleCols(array("item","ref_fx","hist_return","hist_volatility","ref_security")) 
			->setWidth(500)
			->showNavigationBar();

		//$this->setRequiredField("description");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Exposure type detail")
			->anchor($this->fields->exposure_item_id)
			->anchor($this->fields->ref_currency_id)
			->anchor($this->fields->ref_security_id)
			->anchor($this->fields->hist_return)
			->anchor($this->fields->market_return)
			->anchor($this->fields->expected_return)
			->anchor($this->fields->hist_volatility)
			->anchor($this->fields->implied_volatility)
			->anchor($this->fields->expected_volatility)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->ref_currency_id);
	}
}
