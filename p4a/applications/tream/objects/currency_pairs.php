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
class Currency_pairs extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->currency_pairs);
		$this->firstRow();

		$this->fields->currency1_id
			->setLabel("From curr")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->currency2_id
			->setLabel("To curr")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->price_feed_type_id
			->setLabel("Price feed")
			->setType("select")
			->setSource(P4A::singleton()->select_security_price_feed_types)
			->setSourceDescriptionField("type_name");

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
			->setSource($p4a->currency_pairs)
			->setVisibleCols(array("from_fx","to_fx","fx_rate"))
			->setWidth(500)
			->showNavigationBar();

		$this->setRequiredField("description");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Currency pair detail")
			->anchor($this->fields->currency1_id)
			->anchor($this->fields->currency2_id)
			->anchor($this->fields->fx_rate)
			->anchor($this->fields->factor)
			->anchor($this->fields->decimals)
			->anchor($this->fields->price_feed_type_id)
			->anchor($this->fields->symbol_market_map)
			->anchor($this->fields->symbol_yahoo)
			->anchor($this->fields->hist_return)
			->anchor($this->fields->market_return)
			->anchor($this->fields->expected_return)
			->anchor($this->fields->hist_volatility)
			->anchor($this->fields->implied_volatility)
			->anchor($this->fields->expected_volatility)
			->anchor($this->fields->description)
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
