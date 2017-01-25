<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class PaypalController extends BaseController
{
	private $_api_context;

	public function __construct()
	{
		// setup PayPal api context
		$paypal_conf = Config::get('paypal');
		$this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
		$this->_api_context->setConfig($paypal_conf['settings']);
	}

	public function postAddCredits()
	{
		$input = Input::all();

		Session::put('input', $input);

		$rules = array(
			'credit' 		=> 'required|integer|between:5,100',
		);

		$validation = Validator::make($input, $rules);


		if($validation->passes()) {
			$payer = new Payer();
			$payer->setPaymentMethod('paypal');

			$item = new Item();
			$item->setName(ucwords("Add Credits to Lawyer Friend")) // item name
				->setCurrency('USD')
				->setQuantity(1)
				->setPrice($input['credit']); // unit price

			// add item to list
			$item_list = new ItemList();
			$item_list->setItems(array($item));

			$amount = new Amount();
			$amount->setCurrency('USD')
				   ->setTotal($input['credit']);

			$transaction = new Transaction();
			$transaction->setAmount($amount)
						->setItemList($item_list)
						->setDescription("Add Credits to Lawyer Friend");

			$redirect_urls = new RedirectUrls();
			$redirect_urls->setReturnUrl(URL::route('getAddCreditsStatus'))
						  ->setCancelUrl(URL::route('getAddCreditsStatus'));

			$payment = new Payment();
			$payment->setIntent('Sale')
				->setPayer($payer)
				->setRedirectUrls($redirect_urls)
				->setTransactions(array($transaction));

			try {
				$payment->create($this->_api_context);
			} catch (\PayPal\Exception\PPConnectionException $ex) {
				if (\Config::get('app.debug')) {
					echo "Exception: " . $ex->getMessage() . PHP_EOL;
					$err_data = json_decode($ex->getData(), true);
					exit;
				} else {
					die('Some error occur, sorry for inconvenient');
				}
			}


			foreach($payment->getLinks() as $link) {
				if($link->getRel() == 'approval_url') {
					$redirect_url = $link->getHref();
					break;
				}
			}

			// add payment ID to session
			Session::put('paypal_payment_id', $payment->getId());

			if(isset($redirect_url)) {
				// redirect to paypal
				return Redirect::away($redirect_url);
			}

			return ['flash' => ['message'	=>	'Unknown errr occured', 
								'type'		=>	'danger']];

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved!", 
					'type'		=>	'success']);
		} else {
			return Redirect::back()
				->withInput()
				->withErrors($validation);
		}
	}

	public function getAddCreditsStatus()
	{
		// Get the payment ID before session clear
		$payment_id = Session::get('paypal_payment_id');


		// // clear the session payment ID
		// Session::forget('paypal_payment_id');

		if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
			return Redirect::route('original.route')
				->with('error', 'Payment failed');
		}

		$payment = Payment::get($payment_id, $this->_api_context);

		// PaymentExecution object includes information necessary 
		// to execute a PayPal account payment. 
		// The payer_id is added to the request query parameters
		// when the user is redirected from paypal back to your site
		$execution = new PaymentExecution();
		$execution->setPayerId(Input::get('PayerID'));

		//Execute the payment
		$result = $payment->execute($execution, $this->_api_context);

		if ($result->getState() == 'approved') { // payment made
			$input = Session::get('input');

			$profile = LawyerProfile::where('user_id', '=', Auth::user()->id)->get()->first();
			$profile->credits = $profile->credits + $input['credit'];
			$profile->save();

			if(!$profile->id){
			    App::abort(500, 'Some Error');
			}

			return Redirect::route('getAddCredits')
				->with('flash', ['message'	=>	'Successfully added credits.', 
								'type'		=>	'success']);
		}
		return Redirect::route('getAddCredits')
			->with('error', 'Payment failed');
	}


	public function postPayment()
	{
		$payer = new Payer();
		$payer->setPaymentMethod('paypal');

		$item = new Item();
		$item->setName(ucwords("ITEM NAME")) // item name
			->setCurrency('USD')
			->setQuantity(1)
			->setPrice(5); // unit price

		// add item to list
		$item_list = new ItemList();
		$item_list->setItems(array($item));

		$amount = new Amount();
		$amount->setCurrency('USD')
			   ->setTotal(5);

		$transaction = new Transaction();
		$transaction->setAmount($amount)
					->setItemList($item_list)
					->setDescription("DESCRIPTION");

		$redirect_urls = new RedirectUrls();
		$redirect_urls->setReturnUrl(URL::route('payment.status'))
					  ->setCancelUrl(URL::route('payment.status'));

		$payment = new Payment();
		$payment->setIntent('Sale')
			->setPayer($payer)
			->setRedirectUrls($redirect_urls)
			->setTransactions(array($transaction));

		try {
			$payment->create($this->_api_context);
		} catch (\PayPal\Exception\PPConnectionException $ex) {
			if (\Config::get('app.debug')) {
				echo "Exception: " . $ex->getMessage() . PHP_EOL;
				$err_data = json_decode($ex->getData(), true);
				exit;
			} else {
				die('Some error occur, sorry for inconvenient');
			}
		}


		foreach($payment->getLinks() as $link) {
			if($link->getRel() == 'approval_url') {
				$redirect_url = $link->getHref();
				break;
			}
		}

		// add payment ID to session
		Session::put('paypal_payment_id', $payment->getId());

		if(isset($redirect_url)) {
			// redirect to paypal
			return Redirect::away($redirect_url);
		}

		return ['flash' => ['message'	=>	'Unknown errr occured', 
							'type'		=>	'danger']];
	}


	public function getPaymentStatus()
	{
		// Get the payment ID before session clear
		$payment_id = Session::get('paypal_payment_id');

		// clear the session payment ID
		Session::forget('paypal_payment_id');

		if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
			return Redirect::route('original.route')
				->with('error', 'Payment failed');
		}

		$payment = Payment::get($payment_id, $this->_api_context);

		// PaymentExecution object includes information necessary 
		// to execute a PayPal account payment. 
		// The payer_id is added to the request query parameters
		// when the user is redirected from paypal back to your site
		$execution = new PaymentExecution();
		$execution->setPayerId(Input::get('PayerID'));

		//Execute the payment
		$result = $payment->execute($execution, $this->_api_context);

		if ($result->getState() == 'approved') { // payment made
			$input = Session::get('input');

			$user 					= new User;
			$user->role 			= 'user';
			$user->email 			= $input['email'];
			$user->password 		= Hash::make($input['password']);
			$user->isBanned 		= 0;
			$user->save();

			$input['mail_config']		= Config::get('mail')['from'];

			Mail::send('emails.welcome', $input, function($message) use ($input)
			{
				$message->from($input['mail_config']['address'], $input['mail_config']['name']);
				$message->subject("Welcome to " . $input['mail_config']['name']);
				$message->to($input['email']);
			});

			Auth::login($user);

			return Redirect::route('home')
				->with('flash', ['message'	=>	'You have been successfully listed with' . $input['payment_type']['name'] . 'plan.', 
								'type'		=>	'success']);
		}
		return Redirect::route('original.route')
			->with('error', 'Payment failed');
	}
}
