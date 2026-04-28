<?php

/**
 * Get status name from status ID
 */
if (!function_exists('getStatusName')) {
    function getStatusName($statusId)
    {
        $statuses = [
            1 => 'Draft',
            2 => 'Pending Review',
            3 => 'Rejected',
            4 => 'Approved',
            5 => 'Published',
        ];

        return $statuses[$statusId] ?? 'Unknown';
    }
}

/**
 * Get status badge class from status ID
 */
if (!function_exists('getStatusBadge')) {
    function getStatusBadge($statusId)
    {
        $badges = [
            1 => 'badge-secondary',
            2 => 'badge-warning',
            3 => 'badge-danger',
            4 => 'badge-info',
            5 => 'badge-success',
        ];

        return $badges[$statusId] ?? 'badge-secondary';
    }
}

/**
 * Format file size
 */
if (!function_exists('formatFileSize')) {
    function formatFileSize($bytes)
    {
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}
