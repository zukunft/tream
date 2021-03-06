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
class Security_payment_types extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->security_payment_types);
		$this->firstRow();

		$this->fields->use_for_performance_brutto->setLabel("Brutto");
		$this->fields->use_for_performance_netto->setLabel("Netto");
		$this->fields->use_for_performance_netto_all->setLabel("All");
		$this->fields->use_for_tax->setLabel("Tax");

		$this->fields->code_id->disable();
		$this->fields->comment->setWidth(400);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($p4a->security_payment_types)
			->setVisibleCols(array("type_name","code_id","use_for_performance_brutto","use_for_performance_netto","use_for_performance_netto_all","use_for_tax","reconciliation_id","comment"))
			->setWidth(900)
			->showNavigationBar();

		$this->setRequiredField("type_name");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Security payment type detail")
			->anchor($this->fields->type_name)
			->anchor($this->fields->code_id)
			->anchor($this->fields->use_for_simulation)
			->anchor($this->fields->use_for_tax)
			->anchor($this->fields->use_for_performance_brutto)
			->anchor($this->fields->use_for_performance_netto)
			->anchor($this->fields->use_for_performance_netto_all)
			->anchor($this->fields->use_for_reconciliation)
			->anchor($this->fields->reconciliation_id)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->type_name);
	}
}
