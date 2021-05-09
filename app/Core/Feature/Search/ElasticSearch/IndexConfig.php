<?php


namespace LocalheroPortal\Core\Feature\Search\ElasticSearch;


class IndexConfig
{
     const settings = [
        'max_ngram_diff' => 20,
        'analysis' => [
            'analyzer' => [
                'autocomplete_name' => [
                    'tokenizer' => 'name_search_tokenizer',
                    'filter' => ['lowercase']
                ],
                'autocomplete_phone' => [
                    'tokenizer' => 'phone_search_tokenizer',
                    'char_filter' => [
                        'phone_char_filter'
                    ]
                ],
                'phone_search' => [
                    'tokenizer' => 'standard',
                    'char_filter' => [
                        'phone_char_filter'
                    ]
                ],
                "autocomplete_search" => [
                    "tokenizer" => "lowercase"
                ]
            ],
            'char_filter' => [
                'phone_char_filter' => [
                    'type' => 'pattern_replace',
                    'pattern' => '[^0-9]',
                    'replacement' => ''
                ]
            ],
            'tokenizer' => [
                'name_search_tokenizer' => [
                    'type' => 'edge_ngram',
                    'min_gram' => "2",
                    'max_gram' => "10",
                    'token_chars' => [
                        'letter',
                        'digit'
                    ]
                ],
                'phone_search_tokenizer' => [
                    'type' => 'ngram',
                    'min_gram' => "4",
                    'max_gram' => "18",
                ],
            ]
        ]
    ];
}