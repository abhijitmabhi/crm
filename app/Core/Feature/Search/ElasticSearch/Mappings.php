<?php


namespace LocalheroPortal\Core\Feature\Search\ElasticSearch;


class Mappings
{
    public const mapping = [
        'properties' => [
            'name' => [
                'type'     => 'text',
                'analyzer' => 'german',
                'fields'   => [
                    'exact' => [
                        'type' => 'keyword'
                    ],
                    'autocomplete' => [
                        'type' => 'text',
                        'analyzer' => 'autocomplete_name',
                        'search_analyzer' => 'autocomplete_search'
                    ]
                ],
            ],
            'phone'        => [
                'type'     => 'keyword',
                'fields' => [
                    'exact' => [
                        'type' => 'keyword'
                    ],
                ]
            ],
            'email'        => [
                'type'   => 'text',
                'fields' => [
                    'exact' => [
                        'type' => 'keyword'
                    ],
                ],
            ],
            'address' => [
                'type'   => 'text',
                'fields' => [
                    'exact' => [
                        'type' => 'keyword'
                    ],
                ]
            ],
        ]
    ];
}