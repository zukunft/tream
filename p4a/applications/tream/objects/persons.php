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
class Persons extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "persons")
			->setTable("persons")
			->addOrder("lastname")
			->addJoinLeft("person_types", "persons.person_type_id  = person_types.person_type_id",
					  array('description'=>'type'))
			->setPageLimit(30)
			->load();

		$this->build("p4a_db_source", "contact_numbers")
			->setTable("contact_numbers")
			->addOrder("contact_number_id")
			->addJoin("contact_number_types", "contact_numbers.contact_number_type_id = contact_number_types.contact_number_type_id",
					  array('type_name'=>'number_type'))
			->load();

		$this->build("p4a_db_source", "address_links")
			->setTable("address_links")
			->addOrder("address_id")
			->addJoin("address_link_types", "address_links.address_link_type_id = address_link_types.address_link_type_id",
					  array('type_name'=>'type'))
			->addJoin("addresses", "address_links.address_id = addresses.address_id",
					  array('street'=>'street','city_code'=>'city_code','city'=>'city'))
			->load();

		$this->setSource($this->persons);
		$this->firstRow();

		$this->fields->person_type_id
			->setLabel("Type")
			->setType("select")
			->setSource(P4A::singleton()->select_person_types)
			->setSourceDescriptionField("description");

		$this->fields->owner_id
			->setLabel("Beneficial Owner")
			->setType("select")
			->setSource(P4A::singleton()->select_persons)
			->setSourceDescriptionField("select_name");

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->build("p4a_table", "table")
			->setSource($this->persons)
			->setWidth(500)
			->setVisibleCols(array("lastname","firstname","date_of_birth","type"))
			->showNavigationBar();

		$this->build("p4a_table", "table_numbers")
			->setSource($this->contact_numbers)
			->setWidth(300)
			->setVisibleCols(array("number_type","contact_number"))
			->showNavigationBar();
		$this->contact_numbers->addFilter("contact_numbers.person_id = ?", $this->persons->fields->person_id); 

		$this->build("p4a_table", "table_addresses")
			->setSource($this->address_links)
			->setWidth(300)
			->setVisibleCols(array("type","street","city_code","city"))
			->showNavigationBar();
		$this->address_links->addFilter("address_links.person_id = ?", $this->persons->fields->person_id); 

		$this->setRequiredField("lastname");
		$this->table->cols->person_id->setLabel("Person ID");
		$this->fields->person_id
			->disable()
			->setLabel("Person ID");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Person detail")
/*			->anchor($this->fields->person_id) */
			->anchor($this->fields->title)
			->anchor($this->fields->firstname)
			->anchor($this->fields->lastname)
			->anchor($this->fields->display_name) 
			->anchor($this->fields->person_type_id)
			->anchor($this->fields->date_of_birth)
			->anchor($this->fields->owner_id)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchorLeft($this->fs_details)
 			->anchorLeft($this->table_numbers)
 			->anchorLeft($this->table_addresses);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->lastname);
	}
}
