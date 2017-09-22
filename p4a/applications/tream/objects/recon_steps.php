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
class Recon_steps extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->recon_steps);
		$this->firstRow();

		// Customizing fields properties
		$this->fields->filter
			->setTooltip("use fieldname=value to use only rows matching this pattern");

		$this->fields->source_field_name
			->setLabel("External field name")
			->setTooltip("Name of the field in the external file that should be used for reconciliation or a reference id set by a privious reconciliation step; e.g. sec_curr_id for the currency_id of the trade related security; in case of 'get field' the name where the loaded value should be saved");

		$this->fields->source_id_field
			->setLabel("External ID field")
			->setTooltip("Name of the field in the external file that is used to identify the record in the TREAM database; in many cases this field name is set by an earlier step; e.g. in the earlier step the ISIN is replaced by the security_id, so if the valor should be added in this step use security_id; if the step is used to remove rows, this field is used to limit the search");

		$this->fields->dest_table
			->setLabel("Database table")
			->setTooltip("The TREAM database table name where the values should be saved / added.");

		$this->fields->dest_field
			->setLabel("Database field")
			->setTooltip("The TREAM database field name where the values should be saved / added.");

		$this->fields->dest_id_field
			->setLabel("New ID field")
			->setTooltip("Used only for type 'unique subtable': the name of the field in the external file after the values have been replaced with the database id; e.g. for the security currency use 'sec_curr_id' and in the step after select the value with 'sec_curr_id' in the field 'External field name' to adjust the currency of a security.");

		$this->fields->source_field_name->setWidth(700);
		$this->fields->event_description_unique->setWidth(700);
		$this->fields->event_description->setWidth(700);
		$this->fields->solution1_text->setWidth(700);
		$this->fields->solution1_sql->setWidth(700);
		$this->fields->solution2_text->setWidth(700);
		$this->fields->solution2_sql->setWidth(700);
		$this->fields->comment->setWidth(700);

		$this->fields->recon_file_id
			->setLabel("File")
			->setType("select")
			->setSource(P4A::singleton()->recon_file_select)
			->setSourceDescriptionField("file_name");

		$this->fields->recon_step_type_id
			->setLabel("Type")
			->setType("select")
			->setSource(P4A::singleton()->recon_step_type_select)
			->setSourceDescriptionField("type_name");

		$this->fields->recon_value_type_id
			->setLabel("Value type")
			->setType("select")
			->setSource(P4A::singleton()->recon_value_type_select)
			->setSourceDescriptionField("type_name");

		$this->fields->err_step_for_step
			->setLabel("use only if this steps fails")
			->setType("select")
			->setSource(P4A::singleton()->recon_step_select)
			->setSourceDescriptionField("recon_select_name");

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		//$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($p4a->recon_steps)
			->setVisibleCols(array("file","order_nbr","dest_table","dest_field","filter"))
			//->addOrder("recon_file_id, order_nbr")
			->setWidth(800)
			->showNavigationBar();

		//$this->setRequiredField("file_name");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Reconciliation step detail")
			->anchor($this->fields->recon_file_id)
			->anchor($this->fields->order_nbr)
			->anchor($this->fields->filter)
			->anchor($this->fields->recon_step_type_id)
			->anchor($this->fields->source_field_name)
			->anchor($this->fields->source_id_field)
			->anchor($this->fields->dest_table)
			->anchor($this->fields->dest_field)
			->anchor($this->fields->dest_id_field)
			->anchor($this->fields->recon_value_type_id)
			->anchor($this->fields->event_description_unique)
			->anchor($this->fields->event_description)
			->anchor($this->fields->solution1_text)
			->anchor($this->fields->solution1_sql)
			->anchor($this->fields->solution2_text)
			->anchor($this->fields->solution2_sql)
			->anchor($this->fields->stop_line_on_err)
			->anchor($this->fields->err_step_for_step)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->filter);
	}
}
