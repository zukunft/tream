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
class Contacts extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();
		
		$this->build("p4a_db_source", "contacts")
			->setTable("contacts")
			->addOrder("description")
			->addJoin("contact_types", "contact_types.contact_type_id = contacts.contact_type_id",
					  array('type_name'=>'type'))
			->addJoin("contact_categories", "contact_categories.contact_category_id = contacts.contact_category_id",
					  array('category_name'=>'category'))
			->addJoin("persons", "contacts.person_id = persons.person_id",
					  array('lastname'=>'person'))
			->addJoin("accounts", "contacts.account_id = accounts.account_id",
					  array('account_name'=>'account'))
			->load();

		$this->build("p4a_db_source", "actions")
			->setTable("actions")
			->addOrder("deadline")
			->addJoin("action_stati", "actions.action_status_id  = action_stati.action_status_id",
					  array('status_text'=>'status'))
			->addJoin("contacts", "actions.create_contact_id = contacts.contact_id",
					  array('description'=>'contact'))
			->load();

		$this->build("p4a_db_source", "contact_members")
			->setTable("contact_members")
			->addOrder("contact_id")
			->addJoin("persons", "contact_members.person_id = persons.person_id",
					  array('lastname'=>'person'))
			->addJoin("contact_member_types", "contact_members.contact_member_type_id = contact_member_types.contact_member_type_id",
					  array('member_type'=>'type'))
			->load();

		$this->setSource($this->contacts);
		$this->setTitle("Client activities - add here a note for every contact that you have with the client");
		$this->firstRow();

		// Customizing fields properties
		$this->fields->start->setLabel("When");

		$this->fields->main_action
			->setType("textarea")
			->setWidth(700)
			->setHeight(80);

		$this->fields->details
			->setType("rich_textarea")
			->setWidth(700)
			->setHeight(200);

		$this->fields->contact_type_id
			->setLabel("Type")
			->setType("select")
			->setSource(P4A::singleton()->select_contact_types)
			->setSourceDescriptionField("type_name");

		$this->fields->contact_category_id
			->setLabel("Category")
			->setType("select")
			->setSource(P4A::singleton()->select_contact_categories)
			->setSourceDescriptionField("category_name");

		$this->fields->account_id
			->setLabel("Account")
			->setType("select")
			->setSource(P4A::singleton()->select_accounts)
			->setSourceDescriptionField("account_select_name"); 

		$this->fields->portfolio_id
			->setLabel("Portfolio")
			->setType("select")
			->setSource(P4A::singleton()->select_portfolios)
			->setSourceDescriptionField("portfolio_select_name"); 

		$this->fields->person_id
			->setLabel("Internal Person")
			->setType("select")
			->setSource(P4A::singleton()->internal_persons)
			->setSourceDescriptionField("select_name");

		$this->fields->action_status_id
			->setLabel("Status")
			->setType("select")
			->setSource(P4A::singleton()->action_stati)
			->setSourceDescriptionField("status_text");

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->build("p4a_table", "table")
			->setSource($this->contacts)
			->setWidth(700)
			->setVisibleCols(array("start","description","person","account","type","category","main_action"))
			->showNavigationBar();

		$this->build("p4a_table", "table_actions")
			->setSource($this->actions)
			->setWidth(600)
			->setVisibleCols(array("creation_time","description","deadline","status"))
			->showNavigationBar();
		$this->actions->addFilter("create_contact_id = ?", $this->contacts->fields->contact_id); 

		$this->build("p4a_table", "table_members")
			->setSource($this->contact_members)
			->setWidth(200)
			->setVisibleCols(array("type","person"))
			->showNavigationBar();
		$this->contact_members->addFilter("contact_id = ?", $this->contacts->fields->contact_id); 

		$this->setRequiredField("contact_type_id");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Client activity detail")
			->anchor($this->fields->start)
			->anchor($this->fields->contact_category_id)
			->anchor($this->fields->contact_type_id)
			->anchor($this->fields->person_id)
			->anchor($this->fields->account_id)
			->anchor($this->fields->portfolio_id)
			->anchor($this->fields->description)
			->anchor($this->fields->main_action)
			->anchor($this->fields->details)
			->anchor($this->fields->action_status_id);
		
		$this->frame
			->anchor($this->table)
 			->anchorLeft($this->fs_details)
 			->anchor($this->table_actions)
 			->anchorLeft($this->table_members);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->contact_type_id);
	}
}
