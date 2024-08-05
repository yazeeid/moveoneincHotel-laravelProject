<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class baytController extends Controller
{
    public function indexList()
    {
        // Fetch and parse the XML

        $file = file_get_contents('https://careers.moveoneinc.com/rss/all-rss.xml/');
        $object = simplexml_load_string($file);
        return view('lala.List', compact('object'));
    }

        public function indexMap()
    {
        $file = file_get_contents('https://careers.moveoneinc.com/rss/all-rss.xml/');
        $object = simplexml_load_string($file);

        $locations = [];
        $messages = [];

        foreach ($object->channel->item as $element) {
            $location = trim((string) $element->country);
            if ($element->city != null) {
                $location .= ", " . trim((string) $element->city);
            }
            $message = trim((string) $element->title);

            if (!isset($locations[$location])) {
                $locations[$location] = [];
            }
            $locations[$location][] = $message;
        }

        $locationArray = array_keys($locations);
        $messagesArray = array_values($locations);

        return view('lala.map', ['locations' => $locationArray, 'messages' => $messagesArray]);
    }
}
