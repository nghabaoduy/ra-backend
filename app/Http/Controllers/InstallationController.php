<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Installation;
use Illuminate\Http\Response;

class InstallationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
        $data = Installation::get();
        return response($data);
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
	public function store(Request $request)
	{
		//
        $newInstallation = Installation::updateOrCreate($request->all());
        return response($newInstallation);
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
        $installation = Installation::where('id', $id)->first();
        return response($installation);
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
	public function update($id, Request $request)
	{
		//
        $installation = Installation::where('id', $id)->first();

        if (!$installation)
            return response(json_encode(['message' => 'installation not found']));

        $installation->update($request->all());

        return response($installation);
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
        $installation = Installation::where('id', $id)->first();

        if (!$installation)
            return response(json_encode(['message' => 'installation not found']));

        $installation->delete();

        return response(null, 204);
	}

}
