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
class Accounts extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "accounts")
			->setTable("accounts")
			->addOrder("account_name")
			->addJoinLeft("currencies", "accounts.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addJoinLeft("banks", "accounts.bank_id = banks.bank_id",
					  array('bank_name'=>'bank'))
			->addJoinLeft("account_mandates", "accounts.account_mandat_id = account_mandates.account_mandat_id",
					  array('description'=>'mandate')) 
			->addJoinLeft("account_types", "accounts.account_type_id = account_types.account_type_id",
					  array('description'=>'status')) 
			->setPK("account_id")
			->load();

		$this->build("p4a_db_source", "actions")
			->setTable("actions")
			->addOrder("deadline")
			->setWhere("actions.action_status_id <> 3")
			->addJoin("action_stati", "actions.action_status_id  = action_stati.action_status_id",
					  array('status_text'=>'status'))
			->addJoin("contacts", "actions.create_contact_id = contacts.contact_id",
					  array('description'=>'contact'))
			->load();

		$this->build("p4a_db_source", "bills")
			->setTable("v_bill")
			->addOrder("account_id")
			->load(); 

		$this->setSource($this->accounts);
		$this->setTitle("Mandates - ".$_SESSION['mandate_filter']);
		$this->firstRow();

		// Customizing fields properties
		$this->fields->currency_id
			->setLabel("Ref. curr")
			->setType("select")
			->setSource(P4A::singleton()->select_currencies)
			->setSourceDescriptionField("symbol");

		$this->fields->bank_id
			->setLabel("Main Bank")
			->setType("select")
			->setSource(P4A::singleton()->select_banks)
			->setSourceDescriptionField("bank_name");

		$this->fields->account_mandat_id
			->setLabel("Mandate")
			->setType("select")
			->setSource(P4A::singleton()->select_account_mandates)
			->setSourceDescriptionField("description");

		$this->fields->account_type_id
			->setLabel("Status")
			->setType("select")
			->setSource(P4A::singleton()->select_account_types)
			->setSourceDescriptionField("description");

		$this->fields->first_contact_person_id
			->setLabel("First contact person")
			->setType("select")
			->setSource(P4A::singleton()->select_persons)
			->setSourceDescriptionField("select_name");

		$this->fields->fee_tp->setLabel("Own Fee");
		$this->fields->fee_finder->setLabel("Finders Fee");
		$this->fields->fee_bank->setLabel("Bank Fee (flat)");
		$this->fields->discount_bank->setLabel("Bank Discount");
		$this->fields->fee_performance->setLabel("Performance Fee"); 

		$this->fields->first_contact->setWidth(400);
		$this->fields->familiy_background->setWidth(400);
		$this->fields->occupation->setWidth(400);
		$this->fields->source_of_income->setWidth(400);
		$this->fields->source_of_wealth->setWidth(400);
                        
		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this); 

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($this->accounts)
			->setWidth(900)
			->setVisibleCols(array("account_name","mandate","fx","bank","status")) 
			->showNavigationBar();

		$this->build("p4a_table", "table_actions")
			->setSource($this->actions)
			->setWidth(500)
			->setVisibleCols(array("creation_time","description","deadline","status"))
			->showNavigationBar();
		$this->actions->addFilter("account_id = ?", $this->accounts->fields->account_id); 

		$this->build("p4a_table", "table_bill")
			->setSource($this->bills)
			->setWidth(500)
			->setVisibleCols(array("aum","start","days","fees","mwst","total"))
			->showNavigationBar();
		$this->bills->addFilter("account_id = ?", $this->accounts->fields->account_id); 
		
		$this->build("p4a_button", "btn_filter_on") 
			->setLabel("Set filter") 
			->implement("onclick", $this,"_btn_filter_on_click");

		$this->build("p4a_button", "btn_filter_off") 
			->setLabel("Show all")
			->implement("onclick", $this,"_btn_filter_off_click");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Account detail")
			->anchor($this->fields->account_name)
			->anchor($this->fields->account_mandat_id)
			->anchor($this->fields->account_type_id)
			->anchor($this->fields->currency_id)
			->anchor($this->fields->start_mandat)
			->anchor($this->fields->start_fee)
			->anchor($this->fields->end_finders)
			->anchor($this->fields->bank_id)
			->anchor($this->fields->fee_tp)
			->anchor($this->fields->fee_finder)
			->anchor($this->fields->fee_bank) 
			->anchor($this->fields->discount_bank) 
			->anchor($this->fields->fee_performance)
			->anchor($this->fields->first_contact_person_id)
			->anchor($this->fields->first_contact)
			->anchor($this->fields->familiy_background)
			->anchor($this->fields->occupation)
			->anchor($this->fields->source_of_income)
			->anchor($this->fields->source_of_wealth)
			->anchor($this->fields->inactive);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details)
 			->anchorLeft($this->table_actions)
 			->anchor($this->table_bill)
 			->anchorLeft($this->btn_filter_on)
 			->anchorLeft($this->btn_filter_off);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->account_name);
	}

	// show only this mandate
	public function _btn_filter_on_click()
	{
		// set the filter
		$_SESSION['mandate_filter'] = $this->accounts->fields->account_name;

//		$this->openMask($this->active_object->getName());
//		$this->messageInfo("Show only one mandate");

//		$p4a->menu->display;
/*		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->account_name); */
		//$this->messageInfo('Show only '.$this->accounts->fields->account_name.'', 'info');
		//$p4a$->menu->messageInfo('Show only one mandate', 'info');
		// $this->txt_field1->setNewValue("This is my first P4A application!");
	}

	public function _btn_filter_off_click()
	{
		// set the filter
		$_SESSION['mandate_filter'] = "all";

		//$this->messageInfo("Show all mandates");
//		$p4a->menu->messageInfo("Show all mandates");

		//$this->messageInfo('Show only '.$this->accounts->fields->account_name.'', 'info');
		//$p4a$->menu->messageInfo('Show all mandates', 'info');
		// $this->txt_field1->setNewValue("This is my first P4A application!");
	}
}
