<?php

if (!function_exists('logActivity')) {
    function logActivity($description, $subject = null)
    {
        $user = auth()->user();
        
        if (!$user) return;
        
        $activity = new \App\Models\Activity();
        $activity->user_id = $user->id;
        $activity->description = $description;
        
        if ($subject) {
            $activity->subject_type = get_class($subject);
            $activity->subject_id = $subject->id;
        }
        
        $activity->save();
    }
}