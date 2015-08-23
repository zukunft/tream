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
class Messages extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "messages")
			->setTable("messages")
			->addOrder("subject")
			->addJoinLeft("message_types", "messages.message_type_id  = message_types.message_type_id",
					  array('type_name'=>'type'))
			->load();

		$this->setSource($this->messages);
		$this->firstRow();

		// Customizing fields properties
		$this->fields->message_type_id
			->setLabel("Type")
			->setType("select")
			->setSource(P4A::singleton()->select_message_types)
			->setSourceDescriptionField("type_name");

		$this->fields->subject
			->setType("textarea")
			->setWidth(700)
			->setHeight(80);

		$this->fields->body
			->setType("rich_textarea")
			->setWidth(700)
			->setHeight(200);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($this->messages)
			->setWidth(500)
			->setVisibleCols(array("type","subject"))
			->showNavigationBar();

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Message details")
			->anchor($this->fields->message_type_id)
			->anchor($this->fields->subject)
			->anchor($this->fields->body);
		
		$this->frame
			->anchor($this->table)
 			->anchorLeft($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->body);
	}
}
