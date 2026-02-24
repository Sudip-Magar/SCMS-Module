<?php

namespace App\Support;

use Illuminate\Support\Facades\Request;

class Spotlight
{
    protected $menus;

    public function __construct()
    {
        // Load menus from your config/menu.php file
        $this->menus = include base_path('config/menu.php');
    }

    /**
     * Search the menus based on query
     *
     * @param string|null $query
     * @return array
     */
    public function search($query = null)
    {
        if (!$query) {
            return [];
        }

        $results = [];

        // Flatten menus to search in children as well
        foreach ($this->menus as $menu) {
            // Search top-level menu
            if (isset($menu['search']) && $this->match($menu['search'], $query)) {
                $results[] = [
                    'title' => $menu['title'],
                    'route' => $menu['route'] ?? null,
                    'icon' => $menu['icon'] ?? null,
                ];
            }

            // Search children if any
            if (isset($menu['children']) && is_array($menu['children'])) {
                foreach ($menu['children'] as $child) {
                    if (isset($child['search']) && $this->match($child['search'], $query)) {
                        $results[] = [
                            'title' => $child['title'],
                            'route' => $child['route'] ?? null,
                            'icon' => $child['icon'] ?? $menu['icon'] ?? null,
                        ];
                    }
                }
            }
        }

        return $results;
    }

    /**
     * Check if query matches any of the searchable terms
     */
    protected function match(array $searchTerms, string $query): bool
    {
        foreach ($searchTerms as $term) {
            if (stripos($term, $query) !== false) {
                return true;
            }
        }
        return false;
    }
}
