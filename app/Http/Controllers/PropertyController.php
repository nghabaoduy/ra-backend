<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Property;
use App\FileAsset;
use App\User;
use App\Http\Requests\SearchPropertyRequest;

class PropertyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(SearchPropertyRequest $request)
	{
        //die(json_encode($request->all()));
		//
        $skip = $request->get('skip')?: 0;
        $take = $request->get('take')?:100;

        $query = Property::with(['propertyImage', 'agent', 'creator', 'propertyImages'])->skip($skip)->take($take);

        if ($request->has('agent_id') && $request->get('agent_id') != "") {

            //For Beta Test Purpose Only
            $agent_code = $request->get('agent_id');
            $current_agent = User::where('object_id', $agent_code)->first();
            if (!$current_agent || $current_agent->user_type != 'agent') return response(json_encode(['message' => 'Agent not found']), 404);

            $query->where('agent_id', $current_agent->id);

           // $query->where('agent_id', $request->get('agent_id'));
        }


        if ($request->has('listing_type') && $request->get('listing_type') != "") {
            $query->where('listing_type', $request->get('listing_type'));
        }

        //Pr Type equal
        if ($request->has('property_type') && $request->get('property_type') != "") {
            $query->where('property_type', $request->get('property_type'));
        }

        //Pr Type equal
        if ($request->has('pr_type') && $request->get('pr_type') != "") {
            $query->where('pr_type', $request->get('pr_type'));
        }

        //District equal
        if ($request->has('district') && $request->get('district') != "") {
            $query->where('district', $request->get('district'));
        }

        //Floor size min max
        if ($request->has('area_min') && $request->get('area_min') != "" && $request->has('area_max') && $request->get('area_max') != "")  {
            $query->whereBetween('area', [$request->get('area_min'), $request->get('area_max')]);
        } else  if ($request->has('area_min') && $request->get('area_min') != "") {
            $query->where('area','>=', $request->get('area_min'));
        } else  if ($request->has('area_max') && $request->get('area_max') != "") {
            $query->where('area','<=', $request->get('area_max'));
        }

        //Price min max
        if ($request->has('price_min') && $request->get('price_min') != "" && $request->has('price_max') && $request->get('price_max') != "")  {
            $query->whereBetween('price', [$request->get('price_min'), $request->get('price_max')]);
        } else  if ($request->has('price_min') && $request->get('price_min') != "") {
            $query->where('price','>=', $request->get('price_min'));
        } else  if ($request->has('price_max') && $request->get('price_max') != "") {
            $query->where('price','<=', $request->get('price_max'));
        }

        //Project like
        if ($request->has('project') && $request->get('project') != "") {
            $query->where('project','LIKE', '%' .$request->get('project'). '%');
        }

        //Land size min max
        if ($request->has('land_size_min') && $request->get('land_size_min') != "" && $request->has('land_size_max') && $request->get('land_size_max') != "")  {
            $query->whereBetween('land_size', [$request->get('land_size_min'), $request->get('land_size_max')]);
        } else  if ($request->has('land_size_min') && $request->get('land_size_min') != "") {
            $query->where('land_size','>=', $request->get('land_size_min'));
        } else  if ($request->has('land_size_max') && $request->get('land_size_max') != "") {
            $query->where('land_size','<=', $request->get('land_size_max'));
        }

        //PSF min max
        if ($request->has('psf_min') && $request->get('psf_min') != "" && $request->has('psf_max') && $request->get('psf_max') != "")  {
            $query->whereBetween('psf', [$request->get('psf_min'), $request->get('psf_max')]);
        } else  if ($request->has('psf_min') && $request->get('psf_min') != "") {
            $query->where('psf','>=', $request->get('psf_min'));
        } else  if ($request->has('psf_max') && $request->get('psf_max') != "") {
            $query->where('psf','<=', $request->get('psf_max'));
        }

        //Top year min max
        if ($request->has('top_year_min') && $request->get('top_year_min') != "" && $request->has('top_year_max') && $request->get('top_year_max') != "")  {
            $query->whereBetween('top_year', [$request->get('top_year_min'), $request->get('top_year_max')]);
        } else  if ($request->has('top_year_min') && $request->get('top_year_min') != "") {
            $query->where('top_year','>=', $request->get('top_year_min'));
        } else  if ($request->has('top_year_max') && $request->get('top_year_max') != "") {
            $query->where('top_year','<=', $request->get('top_year_max'));
        }

        //Bed room min max
        if ($request->has('bedroom_min') && $request->get('bedroom_min') != "" && $request->has('bedroom_max') && $request->get('bedroom_max') != "")  {
            $query->whereBetween('bedroom', [$request->get('bedroom_min'), $request->get('bedroom_max')]);
        } else  if ($request->has('bedroom_min') && $request->get('bedroom_min') != "") {
            $query->where('bedroom','>=', $request->get('bedroom_min'));
        } else  if ($request->has('bedroom_max') && $request->get('bedroom_max') != "") {
            $query->where('bedroom','<=', $request->get('bedroom_max'));
        }

        //tenure equal
        if ($request->has('tenure') && $request->get('tenure') != "") {
            $query->where('tenure', $request->get('tenure'));
        }

        //Status equal
        if ($request->has('status') && $request->get('status') != "") {
            $query->where('status', $request->get('status'));
        }

        //Rented price min max
        if ($request->has('rented_price_min') && $request->get('rented_price_min') != "" && $request->has('rented_price_max') && $request->get('rented_price_max') != "")  {
            $query->whereBetween('rented_price', [$request->get('rented_price_min'), $request->get('rented_price_max')]);
        } else  if ($request->has('rented_price_min') && $request->get('rented_price_min') != "") {
            $query->where('rented_price','>=', $request->get('rented_price_min'));
        } else  if ($request->has('rented_price_max') && $request->get('rented_price_max') != "") {
            $query->where('rented_price','<=', $request->get('rented_price_max'));
        }


        //Rental yield price min max
        if ($request->has('rental_yield_min') && $request->get('rental_yield_min') != "" && $request->has('rental_yield_max') && $request->get('rental_yield_max') != "")  {
            $query->whereBetween('rental_yield', [$request->get('rental_yield_min'), $request->get('rental_yield_max')]);
        } else  if ($request->has('rental_yield_min') && $request->get('rental_yield_min') != "") {
            $query->where('rental_yield','>=', $request->get('rental_yield_min'));
        } else  if ($request->has('rental_yield_max') && $request->get('rental_yield_max') != "") {
            $query->where('rental_yield','<=', $request->get('rental_yield_max'));
        }


        //Rental yield price min max
        if ($request->has('valuation_min') && $request->get('valuation_min') != "" && $request->has('valuation_max') && $request->get('valuation_max') != "")  {
            $query->whereBetween('valuation', [$request->get('valuation_min'), $request->get('valuation_max')]);
        } else  if ($request->has('valuation_min') && $request->get('valuation_min') != "") {
            $query->where('valuation','>=', $request->get('valuation_min'));
        } else  if ($request->has('valuation_max') && $request->get('valuation_max') != "") {
            $query->where('valuation','<=', $request->get('valuation_max'));
        }


        //Furnishing equal
        if ($request->has('furnishing') && $request->get('furnishing') != "") {
            $query->where('furnishing', $request->get('furnishing'));
        }

        //Main door direction equal
        if ($request->has('main_door_direction') && $request->get('main_door_direction') != "") {
            $query->where('main_door_direction', $request->get('main_door_direction'));
        }

        //Main gate facing equal
        if ($request->has('main_gate_facing') && $request->get('main_gate_facing') != "") {
            $query->where('main_gate_facing', $request->get('main_gate_facing'));
        }

        return response($query->get());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
