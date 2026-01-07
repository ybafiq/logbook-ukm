<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEntrySubmitted extends Notification
{
    use Queueable;

    protected $entry;
    protected $student;
    protected $entryType;

    /**
     * Create a new notification instance.
     */
    public function __construct($entry, $student, $entryType = 'log')
    {
        $this->entry = $entry;
        $this->student = $student;
        $this->entryType = $entryType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $entryTypeName = $this->entryType === 'log' ? 'Log Entry' : 'Project Entry';
        $url = $this->entryType === 'log' 
            ? route('supervisor.pendingEntries') 
            : route('supervisor.pendingProjectEntries');

        return (new MailMessage)
            ->subject('New ' . $entryTypeName . ' Submitted - ' . $this->student->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new ' . strtolower($entryTypeName) . ' has been submitted by **' . $this->student->name . '**')
            ->line('**Date:** ' . $this->entry->date->format('M d, Y'))
            ->line('**Activity:** ' . \Str::limit($this->entry->activity, 100))
            ->action('Review Entry', $url)
            ->line('Please review and approve this entry at your earliest convenience.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'entry_id' => $this->entry->id,
            'entry_type' => $this->entryType,
            'student_id' => $this->student->id,
            'student_name' => $this->student->name,
            'entry_date' => $this->entry->date->format('Y-m-d'),
            'activity' => \Str::limit($this->entry->activity, 100),
            'message' => $this->student->name . ' submitted a new ' . ($this->entryType === 'log' ? 'log entry' : 'project entry') . ' for ' . $this->entry->date->format('M d, Y'),
        ];
    }
}
