<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Guzzle\Http\Message\Response;
use Illuminate\Http\Request;
use App\UserFavorite;

class FavoriteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
        $data = UserFavorite::get();

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
        $newFavorite = UserFavorite::create($request->all());
        return response($newFavorite);
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
        $favorite = UserFavorite::where('id', $id)->first();
        if (!$favorite)
            return response(json_encode(['message' => 'favorite not found']));
        return response($favorite);
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
        $favorite = UserFavorite::where('id', $id)->first();
        if (!$favorite)
            return response(json_encode(['message' => 'favorite not found']));
        $favorite->delete();
        return response(null, 204);
	}

}
