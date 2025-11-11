<?php

namespace App\Models;

class CacheKey
{
    // Dashboard cache keys
    const DASHBOARD_STATS = 'dashboard_stats_';
    const TODAY_ACTIVITIES = 'today_activities_';
    
    // User cache keys
    const ONLINE_USERS = 'online_users';
    
    // Notification cache keys
    const UNREAD_NOTIFICATIONS = 'unread_notifications_';
    
    /**
     * Get cache key with user ID
     */
    public static function userKey($key)
    {
        return $key . auth()->id();
    }
    
    /**
     * Clear user-specific cache
     */
    public static function clearUserCache($userId)
    {
        $keys = [
            self::DASHBOARD_STATS . $userId,
            self::TODAY_ACTIVITIES . $userId,
            self::UNREAD_NOTIFICATIONS . $userId,
        ];
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
    
    /**
     * Clear general cache
     */
    public static function clearGeneralCache()
    {
        $keys = [
            self::ONLINE_USERS,
        ];
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}