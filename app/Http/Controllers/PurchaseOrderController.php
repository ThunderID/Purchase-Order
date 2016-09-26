<?php

namespace App\Http\Controllers;

use App\Libraries\JSend;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Entities\PurchaseOrder;

/**
 * Purchase Order resource representation.
 *
 * @Resource("Purchase Orders", uri="/purchase/orders")
 */
class PurchaseOrderController extends Controller
{
	public function __construct(Request $request)
	{
		$this->request 				= $request;
	}

	/**
	 * Show all purchase/orders
	 *
	 * @Get("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"search":{"_id":"string","issuedby":"string","issuedto":"string","issuedat":"string"},"sort":{"newest":"asc|desc","issedby":"desc|asc","issuedto":"desc|asc", "issuedat":"desc|asc"}, "take":"integer", "skip":"integer"}),
	 *      @Response(200, body={"status": "success", "data": {"data":{"_id":"string","code":"string","issued_by":{"type":"string","identifier":"string","name":"string"},"issued_at":"datetime","issued_to":{"type":"string","identifier":"string","name":"string"},"products":{"description":"string","code":"string","price":{"gross":"number","net":"number","discount":"number"},"quantity":"number"}, "expenses":{"desc":"string","subtitle":"true"}},"count":"integer"} })
	 * })
	 */
	public function index()
	{
		$result						= new PurchaseOrder;

		if(Input::has('search'))
		{
			$search					= Input::get('search');

			foreach ($search as $key => $value) 
			{
				switch (strtolower($key)) 
				{
					case '_id':
						$result		= $result->id($value);
						break;
					case 'issuedby':
						$result		= $result->issuedby($value);
						break;
					case 'issuedto':
						$result		= $result->issuedto($value);
						break;
					case 'issuedat':
						$result		= $result->issuedat($value);
						break;
					default:
						# code...
						break;
				}
			}
		}

		if(Input::has('sort'))
		{
			$sort					= Input::get('sort');

			foreach ($sort as $key => $value) 
			{
				if(!in_array($value, ['asc', 'desc']))
				{
					return response()->json( JSend::error([$key.' harus bernilai asc atau desc.'])->asArray());
				}
				switch (strtolower($key)) 
				{
					case 'newest':
						$result		= $result->orderby('created_at', $value);
						break;
					case 'issedby':
						$result		= $result->orderby('issued_by.name', $value);
						break;
					case 'issuedto':
						$result		= $result->orderby('issued_to.name', $value);
						break;
					case 'issuedat':
						$result		= $result->orderby('issued_at', $value);
						break;
					default:
						# code...
						break;
				}
			}
		}

		$count						= count($result->get());

		if(Input::has('skip'))
		{
			$skip					= Input::get('skip');
			$result					= $result->skip($skip);
		}

		if(Input::has('take'))
		{
			$take					= Input::get('take');
			$result					= $result->take($take);
		}

		$result 					= $result->get();
		
		return response()->json( JSend::success(['data' => $result->toArray(), 'count' => $count])->asArray())
				->setCallback($this->request->input('callback'));
	}

	/**
	 * Store PurchaseOrder
	 *
	 * @Post("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"_id":"null","code":"string","issued_by":{"type":"string","identifier":"string","name":"string"},"issued_at":"datetime","issued_to":{"type":"string","identifier":"string","name":"string"},"products":{"description":"string","code":"string","price":{"gross":"number","net":"number","discount":"number"},"quantity":"number"}, "expenses":{"desc":"string","subtitle":"true"}}),
	 *      @Response(200, body={"status": "success", "data": {"_id":"string","code":"string","issued_by":{"type":"string","identifier":"string","name":"string"},"issued_at":"datetime","issued_to":{"type":"string","identifier":"string","name":"string"},"products":{"description":"string","code":"string","price":{"gross":"number","net":"number","discount":"number"},"quantity":"number"}, "expenses":{"desc":"string","subtitle":"true"}}}),
	 *      @Response(200, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function post()
	{
		$id 			= Input::get('_id');

		if(!is_null($id) && !empty($id))
		{
			$result		= PurchaseOrder::id($id)->first();
		}
		else
		{
			$result 	= new PurchaseOrder;
		}
		

		$result->fill(Input::only('code', 'issued_by', 'issued_at', 'issued_to', 'products', 'expenses'));

		if($result->save())
		{
			return response()->json( JSend::success($result->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		
		return response()->json( JSend::error($result->getError())->asArray());
	}

	/**
	 * Delete PurchaseOrder
	 *
	 * @Delete("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null}),
	 *      @Response(200, body={"status": "success", "data": {"_id":"string","code":"string","issued_by":{"type":"string","identifier":"string","name":"string"},"issued_at":"datetime","issued_to":{"type":"string","identifier":"string","name":"string"},"products":{"description":"string","code":"string","price":{"gross":"number","net":"number","discount":"number"},"quantity":"number"}, "expenses":{"desc":"string","subtitle":"true"}}}),
	 *      @Response(200, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function delete()
	{
		$order			= PurchaseOrder::id(Input::get('_id'))->first();

		$result 		= $order;

		if($order && $order->delete())
		{
			return response()->json( JSend::success($result->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		elseif(!$order)
		{
			return response()->json( JSend::error(['ID tidak valid'])->asArray());
		}

		return response()->json( JSend::error($order->getError())->asArray());
	}
}