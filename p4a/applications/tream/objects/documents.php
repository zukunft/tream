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
class Documents extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->documents);
		$this->firstRow();

		$this->fields->document_type_id
			->setLabel("Type")
			->setType("select")
			->setSource(P4A::singleton()->select_document_types)
			->setSourceDescriptionField("type_name");

		$this->fields->internal_person_id
			->setLabel("Internal Person")
			->setType("select")
			->setSource(P4A::singleton()->internal_persons)
			->setSourceDescriptionField("select_name");

		$this->fields->account_id
			->setLabel("Account")
			->setType("select")
			->setSource(P4A::singleton()->select_accounts)
			->setSourceDescriptionField("account_select_name"); 

		$this->fields->bank_id
			->setLabel("Bank")
			->setType("select")
			->setSource(P4A::singleton()->select_banks)
			->setSourceDescriptionField("bank_name");

		$this->fields->date_internal_sign->setLabel("Date internal sign");

		$this->fields->scanned_document->setType("file");
		
		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->build("p4a_table", "table")
			->setSource($p4a->documents)
			->setVisibleCols(array("account","type","bank","date_to_account","date_received","keywords"))
			->setWidth(500)
			->showNavigationBar();

		$this->setRequiredField("document_type_id");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Document detail")
			->anchor($this->fields->document_type_id)
			->anchor($this->fields->internal_person_id)
			->anchor($this->fields->date_internal_sign)
			->anchor($this->fields->date_to_account)
			->anchor($this->fields->account_id)
			->anchor($this->fields->date_account_sign)
			->anchor($this->fields->bank_id)
			->anchor($this->fields->date_bank_sign)
			->anchor($this->fields->date_received)
			->anchor($this->fields->scanned_document)
			->anchor($this->fields->keywords)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->document_type_id);
	}
}
