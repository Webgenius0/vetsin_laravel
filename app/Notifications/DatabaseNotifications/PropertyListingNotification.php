<?php

namespace App\Notifications\DatabaseNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PropertyListingNotification extends Notification
{
    use Queueable;

    protected $listing;
    protected $creator;

    public function __construct($listing, $creator)
    {
        $this->listing = $listing;
        $this->creator = $creator;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'property_listing',
            'title' => 'New ' . ucfirst($this->listing->property_type) . ' Available!',
            'body' => $this->listing->title . ' in ' . ($this->listing->location ?? 'your area'),
            'listing_id' => $this->listing->id,
            'listing_title' => $this->listing->title,
            'property_type' => $this->listing->property_type,
            'location' => $this->listing->location,
            'current_value' => $this->listing->current_value,
            'creator_id' => $this->creator->id,
            'creator_name' => $this->creator->name,
            'action_url' => '/properties/' . $this->listing->id,
            'created_at' => now()->toISOString(),
        ];
    }
} 