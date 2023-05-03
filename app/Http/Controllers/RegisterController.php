<?php

namespace App\Http\Controllers;

use View;
use Redirect;

class RegisterController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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
		// getting all of the post data
  		$postData = Input::all();

	  	// setting up custom error messages for the field validation
	  	$messages = array(
	    	'email.required' => 'We need to know your email address',
	    	'username.required' => 'Please enter your username',
	    	'firstname.required' => 'Please enter your full first name',
	    	'lastname.required' => 'Please enter your full last name',
	    	'password.required' => 'You have to set a password',
	    	'cpassword.required' => 'Write is again so that you are sure about your password',
	    	'cpassword.matchpass' => 'The two passwords does not match', 
	    	// this is for the custom validatio that we have written
	  	);

        $rule  =  array(
	        'firstname'  => 'required|unique:users',
    	    'lastname'   => 'required|unique:users',
    	    'username'	 => 'required|unique:users',
	        'email'      => 'required|email|unique:users',
	        'password'   => 'required|min:6|same:cpassword',
	        'cpassword'  => 'required|min:6'
	    );
 
		// doing the validation, passing post data, rules and the messages
		$validator = Validator::make($postData, $rules, $messages);
 
        if ($validator->fails())
        {
		    // send back to the page with the input data and errors
		    GlobalHelper::setMessage('Fix the errors.', 'warning'); // settig the error message
		    return Redirect::to('userregister')->withInput()->withErrors($validator);
       }
        else {
		    // send back to the page with success message
		    GlobalHelper::setMessage('Registration data saved.', 'success');
		    return Redirect::to('userregister');
        }
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
