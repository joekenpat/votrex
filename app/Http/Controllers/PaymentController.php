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


class PaymentController extends Controller
{

  /**
   * Redirect the User to Paystack Payment Page
   * @return Url
   */
  public function redirectToGateway(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'first_name' => 'required|alpha|max:25|min:2|',
      'last_name' => 'required|alpha|max:25|min:2|',
      'email' => 'required|email|max:150|min:5|',
      'xquantity' => 'numeric|min:1|',
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
    try {
      $contestant = User::where('id', $request->input('contestant_id'))->firstOrFail();
    } catch (ModelNotFoundException $mnt) {
      return back()->with('error', 'Contestant not found')->withInput();
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->withInput();
    }
    try {
      $paystack =  new Paystack();
      $new_vote = new Vote();
      $new_vote->first_name = $request->input('first_name');
      $new_vote->last_name = $request->input('last_name');
      $new_vote->quantity = $request->input('xquantity');
      $new_vote->email = $request->input('email');
      $new_vote->status = 'invalid';
      $new_vote->amount = $request->input('xquantity') * $contest->vote_fee;
      $new_vote->paystack_ref = $paystack->genTranxRef();
      $new_vote->user_id = $contestant->id;
      $new_vote->contest_id = $contest->id;
      $new_vote->save();

      $request->reference = $new_vote->paystack_ref;
      $request->amount = ($new_vote->amount * 100);
      $request->quantity = 1;
      $request->metadata = ['contestant_id' => $contestant->id, 'contest_id' => $contest->id,];
      $request->key = config('paystack.secretKey');
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage())->withInput();
    }

    return $paystack->getAuthorizationUrl()->redirectNow();
  }

  /**
   * Obtain Paystack payment information
   * @return void
   */
  public function handleGatewayCallback()
  {
    $paystack = new Paystack();
    $paymentDetails = $paystack->getPaymentData();

    // dd($paymentDetails);
    // dd($paymentDetails['data']['reference']);
    $valid_vote = Vote::where('paystack_ref', $paymentDetails['data']['reference'])->firstOrFail();
    if ($paymentDetails['data']['status'] === "success") {
      $valid_vote->status =  'valid';
      $valid_vote->update();
      // $valid_vote->contestant->notify(new NewVote($valid_vote->contestant,$valid_vote));
      // $admin  = User::where('role','admin')->firstOrFail();
      // $admin->notify(new NewVote($admin,$valid_vote));
      return redirect()->route('visit_contest_contestant', ['contest_id' => $valid_vote->contest_id, 'contestant_id' => $valid_vote->user_id])->with('success', sprintf('Your Vote for %s was Successful!', $valid_vote->contestant->get_full_name()));
    } else {
      return redirect()->route('visit_contest_contestant', ['contest_id' => $valid_vote->contest_id, 'contestant_id' => $valid_vote->user_id])->with('error', sprintf('Your Vote for %s was unsuccessful!', $valid_vote->contestant->get_full_name()));
    }

    // Now you have the payment details,
    // you can store the authorization_code in your db to allow for recurrent subscriptions
    // you can then redirect or do whatever you want
  }
}
