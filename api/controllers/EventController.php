<?php

namespace API\Controllers;

use API\Models\Event;

class EventController {
    
    public function index() {
        // Logic to fetch all events
        // Example:
        $events = Event::getAll();
        return json_encode($events);
    }

    public function show($id) {
        // Logic to fetch event by ID
        // Example:
        $event = Event::find($id);
        if ($event) {
            return json_encode($event);
        } else {
            return json_encode(['error' => 'Event not found'], 404);
        }
    }

    public function store() {
        // Logic to store a new event
        // Example:
        // Assuming data is sent via POST request
        $data = $_POST;
        $event = new Event();
        $event->fill($data); // Assuming there's a fill method to fill model properties
        $event->save();
        return json_encode(['message' => 'Event created successfully']);
    }

    public function update($id) {
        // Logic to update an existing event
        // Example:
        // Assuming data is sent via PUT or PATCH request
        $data = $_POST;
        $event = Event::find($id);
        if ($event) {
            $event->fill($data); // Assuming there's a fill method to fill model properties
            $event->save();
            return json_encode(['message' => 'Event updated successfully']);
        } else {
            return json_encode(['error' => 'Event not found'], 404);
        }
    }

    public function destroy($id) {
        // Logic to delete an event
        // Example:
        $event = Event::find($id);
        if ($event) {
            $event->delete();
            return json_encode(['message' => 'Event deleted successfully']);
        } else {
            return json_encode(['error' => 'Event not found'], 404);
        }
    }

}
