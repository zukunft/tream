<?php
/**
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
class Security_issuers extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "security_pos")
			->setTable("v_security_pos")
			->addOrder("security_issuer_id")
			->load();

		$this->setSource($p4a->security_issuers);
		$this->firstRow();

		$this->fields->comment->setWidth(400);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($p4a->security_issuers)
			->setVisibleCols(array("issuer_name","comment"))
			->setWidth(700)
			->showNavigationBar();

		$this->build("p4a_table", "table_security_pos")
			->setSource($this->security_pos)
			->setWidth(600)
			->setVisibleCols(array("position","ISIN","security_name","pos_value_ref","pnl_ref")) 
			->showNavigationBar();
		$this->security_pos->addFilter("security_issuer_id = ?", $p4a->security_issuers->fields->security_issuer_id);  

		$this->setRequiredField("issuer_name");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Trade type detail")
			->anchor($this->fields->issuer_name)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details)
 			->anchor($this->table_security_pos); 

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->issuer_name);
	}
}
