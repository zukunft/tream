<?php
/**
 * This file is part of P4A - PHP For Applications.
 *
 * P4A is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 * 
 */

/**
 * @author Timon Zielonka <timon@zukunft.com>
 * @copyright Copyright (c) 2010-2013 Timon Zielonka 
 */
class Portfolio_monitor extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->build("p4a_db_source", "portfolios")
			->setTable("portfolios")
			->addOrder("portfolio_number")
			->addJoin("v_portfolios_u", "portfolios.portfolio_id  = v_portfolios_u.portfolio_id",
					  array('user_name'=>'user_name'))
			->addJoinLeft("currencies", "v_portfolios_u.currency_id  = currencies.currency_id",
					  array('symbol'=>'fx'))
			->addJoinLeft("banks", "v_portfolios_u.bank_id = banks.bank_id",
					  array('bank_name'=>'bank'))
			->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('v_portfolios_u.user_name', $_SESSION['log_user']))
			->load();

		$this->build("p4a_db_source", "exposure_target_values")
			->setTable("v_exposure_target_values")
			->addOrder("portfolio_id")
			->load();

		$this->setSource($this->portfolios);
		$this->firstRow();


		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($this->portfolios)
			->setWidth(500)
			->setVisibleCols(array("portfolio_number","portfolio_name","fx","bank"))
			->showNavigationBar();

			$this->table->cols->portfolio_number
				->setWidth(30)
				->setLabel('nbr');


		$this->build("p4a_table", "table_target_values")
			->setSource($this->exposure_target_values)
			->setWidth(600)
			->setVisibleCols(array("item_name","limit_up","limit_down","neutral","calc_value","diff_neutral")) 
			->showNavigationBar();
		$this->exposure_target_values->addFilter("portfolio_id = ?", $this->portfolios->fields->portfolio_id);  
		
		
		$this->frame
			->anchor($this->table)
 			->anchorLeft($this->table_target_values); 

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->portfolio_number);
	}
}
