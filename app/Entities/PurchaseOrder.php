<?php

namespace App\Entities;

use App\Entities\Observers\PurchaseOrderObserver;

use Carbon\Carbon;

/**
 * Used for PurchaseOrder Models
 * 
 * @author cmooy
 */
class PurchaseOrder extends BaseModel
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $collection			= 'mt_purchase_order';

	/**
	 * Date will be returned as carbon
	 *
	 * @var array
	 */
	protected $dates				=	['created_at', 'updated_at', 'deleted_at'];

	/**
	 * The appends attributes from mutator and accessor
	 *
	 * @var array
	 */
	protected $appends				=	[];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden 				= [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable				=	[
											'code'							,
											'issued_by'						,
											'issued_at'						,
											'issued_to'						,
											'ship_to'						,
											'products'						,
											'expenses'						,
										];
										
	/**
	 * Basic rule of database
	 *
	 * @var array
	 */
	protected $rules				=	[
											'code'							=> 'required|max:255',
											'issued_by.type'				=> 'required|in:company,person',
											'issued_by.identifier'			=> 'required|max:255',
											'issued_by.name'				=> 'required|max:255',
											'issued_at'						=> 'required|date_format:"Y-m-d H:i:s"',
											'issued_to.type'				=> 'required|in:company,person',
											'issued_to.identifier'			=> 'required|max:255',
											'issued_to.name'				=> 'required|max:255',
											'products.*.description'		=> 'required|max:255',
											'products.*.code'				=> 'required|max:255',
											'products.*.price.gross'		=> 'required|numeric',
											'products.*.price.net'			=> 'required|numeric',
											'products.*.price.discount'		=> 'numeric',
											'products.*.quantity'			=> 'numeric',
											'expenses.*.description'		=> 'required|max:255',
											'expenses.*.subtotal'			=> 'required|max:255',
										];


	/* ---------------------------------------------------------------------------- RELATIONSHIP ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- QUERY BUILDER ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS ----------------------------------------------------------------------------*/
		
	/**
	 * boot
	 * observing model
	 *
	 */
	public static function boot() 
	{
        parent::boot();

		PurchaseOrder::observe(new PurchaseOrderObserver);
    }

	/* ---------------------------------------------------------------------------- SCOPES ----------------------------------------------------------------------------*/

	/**
	 * scope to get condition where code
	 *
	 * @param string or array of code
	 **/
	public function scopeCode($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('code', $variable);
		}

		return $query->where('code', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where issued by identifier
	 *
	 * @param string or array of issued by identifier
	 **/
	public function scopeIssuedBy($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('issued_by.identifier', $variable);
		}

		return $query->where('issued_by.identifier', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where issued to identifier
	 *
	 * @param string or array of issued to identifier
	 **/
	public function scopeIssuedTo($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('issued_to.identifier', $variable);
		}

		return $query->where('issued_to.identifier', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where issued at
	 *
	 * @param string or array of issued at
	 **/
	public function scopeIssuedAt($query, $variable)
	{
		if(is_array($variable))
		{
			$min = Carbon::parse($variable[0])->format('Y-m-d H:i:s');
			$max = Carbon::parse($variable[1])->format('Y-m-d H:i:s');

			if ($min > $max)
			{
				$tmp = $min;
				$min = $max;
				$max = $tmp;
			}

			return $query->where(function($query) use ($min, $max) {
						return $query->where('issued_at', '>=', $min)->where('issued_at', '<=', $max);	
					});
		}

		$variable 	= Carbon::parse($variable)->format('Y-m-d H:i:s');

		return $query->where(function($query) use ($variable) {
					return $query->where('issued_at', '<=', ($variable));	
				});
	}
}
