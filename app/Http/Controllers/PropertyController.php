<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Http\Request;
use App\Property;
use App\FileAsset;
use App\User;
use App\Http\Requests\SearchPropertyRequest;
use App\Http\Requests\CreatePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\PropertyImage;
use App\GroupSharing;
use DateTime;
use Carbon\Carbon;

class PropertyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
     * @param  SearchPropertyRequest  $request
	 * @return Response
	 */
	public function index(SearchPropertyRequest $request)
	{
        //die(json_encode($request->all()));
		//
        $skip = $request->get('skip')?: 0;
        $take = $request->get('take')?:100;

        $query = Property::with(['propertyImage', 'agent', 'creator', 'propertyImages', 'agent.profileImage', 'creator.profileImage'])->skip($skip)->take($take);

        if ($request->has('agent_id') && $request->get('agent_id') != "") {

            /*
            //For Beta Test Purpose Only
            $agent_code = $request->get('agent_id');
            $current_agent = User::where('object_id', $agent_code)->first();
            if (!$current_agent || $current_agent->user_type != 'agent') return response(json_encode(['message' => 'Agent not found']), 404);

            $query->where('agent_id', $current_agent->id);*/

            $query->where('agent_id', $request->get('agent_id'));
        } else {
            $query->where('submit', 'YES');
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
        if ($request->has('area_min') && $request->get('area_min') != "-1" && $request->has('area_max') && $request->get('area_max') != "-1")  {
            $query->whereBetween('area', [$request->get('area_min'), $request->get('area_max')]);
        } else  if ($request->has('area_min') && $request->get('area_min') != "-1") {
            $query->where('area','>=', $request->get('area_min'));
        } else  if ($request->has('area_max') && $request->get('area_max') != "-1") {
            $query->where('area','<=', $request->get('area_max'));
        }

        //Price min max
        if ($request->has('price_min') && $request->get('price_min') != "-1" && $request->has('price_max') && $request->get('price_max') != "-1")  {
            $query->whereBetween('price', [$request->get('price_min'), $request->get('price_max')]);
        } else  if ($request->has('price_min') && $request->get('price_min') != "-1") {
            $query->where('price','>=', $request->get('price_min'));
        } else  if ($request->has('price_max') && $request->get('price_max') != "-1") {
            $query->where('price','<=', $request->get('price_max'));
        }

        //Project like
        if ($request->has('project') && $request->get('project') != "") {
            $query->where('project','LIKE', '%' .$request->get('project'). '%');
        }

        //Land size min max
        if ($request->has('land_size_min') && $request->get('land_size_min') != "-1" && $request->has('land_size_max') && $request->get('land_size_max') != "-1")  {
            $query->whereBetween('land_size', [$request->get('land_size_min'), $request->get('land_size_max')]);
        } else  if ($request->has('land_size_min') && $request->get('land_size_min') != "-1") {
            $query->where('land_size','>=', $request->get('land_size_min'));
        } else  if ($request->has('land_size_max') && $request->get('land_size_max') != "-1") {
            $query->where('land_size','<=', $request->get('land_size_max'));
        }

        //PSF min max
        if ($request->has('psf_min') && $request->get('psf_min') != "-1" && $request->has('psf_max') && $request->get('psf_max') != "-1")  {
            $query->whereBetween('psf', [$request->get('psf_min'), $request->get('psf_max')]);
        } else  if ($request->has('psf_min') && $request->get('psf_min') != "-1") {
            $query->where('psf','>=', $request->get('psf_min'));
        } else  if ($request->has('psf_max') && $request->get('psf_max') != "-1") {
            $query->where('psf','<=', $request->get('psf_max'));
        }

        //Top year min max
        if ($request->has('top_year_min') && $request->get('top_year_min') != "-1" && $request->has('top_year_max') && $request->get('top_year_max') != "-1")  {
            $query->whereBetween('top_year', [$request->get('top_year_min'), $request->get('top_year_max')]);
        } else  if ($request->has('top_year_min') && $request->get('top_year_min') != "-1") {
            $query->where('top_year','>=', $request->get('top_year_min'));
        } else  if ($request->has('top_year_max') && $request->get('top_year_max') != "-1") {
            $query->where('top_year','<=', $request->get('top_year_max'));
        }

        //Bed room min max
        if ($request->has('bedroom_min') && $request->get('bedroom_min') != "-1" && $request->has('bedroom_max') && $request->get('bedroom_max') != "-1")  {
            $query->whereBetween('bedroom', [$request->get('bedroom_min'), $request->get('bedroom_max')]);
        } else  if ($request->has('bedroom_min') && $request->get('bedroom_min') != "-1") {
            $query->where('bedroom','>=', $request->get('bedroom_min'));
        } else  if ($request->has('bedroom_max') && $request->get('bedroom_max') != "-1") {
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
        if ($request->has('rented_price_min') && $request->get('rented_price_min') != "-1" && $request->has('rented_price_max') && $request->get('rented_price_max') != "-1")  {
            $query->whereBetween('rented_price', [$request->get('rented_price_min'), $request->get('rented_price_max')]);
        } else  if ($request->has('rented_price_min') && $request->get('rented_price_min') != "-1") {
            $query->where('rented_price','>=', $request->get('rented_price_min'));
        } else  if ($request->has('rented_price_max') && $request->get('rented_price_max') != "-1") {
            $query->where('rented_price','<=', $request->get('rented_price_max'));
        }


        //Rental yield price min max
        if ($request->has('rental_yield_min') && $request->get('rental_yield_min') != "-1" && $request->has('rental_yield_max') && $request->get('rental_yield_max') != "-1")  {
            $query->whereBetween('rental_yield', [$request->get('rental_yield_min'), $request->get('rental_yield_max')]);
        } else  if ($request->has('rental_yield_min') && $request->get('rental_yield_min') != "-1") {
            $query->where('rental_yield','>=', $request->get('rental_yield_min'));
        } else  if ($request->has('rental_yield_max') && $request->get('rental_yield_max') != "-1") {
            $query->where('rental_yield','<=', $request->get('rental_yield_max'));
        }


        //Rental yield price min max
        if ($request->has('valuation_min') && $request->get('valuation_min') != "-1" && $request->has('valuation_max') && $request->get('valuation_max') != "-1")  {
            $query->whereBetween('valuation', [$request->get('valuation_min'), $request->get('valuation_max')]);
        } else  if ($request->has('valuation_min') && $request->get('valuation_min') != "-1") {
            $query->where('valuation','>=', $request->get('valuation_min'));
        } else  if ($request->has('valuation_max') && $request->get('valuation_max') != "-1") {
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

        //Hdb type equal
        if ($request->has('hdb_type') && $request->get('hdb_type') != "") {
            $query->where('hdb_type', $request->get('hdb_type'));
        }

        //Commercial type equal
        if ($request->has('commercial_type') && $request->get('commercial_type') != "") {
            $query->where('commercial_type', $request->get('commercial_type'));
        }

        //Land type equal
        if ($request->has('land_type') && $request->get('land_type') != "") {
            $query->where('land_type', $request->get('land_type'));
        }

        //Industry type equal
        if ($request->has('industry_type') && $request->get('industry_type') != "") {
            $query->where('industry_type', $request->get('industry_type'));
        }

        //Town equal
        if ($request->has('town') && $request->get('town') != "") {
            $query->where('town', $request->get('town'));
        }

        //Electrical value  min max
        if ($request->has('electrical_value_min') && $request->get('electrical_value_min') != "-1" && $request->has('electrical_value_max') && $request->get('electrical_value_max') != "-1")  {
            $query->whereBetween('electrical_value', [$request->get('electrical_value_min'), $request->get('electrical_value_max')]);
        } else  if ($request->has('electrical_value_min') && $request->get('electrical_value_min') != "-1") {
            $query->where('electrical_value','>=', $request->get('electrical_value_min'));
        } else  if ($request->has('electrical_value_max') && $request->get('electrical_value_max') != "-1") {
            $query->where('electrical_value','<=', $request->get('electrical_value_max'));
        }

        //Ground level equal
        if ($request->has('ground_level') && $request->get('ground_level') != "") {
            $query->where('ground_level', $request->get('ground_level'));
        }

        //gst equal
        if ($request->has('gst') && $request->get('gst') != "") {
            $query->where('gst', $request->get('gst'));
        }

        //Flatted equal
        if ($request->has('flatted') && $request->get('flatted') != "") {
            $query->where('flatted', $request->get('flatted'));
        }

        //Electrical load equal
        if ($request->has('electrical_load') && $request->get('electrical_load') != "") {
            $query->where('electrical_load', $request->get('electrical_load'));
        }

        //Amps equal
        if ($request->has('amps') && $request->get('amps') != "") {
            $query->where('amps', $request->get('amps'));
        }

        //Vehicle access equal
        if ($request->has('vehicle_access') && $request->get('vehicle_access') != "") {
            $query->where('vehicle_access', $request->get('vehicle_access'));
        }

        //Aircon equal
        if ($request->has('aircon') && $request->get('aircon') != "") {
            $query->where('aircon', $request->get('aircon'));
        }

        //Own pantry equal
        if ($request->has('own_pantry') && $request->get('own_pantry') != "") {
            $query->where('own_pantry', $request->get('own_pantry'));
        }

        //Grease trap equal
        if ($request->has('grease_trap') && $request->get('grease_trap') != "") {
            $query->where('grease_trap', $request->get('grease_trap'));
        }

        //Exhaust equal
        if ($request->has('exhaust') && $request->get('exhaust') != "") {
            $query->where('exhaust', $request->get('exhaust'));
        }

        //Server room equal
        if ($request->has('server_room') && $request->get('server_room') != "") {
            $query->where('server_room', $request->get('server_room'));
        }

        //Grade equal
        if ($request->has('grade') && $request->get('grade') != "") {
            $query->where('grade', $request->get('grade'));
        }

        //Car park equal
        if ($request->has('car_park') && $request->get('car_park') != "") {
            $query->where('car_park', $request->get('car_park'));
        }

        //Garden equal
        if ($request->has('garden') && $request->get('garden') != "") {
            $query->where('garden', $request->get('garden'));
        }

        //Swimming pool equal
        if ($request->has('swimming_pool') && $request->get('swimming_pool') != "") {
            $query->where('swimming_pool', $request->get('swimming_pool'));
        }

        //Basement equal
        if ($request->has('basement') && $request->get('basement') != "") {
            $query->where('basement', $request->get('basement'));
        }

        //Roof terrance equal
        if ($request->has('roof_terrance') && $request->get('roof_terrance') != "") {
            $query->where('roof_terrance', $request->get('roof_terrance'));
        }

        //Room position equal
        if ($request->has('room_position') && $request->get('room_position') != "") {
            $query->where('room_position', $request->get('room_position'));
        }

        //No of storey min max
        if ($request->has('no_of_storey_min') && $request->get('no_of_storey_min') != "-1" && $request->has('no_of_storey_max') && $request->get('no_of_storey_max') != "-1")  {
            $query->whereBetween('no_of_storey', [$request->get('no_of_storey_min'), $request->get('no_of_storey_max')]);
        } else  if ($request->has('no_of_storey_min') && $request->get('no_of_storey_min') != "-1") {
            $query->where('no_of_storey','>=', $request->get('no_of_storey_min'));
        } else  if ($request->has('no_of_storey_max') && $request->get('no_of_storey_max') != "-1") {
            $query->where('no_of_storey','<=', $request->get('no_of_storey_max'));
        }

        //Ceiling height min max
        if ($request->has('ceiling_height_min') && $request->get('ceiling_height_min') != "-1" && $request->has('ceiling_height_max') && $request->get('ceiling_height_max') != "-1")  {
            $query->whereBetween('ceiling_height', [$request->get('ceiling_height_min'), $request->get('ceiling_height_max')]);
        } else  if ($request->has('ceiling_height_min') && $request->get('ceiling_height_min') != "-1") {
            $query->where('ceiling_height','>=', $request->get('ceiling_height_min'));
        } else  if ($request->has('ceiling_height_max') && $request->get('ceiling_height_max') != "-1") {
            $query->where('ceiling_height','<=', $request->get('ceiling_height_max'));
        }

        //Floor load min max
        if ($request->has('floor_load_min') && $request->get('floor_load_min') != "-1" && $request->has('floor_load_max') && $request->get('floor_load_max') != "-1")  {
            $query->whereBetween('floor_load', [$request->get('floor_load_min'), $request->get('floor_load_max')]);
        } else  if ($request->has('floor_load_min') && $request->get('floor_load_min') != "-1") {
            $query->where('floor_load','>=', $request->get('floor_load_min'));
        } else  if ($request->has('floor_load_max') && $request->get('floor_load_max') != "-1") {
            $query->where('floor_load','<=', $request->get('floor_load_max'));
        }

        //No of cargo lift  min max
        if ($request->has('no_of_cargo_lift_min') && $request->get('no_of_cargo_lift_min') != "-1" && $request->has('no_of_cargo_lift_max') && $request->get('no_of_cargo_lift_max') != "-1")  {
            $query->whereBetween('no_of_cargo_lift', [$request->get('no_of_cargo_lift_min'), $request->get('no_of_cargo_lift_max')]);
        } else  if ($request->has('no_of_cargo_lift_min') && $request->get('no_of_cargo_lift_min') != "-1") {
            $query->where('no_of_cargo_lift','>=', $request->get('no_of_cargo_lift_min'));
        } else  if ($request->has('no_of_cargo_lift_max') && $request->get('no_of_cargo_lift_max') != "-1") {
            $query->where('no_of_cargo_lift','<=', $request->get('no_of_cargo_lift_max'));
        }


        return response($query->get());
	}

    public function getProjectList() {
        $list = Property::where('project', '!=', '')->groupBy('project')->get();

        $data = [];

        foreach ($list as $property) {
            $data[] = $property->project;
        }
        return response(json_encode($data));
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
     * @param  CreatePropertyRequest  $request
	 * @return Response
	 */
	public function store(CreatePropertyRequest $request)
	{
		//
        //dd ($request->all());
        $newProp = Property::create($request->all());
        return response($newProp);
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
        $property = Property::with(['propertyImage', 'agent', 'creator', 'propertyImages', 'agent.profileImage', 'creator.profileImage'])->where('id', $id)->first();
        if (!$property) {
            return response(json_encode(['message'=>'property not found']), 404);
        }
        return response($property);
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
     * @param  UpdatePropertyRequest  $request
	 * @return Response
	 */
	public function update($id, UpdatePropertyRequest $request)
	{
		//

        //dd($request->all());
        //$property = Property::find($id);

        $isSubmit = false;




        $property = Property::with(['propertyImage', 'agent', 'creator', 'propertyImages', 'agent.profileImage', 'creator.profileImage'])->where('id', $id)->first();



        $data = $request->all();

        if ($property->submit == "NO" && $data["submit"] == "YES") {
            $isSubmit = true;
        }


        if ($isSubmit === true) {
            //$data["expired_at"] = Carbon::now()->addDay(3)->addMinute(2)->toDateTimeString();
            $data["expired_at"] = Carbon::now()->addMinute(12)->toDateTimeString();

            $data["expired_notify"] = 1;
        }

        $property->update($data);

        return response($property);
	}

    public function extendEnlist($id, Request $request) {
        $property = Property::find($id);

        if (!$property) {
            return response(json_encode(['message'=>'property not found']), 404);
        }

        //$property->expired_at = Carbon::now()->addDay(3)->addMinute(2)->toDateTimeString();
        $property->expired_at = Carbon::now()->addMinute(12)->toDateTimeString();
        $property->expired_notify = 1;
        $property->submit = "YES";

        $property->save();

        return response($property);
    }

    public function  upload($id, Request $request, Cloud $cloud) {

        $property = Property::find($id);

        if (!$property) {
            return response(json_encode(['message'=>'property not found']), 404);
        }
        //dd( $request->file('pdf'));

        $files = $request->hasFile('images') ? $request->file('images') : [];

        $paths = [];
        foreach($files as $file) {
            $now = new DateTime();
            $randomString = $this->quickRandom(6);
            $fileName = 'image-'.$randomString.'-'.$now->getTimestamp().'.'.$file->getClientOriginalExtension();
            $success = $cloud->put($fileName, File::get($file) );

            if ($success) {
                $url = env('S3_URL').$fileName;
                $newAsset = [
                    'name' => $fileName,
                    'url' => $url,
                    'type' => 'img',
                    'source' => 'aws-s3'
                ];
                $paths[] = $url;
                $asset = FileAsset::create($newAsset);

                $newPropertyImage  = [
                    'property_id' => $id,
                    'file_id' => $asset->id
                ];

                PropertyImage::create($newPropertyImage);
            }
        }

        return response(json_encode($paths), 200);
    }

    public function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }


    public function removeAllImages($id)
    {
        //dd('here');
        $property = Property::find($id);

        if (!$property) {
            return response(json_encode(['message' => 'property not found'], 404));
        }
        $propImages = $property->propertyImages;

        PropertyImage::where('property_id', $property->id)->delete();


        if ($propImages) {

            foreach ($propImages as $image) {
                $image->delete();
            }
        }

        return response(null, 204);
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Cloud $fileSystem)
	{
		//
        $property = Property::find($id);

        if (!$property) {
            return response(json_encode(['message' => 'property not found'], 404));
        }
        $propImages = $property->propertyImages;

        PropertyImage::where('property_id', $property->id)->delete();



        foreach ($propImages as $image) {

            $fileSrc = $image->source;
            if ($fileSrc == "aws-s3" && $fileSystem->exists($image->name)) {
                $fileSystem->delete($image->name);
            }
            $image->delete();
        }

        $dual = Property::where('dual_prop_project_id', $property->id)->first();
        if ($dual) {
            $dual->dual_prop_project_id = null;
            $dual->save();
        }


        $property->delete();

        return response(null, 204);
	}

}
