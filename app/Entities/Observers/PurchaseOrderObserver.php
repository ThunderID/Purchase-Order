<?php 

namespace App\Entities\Observers;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;

use App\Entities\PurchaseOrder as Model; 

/**
 * Used in PurchaseOrder model
 *
 * @author cmooy
 */
class PurchaseOrderObserver 
{
	public function creating($model)
	{
		return $this->unique_code(0, $model->code, $model);
	}

	public function updating($model)
	{
		return $this->unique_code($model->id, $model->code, $model);
	}

	public function unique_code($id, $code, $model)
	{
		$code 				= Model::notid($id)->code($code)->first();

		if($code)
		{
			$model['errors'] = ['Code harus unique!'];

			return false;
		}

		return true;
	}
}
