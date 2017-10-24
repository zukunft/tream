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
class Portfolio_rights extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->portfolio_rights);
		$this->setTitle("Portfolio access - define the roles of user OR team for a single portfolio");
		$this->firstRow();

		$this->fields->portfolio_id
			->setLabel("Portfolio")
			->setType("select")
			->setSource(P4A::singleton()->select_portfolios)
			->setSourceDescriptionField("portfolio_select_name"); 

		$this->fields->user_id
			->setLabel("User")
			->setType("select")
			->setSource(P4A::singleton()->user_select)
			->setSourceDescriptionField("username"); 

		$this->fields->user_group_id
			->setLabel("Team")
			->setType("select")
			->setSource(P4A::singleton()->user_group_select)
			->setSourceDescriptionField("group_name"); 

		$this->fields->user_right_id
			->setLabel("Role")
			->setType("select")
			->setSource(P4A::singleton()->user_right_select)
			->setSourceDescriptionField("right_name"); 

		$this->fields->comment->setWidth(400);
                        
		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($p4a->portfolio_rights)
			->setVisibleCols(array("portfolio","user","group","right","comment"))
			->setWidth(500)
			->showNavigationBar();

		$this->setRequiredField("portfolio_id");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Portfolio right detail")
			->anchor($this->fields->portfolio_id)
			->anchor($this->fields->user_id)
			->anchor($this->fields->user_group_id)
			->anchor($this->fields->user_right_id)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->portfolio_id);
	}
}
