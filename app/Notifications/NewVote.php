<?php

namespace App\Notifications;

use App\User;
use App\Vote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVote extends Notification implements ShouldQueue
{
  use Queueable;
  protected $user;
  protected $vote;
  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(User $user, Vote $vote)
  {
    $this->vote = $vote;
    $this->user = $user;
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
    $title = sprintf("%s New Vote Recieved", $this->vote->quantity);
    $message1 = sprintf("You just recieved %s new vote for: %s contest from %s %s.", $this->vote->quantity, $this->vote->contest->title, $this->vote->last_name, $this->vote->first_name);
    $message2 = sprintf("The Voter left an email: %s you can write back to appreciate the effort.", $this->vote->email);
    $message3 = sprintf("The Contest: %s has just received %s new vote that amounting to N%s.", $this->vote->contest->title, $this->vote->quantity, $this->vote->amount);
    return (new MailMessage)
      ->greeting($greeting)
      ->line(sprintf("%s", $this->user->role == 'admin' ? $message3 : $message1 . $message2))
      ->action('View votes', route('list_vote'))
      ->line('Thank you for taking your time to read this email')
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

    return [];
  }
}
