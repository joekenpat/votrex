<?php

namespace App\Http\Controllers;

use App\Contest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Notifications\NewVote;
use App\User;
use App\Vote;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Unicodeveloper\Paystack\Paystack;
use KingFlamez\Rave\Facades\Rave;

class PaymentController extends Controller
{

  /**
   * Redirect the User to Paystack Payment Page
   * @return Url
   */
  public function redirectToGateway(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'contest_id' => 'required|uuid|exists:contests,id|',
      'contestant_id' => 'required|uuid|exists:users,id|',
    ]);
    if ($validator->fails()) {
      // dd($validator->errors());
      return back()->withErrors($validator->errors())->withInput();
    }
    try {
      $contest = Contest::where('id', $request->input('contest_id'))->firstOrFail();
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contest not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
    $validator = Validator::make($request->all(), [
      'first_name' => 'required|alpha|max:25|min:2|',
      'last_name' => 'required|alpha|max:25|min:2|',
      'email' => 'required|email|max:150|min:5|',
      'xquantity' => "numeric|min:{$contest->minimum_vote}|",
    ], [
      'xquantity.min' => "The quantity of vote must be greater or equal to: {$contest->minimum_vote}",
      'first_name.required' => "Your first name is Required!",
      'first_name.alpha' => "Only alphabets are allowed in your first name",
      'last_name.required' => "Your last name is Required!",
      'last_name.alpha' => "Only alphabets are allowed in your last name",
    ]);
    if ($validator->fails()) {
      // dd($validator->errors());
      return back()->withErrors($validator->errors())->withInput();
    }
    try {
      $contestant = User::where('id', $request->input('contestant_id'))->firstOrFail();
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contestant not found')->withInput();
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->withInput();
    }
    if ($contest->vote_fee == 0) {
      $new_vote = new Vote();
      $new_vote->first_name = $request->input('first_name');
      $new_vote->last_name = $request->input('last_name');
      $new_vote->quantity = $request->input('xquantity');
      $new_vote->email = $request->input('email');
      $new_vote->status = 'valid';
      $new_vote->gateway = 'free';
      $new_vote->amount = 0;
      $new_vote->transaction_ref = 'free';
      $new_vote->user_id = $contestant->id;
      $new_vote->contest_id = $contest->id;
      $new_vote->save();
      return redirect()->route('visit_contest_contestant', ['contest_id' => $new_vote->contest_id, 'contestant_id' => $new_vote->user_id])->with('success', sprintf('Your Vote for %s was Successful!', $new_vote->contestant->get_full_name()));
    } else {
      try {
        $paystack =  new Paystack();
        $new_vote = new Vote();
        $new_vote->first_name = $request->input('first_name');
        $new_vote->last_name = $request->input('last_name');
        $new_vote->quantity = $request->input('xquantity');
        $new_vote->email = $request->input('email');
        $new_vote->status = 'invalid';
        $new_vote->gateway = 'paystack';
        $new_vote->amount = $request->input('xquantity') * $contest->vote_fee;
        $new_vote->transaction_ref = $paystack->genTranxRef();
        $new_vote->user_id = $contestant->id;
        $new_vote->contest_id = $contest->id;
        $new_vote->save();

        $request->reference = $new_vote->transaction_ref;
        $request->amount = ($new_vote->amount * 100);
        $request->quantity = 1;
        $request->metadata = ['contestant_id' => $contestant->id, 'contest_id' => $contest->id,];
        $request->key = config('paystack.secretKey');
        $request->callback_url = route('paystack_payment_callback');
      } catch (\Exception $e) {
        return back()->with('error', $e->getMessage())->withInput();
      }
      return $paystack->getAuthorizationUrl()->redirectNow();
    }
  }

  /**
   * Obtain Paystack payment information
   * @return void
   */
  public function handleGatewayCallback()
  {
    $paystack = new Paystack();
    $paymentDetails = $paystack->getPaymentData();
    // return dd($paymentDetails);
    $valid_vote = Vote::where('gateway', 'paystack')->where('transaction_ref', $paymentDetails['data']['reference'])->firstOrFail();
    if ($paymentDetails['data']['status'] === "success") {
      $valid_vote->status =  'valid';
      $valid_vote->update();
      $valid_vote->contestant->notify(new NewVote($valid_vote->contestant, $valid_vote));
      $admins  = User::where('role', 'admin')->get();
      foreach ($admins as $admin) {
        $admin->notify(new NewVote($admin, $valid_vote));
      }
      return redirect()->route('visit_contest_contestant', ['contest_id' => $valid_vote->contest_id, 'contestant_id' => $valid_vote->user_id])->with('success', sprintf('Your Vote for %s was Successful!', $valid_vote->contestant->get_full_name()));
    } else {
      return redirect()->route('visit_contest_contestant', ['contest_id' => $valid_vote->contest_id, 'contestant_id' => $valid_vote->user_id])->with('error', sprintf('Your Vote for %s was unsuccessful!', $valid_vote->contestant->get_full_name()));
    }

    // Now you have the payment details,
    // you can store the authorization_code in your db to allow for recurrent subscriptions
    // you can then redirect or do whatever you want
  }


  /**
   * Redirect the User to Flutterwave Payment Page
   * @return Url
   */
  public function initialize(Request $request)
  {
    if($request->method() == 'GET'){
      return redirect()->route('list_contest');
    }
    $validator = Validator::make($request->all(), [
      'contest_id' => 'required|uuid|exists:contests,id|',
      'contestant_id' => 'required|uuid|exists:users,id|',
    ]);
    if ($validator->fails()) {
      // dd($validator->errors());
      return back()->withErrors($validator->errors())->withInput();
    }
    try {
      $contest = Contest::where('id', $request->input('contest_id'))->firstOrFail();
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contest not found');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
    $validator = Validator::make($request->all(), [
      'first_name' => 'required|alpha|max:25|min:2|',
      'last_name' => 'required|alpha|max:25|min:2|',
      'email' => 'required|email|max:150|min:5|',
      'xquantity' => "numeric|min:{$contest->minimum_vote}|",
    ], [
      'xquantity.min' => "The quantity of vote must be greater or equal to: {$contest->minimum_vote}",
      'first_name.required' => "Your first name is Required!",
      'first_name.alpha' => "Only alphabets are allowed in your first name",
      'last_name.required' => "Your last name is Required!",
      'last_name.alpha' => "Only alphabets are allowed in your last name",
    ]);
    if ($validator->fails()) {
      // dd($validator->errors());
      return back()->withErrors($validator->errors())->withInput();
    }
    try {
      $contestant = User::where('id', $request->input('contestant_id'))->firstOrFail();
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contestant not found')->withInput();
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->withInput();
    }
    if ($contest->vote_fee == 0) {
      $new_vote = new Vote();
      $new_vote->first_name = $request->input('first_name');
      $new_vote->last_name = $request->input('last_name');
      $new_vote->quantity = $request->input('xquantity');
      $new_vote->email = $request->input('email');
      $new_vote->status = 'valid';
      $new_vote->gateway = 'free';
      $new_vote->amount = 0;
      $new_vote->transaction_ref = 'free';
      $new_vote->user_id = $contestant->id;
      $new_vote->contest_id = $contest->id;
      $new_vote->save();
      return redirect()->route('visit_contest_contestant', ['contest_id' => $new_vote->contest_id, 'contestant_id' => $new_vote->user_id])->with('success', sprintf('Your Vote for %s was Successful!', $new_vote->contestant->get_full_name()));
    } else {
      try {
        $trx_ref = sprintf("FWTR_%s", bin2hex(random_bytes(15)));
        $new_vote = new Vote();
        $new_vote->first_name = $request->input('first_name');
        $new_vote->last_name = $request->input('last_name');
        $new_vote->quantity = $request->input('xquantity');
        $new_vote->email = $request->input('email');
        $new_vote->status = 'invalid';
        $new_vote->amount = $request->input('xquantity') * $contest->vote_fee;
        $new_vote->gateway = 'flutterwave';
        $new_vote->transaction_ref = $trx_ref;
        $new_vote->user_id = $contestant->id;
        $new_vote->contest_id = $contest->id;
        $new_vote->save();

        if ($contest->vote_fee == 0) {
          // return redirect()->route('visit_contest_contestant', ['contest_id' => $new_vote->contest_id, 'contestant_id' => $new_vote->user_id])->with('success', sprintf('Your Vote was Successful!'));
        } else {
          $request->amount = ($request->input('xquantity') * $contest->vote_fee);
          $request->firstname = $request->input('first_name');
          $request->lastname = $request->input('last_name');
          $request->metadata = json_encode(['contestant_id' => $contestant->id, 'contest_id' => $contest->id]);
          $request->payment_method = 'card';
          $request->country = 'NG';
          $request->currency = "NGN";
          $request->ref = $trx_ref;
          $request->title = sprintf('%s Votes for %s', $request->input('xquantity'), $new_vote->contestant->get_full_name());
          Rave::initialize(route('flutterwave_callback'));
        }
      } catch (\Exception $e) {
        return back()->with('error', $e->getMessage())->withInput();
      }
    }
  }

  /**
   * Obtain Rave callback information
   * @return void
   */
  public function callback(Request $request)
  {
    $resp = json_decode($request->resp);
    // return dd($resp);
    $data = Rave::verifyTransaction($resp->data->data->txRef);
    $valid_vote = Vote::where('gateway', 'flutterwave')->where('transaction_ref', $resp->data->tx->txRef)->firstOrFail();
    if ($data->status == 'success' && $data->data->chargecode == "00") {
      $valid_vote->status =  'valid';
      $valid_vote->update();
      $valid_vote->contestant->notify(new NewVote($valid_vote->contestant, $valid_vote));
      $admins  = User::where('role', 'admin')->get();
      foreach ($admins as $admin) {
        $admin->notify(new NewVote($admin, $valid_vote));
      }
      return redirect()->route('visit_contest_contestant', ['contest_id' => $valid_vote->contest_id, 'contestant_id' => $valid_vote->user_id])->with('success', sprintf('Your Vote for %s was Successful!', $valid_vote->contestant->get_full_name()));
    } else {
      return redirect()->route('visit_contest_contestant', ['contest_id' => $valid_vote->contest_id, 'contestant_id' => $valid_vote->user_id])->with('error', sprintf('Your Vote for %s was unsuccessful!', $valid_vote->contestant->get_full_name()));
    }
    // Get the transaction from your DB using the transaction reference (txref)
    // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
    // Comfirm that the transaction is successful
    // Confirm that the chargecode is 00 or 0
    // Confirm that the currency on your db transaction is equal to the returned currency
    // Confirm that the db transaction amount is equal to the returned amount
    // Update the db transaction record (includeing parameters that didn't exist before the transaction is completed. for audit purpose)
    // Give value for the transaction
    // Update the transaction to note that you have given value for the transaction
    // You can also redirect to your success page from here

  }
}
