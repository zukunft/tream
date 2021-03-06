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
class Trade_types extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->trade_types);
		$this->firstRow();

		$this->fields->factor->setTooltip("1 for buy, -1 for sell trades and 0 for simulation");
		$this->fields->do_not_use_size
                        ->setLabel("Simulation factor")
                        ->setTooltip("like factor use 1 and -1 for simulation");;
		$this->fields->use_cash->setLabel("use for cash trades");
		$this->fields->use_fx->setLabel("FX");
		$this->fields->use_fx_swap->setLabel("FX Swap");
		$this->fields->use_bond->setLabel("Bonds");
		$this->fields->use_equity->setLabel("Equity");
		$this->fields->use_fund->setLabel("Fund");
		$this->fields->use_etf->setLabel("ETF");
		$this->fields->use_metal->setLabel("Metals");
		$this->fields->use_option->setLabel("Options");
		$this->fields->use_future->setLabel("Futures");
		$this->fields->use_product->setLabel("Others");
		$this->fields->code_id->disable();

		$this->fields->comment->setWidth(300);
                        
		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($p4a->trade_types)
			->setVisibleCols(array("description","factor","code_id","use_cash","use_equity","use_option"))
			->setWidth(500)
			->showNavigationBar();

		$this->setRequiredField("description");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Trade type detail")
			->anchor($this->fields->description)
			->anchor($this->fields->factor)
			->anchor($this->fields->do_not_use_size)
			->anchor($this->fields->use_cash)
			->anchor($this->fields->use_fx)
			->anchorLeft($this->fields->use_fx_swap)
			->anchor($this->fields->use_bond)
			->anchor($this->fields->use_equity)
			->anchor($this->fields->use_fund)
			->anchorLeft($this->fields->use_etf)
			->anchor($this->fields->use_metal)
			->anchor($this->fields->use_option)
			->anchorLeft($this->fields->use_future)
			->anchor($this->fields->use_product)
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
