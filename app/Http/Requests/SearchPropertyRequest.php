<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class SearchPropertyRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			//
            'skip' => 'numeric',
            'take' => 'numeric',
            //'agent_id' =>
            'floor_load_min' => 'numeric',
            'floor_load_max' => 'numeric',

            'price_min' => 'numeric',
            'price_max' => 'numeric',

            'land_size_min' => 'numeric',
            'land_size_max' => 'numeric',

            'psf_min' => 'numeric',
            'psf_max' => 'numeric',

            'top_year_min' => 'numeric',
            'top_year_max' => 'numeric',

            'bedroom_min' => 'numeric',
            'bedroom_max' => 'numeric',

            'rented_price_min' => 'numeric',
            'rented_price_max' => 'numeric',

            'rental_yield_min' => 'numeric',
            'rental_yield_max' => 'numeric',

            'valuation_min' => 'numeric',
            'valuation_max' => 'numeric',

            'electrical_value_min' => 'numeric',
            'electrical_value_max' => 'numeric',

            'no_of_storey_min' => 'numeric',
            'no_of_storey_max' => 'numeric',

            'ceiling_height_min' => 'numeric',
            'ceiling_height_max' => 'numeric',

            'no_of_cargo_lift_min' => 'numeric',
            'no_of_cargo_lift_max' => 'numeric',
		];
	}

}
