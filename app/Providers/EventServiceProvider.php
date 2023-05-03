<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'event.name' => [
			'EventListener',
		],
//        'Illuminate\Auth\Events\Registered' => [
//            'App\Listeners\LogRegisteredUser',
//        ],
//        'Illuminate\Auth\Events\Attempting' => [
//            'App\Listeners\LogAuthenticationAttempt',
//        ],
//        'Illuminate\Auth\Events\Authenticated' => [
//            'App\Listeners\LogAuthenticated',
//        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\UserLoggedIn',
        ],
//        'Illuminate\Auth\Events\Failed' => [
//            'App\Listeners\LogFailedLogin',
//        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\UserLoggedOut',
            //'App\Listeners\SuspendInstances',
        ],
//        'Illuminate\Auth\Events\Lockout' => [
//            'App\Listeners\LogLockout',
//        ],
//        'Illuminate\Auth\Events\PasswordReset' => [
//            'App\Listeners\LogPasswordReset',
//        ],
//        'App\Events\UserSuspendInstances' => [
//            'App\Listeners\SuspendInstances',
//        ],
	];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\UserEventSubscriber',
    ];

	/**
	 * Register any other events for your application.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();

		//
	}

}
