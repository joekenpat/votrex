<?php

namespace App\Notifications;

use App\Contest;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContestApplicationStatus extends Notification implements ShouldQueue
{
  use Queueable;
  protected $user;
  protected $contest;
  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(User $user, Contest $contest)
  {
    $this->user = $user;
    $this->contest = $contest;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    $greeting = sprintf("Hello! %s", $this->user->first_name);
    $title = sprintf("Contest Application Review");
    $message1 = sprintf("Your application to join the contest: %s was %s. you can login to your account to see more.", $this->contest->title, $this->user->contests()->where('contest_id', $this->contest->id)->first()->pivot->status);
    return (new MailMessage)
      ->greeting($greeting)
      ->line($title)
      ->line($message1)
      ->action('View contest applications', route('get_application'))
      ->line(sprintf("You are receiving this email because your are registered as %s at Forsi.org", $this->user->role == 'admin' ? 'an Administrator' : 'a Contestant'));
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toArray($notifiable)
  {
    return [
      //
    ];
  }
}
