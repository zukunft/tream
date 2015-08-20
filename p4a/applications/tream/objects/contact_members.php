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
class Contact_members extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->contact_members);
		$this->setTitle("Activity members - if several persons have been involved in a client contact, add addition persons here");
		$this->firstRow();

		// Customizing fields properties
		$this->fields->contact_id
			->setLabel("Contact")
			->setType("select")
			->setSource(P4A::singleton()->select_contacts)
			->setSourceDescriptionField("contact_select");

		$this->fields->contact_member_type_id
			->setLabel("Type")
			->setType("select")
			->setSource(P4A::singleton()->select_contact_member_types)
			->setSourceDescriptionField("member_type");

		$this->fields->person_id
			->setLabel("Person")
			->setType("select")
			->setSource(P4A::singleton()->select_persons)
			->setSourceDescriptionField("select_name");

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->build("p4a_table", "table")
			->setSource($p4a->contact_members)
			->setWidth(500)
			->setVisibleCols(array("contact","person","type","comment"))
			->showNavigationBar();

		$this->setRequiredField("contact_id");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Contact type detail")
			->anchor($this->fields->contact_id)
			->anchor($this->fields->contact_member_type_id)
			->anchor($this->fields->person_id)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->person_id);
	}
}
