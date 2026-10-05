<?php
require_once __DIR__ . '/Database.php';

class Seo {
    public static function get(string $entityType, int $entityId): ?array {
        return Database::fetch("SELECT * FROM seo_metadata WHERE entity_type = ? AND entity_id = ?", [$entityType, $entityId]);
    }

    public static function set(string $entityType, int $entityId, array $data): bool {
        $exists = self::get($entityType, $entityId);
        if ($exists) {
            Database::query(
                "UPDATE seo_metadata SET meta_title = ?, meta_description = ?, canonical_url = ?, og_title = ?, og_description = ?, og_image = ?, twitter_title = ?, twitter_description = ?, twitter_image = ?, schema_json = ?, robots = ? WHERE entity_type = ? AND entity_id = ?",
                [
                    $data['meta_title'] ?? null,
                    $data['meta_description'] ?? null,
                    $data['canonical_url'] ?? null,
                    $data['og_title'] ?? $data['meta_title'] ?? null,
                    $data['og_description'] ?? $data['meta_description'] ?? null,
                    $data['og_image'] ?? null,
                    $data['twitter_title'] ?? $data['meta_title'] ?? null,
                    $data['twitter_description'] ?? $data['meta_description'] ?? null,
                    $data['twitter_image'] ?? $data['og_image'] ?? null,
                    $data['schema_json'] ?? null,
                    $data['robots'] ?? 'index, follow',
                    $entityType,
                    $entityId
                ]
            );
        } else {
            Database::query(
                "INSERT INTO seo_metadata (entity_type, entity_id, meta_title, meta_description, canonical_url, og_title, og_description, og_image, twitter_title, twitter_description, twitter_image, schema_json, robots) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $entityType,
                    $entityId,
                    $data['meta_title'] ?? null,
                    $data['meta_description'] ?? null,
                    $data['canonical_url'] ?? null,
                    $data['og_title'] ?? $data['meta_title'] ?? null,
                    $data['og_description'] ?? $data['meta_description'] ?? null,
                    $data['og_image'] ?? null,
                    $data['twitter_title'] ?? $data['meta_title'] ?? null,
                    $data['twitter_description'] ?? $data['meta_description'] ?? null,
                    $data['twitter_image'] ?? $data['og_image'] ?? null,
                    $data['schema_json'] ?? null,
                    $data['robots'] ?? 'index, follow'
                ]
            );
        }
        return true;
    }

    public static function generateLocalBusinessSchema(): string {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => APP_NAME,
            'image' => SITE_URL . '/assets/images/logo.png',
            '@id' => CANONICAL_DOMAIN,
            'url' => CANONICAL_DOMAIN,
            'telephone' => CONTACT_PHONE,
            'priceRange' => '€€',
            'servesCuisine' => ['Pakistani', 'Indian', 'Halal', 'Asian'],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Smelbrege 4',
                'addressLocality' => 'Stiens',
                'postalCode' => '9051 BH',
                'addressRegion' => 'Friesland',
                'addressCountry' => 'NL'
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 53.2625,
                'longitude' => 5.7602
            ],
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday'],
                    'opens' => '16:00',
                    'closes' => '20:00'
                ],
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Friday', 'Saturday', 'Sunday'],
                    'opens' => '16:00',
                    'closes' => '21:00'
                ]
            ],
            'sameAs' => [
                FACEBOOK_URL
            ]
        ];
        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public static function generateArticleSchema(array $post): string {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => CANONICAL_DOMAIN . '/' . $post['slug'] . '/'
            ],
            'headline' => $post['title'],
            'description' => $post['excerpt'] ?: substr(strip_tags($post['content']), 0, 160),
            'image' => $post['featured_image'] ?: (SITE_URL . '/assets/images/logo.png'),
            'author' => [
                '@type' => 'Person',
                'name' => $post['author'] ?: 'Lahore Catering'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => APP_NAME,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => SITE_URL . '/assets/images/logo.png'
                ]
            ],
            'datePublished' => date('c', strtotime($post['created_at'])),
            'dateModified' => date('c', strtotime($post['updated_at']))
        ];
        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
