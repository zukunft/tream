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
class Values extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->values);
		$this->firstRow();

		// Customizing fields properties
		$this->fields->account_id
			->setLabel("Account")
			->setType("select")
			->setSource(P4A::singleton()->accounts)
			->setSourceDescriptionField("account_name");

		$this->fields->value_type_id
			->setLabel("Value type")
			->setType("select")
			->setSource(P4A::singleton()->value_types)
			->setSourceDescriptionField("description"); 

		$this->fields->value_status_id
			->setLabel("Value status")
			->setType("select")
			->setSource(P4A::singleton()->value_stati)
			->setSourceDescriptionField("status_text"); 

		$this->fields->currency_id
			->setLabel("Currency")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->security_id
			->setLabel("Related to security")
			->setType("select")
			->setTooltip("only select if the value is for one specific security; other wwise set to 'not set'")
			->setSource(P4A::singleton()->select_securities)
			->setSourceDescriptionField("select_name");

		$this->fields->comment->setWidth(500);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->build("p4a_table", "table")
			->setSource($p4a->values)
			->setVisibleCols(array("account","value_date","val_number","status","comment"))
			->setWidth(1200)
			->showNavigationBar();

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Value detail")
			->anchor($this->fields->account_id)   /* maybe not needed because already defined over portfolio id */
			->anchor($this->fields->value_date)
			->anchor($this->fields->value_type_id)
			->anchor($this->fields->val_number)
			->anchor($this->fields->currency_id)
			->anchor($this->fields->security_id)
			->anchor($this->fields->value_status_id)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->val_number);
	}
}
