<?php

namespace LocalheroPortal\Core\Feature\Search\ElasticSearch;

use ScoutElastic\SearchRule;

class LeadSearchRule extends SearchRule
{
    /**
     * @inheritdoc
     */
    public function buildHighlightPayload()
    {
        //
    }

    /**
     * @inheritdoc
     */
    public function buildQueryPayload()
    {
        $query = $this->builder->query;
        return [
            'should' => [
                [
                    'match' => [
                        'name.exact' => [
                            'query' => $query,
                            'boost' => 2
                        ],

                    ],
                ],
                [
                    'match' =>  [
                        'name.autocomplete' => [
                            'query' => $query,
                            'boost' => 1
                        ]
                    ]
                ],
                [
                    'match' =>  [
                        'name' => [
                            'query' => $query,
                            'boost' => 1
                        ]
                    ]
                ],
                [
                    'match' =>  [
                        'phone' => [
                            'query' => $query,
                            'boost' => 1
                        ]
                    ]
                ],
                [
                    'match' =>  [
                        'phone.exact' => [
                            'query' => $query,
                            'boost' => 2
                        ]
                    ]
                ],
                [
                    'match' =>  [
                        'contact_name.exact' => [
                            'query' => $query,
                            'boost' => 2
                        ]
                    ]
                ],
                [
                    'match' =>  [
                        'contact_name' => [
                            'query' => $query,
                            'boost' => 1
                        ]
                    ]
                ],
                [
                    'match' =>  [
                        'email' => [
                            'query' => $query,
                            'boost' => 1
                        ]
                    ]
                ],
                [
                'match' =>  [
                    'email.exact' => [
                        'query' => $query,
                        'boost' => 2
                    ]
                ]
            ]

            ]
        ];
    }
}