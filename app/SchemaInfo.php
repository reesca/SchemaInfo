<?php

namespace App;

use App\BaseSchemaInfo;

/**
* 
*/
class SchemaInfo extends BaseSchemaInfo
{

    protected static $schema = [

        'roles' => [
            ['id','increments'],
            ['name','string:30','required|unique'],
            ['timestamps'],
        ],
        'permission_types' => [
            ['id','increments'],
            ['name','string:30','required|unique'],
            ['timestamps'],
        ],

        'users' => [
            ['id','increments'],
            ['user_id','string:32','unique'],
            ['first_name','string:35','required'],
            ['middle_name','string:35'],
            ['last_name','string:35','required'],
            ['email','string:50','unique','required'],
            ['role_id','integer'],
            ['password','string:64','required'],
            ['rememberToken'],
            ['timestamps'],
            ['disabled','boolean'],
            ['role_id','foreign','id|roles'],
            ['last_name', 'index'],
            ['email', 'index'],
        ],

        'customers' => [
            ['id'               , 'increments'                            ],
            ['name'             , 'string:50',    'required|unique'       ],
            ['detail'           , 'text'                                  ],
            ['hierarchical'     , 'boolean',      'required|default:true' ],
            ['subdomain'        , 'string:50',    'required|unique'       ],
            ['logo'             , 'string:50'                             ],
            ['personnel_file'   , 'string:50'                             ],
            ['stylesheet'       , 'string:50'                             ],
            ['winteam_id'       , 'string:10'                             ],
            ['client_id_prefix' , 'string:15',    'required|unique'       ],
            ['disabled'         , 'boolean'                               ],
            ['timestamps'                                                 ],
            ['name'             , 'index'                                 ],
        ], 

        'clients' => [
            ['id',                      'increments'                   ],
            ['client_id',               'string:30',  'required|unique'],
            ['insured',                 'boolean'                      ],
            ['insured_contact',         'string:9'                     ],
            ['attorney_id',             'string:9'                     ],
            ['med_id',                  'string:9'                     ],
            ['contact_id',              'string:9'                     ],
            ['billing_id',              'string:9'                     ],
            ['billing_amt',             'decimal:11:2'                 ],
            ['billing_rate',            'string:10'                    ],
            ['charge_for_travel_time',  'boolean'                      ],  
            ['send_verbal_to',          'string'                       ],
            ['verbals_sent_via',        'string'                       ],
            ['charge_for_research',     'boolean'                      ],
            ['other',                   'boolean'                      ],
            ['other_yn',                'boolean'                      ],
            ['siu',                     'boolean'                      ],
            ['adjuster',                'boolean'                      ],
            ['billing',                 'boolean'                      ],
            ['office_id',               'integer'                      ],
            ['timestamps'                                              ]
        ], 

        'users_to_clients' => [
            ['id',                      'increments'                   ],
            ['user_id',                 'integer'                      ],
            ['client_id',               'integer'                      ],
            ['user_id',                 'foreign',        'id|users'   ],
            ['client_id',               'foreign',        'id|clients' ],
        ], 

        'claimants' => [
            ['id',                      'increments'                    ],
            ['ssn',                     'string:12',        'unique'    ],
            ['first_name',              'string:30'                     ],
            ['middle_name',             'string:30'                     ],
            ['nick_name',               'string:62'                     ],
            ['last_name',               'string:30'                     ],
            ['gender',                  'string:6'                      ],
            ['race',                    'string:20'                     ],
            ['date_of_birth',           'date'                          ],
            ['height',                  'string:6'                      ],
            ['weight',                  'string:7'                      ],
            ['hair_color',              'string:10'                     ],
            ['hair_length',             'string:10'                     ],
            ['glasses',                 'string:10'                     ],
            ['other_description',       'text'                          ],
            ['facial_hair',             'string:12'                     ],
            ['claimant_age',            'integer'                       ],
            ['drivers_license',         'string:15'                     ],
            ['marital_status',          'string:10'                     ],
            ['spouse',                  'string:25'                     ],
            ['children',                'text'                          ],
            ['occupation',              'string:50'                     ],
            ['represented',             'text'                          ],
            ['other_address',           'text'                          ],
            ['s_other',                 'text'                          ],
            ['vehicle_info',            'text'                          ],
            ['timestamps',                                              ],
            ['ssn',                     'index'                         ],
            ['last_name',               'index'                         ],
        ],
        'groups' => [
            ['id','increments'],
            ['customer_id','integer'],
            ['name','string:30','required|unique'],
            ['timestamps'],
            ['name','index'],
        ],
        'group_members' => [
            ['id','increments'],
            ['client_id', 'integer'],
            ['group_id',    'integer'],
            ['timestamps'],
        ],

         'cases' => [
            ['id','increments'],
            ['caseno'                , 'string:12' , 'unique'   ],
            ['client_id'             , 'integer'   , 'required' ],
            ['customer_id'           , 'integer'   , 'required' ],
            ['claimant_id'           , 'integer'   , 'required' ],
            ['customer_instructions' , 'text'                   ],
            ['client_file_id'        , 'string:30'              ],
            ['adjuster_id'           , 'string:12'              ],
            ['work_id'               , 'string:12'              ],
            ['group_id'              , 'integer'                ],
            ['workflow_status'       , 'integer'   , 'default:0'],
            ['adj_or_dom'            , 'string:3'               ],
            ['siu_name'              , 'string'                 ],
            ['adjuster_name'         , 'string'                 ],
            ['custnumb'              , 'string:40'              ],
            ['processed'             , 'boolean'                ],
            ['type_of_assignment'    , 'integer'                ],
            ['timestamps'],
            ['client_id'             , 'foreign'   , 'id|clients'   ],
            ['customer_id'           , 'foreign'   , 'id|customers' ],
            ['claimant_id'           , 'foreign'   , 'id|claimants' ],
            ['caseno'                , 'index'                      ],
            ['client_id'             , 'index'                      ],
            ['customer_id'           , 'index'                      ],
            ['adjuster_id'           , 'index'                      ],
            ['group_id'              , 'index'                      ],
            // $table->foreign('claimant_ssn')->references('ssn')->on('claimants');
            // $table->foreign('group_id')->references('id')->on('groups');
        ],
        'permissions' => [
            ['id','increments'],
            ['permission_type_id', 'integer', 'required'],
            ['role_id', 'integer'],
            ['user_id', 'integer'],
            ['case_id', 'integer'],
            ['expires_at', 'timestamp'],
            ['timestamps'],
            ['role_id',             'foreign',  'id|roles'           ],
            ['permission_type_id',  'foreign',  'id|permission_types'],
            ['user_id',             'foreign',  'id|users'           ],
        ],

   ];
}
