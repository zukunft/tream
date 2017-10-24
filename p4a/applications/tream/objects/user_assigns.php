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
 * @link http://tream.biz
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License

*/
class User_assigns extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->user_assigns);
		$this->setTitle("Team members - Define the members of a team so that all team members can have at least the same rights");
		$this->firstRow();

		$this->fields->user_id
			->setLabel("Member")
			->setType("select")
			->setSource(P4A::singleton()->user_select)
			->setSourceDescriptionField("username"); 

		$this->fields->user_group_id
			->setLabel("Team")
			->setType("select")
			->setSource(P4A::singleton()->user_group_select)
			->setSourceDescriptionField("group_name"); 

		$this->fields->comment->setWidth(400);
                        
		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($p4a->user_assigns)
			->setVisibleCols(array("group","user","comment"))
			->setWidth(500)
			->showNavigationBar();

		$this->setRequiredField("user_id");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("User assign detail")
			->anchor($this->fields->user_group_id)
			->anchor($this->fields->user_id)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->user_id);
	}
}
